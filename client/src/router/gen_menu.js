const login = {
        //登陆
        path: '/login',
        component: () =>
            import('@view/login'),
        meta: {
            name: '登录',
        }
    },
    dashboard = {
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
        }]
    },
    forbidden = {
        path: '/403',
        component: () =>
            import('@view/error/Forbidden'),
    },
    notFound = {
        path: '/404',
        component: () =>
            import('@view/error/Forbidden'),
    },
    notExists = {
        "path": "*",
        "redirect": "/404",
        "hidden": true
    };

const genRouter = (data, useLayout) => {
    let menu = [login, dashboard];

    if(data && data.length){
        if(useLayout){

        }else{
            menu.push(...data);
        }
    }

    menu.push(forbidden);
    menu.push(notFound);
    menu.push(notExists);
}

export default genRouter;