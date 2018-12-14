const check = (value) =>{

};

const setHas = (Vue) => {
    //自定义权限的指令，用于检测是否拥有按钮权限
    Vue.directive('has', {
        bind: function (el, binding) {
            if (!Vue.prototype.$_has(binding.value)) {
                console.log(el)

                // el.parentNode.removeChild(el);
            }
        }
    });

    Vue.prototype.$_has = function (value) {
        let isExist = false;
        let buttonpermsStr = sessionStorage.getItem("buttenpremissions");
        if (buttonpermsStr == undefined || buttonpermsStr == null) {
            return false;
        }
        let buttonperms = JSON.parse(buttonpermsStr);
        for (let i = 0; i < buttonperms.length; i++) {
            if (buttonperms[i].perms.indexOf(value) > -1) {
                isExist = true;
                break;
            }
        }
        return isExist;
    };
}

const install = function (Vue) {
    if (install.installed) return
    install.installed = true
    
    //设置
    setHas(Vue);
};

export default install;