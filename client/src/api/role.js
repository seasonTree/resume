import request from '../common/request';

const prefix = '/role';

//获取角色列表
export function get(data){
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
    })
}

//修改当前角色的状态
export function changeStatus(data){
    return request({
        url: `${prefix}/change_status`,
        method: 'post',
        data: data
    })
}

//根据id获取角色的信息
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

//删除
export function del(data){
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}

//根据role的id获取用户
export function getUser(data){
    return request({
        url: `${prefix}/get_user_by_id`,
        method: 'get',
        params: data
    });
}

//设置当前角色的用户
export function setRoleUser(data){
    return request({
        url: `${prefix}/set_role_user`,
        method: 'post',
        data: data
    });
}

//获取当前角色选中的权限， 只需要返回id列表就可以
export function getCheckPermission(data){
    return request({
        url: `${prefix}/get_check_permission`,
        method: 'get',
        params: data
    });
}

//设置当用角色的权限
export function setRolePermission(data){
    return request({
        url: `${prefix}/set_role_permission`,
        method: 'post',
        data: data
    });
}