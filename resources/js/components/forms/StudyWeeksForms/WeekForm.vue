<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <Form ref="form" :model="form" :rules="formRules" :label-width="200">
      <FormItem prop="is_even" :label="form.is_even === 1 ? 'Четная неделя' : 'Нечетная неделя'">
        <i-switch :disabled="isLoading" v-model="form.is_even" :true-value="1" :false-value="0" />
      </FormItem>
      <FormItem v-if="!itemId" prop="created_at" label="Дата начала недели">
        <DatePicker type="date"
                    format="yyyy-MM-dd"
                    :disabled="isLoading"
                    v-model="form.created_at"
                    placeholder="Выбрать начало недели"
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
    name: "WeekForm",
    props:  ['itemId' ],

    data: function () {
      return {
        isLoading: false,
        form_title: '',

        // Форма
        form: {
          is_even: 0,
          created_at: null,
        },
        formRules: {
        },
      }
    },

    methods: {
      cleanAddFieldForm() {
        this.form = {
          is_even: false,
          created_at: null,
        };
      },

      getItem() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/study-weeks/' + this.itemId,
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
        fd.append('is_even', this.form.is_even);
        if (!this.itemId) {
          this.form.created_at ? fd.append('created_at', this.formatDate(this.form.created_at)) : null;
        } else {
          fd.append('_method', 'PUT');
        }

        this.$refs['form'].validate((valid) => {
          if (valid) {
            axios({
              method: 'post',
              url: this.itemId ? '/api/v1/study-weeks/' + this.itemId : '/api/v1/study-weeks',
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
