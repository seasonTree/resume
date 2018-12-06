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
        }
    },
    {
        path: '/',
        component: () => import('@view/layout/Layout'),
        redirect: '/dashboard',

        children: [{
                path: 'dashboard',
                component: () =>
                    import('@view/dashboard/Index'),
                meta: {
                    name: '首页',
                    icon: "list-alt"
                },
            }, , {
                path: '403',
                component: () =>
                    import('@view/error/Forbidden'),
            },
            {
                path: '404',
                component: () =>
                    import('@view/error/NotFound'),
            }, {
                //test
                path: '/test',
                component: () =>
                    import('@view/test/Index'),
                meta: {
                    name: '登录',
                }
            }
        ]
    },
    {
        path: '/resume',
        component: () => import('@view/layout/Layout'),
        redirect: '/resume/index',
        meta: {
            name: '简历管理',
        },

        children: [{
                path: 'index',
                component: () =>
                    import('@view/resume/Index'),
                meta: {
                    name: '简历信息',
                    paths: [ { name: '简历管理'}]
                }
            },
            {
                path: 'search',
                component: () =>
                    import('@view/resume_search/Index'),
                meta: {
                    name: '简历搜索',
                    paths: [ { name: '简历管理'}]
                }
            },
        ]
    },
    {
        path: '/user',
        component: () => import('@view/layout/Layout'),
        redirect: '/user/index',

        children: [{
                path: 'index',
                component: () =>
                    import('@view/user/Index'),
                meta: {
                    name: '用户信息',
                    paths: [ { name: '用户管理'} ]
                }
            },
            {
                path: 'role',
                component: () =>
                    import('@view/role/Index'),
                meta: {
                    name: '用户角色',
                    paths: [ { name: '用户管理'} ]
                }
            },
            {
                path: 'permission',
                component: () =>
                    import('@view/permission/Index'),
                meta: {
                    name: '用户权限',
                    paths: [ { name: '用户管理'} ]
                }
            },
        ]
    },
    {
        path: '/report',
        component: () => import('@view/layout/Layout'),
        redirect: '/report/index',

        children: [{
                path: 'index',
                component: () =>
                    import('@view/report/Test'),
                meta: {
                    name: '报表测试'
                }
            },
        ]
    },
    {
        "path": "*",
        "redirect": "/404",
        "hidden": true
    }
];

const router = new VueRouter({
    routes
});

router.beforeEach((to, from, next) => {

    let toPath = to.path;

    if (toPath == '/login') {
        next();
    } else {
        getUserInfo().then((res) => {
            if (res.code == 0) {
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