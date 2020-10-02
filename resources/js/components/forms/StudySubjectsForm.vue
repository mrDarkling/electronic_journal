<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <Form ref="form" :model="form" :rules="formRules">
      <FormItem prop="name" label="Название предмета">
        <Input :disabled="isLoading" type="text" v-model="form.name" placeholder="Введите название предмета"></Input>
      </FormItem>

      <FormItem>
        <Button type="primary" @click="saveFormData()">Сохранить</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
  export default {
    name: "StudySubjectsForm",
    props:  ['itemId' ],

    data: function () {
      return {
        isLoading: false,
        form_title: '',
        enabled:false,
        studentsGroups: [],

        // Форма
        form: {
          name: '',
        },
        formRules: {
          name: [
            { required: true, message: 'Пожалуйста, заполните поле "Предмет"', trigger: 'blur' },
            { type: 'string', min: 2, message: 'Длина поля "Предмет" не может быть менее 2 символов', trigger: 'blur' }
          ],
        },
      }
    },

    methods: {
      cleanAddFieldForm() {
        this.form = {
          name: '',
        };
      },

      getItem() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/study-subjects/' + this.itemId,
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
        fd.append('name', this.form.name);

        if (this.itemId) {
          fd.append('_method', 'PUT');
        }

        this.$refs['form'].validate((valid) => {
          if (valid) {
            axios({
              method: 'post',
              url: this.itemId ? '/api/v1/study-subjects/' + this.itemId : '/api/v1/study-subjects',
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
