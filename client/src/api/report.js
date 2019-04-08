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

//获取 候选人信息统计
export function candidate_info_statistics(data){
    return request({
        url: `${prefix}/candidate_info_statistics`,
        method: 'get',
        params: data
    })
}

