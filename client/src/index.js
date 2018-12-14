import Vue from 'vue';
import App from './App';
import store from './store';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import router from './router';
import api from './api';
import button_permission from '@component/button_permission'

//图标
Vue.config.productionTip = false;

//远程api
Vue.use(api);

Vue.use(ElementUI);

//使用权限按钮验证
Vue.use(button_permission);

new Vue({
	el: '#app',
	router,
	store,
	template: '<App/>',
	components: { App }
});
