<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">Учебные недели</h3>
        <p class="panel-subtitle">создание, удаление и редактирование учебных недель</p>

        <Button class="float-left mt-1 mb-1" type="primary" @click="addWeek()">
          Добавить неделю
        </Button>
        <Button :disabled="isLoading || !hasChanges" type="primary" @click="saveClasses()" class="float-right mt-1 mb-1"
                title="Сохранить изменения">
          Сохранить
        </Button>
      </div>

      <div class="panel-body">
        <div v-if="tableList && tableList.length > 0" class="mt-3">
          <Collapse v-model="collapse">
            <Panel v-for="(row, index) in tableList" :key="row.id" :name="`${index}`">
              <span class="float-left mr-1">{{ row.is_even === 1 ? 'Четная неделя' : 'Нечетная неделя' }}</span>

              <span class="float-right mr-1">
                <Button type="error"
                        shape="circle"
                        icon="md-trash"
                        @click.stop.prevent="removeWeek(row.id)"
                        title="Удалить неделю"></Button>
              </span>
              <span class="float-right mr-1">
                <Button type="primary"
                        shape="circle"
                        icon="md-create"
                        @click.stop.prevent="editWeek(row.id)"
                        title="Редактировать неделю"
                ></Button>
              </span>
              <div slot="content">
                <div v-if="row.days.length === 0" class="text-center">
                  <Button size="large"
                          icon="md-add"
                          type="success"
                          shape="circle"
                          title="Добавить новый день"
                          @click.stop.prevent="addDay(row.id)"
                  ></Button>
                </div>

                <Collapse accordion v-model="days_show" v-for="(study_day, sd_index) in row.days" :key="study_day.id">
                  <Panel :name="`${index}-${sd_index}`">
                    {{ getStudyDay(study_day.created_at) }} - {{ study_day.created_at }}

                    <span class="float-right mr-1">
                      <Button type="error"
                              shape="circle"
                              icon="md-trash"
                              title="Удалить день учебной недели"
                              @click.stop.prevent="removeDay(study_day.id)"
                      ></Button>
                      <Button type="primary"
                              shape="circle"
                              icon="md-create"
                              @click.stop.prevent="editDay(row.id, study_day.id)"
                              title="Редактировать день учебной недели"
                      ></Button>
                      <Button :disabled="isDisabled(sd_index, row.days)"
                              type="success"
                              shape="circle"
                              icon="md-add"
                              title="Создание дня учебной недели"
                              @click.stop.prevent="addDay(row.id)"
                      ></Button>
                    </span>
                    <div slot="content">
                      <div v-if="row.days[sd_index].study_classes.length === 0" class="text-center">
                        <Button size="large"
                                icon="md-add"
                                type="success"
                                shape="circle"
                                title="Добавить новое расписание учебного дня"
                                @click.stop.prevent="addClass(index, sd_index)"
                        ></Button>
                      </div>

                      <table v-else class="table tb-places">
                        <tr>
                          <th class="text-center">№ пп</th>
                          <th class="text-center">Начало занятий</th>
                          <th class="text-center">Конец занятий</th>
                          <th class="text-center">Предмет</th>
                          <th class="text-center">Группа</th>
                          <th class="text-center" style="width: 150px;">Действия</th>
                        </tr>
                        <tr :key="study_class.id" v-for="(study_class, study_class_index) in study_day.study_classes">
                          <td class="text-center">
                            {{ study_class_index + 1 }}
                          </td>
                          <td class="text-left">
                            <TimePicker type="time"
                                        :disabled="isLoading"
                                        format="HH:mm"
                                        placeholder="Время начала занятия"
                                        v-model="study_class.start_time"
                                        @on-change="onClassesChange" />
                          </td>
                          <td class="text-left">
                            <TimePicker type="time"
                                        :disabled="isLoading"
                                        format="HH:mm"
                                        placeholder="Время конца занятия"
                                        v-model="study_class.end_time"
                                        @on-change="onClassesChange" />
                          </td>
                          <td class="ml-2 text-left">
                            <Select :disabled="isLoading"
                                    v-model="tableRows[index].days[sd_index].study_classes[study_class_index].study_subject_id"
                                    placeholder="Выберите предмет"
                                    filterable
                                    clearable
                                    @on-change="onClassesChange">
                              <Option v-for="subject in studySubjects"
                                      :key="subject.id"
                                      :value="subject.id">{{ subject.id }} - {{ subject.name }}</Option>
                            </Select>
                          </td>
                          <td v-if="tableRows[index].days[sd_index].study_classes[study_class_index]" class="ml-2 text-left">
                            <Select :disabled="isLoading"
                                    v-model="tableRows[index].days[sd_index].study_classes[study_class_index].group_id"
                                    placeholder="Выберите группу"
                                    filterable
                                    clearable
                                    @on-change="onClassesChange">
                              <Option v-for="group in studentsGroups"
                                      :key="group.id"
                                      :value="group.id">{{ group.id }} - {{ group.name }}</Option>
                            </Select>
                          </td>
                          <td class="text-left ml-2">
                            <!-- Actions -->
                            <Button :disabled="isLoading"
                                    type="error"
                                    shape="circle"
                                    icon="md-remove"
                                    title="Удалить расписание дня учебной недели"
                                    @click="delClass(index, sd_index, study_class_index)">
                            </Button>
                            <Button v-if="study_class_index +1 === study_day.study_classes.length" :disabled="isLoading"
                                    type="primary"
                                    shape="circle"
                                    icon="md-add"
                                    title="Добавить расписание дня учебной недели"
                                    @click="addClass(index, sd_index)">

                            </Button>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </Panel>
                </Collapse>
              </div>
            </Panel>
          </Collapse>

          <Page :total="tableTotal"
                :current="currentPage"
                :page-size="perPage"
                @on-change="changePage"
                v-if="totalPages > 1"
                class="mt-3 mb-1 text-center" />

        </div>

        <div v-else class="text-center mt-4">
          <span>Нет данных</span>
        </div>
      </div>

      <BackTop></BackTop>

      <!-- Формы -->
      <Modal v-model="isFormWeekShow" :closable="true" :footer-hide="true" :title="getWeekFormTitle()" width="60%">
        <StudyWeeksForm v-if="isFormWeekShow" :itemId="weekId" @save="getItems()" />
      </Modal>

      <Modal v-model="isFormDayShow" :closable="true" :footer-hide="true" :title="getDayFormTitle()" width="60%">
        <StudyDayForm v-if="isFormDayShow" :weekId="weekId" :itemId="dayId" @save="getItems()" />
      </Modal>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  import StudyWeeksForm from './forms/StudyWeeksForms/WeekForm'
  import StudyDayForm from './forms/StudyWeeksForms/DayForm'
  export default {
    components: {StudyWeeksForm, StudyDayForm},
    data: function() {
      return {
        days_show: [0],
        days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
        isLoading: false,
        dialog:false,
        weekId: null,
        dayId: null,
        collapse: [0],
        studentsGroups: [],
        hasChanges: false,
        isFormWeekShow: false,
        isFormDayShow: false,
        studySubjects: [],

        // таблица
        tableTotal: 0,
        tableRows: [],
        tableList: [],

        // пейджинация
        currentPage: 1,
        perPage: 10,

      }
    },
    methods: {
      // Получение списка страниц
      getPageList(p, size) {
        return this.tableRows.slice((p - 1) * size, p * size);
      },

      // Переключение страницы
      changePage(p) {
        this.currentPage = p;
        this.tableList = this.getPageList(p, this.perPage);
      },

      getItems() {
        this.isFormWeekShow = false;
        this.isFormDayShow = false;
        this.isLoading = true;

        axios.get("/api/v1/study-weeks", {
          headers: window.api.apiHeader,
        }).then((res) => {
          this.tableRows = res.data.data;
          this.tableTotal = this.tableRows.length;
          this.changePage(1);
          this.isLoading = false;
        }, (err) => {
          console.log(err);
          alert("Ошибка получения данных!");
          this.isLoading = false;
        });
      },

      loadStudentsGroups() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/students-groups',
          headers: window.api.apiHeader
        }).then((res) => {
          if (res.data.data.length > 0) {
            this.studentsGroups = res.data.data;
          }
          this.isLoading = false;
        }, (err) => {
          console.log(err);
          this.isLoading = false;
        });
      },

      loadStudySubjects() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/study-subjects',
          headers: window.api.apiHeader
        }).then((res) => {
          if (res.data.data.length > 0) {
            this.studySubjects = res.data.data;
          }
          this.isLoading = false;
        }, (err) => {
          console.log(err);
          this.isLoading = false;
        });
      },

      addWeek() {
        this.weekId = null;
        this.isFormWeekShow = true;
      },

      editWeek(id){
        this.weekId = id;
        this.isFormWeekShow = true;
      },

      removeWeek(id) {
        this.$Modal.confirm({
          title: 'Подтверждение!',
          loading: false,
          content : `Вы действительно хотите удалить учебную неделю #${id} ?`,
          onOk: () => {
            this.isLoading = true;

            axios({
              method: 'delete',
              url: `/api/v1/study-weeks/${id}`,
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

      addDay(weekId) {
        this.dayId = null;
        this.weekId = weekId;
        this.isFormDayShow = true;
      },

      editDay(weekId, id){
        this.dayId = id;
        this.weekId = weekId;
        this.isFormDayShow = true;
      },

      removeDay(id) {
        this.$Modal.confirm({
          title: 'Подтверждение!',
          loading: false,
          content : `Вы действительно хотите удалить день учебной недели #${id} ?`,
          onOk: () => {
            this.isLoading = true;

            axios({
              method: 'delete',
              url: `/api/v1/study-days/${id}`,
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

      addClass(sw_index, sd_index) {
        this.hasChanges = true;
        this.tableRows[sw_index].days[sd_index].study_classes.push({
          start_time: null,
          end_time: null,
          group_id: null,
          study_day_id: this.tableRows[sw_index].days[sd_index].id
        });
      },

      delClass(sw_index, sd_index, sc_index) {
        this.hasChanges = true;
        this.tableRows[sw_index].days[sd_index].study_classes.splice(sc_index, 1);
      },

      saveClasses() {
        this.hasChanges = false;
        let sendData = [];

        for (let index in this.tableRows) {
          for (let sd_index in this.tableRows[index].days) {
            sendData[this.tableRows[index].days[sd_index].id] = this.tableRows[index].days[sd_index].study_classes;
          }
        }

        axios({
          method: 'post',
          url: '/api/v1/study-weeks/mass-update',
          data: sendData,
          headers: window.api.apiHeader
        }).then((res) => {
          if (res.data.length === 0) {
            this.$snotify.success('Запись сохранена!');
            this.getItems();
          } else {
            this.hasChanges = true;
            for (let i in res.data) {
              this.$snotify.error(`Учебный день "${res.data[i].day_id}", ошибка: ${res.data[i].error}`);
            }
          }
        }, (err) => {
          this.$snotify.error(err.response.data.message || `Ошибка отправки данных!`);
        });
      },

      onClassesChange() {
        this.hasChanges = true;
      },

      getStudyDay(ymd) {
        let d = new Date(ymd);
        if(d) {
          return this.days[d.getDay()];
        }
      },

      isDisabled(sd_index, days) {
        return !(days && (days.length === (sd_index + 1) && (sd_index + 1) < 7));
      },

      getWeekFormTitle() {
        if (this.weekId) {
          return 'Форма редактирования учебной недели';
        }
        return 'Форма создания учебной недели';
      },

      getDayFormTitle() {
        if (this.dayId) {
          return 'Форма редактирования дня учебной недели';
        }
        return 'Форма создания дня учебной недели';
      },
    },

    computed: {
      totalPages() {
        return this.tableTotal > 0 ? Math.ceil(this.tableRows.length / this.perPage) : 1;
      },
    },

    created() {
      this.loadStudySubjects();
      this.loadStudentsGroups();
      this.getItems();
    }
  }
</script>

<style>
  .ivu-collapse>.ivu-collapse-item>.ivu-collapse-header {
    height: 45px!important;
  }
</style>
