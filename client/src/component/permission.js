import store from '../store';

const install = function (Vue) {
    if (install.installed) return
    install.installed = true
    Object.defineProperties(Vue.prototype, {

        $check_pm: {
            get() {
                return (key) =>{
                    return !!store.getters.btn_act[key];
                };
            }
        }
    })
};

export default install;