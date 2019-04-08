import request from '../common/request';
const prefix = '/report';

//获取 个人候选人信息
export function personal_candidate_info(data){
    return request({
        url: `${prefix}/personal_candidate_info`,
        method: 'get',
        params: data
    })
}