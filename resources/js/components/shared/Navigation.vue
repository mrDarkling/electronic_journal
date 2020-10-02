<template>
<div>
  <!-- NAVBAR -->
  <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 bg-dark shadow-sm sticky-top">
    <h5 class="my-0 mr-md-auto font-weight-normal text-white">Электронный журнал</h5>
    <nav class="my-2 my-md-0 mr-md-3 navbar-fixed-top">
      <a class="p-2 text-white" href="#" @click="redirectTo('home')">Главная</a>
      <a class="p-2 text-white" href="#" @click="redirectTo('study-weeks')">Учебные недели</a>
      <a class="p-2 text-white" href="#" @click="redirectTo('students-groups')">Учебные группы</a>
      <a class="p-2 text-white" href="#" @click="redirectTo('students')">Студенты</a>
      <a class="p-2 text-white" href="#" @click="redirectTo('study-subjects')">Предметы</a>
      <a v-if="isAdmin === '1'" class="p-2 text-white" href="#" @click="redirectTo('users')">Пользователи</a>
    </nav>
    <a class="btn btn-outline-primary" href="#" @click="logout()">Выйти {{getUser()}}</a>
  </div>
  <!-- END NAVBAR -->
</div>

</template>

<script>
  import utils from "./utils.js"
  import axios from "axios";

  export default {
    props: ['url', 'urlTo'],

    data: function() {
      return {
        drawer: false,
        group: [],
      }
    },

    methods: {
      redirectTo(to) {
        utils.redirect(to)
      },
      getUser() {
        return window.currentUser.email;
      },
      logout() {
        let headers = {
          Authorization: window.api.apiHeader.Authorization,
          'X-CSRF-TOKEN': document.querySelector('head meta[name="csrf-token"]').getAttribute('content'),
        };

        this.$Modal.confirm({
          title: 'Подтверждение!',
          loading: false,
          content : `Вы действительно хотите выйти?`,
          onOk: () => {
            this.isLoading = true;
            axios({
              method: 'post',
              url:  this.url,
              headers: headers,
            }).then((res) => {
              location.replace('/')
            }, (err) => {
              this.$Message.error(`Ошибка отправки данных!`);
              this.isLoading = false;
            });
          }
        });
      }
    },

    computed: {
      isAdmin: function () {
        return window.api.isAdmin;
      }
    },
  }
</script>
