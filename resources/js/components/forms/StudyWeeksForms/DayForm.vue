<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <Form ref="form" :model="form" :rules="formRules" :label-width="200">
      <FormItem prop="created_at" label="Дата">
        <DatePicker type="date"
                    format="yyyy-MM-dd"
                    :disabled="isLoading"
                    v-model="form.created_at"
                    placeholder="Выбрать дату"
                    style="width: 200px" />
      </FormItem>
      <FormItem>
        <Button type="primary" @click="saveFormData()">Сохранить</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
  export default {
    name: "DayForm",
    props:  ['weekId', 'itemId' ],

    data: function () {
      return {
        isLoading: false,
        form_title: '',

        // Форма
        form: {
          created_at: null,
          study_week_id: null,
        },
        formRules: {
        },
      }
    },

    methods: {
      cleanAddFieldForm() {
        this.form = {
          created_at: null,
          study_week_id: null,
        };
      },

      getItem() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/study-days/' + this.itemId,
          headers: window.api.apiHeader
        }).then((res) => {
          this.isLoading = false;
          this.form = res.data.data;
        }, (err) => {
          console.log(err);
          this.isLoading = false;
        });
      },

      saveFormData() {
        let fd = new FormData();

        // сконвертировать JS-объект в FormData объект
        fd.append('created_at', this.formatDate(this.form.created_at));
        fd.append('study_week_id', this.form.study_week_id);

        if (this.itemId) {
          fd.append('_method', 'PUT');
        }

        this.$refs['form'].validate((valid) => {
          if (valid) {
            axios({
              method: 'post',
              url: this.itemId ? '/api/v1/study-days/' + this.itemId : '/api/v1/study-days',
              data: fd,
              headers: window.api.apiHeader
            }).then((res) => {
              this.cleanAddFieldForm();
              this.$snotify.success('Запись сохранена!');
              this.$emit('save');
            }, (err) => {
              console.log(err.response);
              if (err.response && err.response.data.errors) {
                for (let key in err.response.data.errors) {
                  this.$Message.error(err.response.data.errors[key].join(', '));
                }
              } else {
                this.$Message.error(err.response.data.message || `Ошибка отправки данных!`);
              }
            });
          } else {
            this.$Message.error('Ошибка валидации формы!');
            return;
          }
        })
      },

      formatDate(date) {
        if (date) {
          let months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
          let month = months[date.getMonth()];
          let day = date.getDate() < 10 ? '0' + date.getDate() : date.getDate();

          return date.getFullYear()
            + '-' + month
            + '-' + day;
        }

        return null;
      },

    },

    mounted() {
      this.form.study_week_id = this.weekId;
      if (this.itemId) {
        this.getItem();
      }
    }
  }
</script>

<style scoped>
  .spin-icon-load {
    animation: ani-demo-spin 1s linear infinite;
  }
</style>
