import axios from 'axios';
// import router from '../router';

const instance = axios.create({
    baseURL: '/api', // api的base_url
    timeout: 6000 // 请求超时时间
})
// request拦截器
instance.interceptors.request.use(
    config => {
        // config.params = config.params || {}
        // config.headers = config.headers || {}

        if(config.params){
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
        const resp = response.data
        // if (response.status === 200) {

        //     // if (resp.error == '403') {
        //     //     that.$message.error("未登录.");

        //     //     router.replace({
        //     //         path: '/login',
        //     //         query: {
        //     //             redirect: router.currentRoute.fullPath
        //     //         } //登录成功后跳入浏览的当前页面
        //     //     })
        //     // }
        // }

        return resp;
    },
    error => {
        return Promise.reject(error);
    }
)

export default instance;