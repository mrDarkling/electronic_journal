<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <Form ref="form" :model="form" :rules="formRules">
      <FormItem prop="name" label="Название группы">
        <Input :disabled="isLoading" type="text" v-model="form.name" placeholder="Введите название группы"></Input>
      </FormItem>

      <FormItem>
        <Button type="primary" @click="saveFormData()">Сохранить</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
  export default {
    name: "StudentsGroupForm",
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
            { required: true, message: 'Пожалуйста, заполните поле "Название группы"', trigger: 'blur' },
            { type: 'string', min: 2, message: 'Длина поля "Название группы" не может быть менее 2 символов', trigger: 'blur' }
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
          url: '/api/v1/students-groups/' + this.itemId,
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
        for (let key in this.form) {
          if (this.form.hasOwnProperty(key)) {
            if (this.form[key] !== undefined && this.form[key] !== null) {
              fd.append(key, this.form[key]);
            } else {
              fd.append(key, "");
            }
          }
        }

        if (this.itemId) {
          fd.append('_method', 'PUT');
        }
        this.$refs['form'].validate((valid) => {
          if (valid) {
            axios({
              method: 'post',
              url: this.itemId ? '/api/v1/students-groups/' + this.itemId : '/api/v1/students-groups',
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
