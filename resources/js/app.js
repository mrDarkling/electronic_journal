/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Vue.js
import Vue from 'vue';

// Подключаю Snotify библиотеку
import Snotify, { SnotifyPosition } from 'vue-snotify'

// Подключаю iview
import ViewUI from 'view-design';
import locale from 'view-design/dist/locale/ru-RU';

// Подключаю axios
import axios from 'axios'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


Vue.use(ViewUI, { locale });
/*
Vue.use(ViewUI, {
  locale: locale,
  transfer: true,
  size: 'large',
  capture: false,
  select: {
    arrow: 'md-arrow-dropdown',
    arrowSize: 20
  }
});
*/

const options = {toast: {position: SnotifyPosition.rightTop}};
Vue.use(Snotify, options);

import Home from './components/Home';
import Students from './components/Students';
import StudentsGroups from './components/StudentsGroups';
import Users from './components/Users';
import Navigation from './components/shared/Navigation';
import StudyWeeks from './components/StudyWeeks';
import StudySubjects from './components/StudySubjects';


Vue.component('Home', Home);
Vue.component('Students', Students);
Vue.component('StudentsGroups', StudentsGroups);
Vue.component('Users', Users);
Vue.component('Navigation', Navigation);
Vue.component('StudyWeeks', StudyWeeks);
Vue.component('StudySubjects', StudySubjects);

// Экземпляр Vue.js
const app = new Vue({
  el: '#app',
});
