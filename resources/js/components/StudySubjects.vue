<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">Предметы</h3>
        <p class="panel-subtitle">создание, удаление и редактирование предметов</p>
        <Button class="float-left mt-1 mb-1" type="primary" @click="createItem">Добавить предмет</Button>
      </div>

      <div class="mt-1 mb-1"></div>

      <div class="panel-body">
        <div class="mt-3">
          <Table transfer border :columns="tableColumns" :data="tableList">
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
      <study-subjects-form v-if="isModalShown" :itemId="itemId" @save="getItems()" />
    </Modal>
  </div>
</template>

<script>
  import axios from 'axios'
  import StudySubjectsForm from './forms/StudySubjectsForm'

  export default {
    components: {StudySubjectsForm},
    data: function () {
      return {
        isLoading: false,
        itemId: null,
        isModalShown: false,


        // Описание заголовка таблицы
        tableColumns: [
          {title: 'ID', key: 'id'},
          {title: 'Название предмета', key: 'name', sortable: true},
          {title: 'Действия', key: 'actions', slot: 'actions'},
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

      // получение списка групп
      getItems() {
        this.isLoading = true;
        axios.get("/api/v1/study-subjects", {
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
          this.isModalShown = false;
        });
      },

      // создание предмета
      createItem() {
        this.itemId = null;
        this.isModalShown = true;
      },

      // редактирование предмета
      editItem(id) {
        this.itemId = id;
        this.isModalShown = true;
      },

      //удаление предмета
      deleteItem(itemId) {
        this.$Modal.confirm({
          title: 'Подтверждение!',
          loading: false,
          content : `Вы действительно хотите удалить предмет #${itemId} ?`,
          onOk: () => {
            this.isLoading = true;

            axios({
              method: 'delete',
              url:  "/api/v1/study-subjects/" + itemId,
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
          return 'Форма редактирования предмета';
        }

        return 'Форма создания предмета';
      }

      // конец методов
    },

    computed: {
      totalPages() {
        return this.tableTotal > 0 ? Math.ceil(this.tableData.length / this.perPage) : 1;
      },
    },

    created() {
      this.getItems();
    }
  }
</script>
