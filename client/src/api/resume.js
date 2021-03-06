import request from '../common/request';

const prefix = '/resume';

//获取简历列表
export function get(data){
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
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

//根据id获取简历的信息
export function getByID(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'get',
        params: data
    })
}

//修改
export function edit(data){
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}

//删除
export function del(data){
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}



//添加附件
export function uploadFile(data) {
    return request({
        url: `${prefix}/upload_file`,
        method: 'post',
        data: data,
        timeout: 60e3
    })
}

// //批量
// export function getImportExecl(data) {
//     return request({
//         url: `${prefix}/import_execl`,
//         method: 'post',
//         data: data
//     })
// }


//获取附件列表
export function getUploadFile(data) {
    return request({
        url: `${prefix}/get_upload_file`,
        method: 'get',
        params: data
    })
}

//新增沟通
export function addCommunication(data){
    return request({
        url: `${prefix}/add_communication`,
        method: 'post',
        data: data
    });
}

// //修改沟通
// export function editCommunication(data){
//     return request({
//         url: `${prefix}/edit_communication`,
//         method: 'post',
//         data: data
//     });
// }

//分析简历
export function analyze(data){
    return request({
        url: `${prefix}/analyze`,
        method: 'post',
        data: data
    });
}

//删除文件
export function delFile(data){
    return request({
        url: `${prefix}/del_file`,
        method: 'post',
        data: data
    });
}


//检查是否存在
export function checkName(data) {
    return request({
        url: `${prefix}/check_name`,
        method: 'get',
        params: data
    })
}

//批量新增
export function batchAdd(data){
    return request({
        url: `${prefix}/batch_add`,
        method: 'post',
        data: data,
        headers:{'Content-Type':'multipart/form-data'},
        timeout: 120e3
    });    
}