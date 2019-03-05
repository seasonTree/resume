import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
//组件模块登记
import components from "./components";
import {
    Loading
} from 'element-ui';

//因为全局路由守卫不能获取this，这里直接使用方法来获取是否登录
import {
    getUserInfo,
    getUserPermission
} from "../api/user";

Vue.use(VueRouter);

//快速索引，用于router的地址是否存在
// const urlArr = {
//     "/login": true,
//     "/dashboard": true,
//     "/error": true,
//     "/404": true,
// }

//基础的路由
const routes = [{
        //登陆
        path: "/login",
        component: () => import("@view/login"),
        meta: {
            name: "登录"
        }
    },
    {
        path: "/",
        component: () => import("@view/layout/Layout"),
        redirect: "/dashboard",

        children: [{
            path: "dashboard",
            component: () => import("@view/dashboard/Index"),
            meta: {
                name: "首页",
                icon: "list-alt"
            }
        }]
    },
    {
        path: "/error",
        component: () => import("@view/error/Error"),
        meta: {
            notAuth: true
        }
    },
    //去除404页面，等待登陆后自动添加，未登录一直跳登录页面
    // {
    //     path: "/404",
    //     component: () => import("@view/error/NotFound"),
    //     meta: {
    //         notAuth: true
    //     }
    // }
];

//获取菜单按钮功能
//使用的数组一定要保持顺序
const getMenuData = data => {
    let quickTarget = {},
        //做多份，防止menu里面过多的数据，用于处理路由的
        routerQuickTarget = {},
        action = {},
        menu = [{
            url: "/dashboard",
            name: "首页",
            icon: "fa fa-list-alt"
        }],
        routerArr = [];

    for (var i = 0; i < data.length; i++) {
        var item = data[i];

        if (item.p_type == 0) {
            //菜单
            quickTarget[item.id] = {
                url: item.url,
                name: item.p_name,
                icon: item.p_icon,
                parent_id: item.parent_id
            };

            //处理路由的 -------------------------------------
            routerQuickTarget[item.id] = {
                path: item.url,
                meta: {
                    name: item.p_name
                }
            };

            if (item.parent_id == 0) {
                //父
                routerQuickTarget[item.id]["component"] = resolve =>
                    require.ensure([], () =>
                        resolve(require("@view/layout/Layout"))
                    );
                routerQuickTarget[item.id]["redirect"] = `${item.url}/index`;
            } else {
                //子
                if (item.p_component) { //如果子页面注册了组件，表示这个是个功能页面
                    //无法使用webpage的import的预编译,所以要预先定义组件列表
                    routerQuickTarget[item.id]["component"] = components[item.p_component];

                    //注入url快速索引表，用于检查页面
                    // urlArr[item.url] = true;
                }
            }

            //----------------------------------------------
        } else if (item.p_type == 1) {
            action[item["p_act_name"]] = true;
        }
    }

    for (var key in quickTarget) {
        var item = quickTarget[key],
            parent_id = item.parent_id,
            routerItem = routerQuickTarget[key];

        if (item.parent_id == 0) {
            menu.push(item);

            //追加父路由
            routerArr.push(routerItem);
        } else if (parent_id != 0 && quickTarget[parent_id]) {
            quickTarget[parent_id].children =
                quickTarget[parent_id].children || [];
            quickTarget[parent_id].children.push(item);

            //路由------------------------------------------
            routerQuickTarget[parent_id].children =
                routerQuickTarget[parent_id].children || [];
            routerQuickTarget[parent_id].children.push(routerItem);

            //处理子路由
            if (parent_id != 0) {
                //处理面包屑
                var parentItem = routerQuickTarget[parent_id];
                routerItem.meta.paths = [
                    ...(parentItem.meta.paths || []),
                    {
                        name: parentItem.meta.name
                    }
                ];

                //处理子路由
                routerItem.path = routerItem.path.replace(
                    parentItem.path + "/",
                    ""
                );
            }

            //---------------------------------------------
        }
    }

    //最后追加404页面
    routerArr.push({
        path: "*",
        component: () => import("@view/error/NotFound"),
        meta: {
            notAuth: true
        }
    });

    return {
        action,
        menu,
        routerArr
    };
};

//生成动态菜单
const genRoute = async (router, store) => {
    let suceess = true;
    try {
        //同步获取数据，锁死整个页面
        let resp = await getUserPermission(),
            pdata = resp.data;

        if (resp.code == 0) {
            let mdata = getMenuData(pdata, menu, action),
                {
                    menu,
                    action,
                    routerArr
                } = mdata;

            store.commit("setMenu", menu);
            store.commit("setActions", action);

            router.addRoutes(routerArr);

            // router.addRoutes([{
            //         path: '/resume',
            //         component: () => import('@view/layout/Layout'),
            //         redirect: '/resume/index',
            //         meta: {
            //             name: '简历管理',
            //         },

            //         children: [{
            //             path: 'index',
            //             component: () =>
            //                 import('@view/resume/Index'),
            //             meta: {
            //                 name: '简历信息',
            //                 paths: [{
            //                     name: '简历管理'
            //                 }]
            //             }
            //         }]
            //     },
            //     {
            //         path: '/user',
            //         component: () => import('@view/layout/Layout'),
            //         redirect: '/user/index',

            //         children: [{
            //                 path: 'index',
            //                 component: () =>
            //                     import('@view/user/Index'),
            //                 meta: {
            //                     name: '用户信息',
            //                     paths: [{
            //                         name: '用户管理'
            //                     }]
            //                 }
            //             },
            //             {
            //                 path: 'role',
            //                 component: () =>
            //                     import('@view/role/Index'),
            //                 meta: {
            //                     name: '用户角色',
            //                     paths: [{
            //                         name: '用户管理'
            //                     }]
            //                 }
            //             },
            //             {
            //                 path: 'permission',
            //                 component: () =>
            //                     import('@view/permission/Index'),
            //                 meta: {
            //                     name: '用户权限',
            //                     paths: [{
            //                         name: '用户管理'
            //                     }]
            //                 }
            //             },
            //         ]
            //     },
            //     {
            //         path: '/report',
            //         component: () => import('@view/layout/Layout'),
            //         redirect: '/report/personal_recruitment',

            //         children: [{
            //             path: 'personal_recruitment',
            //             component: () =>
            //                 import('@view/report/PersonalRecruitment'),
            //             meta: {
            //                 name: '个人招聘统计',
            //                 paths: [{
            //                     name: '报表'
            //                 }]
            //             }
            //         }, ]
            //     }
            // ]);
        } else {
            suceess = false;
        }
    } catch (e) {
        suceess = false;
    } finally {
        return suceess;
    }
};

//初始化路由
const router = new VueRouter({
    routes
});

//用于处理刷新后是否重新获取重新渲染页面
let initRouter = false;

router.beforeEach(async (to, from, next) => {
    let toPath = to.path;

    // //检查当前页面url是否存在, 在初始化后调用
    // if (initRouter && !urlArr[toPath]) {
    //     next({
    //         path: "/404"
    //     });
    //     return;
    // }

    //直接过滤掉的页面，不要验证
    if (to.meta.notAuth === true) {
        next();
        return;
    }

    //是否加载中
    let loadingInstance = null;

    //获取当前登录的用户
    let user = store.getters.userInfo;
    if (!user.uname) {
        let checkError = false;

        //加载loading
        loadingInstance = Loading.service({
            fullscreen: true
        });

        try {
            let resp = await getUserInfo(),
                udata = resp.data;

            if (resp.code == 0) {
                user = udata;
                store.commit("setUserInfo", udata);
            } else {
                store.commit("clearUserInfo");
            }

        } catch (error) {
            checkError = true;

        } finally {

            //关闭loading
            loadingInstance && loadingInstance.close();

            if (checkError) {
                router.push("/error");
                return;
            }
        }
    }

    //初始化路由成功后,如果当前用户没有登录的话，跳到登录
    if (user.uname) {
        //初始化菜单------------------------

        if (!initRouter) {
            //加载loading
            loadingInstance = Loading.service({
                fullscreen: true
            });

            let suceess = await genRoute(router, store);

            if (suceess) {
                initRouter = true;
                //一定要写toPath, 用于等待数据返回再次刷新
                next({
                    path: toPath
                });
            } else {
                //跳到出错页面
                router.push("/error");
            }

            loadingInstance && loadingInstance.close();

        } else if (toPath == "/login") {

            //登录后还想跳到登录页面的，直接跳首页
            router.replace("/dashboard");
        } else { //menu都有了直接next
            next();
        }

        //---------------------------
    } else if (toPath != "/login") {
        //没有登录,并且想跳到其他地方，直接跳到登录
        router.replace("/login");
    } else {
        //登录页面直接跳转
        next();
    }
});

export default router;