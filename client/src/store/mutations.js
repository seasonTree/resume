export default {
    setUserInfo(state, value){
        state.user = value;
        window.sessionStorage.setItem('_user', JSON.stringify(value));
    },

    clearUserInfo(state, value){
        state.user = null;
        window.sessionStorage.removeItem('_user');
    },

    //设置功能权限
    setActions(state, value){
        state.btn_action = value;
    },
}