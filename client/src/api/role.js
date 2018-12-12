import request from '../common/request';

const prefix = '/role';

/** 
 * 获取角色列表（分页）
 * url: /api/role/list?name=角色1
 * @method get
 * @param {Object} data 发送的数据
 *      {
 *          name：'角色1' //根据角色名称模糊查找
 *          pageIndex： 1，
 *          pageSize： 10
 *      }
 * @returns Object
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: [
 *          {
 *              id: 1,
 *              role_name: '角色1',
 *              ct_user: '创建人'
 *              ct_time: '2018-01-01 12:30',
 *              status: 0, //启用
 *          },
 *          {
 *              id: 2,
 *              role_name: '角色2',
 *              ct_user: '创建人'
 *              ct_time: '2018-01-01 12:30',
 *              status: 1, //禁止   
 *          },
 *      ]
 * }
 */
export function get(data){
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
    })
}

/** 
 * 修改当前角色的状态
 * url: /api/role/change_status
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          id: 1, // 角色id
 *          status: 0, // 0: 启用, 1: 禁用
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: 可以不返回
 *      }
 */
export function changeStatus(data){
    return request({
        url: `${prefix}/change_status`,
        method: 'post',
        data: data
    })
}

/** 
 * 根据id获取角色的信息
 * url: /api/role/get_by_id
 * @method get
 * @param {Object} data 发送的数据
 *      {
 *          id: 1, // 用户id
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1,
 *              role_name: '角色1',
 *              status: 1 //角色状态
 *          }
 *      }
 */
export function getByID(data){
    return request({
        url: `${prefix}/get_by_id`,
        method: 'post',
        data: data
    })
}

/** 
 * 新增角色
 * url: /api/role/add
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          role_name： '角色名',
 *          status: 1 //角色状态
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1,
 *              role_name：: '角色名',
 *              status: 1 //角色状态
 *          }
 *      }
 */
export function add(data){
    return request({
        url: `${prefix}/add`,
        method: 'post',
        data: data
    });
}

/** 
 * 修改角色信息
 * url: /api/role/edit
 * @method post
 * @param {Object} data 修改的数据
 *      {
 *          id: 1
 *          role_name: '角色名',
 *          status: 1 //角色状态
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1
 *              role_name: '角色名',
 *              status: 1 //角色状态
 *          }
 *      }
 */
export function edit(data){
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}

/** 
 * 删除
 * url: /api/role/del
 * @method post
 * @param {Object} data 删除的数据
 *      {
 *          id: 1
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: null //不返回
 *      }
 */
export function del(data){
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}

/** 
 * 根据role的id获取用户
 * url: /api/role/get_user_by_id
 * @method get
 * @param {Object} data 发送的数据
 *      {
 *          role_id: 1, // 角色的id
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1,
 *              uname: '用户名',
 *          }
 *      }
 */
export function getUser(data){
    return request({
        url: `${prefix}/get_user_by_id`,
        method: 'get',
        params: data
    });
}

/** 
 * 设置当前角色的用户
 * url: /api/role/set_role_user
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          role_id: 1, // 角色的id
 *          role_user: [
 *              { id: 1, umane: 'aaaa' }, //用户的id, 用户名
 *              { id: 2, umane: 'bbbb' } //用户的id, 用户名
 *          ]
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: 不返回
 *      }
 */
export function setRoleUser(data){
    return request({
        url: `${prefix}/set_role_user`,
        method: 'post',
        data: data
    });
}

/** 
 * 获取当前角色选中的权限， 只需要返回id列表就可以
 * url: /api/role/get_check_permission
 * @method get
 * @param {Object} data 发送的数据
 *      {
 *          id: 1, // 角色的id
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: [1, 2, 3]
 *      }
 */
export function getCheckPermission(data){
    return request({
        url: `${prefix}/get_check_permission`,
        method: 'get',
        params: data
    });
}

/** 
 * 设置当用角色的权限
 * url: /api/role/set_role_permission
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          id: 1, // 角色的id
 *          permission: [1, 2, 3] //权限的id列表
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: 不返回
 *      }
 */
export function setRolePermission(data){
    return request({
        url: `${prefix}/set_role_permission`,
        method: 'post',
        data: data
    });
}