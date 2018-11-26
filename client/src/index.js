import Vue from 'vue';
import App from './App';
import store from './store';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import router from './router';
import api from './api';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import './config/icon';

//图标
Vue.component('font-awesome-icon', FontAwesomeIcon);
Vue.config.productionTip = false;

//远程api
Vue.use(api);

Vue.use(ElementUI);

new Vue({
	el: '#app',
	router,
	store,
	template: '<App/>',
	components: { App }
});
