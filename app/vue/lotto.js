import Vue from 'vue'
import VueResource from 'vue-resource'

import dashboard from './dashboard.vue'

Vue.use(VueResource);
Vue.component('dashboard', dashboard);

var vm = new Vue({
  el: '.boat',
})

window.vm = vm;
