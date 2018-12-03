
import request from '../common/request';

const prefix = '/user';

//登录
export function login(data) {
    return request({
        url: `${prefix}/login`,
        method: 'post',
        data: data
    })
}

//登出
export function logout(data) {
    return request({
        url: `${prefix}/logout`,
        method: 'post',
        data: data
    })
}

//修改密码
export function changePassword(data) {
    return request({
        url: `${prefix}/change_password`,
        method: 'post',
        data: data
    })
}

//获取用户信息
export function getUserInfo(data){
    return request({
        url: `${prefix}/get_userInfo`,
        method: 'get',
        params: data
    })
}

//获取用户列表
export function get(data){
    return request({
        url: `${prefix}/get_userInfo`,
        method: 'get',
        params: data
    })
}

//修改当前用户的状态
export function changeStatus(data){
    return request({
        url: `${prefix}/change_status`,
        method: 'post',
        data: data
    })
}

//根据id获取用户的信息
export function getByID(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'post',
        data: data
    })
}

//修改
export function edit(data){
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}

//系统管理员修改用户的密码
export function changeUserPasswd(data){
    return request({
        url: `${prefix}/change_user_passwd`,
        method: 'post',
        data: data
    });
}

//删除
export function del(data){
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}