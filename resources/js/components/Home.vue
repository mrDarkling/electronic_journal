<template>
  <div>
    <div class="panel panel-headline">
      <div class="panel-heading">
        <h3 class="panel-title">Начальная страница</h3>
        <p class="panel-subtitle">список актуальных учебных недель</p>
      </div>

      <div class="panel-body">
        <Row v-if="studyWeeks && studyWeeks.length > 0">
          <Col v-for="(row, index) in studyWeeks" :key="row.id" :span="studyWeeks.length > 0 ? 24 / studyWeeks.length : 24">
            <Collapse v-model="collapse">
              <Panel :name="`${index}`">
                <span class="float-left mr-1">{{ row.is_even === 1 ? 'Четная неделя' : 'Нечетная неделя' }}</span>
                <div slot="content">
                  <div v-if="row.days.length === 0" class="text-center">
                    Нет расписаний
                  </div>
                  <Collapse v-else accordion v-model="days_show" v-for="(study_day, sd_index) in row.days" :key="study_day.id">
                    <Panel :name="`${index}-${sd_index}`">
                      {{ getStudyDay(study_day.created_at) }} - {{ study_day.created_at }}
                      <div slot="content">
                        <div v-if="row.days[sd_index].study_classes.length === 0" class="text-center">
                          Нет расписаний
                        </div>
                        <table v-else class="table tb-places">
                          <tr>
                            <th class="text-center">№ пп</th>
                            <th class="text-center">Начало занятий</th>
                            <th class="text-center">Конец занятий</th>
                            <th class="text-center">Название предмета</th>
                            <th class="text-center">Группа</th>
                          </tr>
                          <tr :key="study_class.id" v-for="(study_class, study_class_index) in study_day.study_classes">
                            <td class="text-center">
                              {{ study_class_index + 1 }}
                            </td>
                            <td class="text-left">
                              {{ study_class.start_time }}
                            </td>
                            <td class="text-left">
                              {{ study_class.end_time }}
                            </td>
                            <td class="text-left">
                              {{ study_class.subject ? study_class.subject.name : 'Не указан' }}
                            </td>
                            <td class="ml-2 text-left">
                              {{ study_class.group ? study_class.group.name : 'Не выбрана' }}
                            </td>
                          </tr>
                        </table>
                      </div>
                    </Panel>
                  </Collapse>
                </div>
              </Panel>
            </Collapse>
          </Col>
        </Row>

        <div v-if="!studyWeeks && !studyWeeks.length > 0" class="text-center mt-4">
          <span>Нет данных</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'
  export default {
    data: function() {
      return {
        days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
        studyWeeks: [],
        collapse: [],
        days_show: [],
      }
    },
    methods: {
      getItems(){
        axios.get("/api/v1/get-current-weeks", {
          headers: window.api.apiHeader,
        }).then((res) => {
          this.studyWeeks = res.data.data;

          if (res.data.data.length > 0) {
            for (let i in res.data.data) {
              this.collapse.push(i);
            }
          }
        }, (err) => {
          console.log(err);
          alert("Ошибка получения данных!");
        });
      },

      getStudyDay(ymd) {
        let d = new Date(ymd);
        if(d) {
          return this.days[d.getDay()];
        }
      },
    },

    created() {
      this.getItems();
    }
  }
</script>
