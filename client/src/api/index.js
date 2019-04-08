import * as dashboard from './dashboard'
import * as user from './user'
import * as role from './role'
import * as permission from './permission'
import * as resume from './resume';
import * as communication from './communication';
import * as person_recru from './person_recru';
import * as position_cate from './position_cate';
import * as client from './client';
import * as report from './report';

const apis = {
    dashboard,
    user,
    role,
    permission,
    resume,
    person_recru,
    communication,
    position_cate,
    client,
    report
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