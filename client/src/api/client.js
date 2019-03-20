import request from '../common/request';

const prefix = '/client';

/** 
 * 获取客户列表（分页）
 * url: /api/client/list?name=张三
 * @method get
 * @param {Object} data 发送的数据
 *      {
 *          name：'张三' //根据用户名或姓名查找模糊查找，可以不传
 *          pageIndex： 1，
 *          pageSize： 10
 *      }
 * @returns Object
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: {
 *          row: [
 *              {
 *                  id: 1,
 *                  client_name: 'aaaa',
 *                  ct_user: '创建人'
 *                  ct_time: '2018-01-01 12:30',
 *                  status: 1, //禁止   
 *              },
 *              {
  *                 id: 2,
 *                  client_name: 'bbb',
 *                  ct_user: '创建人'
 *                  ct_time: '2018-01-01 12:30',
 *                  status: 0, //允许
 *              },
 *          ],
 *          total: 100 //条数
 *      }
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
 * 修改当前客户的状态
 * url: /api/client/change_status
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          id: 1, // 用户id
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
 * 根据id获取用户的信息
 * url: /api/client/get_by_id
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
 *              client_name: '张三'
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
 * 新增用户
 * url: /api/client/add
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          client_name： '用户名',
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1
 *              client_name：: '张三'
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
 * 修改用户信息
 * url: /api/client/edit
 * @method post
 * @param {Object} data 修改的数据
 *      {
 *          id: 1
 *          client_name: '姓名',
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1
 *              client_name: '张三'
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
 * url: /api/client/del
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
 * 获取所有的客户（不分页，不返回禁用的客户）
 * url: /api/user/all_list
 * @method get
 * @returns Object
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: [
 *          {
 *              id: 1,
 *              client_name: 'aaaa',
 *          },
 *          {
 *              id: 2,
 *              client_name: 'bbb',
 *          },
 *      ]
 * }
 */
export function getAll(data){
    return request({
        url: `${prefix}/all_list`,
        method: 'get',
        params: data
    })
}