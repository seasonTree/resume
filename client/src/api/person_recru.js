import request from '../common/request';

const prefix = '/person_recru';

export function get(data) {
    return request({
        url: `${prefix}/get`,
        method: 'get',
        params: data
    })
}
