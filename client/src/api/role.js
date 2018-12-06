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