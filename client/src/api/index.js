import * as user from './user'
import * as role from './role'
import * as permission from './permission'

const apis = {
    user,
    role,
    permission
}

const install = function (Vue) {
    if (install.installed) return
    install.installed = true
    Object.defineProperties(Vue.prototype, {
        $api: {
            get() {
                return apis;
            }
        }
    })
};

export default install;