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

//获取 招聘负责人统计的报表
export function recruitment_list(data) {
    return request({
        url: `${prefix}/recruitment_list`,
        method: 'get',
        params: data
    })
}
