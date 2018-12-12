import request from '../common/request';

const prefix = '/permission';

/** 
 * 获取权限列表（不分页）
 * url: /api/permission/list
 * @method get
 * @returns Object
 * {
 *      code: 0, // 0表示没问题，不为0表示出错
 *      msg: '提示信息',
 *      data: [
 *          {
 *              id: 1,
 *              p_name: "菜单页面或功能名称",
 *              p_type: 0, //0: 菜单， 1: 功能
 *              url: '/user/aaa', //菜单的url
 *              api: "/ttttttt/ttttt", //按钮的url,
 *              p_act_name: 'user_add', //按钮的英文名称
 *               children: [
 *                    {
 *                        id: 2,
 *                        p_name: "功能页面",
 *                        p_type: 0, //0: 菜单， 1: 功能
 *                        url: '/user/aaa',
 *                        api: "/44444/444",
 *                        p_act_name: 'user_add', //按钮的英文名称
 *                    },
 *                    {
 *                        id: 3,
 *                        p_name: "功能页面2222",
 *                        p_type: 0, //0: 菜单， 1: 功能
 *                        url: '/user/aaa',
 *                        api: "/nnnnnn/nnn",
 *                        p_act_name: 'user_add', //按钮的英文名称
 *                        children: [
 *                            {
 *                                id: 4,
 *                                p_name: "333333333333",
 *                                url: '/user/aaa',
 *                                api: "/33333",
 *                            }
 *                        ]
 *                    }
 *                ]
 *          },
 *          {
 *              id: 16,
 *              p_name: "菜单33",
 *              p_type: 0, //0: 菜单， 1: 功能
 *              api: "/vvvv/vvvvv",
 *              url: '/user/aaa',
 *              p_act_name: 'user_add', //按钮的英文名称
 *          },
 *      ]
 * }
 */
export function get(data) {
    return request({
        url: `${prefix}/list`,
        method: 'get',
        params: data
    })
}

/** 
 * 根据id获取权限的信息
 * url: /api/permission/get_by_id
 * @method get
 * @param {Object} data 发送的数据
 *      {
 *          id: 1, // 权限id
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 16,
 *              p_name: "菜单33",
 *              p_type: 0, //0: 菜单， 1: 功能
 *              api: "/vvvv/vvvvv",
 *              url: '/user/aaa',
 *              p_act_name: 'user_add', //按钮的英文名称
 *              top_class: [0, 1] //层级
 *          }
 *      }
 */
export function getByID(data) {
    return request({
        url: `${prefix}/get_by_id`,
        method: 'post',
        data: data
    })
}

/** 
 * 新增权限
 * url: /api/permission/add
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          p_name: "菜单33",
 *          p_type: 0, //0: 菜单， 1: 功能
 *          api: "/vvvv/vvvvv",
 *          url: '/user/aaa',
 *          p_act_name: 'user_add', //按钮的英文名称
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 1
 *              p_name: "菜单33",
 *              p_type: 0, //0: 菜单， 1: 功能
 *              api: "/vvvv/vvvvv",
 *              url: '/user/aaa',
 *              p_act_name: 'user_add', //按钮的英文名称
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
 * 修改权限
 * url: /api/permission/edit
 * @method post
 * @param {Object} data 发送的数据
 *      {
 *          id: 16
 *          p_name: "菜单33",
 *          p_type: 0, //0: 菜单， 1: 功能
 *          api: "/vvvv/vvvvv",
 *          url: '/user/aaa',
 *          p_act_name: 'user_add', //按钮的英文名称
 *      }
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: {
 *              id: 16
 *              p_name: "菜单33",
 *              p_type: 0, //0: 菜单， 1: 功能
 *              api: "/vvvv/vvvvv",
 *              url: '/user/aaa',
 *              p_act_name: 'user_add', //按钮的英文名称
 *          }
 *      }
 */
export function edit(data) {
    return request({
        url: `${prefix}/edit`,
        method: 'post',
        data: data
    });
}

/** 
 * 删除
 * url: /api/permission/del
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
export function del(data) {
    return request({
        url: `${prefix}/del`,
        method: 'post',
        data: data
    });
}

/** 
 * 菜单排序
 * url: /api/permission/sort
 * @method post
 * @param {Object} data 发送的数据
 *      [1, 2, 3, 4] //下标就是顺序，内容是id，因为下标是从0开始，要每个加1
 * @returns
 *      {
 *          code: 0, // 0表示没问题，不为0表示出错
 *          msg: '提示信息',
 *          data: null //不返回
 *      }
 */
export function sort(data) {
    return request({
        url: `${prefix}/sort`,
        method: 'post',
        data: data
    });
}