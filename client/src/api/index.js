import * as user from './user'
import * as role from './role'
import * as permission from './permission'
import * as resume from './resume';

const apis = {
    user,
    role,
    permission,
    resume
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