<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <Form ref="form" :model="form" :rules="getValidationRules()">
      <FormItem prop="username" label="Имя пользователя">
        <Input :disabled="isLoading" type="text" v-model="form.username" placeholder="Введите Имя пользователя"></Input>
      </FormItem>
      <FormItem prop="email" label="E-mail">
        <Input :disabled="isLoading" type="text" v-model="form.email" placeholder="Введите E-mail пользователя"></Input>
      </FormItem>
      <FormItem prop="password" label="Пароль">
        <Input :disabled="isLoading" type="password" v-model="form.password" placeholder="Введите пароль"></Input>
      </FormItem>
      <FormItem prop="roles" label="Роль">
        <Select :disabled="isLoading" v-model="form.roles" placeholder="Выберите роль" filterable clearable>
          <Option v-for="role in userRoles" :key="role.id" :value="role.value">{{ role.name }}</Option>
        </Select>
      </FormItem>
      <FormItem>
        <Button type="primary" @click="saveFormData()">Сохранить</Button>
      </FormItem>
    </Form>
  </div>
</template>

<script>
  export default {
    name: "UsersForm",
    props:  ['itemId' ],

    data: function () {
      return {
        isLoading: false,
        form_title: '',
        enabled:false,
        userRoles: [],

        // Форма
        form: {
          username: '',
          email: '',
          password: '',
          roles: null,
        },
        // Валидация при создании нового пользователя

        formRulesNew: {
          username: [
            { required: true, message: 'Пожалуйста, заполните поле "Имя пользователя"', trigger: 'blur' },
            { type: 'string', min: 2, message: 'Длина поля "Имя пользователя" не может быть менее 2 символов', trigger: 'blur' }
          ],
          email: [
            { required: true, message: 'Пожалуйста, заполните поле "E-mail"', trigger: 'blur' },
            { type: 'string', min: 5, message: 'Длина поля "E-mail" не может быть менее 5 символов', trigger: 'blur' }
          ],
          password: [
            { required: true, message: 'Пожалуйста, заполните поле "Пароль"', trigger: 'blur' },
            { type: 'string', min: 6, message: 'Длина поля "Пароль" не может быть менее 6 символов', trigger: 'blur' }
          ],
          roles: [
            { required: true, type: 'string', message: 'Пожалуйста, заполните поле "Роль"', trigger: 'blur' },
          ],
        },
        // Валидация при редактировании
        formRulesEdit: {
          username: [
            { required: true, message: 'Пожалуйста, заполните поле "Имя пользователя"', trigger: 'blur' },
            { type: 'string', min: 2, message: 'Длина поля "Имя пользователя" не может быть менее 2 символов', trigger: 'blur' }
          ],
          email: [
            { required: true, message: 'Пожалуйста, заполните поле "E-mail"', trigger: 'blur' },
            { type: 'string', min: 5, message: 'Длина поля "E-mail" не может быть менее 5 символов', trigger: 'blur' }
          ],
          password: [
            { type: 'string', min: 6, message: 'Длина поля "Пароль" не может быть менее 6 символов', trigger: 'blur' }
          ],
          roles: [
            { required: true, type: 'string', message: 'Пожалуйста, заполните поле "Роль"', trigger: 'blur' },
          ],
        },
      }
    },

    methods: {
      getValidationRules() {
        return this.itemId ? this.formRulesEdit : this.formRulesNew;
      },

      cleanAddFieldForm() {
        this.form = {
          username: '',
          email: '',
          password: null,
          group_id: null,
        };
      },

      getItem() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/users/' + this.itemId,
          headers: window.api.apiHeader
        }).then((res) => {
          this.isLoading = false;
          this.form = res.data.data;
        }, (err) => {
          console.log(err);
          this.isLoading = false;
        });
      },

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

      saveFormData() {
        let fd = new FormData();

        // сконвертировать JS-объект в FormData объект
        for (let key in this.form) {
          if (this.form.hasOwnProperty(key)) {
            if (this.form[key] !== undefined && this.form[key] !== null) {
              fd.append(key, this.form[key]);
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
              url: this.itemId ? '/api/v1/users/' + this.itemId : '/api/v1/users',
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
      this.getRolesList();

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
