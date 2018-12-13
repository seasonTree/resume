<template>
    <el-container>
        <el-header class="header clearfix">
            <div class="inline-block title">
                简历管理系统
            </div>
            <div class="pull-left">

            </div>

            <div class="pull-right">
                <el-dropdown class="dropdown-link">
                    <span>
                        <span class="inline-block bg-cover user-image"></span>
                        <span class="inline-block">{{userInfo.uname}}</span>
                    </span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item>
                            <span @click="changePasswordVisable = true">
                                修改密码
                            </span>
                        </el-dropdown-item>
                        <el-dropdown-item>
                            <span @click.stop="logout">
                                退出登录
                            </span>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>

        </el-header>
        <el-container class="content">
            <el-aside
                width="200px"
                class="navbar"
            >
                <el-menu
                    :default-active="$route.path"
                    background-color="#eff1f6"
                    router
                >
                    <menu-tree :menu="menu"></menu-tree>
                </el-menu>
            </el-aside>

            <el-main class="main">
                <div class="main-header">
                    <el-breadcrumb separator="/">

                        <template v-for="(item, index) in $route.meta.paths">
                            <template v-if="item.url">
                                <el-breadcrumb-item :key="index">{{item.name}}</el-breadcrumb-item>
                            </template>

                            <template v-else>
                                <el-breadcrumb-item :key="index">{{item.name}}</el-breadcrumb-item>
                            </template>

                        </template>

                        <el-breadcrumb-item>{{$route.meta.name}}</el-breadcrumb-item>
                    </el-breadcrumb>
                </div>

                <div
                    class="main-body"
                    ref="mainBody"
                >
                    <transition
                        name="fade"
                        mode="out-in"
                    >
                        <router-view :bodyHeight="bodyHeight"></router-view>
                    </transition>
                </div>

                <el-footer class="main-footer">
                    ACHIEVO CO. LIMITED
                </el-footer>
            </el-main>
        </el-container>

        <el-dialog
            class="custom-dialog"
            title="修改密码"
            :visible.sync="changePasswordVisable"
            :close-on-click-modal="false"
        >
            <el-form
                :model="changePwd"
                ref="changePwdForm"
                label-width="100px"
                :rules="rules"
            >
                <el-form-item
                    label="旧密码"
                    prop="oldPass"
                >
                    <el-input
                        v-model="changePwd.oldPass"
                        type="password"
                        autocomplete="off"
                        placeholder="请输入密码"
                    ></el-input>
                </el-form-item>
                <el-form-item
                    label="新密码"
                    prop="newPass"
                >
                    <el-input
                        v-model="changePwd.newPass"
                        type="password"
                        autocomplete="off"
                        placeholder="请输入新密码"
                    ></el-input>
                </el-form-item>
                <el-form-item
                    label="确认密码"
                    prop="newPassRe"
                >
                    <el-input
                        v-model="changePwd.newPassRe"
                        type="password"
                        autocomplete="off"
                        placeholder="请再次输入新密码"
                    ></el-input>
                </el-form-item>
            </el-form>
            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="resetForm">取 消</el-button>
                <el-button
                    :loading="changLoading"
                    type="primary"
                    @click="changePassword"
                >确 定</el-button>
            </div>
        </el-dialog>
    </el-container>
</template>

<script>
import MenuTree from "@component/menutree/MenuTree";

import { mapGetters } from "vuex";

let passRules = [
    {
        required: true,
        message: "请填写所需要的密码",
        trigger: "blur"
    },
    {
        min: 6,
        max: 20,
        message: "密码最少6个字符, 最大20个字符",
        trigger: "blur"
    }
];

export default {
    name: "Layout",
    components: {
        MenuTree
    },

    data() {
        return {
            bodyHeight: 500,

            //TODO
            menu: [
                {
                    url: "/dashboard",
                    name: "首页",
                    icon: "fa fa-list-alt"
                },
                {
                    name: "简历管理",
                    icon: "fa fa-address-book",

                    children: [
                        {
                            url: "/resume/index",
                            name: "简历信息",
                            icon: "fa fa-address-card"
                        }
                    ]
                },
                {
                    id: "5",
                    name: "用户管理",
                    icon: "fa fa-users",

                    children: [
                        {
                            url: "/user/index",
                            name: "用户信息",
                            icon: "fa fa-user-friends"
                        },
                        {
                            url: "/user/role",
                            name: "用户角色",
                            icon: "fa fa-users-cog"
                        },
                        {
                            url: "/user/permission",
                            name: "用户权限",
                            icon: "fa fa-user-shield"
                        }
                    ]
                },
                {
                    id: "6",
                    name: "报表",
                    icon: "fa fa-database",

                    children: [
                        {
                            url: "/report/personal_recruitment",
                            name: "个人招聘统计",
                            icon: "fa fa-user-friends"
                        }
                    ]
                },
                {
                    url: "/test",
                    name: "测试",
                    icon: "fa fa-list-alt"
                }
            ],

            changePasswordVisable: false,

            changePwd: {
                oldPass: "",
                newPass: "",
                newPassRe: ""
            },

            rules: {
                oldPass: passRules,
                newPass: passRules,
                newPassRe: passRules.concat({
                    validator: (rule, value, callback) => {
                        let that = this;

                        if (that.newPass !== value) {
                            callback(
                                new Error("两次的密码不一致，请重新输入.")
                            );
                        }else{
                            callback();
                        }
                    }
                })
            },

            changLoading: false,

            mainBodyTimer: null
        };
    },

    computed: {
        ...mapGetters(["userInfo"])
    },

    mounted() {
        const that = this,
            resize = () => {
                clearTimeout(that.mainBodyTimer);

                that.mainBodyTimer = setTimeout(() => {
                    that.bodyHeight = that.$refs.mainBody.offsetHeight;
                }, 100);
            };

        resize();

        window.onresize = resize;
    },

    methods: {
        changePassword() {
            let that = this;

            that.$refs["changePwdForm"].validate(valid => {
                if (valid) {
                    that.changLoading = true;

                    that.$api.user
                        .changePassword(that.changePwd)
                        .then(res => {
                            if (res.error == 0) {
                                that.$message({
                                    message: "修改成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.resetForm();
                            } else {
                                that.$message.error(res.message);
                            }

                            that.changLoading = false;
                        })
                        .catch(res => {
                            that.$message.error("修改失败，请重试.");

                            that.changLoading = false;
                        });
                }
            });
        },

        logout() {
            let that = this;

            that.$api.user
                .logout()
                .then(res => {
                    that.$store.commit("clearUserInfo");
                    that.$router.replace("/login");
                })
                .catch(res => {
                    that.$message.error("退出失败,请重试.");
                });
        },

        resetForm() {
            this.$refs["changePwdForm"].resetFields();

            this.changePasswordVisable = false;
        }
    }
};
</script>

<style lang="less">
@header-color: #409eff;
@nav-color: #eff1f6;

.header {
    border-bottom: @header-color;
    background-color: @header-color;
    color: white;
    padding-left: 0 !important;

    .title {
        width: 200px;
        font-size: 25px;
        line-height: 60px;
        background-color: rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .dropdown-link {
        line-height: 60px;
        font-size: 16px;
        color: white;
        cursor: pointer;
        padding: 0 6px;

        &:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }
    }

    .user-image {
        background-image: url(../../image/user_image.jpg);
        height: 40px;
        width: 40px;
        vertical-align: middle;
        margin-right: 4px;
        border-radius: 50%;
    }
}

.content {
    position: absolute;
    top: 60px;
    bottom: 0;
    width: 100%;

    .main {
        padding: 0;
        position: relative;

        .main-header,
        .main-body,
        .main-footer {
            padding: 20px;
            position: absolute;
            left: 0;
            right: 0;
        }

        .main-header {
            background-color: #f7f7f7;
            border-bottom: 1px solid #dcdfe6;
        }

        .main-body {
            position: absolute;
            overflow: hidden;
            top: 55px;
            bottom: 40px;
        }

        .main-footer {
            padding: 0;
            bottom: 0;
            line-height: 40px;
            height: 40px !important;
            text-align: center;
            background-color: #dcdfe6;
        }
    }
}

.navbar {
    background-color: @nav-color;
    border: 1px solid #e6e6e6;
    overflow-x: hidden !important;
    box-sizing: border-box;
    
    .el-menu{
        border: none;
    }

    .el-menu-item.is-active {
        border-right: 4px solid @header-color;
        background-color: rgba(0, 0, 0, 0.06) !important;
    }

    .el-submenu .el-menu-item.is-active {
        border-right: 6px solid @header-color;
    }
}
</style>