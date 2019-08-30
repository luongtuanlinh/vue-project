require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import locale from 'element-ui/lib/locale/lang/vi';
import ElementUI from 'element-ui';
import axios from 'axios';
import * as filters from './filters'; // global filters
import Select2 from 'v-select2-component';
import money from 'v-money';

Vue.use(VueRouter);
Vue.use(ElementUI, { locale });
Vue.use(money, {decimal: '.',thousands: ',',prefix: '',suffix: '',precision: 0,masked: false});
Vue.component('select2', Select2);

// register global utility filters.
Object.keys(filters).forEach(key => {
    Vue.filter(key, filters[key]);
});



import routers from './routes';

const router = new VueRouter({
    mode: 'history',
    routes: routers
})

const app = new Vue({
    el: '#app',
    router
});
