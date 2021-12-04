require('./bootstrap');
require('./demo');
//require('./basket');

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue)

import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
Vue.component('icon', Icon)

//Main pages
import App from './views/app.vue'


/*const app = */new Vue({
    el: '#app',
    components: { App }
});
