import request from '../common/request';

const prefix = '/report/person_recru';

//获取个人招聘统计候选人跟踪报表
export function candidate_list(data) {
    return request({
        url: `${prefix}/candidate_list`,
        method: 'get',
        params: data
    })
}

//获取 招聘负责人明细的报表
export function recruitment_list(data) {
    return request({
        url: `${prefix}/recruitment_list`,
        method: 'get',
        params: data,
        timeout: 120e3
    })
}

//获取 招聘负责人汇总的报表
export function recruitment_total(data) {
    return request({
        url: `${prefix}/recruitment_total`,
        method: 'get',
        params: data
    })
} 

//获取 个人候选人信息
export function personal_candidate_info(data){
    return request({
        url: `${prefix}/personal_candidate_info`,
        method: 'get',
        params: data
    })
}