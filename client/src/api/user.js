
import request from '../common/request';

//登录
export function login(data) {
    return request({
        url: '/user/login',
        method: 'post',
        data: data
    })
}

//登出
export function logout(data) {
    return request({
        url: '/user/logout',
        method: 'post',
        data: data
    })
}

//修改密码
export function changePassword(data) {
    return request({
        url: '/user/change_password',
        method: 'post',
        data: data
    })
}

//获取用户信息
export function getUserInfo(data){
    return request({
        url: '/user/get_userInfo',
        method: 'get',
        params: data
    })
}

//获取用户列表
export function get(data){
    return request({
        url: '/user/get_userInfo',
        method: 'get',
        params: data
    })
}