import axios from 'axios';
import {
    Notification
} from 'element-ui';

const instance = axios.create({
    baseURL: '/api', // api的base_url
    timeout: 6000, // 请求超时时间
    // withCredentials: true, //允许携带cookie
})

// request拦截器
instance.interceptors.request.use(
    config => {

        if (config.method.toLocaleUpperCase() == 'GET') {
            config.params = config.params || {};
            config.params['_d'] = Date.now();
        }

        //set 默认值
        return config
    },
    error => ({
        code: 404,
        msg: error.message
    })
)
// respone拦截器
instance.interceptors.response.use(
    response => {

        const resData = response.data

        //登录断开了
        if (resData.code == 403) {
            //与服务器连接已经断开，6秒后自动刷新页面重连
            Notification.error({
                title: '错误',
                message: "系统已登出，6秒后自动刷新页面.",
                duration: 0 //设置不会自动关闭
            });

            setTimeout(() => {
                //重新刷新页面
                window.location && window.location.reload();
            }, 6e3);

            return;
        }

        return resData;
    },
    error => {
        return Promise.reject(error);
    }
)

export default instance;