<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">Пользователи</h3>
        <p class="panel-subtitle">Список пользователей электронного журнала</p>
        <Button class="float-left mt-1 mb-1" type="primary" @click="createItem">Добавить пользователя</Button>
      </div>

      <div class="mt-1 mb-1"></div>

      <div class="panel-body">
        <div class="mt-3">
          <Table transfer border :columns="tableColumns" :data="tableList">
            <template slot-scope="{ row, index }" slot="group_id">
              {{ row.group ? row.group.name : 'Не выбрана' }}
            </template>

            <template slot-scope="{ row, index }" slot="roles">
                <Tag v-if="row.roles === role.value" v-for="role in userRoles" :key="role.id" :value="role.value" color="geekblue">{{ role.name }}</Tag>
            </template>

            <template slot-scope="{ row, index }" slot="actions">
              <Button type="primary"
                      shape="circle"
                      icon="md-create" style="margin-right: 5px" @click="editItem(row.id)"></Button>
              <Button type="error"
                      shape="circle"
                      icon="md-trash" @click="deleteItem(row.id)"></Button>
            </template>
          </Table>

          <Page :total="tableTotal"
                :current="currentPage"
                :page-size="perPage"
                @on-change="changePage"
                v-if="totalPages > 1"
                class="mt-3 mb-1 text-center" />
        </div>
      </div>
    </div>

    <BackTop></BackTop>

    <!-- Форма -->
    <Modal v-model="isModalShown" :closable="true" :footer-hide="true" :title="getFormTitle()" width="60%">
      <users-form v-if="isModalShown" :itemId="itemId" @save="getItems()" />
    </Modal>
  </div>
</template>



<script>
  import axios from 'axios'
  import UsersForm from './forms/UsersForm'
  export default {
    props: [],
    components:{UsersForm},

    data: function() {
      return {
        isLoading: false,
        itemId: null,
        isModalShown: false,
        userRoles: [],

        // Описание заголовка таблицы
        tableColumns: [
          {title: 'ID', key: 'id'},
          {title: 'Имя пользователя', key: 'username', sortable: true},
          {title: 'E-mail', key: 'email', sortable: true},
          {title: 'Роль', align: 'center', key: 'roles', sortable: true, slot: 'roles'},
          {title: 'Действия', key: 'actions', slot: 'actions'}
        ],

        // таблица
        tableTotal: 0,
        tableData: [],
        tableList: [],

        // пейджинация
        currentPage: 1,
        perPage: 10,
      }
    },

    methods: {
      // Получение списка страниц
      getPageList(p, size) {
        return this.tableData.slice((p - 1) * size, p * size);
      },

      // Переключение страницы
      changePage(p) {
        this.currentPage = p;
        this.tableList = this.getPageList(p, this.perPage);
      },

      // получение списка студентов
      getItems() {
        this.isLoading = true;
        axios.get("/api/v1/users", {
          headers: window.api.apiHeader,
        }).then((res) => {
          this.tableData = res.data.data;
          this.tableTotal = this.tableData.length;
          this.changePage(1);
          this.isLoading = false;
          this.isModalShown = false;
        }, (err) => {
          console.log(err);
          this.$snotify.error("Ошибка получения данных!");
          this.isLoading = false;
        });
      },

      // Получить список ролей пользователя
      getRolesList() {
        axios({
          method: 'get',
          url: '/api/v1/get-user-roles/',
          headers: window.api.apiHeader
        }).then((res) => {
          this.isLoading = false;
          this.userRoles = res.data || [];
        }, (err) => {
          console.log(err);
          this.isLoading = false;
        });
      },

      // создание пользователя
      createItem() {
        this.itemId = null;
        this.isModalShown = true;
      },

      // редактирование пользователя
      editItem(id) {
        this.itemId = id;
        this.isModalShown = true;
      },

      // удаление пользователя
      deleteItem(itemId) {
        this.$Modal.confirm({
          title: 'Подтверждение!',
          loading: false,
          content : `Вы действительно хотите удалить пользователя с id #${itemId} ?`,
          onOk: () => {
            this.isLoading = true;

            axios({
              method: 'delete',
              url:  '/api/v1/users/' + itemId,
              headers: window.api.apiHeader
            }).then((res) => {
              this.$snotify.success('Запись удалена!');
              this.isLoading = false;
              this.getItems();
            }, (err) => {
              console.log(err);
              this.$Message.error(`Ошибка отправки данных!`);
              this.isLoading = false;
            });
          }
        });
      },

      // Заголовок для формы
      getFormTitle() {
        if (this.itemId) {
          return 'Форма редактирования пользователя';
        }

        return 'Форма создания нового пользователя';
      }
    },

    computed: {
      totalPages() {
        return this.tableTotal > 0 ? Math.ceil(this.tableData.length / this.perPage) : 1;
      },
    },

    mounted() {
      this.getRolesList();
      this.getItems();
    }
  }
</script>
