import Vue from 'vue';
import VueRouter from 'vue-router';
//因为全局路由守卫不能获取this，这里直接使用方法来获取是否登录
import {
    getUserInfo
} from '../api/user';

Vue.use(VueRouter);

//指定name，组织数据方便
const routes = [{
    //登陆
    path: '/login',
    component: () =>
        import('@view/login'),
    meta: {
        name: '登录',
        checkAuth: false
    }
}, {
    path: '/',
    component: () => import('@view/layout/Layout'),
    redirect: '/test',
    meta: {
        name: '首页',
        checkAuth: true,
    },

    //可以component的用page属性，指定router-view的name
    //只支持一个children，不然显示不聊
    children: [
        {
            path: 'test',
            component: () =>
                import('@view/test/Index'),
            meta: {
                name: '测试',
                icon: "list-alt"
            }
        },
    ]
}];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {

    let toPath = to.path;

    if (toPath == '/login') {
        next();
    } else {
        getUserInfo().then((res) => {
            if (res.error == 0) {
                let sessionUserInfo = window.sessionStorage.getItem('_user'),
                    userInfo = JSON.stringify(res.data);

                if (sessionUserInfo != userInfo) {
                    window.sessionStorage.setItem('_user', userInfo);
                }

                next();
            } else {
                window.sessionStorage.removeItem('_user');
                router.replace('/login');
            }

        }).catch((res) => {
            window.sessionStorage.removeItem('_user');

            router.replace('/login');
        });
    }
});

export default router;