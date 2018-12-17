import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
//因为全局路由守卫不能获取this，这里直接使用方法来获取是否登录
import {
    getUserInfo,
    getUserPermission
} from '../api/user';

Vue.use(VueRouter);

//获取菜单按钮功能
const getMenuData = (data, menu, action) => {
    let quickTarget = {};

    for (var i = 0; i < data.length; i++) {
        var item = data[i];

        if (item.p_type == 0) {
            quickTarget[item.id] = {
                url: item.url,
                name: item.p_name,
                icon: item.p_icon,
                parent_id: item.parent_id
            };
        } else if (item.p_type == 1) {
            action[item["p_act_name"]] = true;
        }
    }

    for (var key in quickTarget) {
        var item = quickTarget[key],
            parent_id = item.parent_id;

        if (item.parent_id == 0) {
            menu.push(item);
        } else if (parent_id != 0 && quickTarget[parent_id]) {
            quickTarget[parent_id].children =
                quickTarget[parent_id].children || [];
            quickTarget[parent_id].children.push(item);
        }
    }
};

//生成动态菜单
const genRoute = async (router, store) => {

    let suceess = true;
    try {
        //同步获取数据，锁死整个页面
        let resp = await getUserPermission();

        if (resp.code == 0) {
            console.log('*********************');
            console.log(resp)
        } else {
            suceess = false;
        }

    } catch (e) {
        suceess = false;
    } finally {
        return false;

        // return suceess;
    }
}

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
            },
            // {
            //     path: '403',
            //     component: () =>
            //         import('@view/error/Forbidden'),
            // },
            // {
            //     path: '404',
            //     component: () =>
            //         import('@view/error/NotFound'),
            // },
            // {
            //     //test
            //     path: '/test',
            //     component: () =>
            //         import('@view/test/Index'),
            //     meta: {
            //         name: '登录',
            //     }
            // }
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
                paths: [{
                    name: '简历管理'
                }]
            }
        }]
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
                    paths: [{
                        name: '用户管理'
                    }]
                }
            },
            {
                path: 'role',
                component: () =>
                    import('@view/role/Index'),
                meta: {
                    name: '用户角色',
                    paths: [{
                        name: '用户管理'
                    }]
                }
            },
            {
                path: 'permission',
                component: () =>
                    import('@view/permission/Index'),
                meta: {
                    name: '用户权限',
                    paths: [{
                        name: '用户管理'
                    }]
                }
            },
        ]
    },
    {
        path: '/report',
        component: () => import('@view/layout/Layout'),
        redirect: '/report/personal_recruitment',

        children: [{
            path: 'personal_recruitment',
            component: () =>
                import('@view/report/PersonalRecruitment'),
            meta: {
                name: '个人招聘统计',
                paths: [{
                    name: '报表'
                }]
            }
        }, ]
    },
    {
        path: "/error",
        component: () =>
            import('@view/error/Error'),
    },
    {
        path: "/404",
        component: () =>
            import('@view/error/NotFound'),
    }
];

// const routes = [
//     {
//         //登陆
//         path: '/login',
//         component: () =>
//             import('@view/login'),
//         meta: {
//             name: '登录',
//         }
//     },
//     {
//         path: '/',
//         component: () => import('@view/layout/Layout'),
//         redirect: '/dashboard',

//         children: [{
//             path: 'dashboard',
//             component: () =>
//                 import('@view/dashboard/Index'),
//             meta: {
//                 name: '首页',
//                 icon: "list-alt"
//             },
//         }]
//     },
//     {
//         path: '/403',
//         component: () =>
//             import('@view/error/Forbidden'),
//     },
//     {
//         path: '/404',
//         component: () =>
//             import('@view/error/Forbidden'),
//     },
//     {
//         "path": "*",
//         "redirect": "/404",
//         "hidden": true
//     }
// ];

const router = new VueRouter({
    routes
});

router.beforeEach(async (to, from, next) => {
    let toPath = to.path;

    if (toPath == '/login') {
        next();
    } else {
        try {
            let user = store.getters.userInfo;

            if(!user){
                let resp = await getUserInfo();

                if(resp.code == 0){


                }else{
                    router.replace('/error');
                    store.commit('clearUserInfo');
                }
            }

            //异步改同步，锁死他
            

            if (resp.code == 0) {

                let menu = store.getters.menu;

                if (menu) {
                    let suceess = genRoute();

                    if (suceess) {
                        next();
                    } else {
                        //调到出错页面
                        // router.replace('/login');        
                    }

                } else {
                    await genRoute(router, store);
                    next();
                }
            } else {
                router.replace('/login');
                store.commit('clearUserInfo');
            }

        } catch (e) {
            console.log('eeeeeeeeeeeeeee');
            console.log(e);

            router.replace('/login');
            store.commit('clearUserInfo');
        }

        // getUserInfo().then((res) => {
        //     if (res.code == 0) {
        //         let sessionUserInfo = window.sessionStorage.getItem('_user'),
        //             userInfo = JSON.stringify(res.data);

        //         if (sessionUserInfo != userInfo) {
        //             window.sessionStorage.setItem('_user', userInfo);
        //         }

        //         next();
        //     } else {
        //         window.sessionStorage.removeItem('_user');
        //         router.replace('/login');
        //     }

        // }).catch((res) => {
        //     window.sessionStorage.removeItem('_user');

        //     router.replace('/login');
        // });
    }
});



export default router;