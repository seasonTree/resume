//检查用户是否又权限
const check = (value) => {
    let isExist = false;
    // let buttonpermsStr = sessionStorage.getItem("buttenpremissions");
    // if (buttonpermsStr == undefined || buttonpermsStr == null) {
    //     return false;
    // }
    // let buttonperms = JSON.parse(buttonpermsStr);
    // for (let i = 0; i < buttonperms.length; i++) {
    //     if (buttonperms[i].perms.indexOf(value) > -1) {
    //         isExist = true;
    //         break;
    //     }
    // }
    return isExist;
};

//注入属性
const setHas = (Vue) => {
    //自定义权限的指令，用于检测是否拥有按钮权限
    Vue.directive('has', {
        bind: function (el, binding) {
            if (check(binding.value)) {

            }
        }
    });

    Vue.prototype.$_has = check;
}

const install = function (Vue) {
    if (install.installed) return
    install.installed = true

    //设置
    setHas(Vue);
};

export default install;