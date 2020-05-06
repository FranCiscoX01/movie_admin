

require('./bootstrap');
import Axios from 'axios';
window.Vue = require('vue');


import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';
Vue.config.productionTip = false;
Vue.use(Antd);


Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Categoria de Peliculas
 */
Vue.component('category-component', require('./components/categories/CategoryComponent.vue').default);

/**
 * Peliculas/Films
 */
Vue.component('films-component', require('./components/films/FilmsComponent.vue').default);
Vue.component('films-create-component', require('./components/films/FilmsCreateComponent.vue').default);


const app = new Vue({
    el: '#app'
});
