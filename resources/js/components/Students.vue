<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">Студенты</h3>
        <p class="panel-subtitle">создание, удаление и редактирование студентов</p>
        <Button class="float-left mt-1 mb-1" type="primary" @click="createItem">Добавить студента</Button>
      </div>

      <div class="mt-1 mb-1"></div>

      <div class="panel-body">
        <div class="mt-3">
          <Table transfer border :columns="tableColumns" :data="tableList">
            <template slot-scope="{ row, index }" slot="group_id">
              {{ row.group ? row.group.name : 'Не выбрана' }}
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
      <students-form v-if="isModalShown" :itemId="itemId" @save="getItems()" />
    </Modal>
  </div>
</template>

<script>
  import axios from 'axios'
  import StudentsForm from './forms/StudentsForm'
  export default {
   components:{StudentsForm},
    data: function() {
      return {
        isLoading: false,
        itemId: null,
        isModalShown: false,

        // Описание заголовка таблицы
        tableColumns: [
          {title: 'ID', key: 'id'},
          {title: 'ФИО', key: 'student_name', sortable: true},
          {title: '№ Зачетки', key: 'record_book', sortable: true},
          {title: 'Группа', key: 'group_id', slot: 'group_id', sortable: true},
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
        axios.get("/api/v1/students", {
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

      // создание студента
      createItem() {
        this.itemId = null;
        this.isModalShown = true;
      },

      // редактирование студента
      editItem(id) {
        this.itemId = id;
        this.isModalShown = true;
      },

      // удаление студента
      deleteItem(itemId) {
        this.$Modal.confirm({
          title: 'Подтверждение!',
          loading: false,
          content : `Вы действительно хотите удалить студента #${itemId} ?`,
          onOk: () => {
            this.isLoading = true;

            axios({
              method: 'delete',
              url:  '/api/v1/students/' + itemId,
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
          return 'Форма редактирования студента';
        }

        return 'Форма создания нового студента';
      }
    },

    computed: {
      totalPages() {
        return this.tableTotal > 0 ? Math.ceil(this.tableData.length / this.perPage) : 1;
      },
    },

    mounted() {
      this.getItems();
    }
  }
</script>
