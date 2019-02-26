import Vue from 'vue';
import App from './App';
import store from './store';
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import router from './router';
import api from './api';
import permission from './component/permission';
import dialogDrag from './component/dialogDrag'
import VCharts from 'v-charts'

//图表
Vue.use(VCharts)

//图标
Vue.config.productionTip = false;

//远程api
Vue.use(api);

Vue.use(ElementUI);

//使用按钮权限
Vue.use(permission);

//添加dialog移动
Vue.use(dialogDrag);


new Vue({
	el: '#app',
	router,
	store,
	template: '<App/>',
	components: { App }
});
