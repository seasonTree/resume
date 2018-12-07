import request from '../common/request';

const prefix = '/resume';

//获取沟通信息
export function getCommunication(data) {
    return request({
        url: `${prefix}/get_communication`,
        method: 'get',
        params: data
    })
}