export default {
    userInfo(state) {
        let user = state.user,
            sessionUserInfo = window.sessionStorage.getItem('_user');

        if (!user && sessionUserInfo) {
            user = JSON.parse(sessionUserInfo);
            state.user = user;
        }
        return user || {};
    }
}