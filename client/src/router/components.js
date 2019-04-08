//主的组件
const components = {
    // "/resume/index": import("@view/resume/Index"),
    // "/user/index": import("@view/user/Index"),
    // "/user/role": import("@view/role/Index"),
    // "/user/permission": import("@view/permission/Index"),
    // "/report/personal_recruitment": import("@view/report/PersonalRecruitment")

    // "/resume/index": resolve =>
    //     require.ensure([], () => resolve(require("@view/resume/Index"))),
    // "/user/index": resolve =>
    //     require.ensure([], () => resolve(require("@view/user/Index"))),
    // "/user/role": resolve =>
    //     require.ensure([], () => resolve(require("@view/role/Index"))),
    // "/user/permission": resolve =>
    //     require.ensure([], () => resolve(require("@view/permission/Index"))),
    // "/report/personal_recruitment": resolve =>
    //     require.ensure([], () =>
    //         resolve(require("@view/report/PersonalRecruitment"))
    //     )

    "/resume/Index": resolve =>
        require.ensure([], () => resolve(require("@view/resume/Index"))),
    "/position_cate/Index": resolve =>
        require.ensure([], () => resolve(require("@view/position_cate/Index"))),
    "/user/Index": resolve =>
        require.ensure([], () => resolve(require("@view/user/Index"))),
    "/role/Index": resolve =>
        require.ensure([], () => resolve(require("@view/role/Index"))),
    "/permission/Index": resolve =>
        require.ensure([], () => resolve(require("@view/permission/Index"))),
    "/report/personal_recruitment/Index": resolve =>
        require.ensure([], () =>
            resolve(require("@view/report/personal_recruitment/Index"))
        ),
    //客户
    "/client/Index": resolve =>
        require.ensure([], () => resolve(require("@view/client/Index"))),

    //个人候选人信息
    "/personal_candidate_info/Index": resolve =>
        require.ensure([], () =>
            resolve(require("@view/report/personal_candidate_info/Index"))
        )
};

export default components;
