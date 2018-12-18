export default {
    userInfo(state) {
        let user = state.user;
        //     sessionUserInfo = null;

        // if (state.token) {
        //     sessionUserInfo = window.sessionStorage.getItem(state.token)
        // }

        // if (!user && sessionUserInfo) {
        //     state.user = JSON.parse(sessionUserInfo);
        // }

        return user || {};
    },

    //获取菜单
    menu(state) {
        let menu = state.menu;
        return menu || [];
    },

    //功能权限
    checkAction(state) {
        // let action = state.btn_action;
        // return action;
    },
}