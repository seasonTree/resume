export default {
    userInfo(state) {
        let user = state.user,
            //todo，这里要改成用token做key
            sessionUserInfo = window.sessionStorage.getItem('_user');

        if (!user && sessionUserInfo) {
            state.user = JSON.parse(sessionUserInfo);
        }
        return user;
    },

    //获取菜单
    menu(state) {
        let menu = state.menu,
            //todo，这里要改成用token做key
            sessionMenu = window.sessionStorage.getItem('6666666');

        if (!menu && sessionMenu) {
            state.menu = JSON.parse(sessionMenu);
        }
        return menu;
    },

    //功能权限
    checkAction(state) {
        // let action = state.btn_action;
        // return action;
    },
}