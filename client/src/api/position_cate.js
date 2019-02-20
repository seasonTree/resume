import request from '../common/request';

const prefix = '/position_cate';

//获取职位分类（分页）
export function get(data){
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
    })
}

//获取全部的职位
export function getAll(data){
    return request({
        url: `${prefix}/get_all`,
        method: 'get',
        params: data
    })
}

//根据id获取当前的职位分类
export function getByID(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'post',
        data: data
    })
}

//新增职位分类
export function add(data){
    return request({
        url: `${prefix}/add`,
        method: 'post',
        data: data
    });
}

//修改职位分类
export function edit(data){
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}

//删除职位分类
export function del(data){
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}

