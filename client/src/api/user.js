import request from '../common/request';

const prefix = '/user';


/**
 * 登录 
 * url: /api/user/login
 * @method post
 * @param {Object} data 用户登录数据
 *      {
 *          uname: '用户名'
 *          password: '密码'
 *      }
 * 
 * @returns Object
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: 可以不返回
 *      }
 */
export function login(data) {
    return request({
        url: `${prefix}/login`,
        method: 'post',
        data: data
    })
}

/** 
 * 登出
 * url: /api/user/logout
 * @method post
 * @returns 返回的数据结构
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: 可以不返回
 * }
 */
export function logout(data) {
    return request({
        url: `${prefix}/logout`,
        method: 'post',
        data: data
    })
}

/** 
 * 当前登录的用户
 * url: /api/user/get_user_info
 * @method get
 * @returns Object
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: {
 *              id: 1,
 *              uname: 'aaaa',
 *              status: 1, //禁止   
 *          }
 * }
 */
export function getUserInfo(data){
    return request({
        url: `${prefix}/get_user_info`,
        method: 'get',
        params: data
    })
}

/** 
 * 修改自己密码
 * url: /api/user/change_password
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          passwd: '6666666', 
 *          repasswd: '6666666',
 *      }
 * 
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: 可以不返回
 *      }
 */
export function changePassword(data) {
    return request({
        url: `${prefix}/change_password`,
        method: 'post',
        data: data
    })
}

/** 
 * 获取用户列表（分页）
 * url: /api/user/list?name=张三
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
 *                  uname: 'aaaa',
 *                  personal_name: '张三',
 *                  phone: 136666666,
 *                  ct_user: '创建人'
 *                  ct_time: '2018-01-01 12:30',
 *                  status: 1, //禁止   
 *              },
 *              {
  *                 id: 2,
 *                  uname: 'bbb',
 *                  personal_name: '张四',
 *                  phone: 136666666,
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
 * 修改当前用户的状态
 * url: /api/user/change_status
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
 * url: /api/user/get_by_id
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
 *              personal_name: '张三', //姓名
 *              phone: 1366666666
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
 * url: /api/user/add
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          uname： '用户名', ^[a-zA-Z][\da-zA-Z]， 用户必须是字母或数字，并且以字母开头
 *          personal_name: '姓名',
 *          phone: 136666666,
 *          passwd: '密码'
 *          repasswd: '确认密码'
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1
 *              personal_name: '张三', //姓名
 *              phone: 1366666666
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
 * url: /api/user/edit
 * @method post
 * @param {Object} data 修改的数据
 *      {
 *          id: 1
 *          personal_name: '姓名',
 *          phone: 136666666,
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1
 *              personal_name: '张三', //姓名
 *              phone: 1366666666
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
 * 系统管理员修改用户的密码
 * url: /api/user/change_user_passwd
 * @method post
 * @param {Object} data 修改的数据
 *      {
 *          id: 1
 *          passwd: '密码',
 *          repasswd: '再次输入的密码',
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: null //不返回
 *      }
 */
export function changeUserPasswd(data){
    return request({
        url: `${prefix}/change_user_passwd`,
        method: 'post',
        data: data
    });
}

/** 
 * 删除
 * url: /api/user/del
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
 * 获取所有的用户，用户权限分配（不分页）
 * url: /api/user/all_list
 * @method get
 * @returns Object
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: [
 *          {
 *              id: 1,
 *              uname: 'aaaa',
 *              status: 1, //禁止   
 *          },
 *          {
  *             id: 2,
 *              uname: 'bbb',
 *              status: 0, //允许
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

//获取当前用户的权限
export function getUserPermission(data){
    return request({
        url: `${prefix}/get_user_permission`,
        method: 'get',
        params: data
    })
}

//更新用户自己的头像
export function updateUserAvatar(data){
    return request({
        url: `${prefix}/update_avatar`,
        method: 'post',
        data: data
    });
}

//更新用户的头像（管理员）
export function changeUserAvatar(data){
    return request({
        url: `${prefix}/change_user_avatar`,
        method: 'post',
        data: data
    });
}