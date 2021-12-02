require('./bootstrap');
require('./demo');
//require('./basket');

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

Vue.use(BootstrapVue)

//Main pages
import App from './views/app.vue'


const app = new Vue({
    el: '#app',
    components: { App }
});
