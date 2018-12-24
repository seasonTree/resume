import request from '../common/request';

const prefix = '/communication';

//根据id获取沟通的信息
export function getByID(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'get',
        params: data
    })
}

export function edit(data){
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}


//根据id获取交流的信息
export function getByIDCom(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'get',
        params: data
    })
}