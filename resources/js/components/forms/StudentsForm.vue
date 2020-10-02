<template>
  <div>
    <Spin fix v-if="isLoading">
      <Icon type="ios-loading" size=82 class="spin-icon-load"></Icon>
      <div>Пожалуйста, подождите...</div>
    </Spin>

    <Form ref="form" :model="form" :rules="formRules">
      <FormItem prop="student_name" label="ФИО студента">
        <Input :disabled="isLoading" type="text" v-model="form.student_name" placeholder="Введите ФИО студента"></Input>
      </FormItem>
      <FormItem prop="record_book" label="№ Зачетной книжки">
        <Input :disabled="isLoading" type="text" v-model="form.record_book" placeholder="Введите номер зачетной книжки"></Input>
      </FormItem>
      <FormItem prop="group_id" label="Группа">
        <Select :disabled="isLoading" v-model="form.group_id" placeholder="Выберите группу" filterable clearable>
          <Option v-for="group in studentsGroups" :key="group.id" :value="group.id">{{ group.id }} - {{ group.name }}</Option>
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
    name: "StudentsForm",
    props:  ['itemId' ],

    data: function () {
      return {
        isLoading: false,
        form_title: '',
        enabled:false,
        studentsGroups: [],

        // Форма
        form: {
          student_name: '',
          record_book: '',
          group_id: null,
        },
        formRules: {
          student_name: [
            { required: true, message: 'Пожалуйста, заполните поле "ФИО студента"', trigger: 'blur' },
            { type: 'string', min: 2, message: 'Длина поля "ФИО студента" не может быть менее 2 символов', trigger: 'blur' }
          ],
          record_book: [
            { required: true, message: 'Пожалуйста, заполните поле "№ Зачетной книжки"', trigger: 'blur' },
            { type: 'string', min: 2, message: 'Длина поля "№ Зачетной книжки" не может быть менее 2 символов', trigger: 'blur' }
          ],
          group_id: [
            { required: true, type: 'integer', message: 'Пожалуйста, заполните поле "Группа"', trigger: 'blur' },
          ],
        },
      }
    },

    methods: {
      cleanAddFieldForm() {
        this.form = {
          student_name: '',
          record_book: '',
          group_id: null,
        };
      },

      getItem() {
        this.isLoading = true;

        axios({
          method: 'get',
          url: '/api/v1/students/' + this.itemId,
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
              url: this.itemId ? '/api/v1/students/' + this.itemId : '/api/v1/students',
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
    },

    mounted() {
      this.loadStudentsGroups();

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
