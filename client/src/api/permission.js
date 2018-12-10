import request from '../common/request';

const prefix = '/role';

//获取权限列表
export function get(data) {
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
    })
}

//根据id获取权限的信息
export function getByID(data) {
    return request({
        url: `${prefix}/get_by_id`,
        method: 'post',
        data: data
    })
}

//新增
export function add(data){
    return request({
        url: `${prefix}/add`,
        method: 'post',
        data: data
    });
}


//修改
export function edit(data) {
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}

//删除
export function del(data) {
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}

//菜单排序
export function sort(data) {
    return request({
        url: `${prefix}/sort`,
        method: 'post',
        data: data
    });
}