import request from '../common/request';

const prefix = '/communication';

//获取沟通信息
export function get(data) {
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
    })
}

//根据id获取沟通的信息
export function getByID(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'get',
        params: data
    })
}

//新增沟通
export function add(data){
    return request({
        url: `${prefix}/add`,
        method: 'post',
        data: data
    });
}


//修改沟通
export function edit(data){
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}
