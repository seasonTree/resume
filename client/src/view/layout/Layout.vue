<template>
    <el-container class="content">
        <div
            class="aside"
            :style="{
                width: navWidth
            }"
        >
            <el-aside :width="navWidth">
                <div>
                    <el-header
                        class="header title"
                        v-show="showNav"
                    >
                        招聘管理系统
                    </el-header>

                    <el-header
                        class="header title small-title"
                        v-show="!showNav"
                    >
                        招
                    </el-header>

                    <div class="navbar">
                        <el-menu
                            :default-active="$route.path"
                            background-color="#eff1f6"
                            router
                            :collapse="!showNav"
                        >
                            <menu-tree
                                :menu="menu"
                                :collapse="!showNav"
                            ></menu-tree>
                        </el-menu>

                    </div>
                </div>

            </el-aside>
        </div>
        <el-container class="main">
            <el-header class="header">
                <el-row
                    type="flex"
                    class="row-bg"
                    justify="space-between"
                >
                    <el-col class="header-left">
                        <i
                            @click="navCollapse"
                            class="shrink fa fa-align-justify"
                            :class="{ 'collapse': !showNav }"
                        ></i>
                    </el-col>
                    <el-col>
                        <el-dropdown class="dropdown-link">
                            <span>
                                <span
                                    @click="userImageDialog = true"
                                    class="inline-block bg-cover user-image"
                                    :style="{
                                        backgroundImage: `url(${userInfo.avatar})`
                                    }"
                                ></span>
                                <span class="inline-block">{{userInfo.userid}}</span>
                            </span>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item @click.native="changePasswordDialog = true">
                                    Change Password
                                </el-dropdown-item>
                                <el-dropdown-item @click.native="logout">
                                    Log Out
                                </el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </el-col>
                </el-row>
            </el-header>
            <el-main class="main-content">
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

                    <div class="full-screen">
                        <el-tooltip
                            effect="dark"
                            content="Full Screen"
                            placement="top"
                        ><i
                                class="fa fa-expand-arrows-alt"
                                @click="handleFullScreen"
                            ></i>
                        </el-tooltip>
                    </div>
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
            </el-main>
        </el-container>

        <!-- <el-header class="header clearfix">
            <div class="inline-block title">
                招聘管理系统
            </div>
            <div class="pull-left">

            </div>

            <div class="pull-right">
                <el-dropdown class="dropdown-link">
                    <span>
                        <span
                            @click="userImageDialog = true"
                            class="inline-block bg-cover user-image"
                            :style="{
                                backgroundImage: `url(${userInfo.avatar})`
                            }"
                        ></span>
                        <span class="inline-block">{{userInfo.uname}}</span>
                    </span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item @click.native="changePasswordDialog = true">
                            修改密码
                        </el-dropdown-item>
                        <el-dropdown-item @click.native="logout">
                            退出登录
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
            </el-main>
        </el-container> -->

        <change-password :show.sync="changePasswordDialog">
        </change-password>

        <user-image
            :show.sync="userImageDialog"
            :avatar="userInfo.avatar"
        ></user-image>

    </el-container>
</template>

<script>
import MenuTree from "@component/menutree/MenuTree";
import ChangePassword from "./ChangePassword";
import UserImage from "./UserImage";
import { mapGetters } from "vuex";

export default {
    name: "Layout",
    components: {
        MenuTree,
        ChangePassword,
        UserImage
    },

    data() {
        return {
            bodyHeight: 500,

            // menu: [
            // {
            //     url: "/dashboard",
            //     name: "首页",
            //     icon: "fa fa-list-alt"
            // },
            // {
            //     name: "简历管理",
            //     icon: "fa fa-address-book",
            //     children: [
            //         {
            //             url: "/resume/index",
            //             name: "简历信息",
            //             icon: "fa fa-address-card"
            //         }
            //     ]
            // },
            // {
            //     id: "5",
            //     name: "用户管理",
            //     icon: "fa fa-users",
            //     children: [
            //         {
            //             url: "/user/index",
            //             name: "用户信息",
            //             icon: "fa fa-user-friends"
            //         },
            //         {
            //             url: "/user/role",
            //             name: "用户角色",
            //             icon: "fa fa-users-cog"
            //         },
            //         {
            //             url: "/user/permission",
            //             name: "用户权限",
            //             icon: "fa fa-user-shield"
            //         }
            //     ]
            // },
            // {
            //     id: "6",
            //     name: "报表",
            //     icon: "fa fa-database",
            //     children: [
            //         {
            //             url: "/report/personal_recruitment",
            //             name: "个人招聘统计",
            //             icon: "fa fa-user-friends"
            //         }
            //     ]
            // },
            // {
            //     url: "/test",
            //     name: "测试",
            //     icon: "fa fa-list-alt"
            // }
            // ],

            mainBodyTimer: null,

            changePasswordDialog: false,

            userImageDialog: false,

            navWidth: "200px",
            showNav: true
        };
    },

    computed: {
        ...mapGetters(["userInfo", "menu"])
        // asideWidth() {
        //     return this.showNav ? this.navWidth : 0;
        // }
    },

    created() {
        let that = this;
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
        // updateAvatar(data){
        //     let that = this;
        //     that.userAvatar = `url(${data})`;
        // },

        handleFullScreen() {
            let isFullscreen =
                    document.fullScreen ||
                    document.mozFullScreen ||
                    document.webkitIsFullScreen,
                element = document.documentElement;

            if (!isFullscreen) {
                //进入全屏,多重短路表达式
                //普通
                (element.requestFullscreen && element.requestFullscreen()) ||
                    //moz
                    (element.mozRequestFullScreen &&
                        element.mozRequestFullScreen()) ||
                    //webkit
                    (element.webkitRequestFullscreen &&
                        element.webkitRequestFullscreen()) ||
                    //ie
                    (element.msRequestFullscreen &&
                        element.msRequestFullscreen());
            } else {
                //退出全屏,三目运算符
                //普通
                document.exitFullscreen
                    ? document.exitFullscreen()
                    : //moz
                    document.mozCancelFullScreen
                    ? document.mozCancelFullScreen()
                    : //webkit
                    document.webkitExitFullscreen
                    ? document.webkitExitFullscreen()
                    : //ie
                    document.msExitFullscreen
                    ? document.msExitFullscreen()
                    : "";
            }
        },

        navCollapse() {
            let that = this;
            that.showNav = !that.showNav;

            if (that.showNav) {
                //右侧拉开的时候加个延迟，避免加载闪动
                setTimeout(() => {
                    that.navWidth = "200px";
                }, 140);
            } else {
                that.navWidth = "64px";
            }
        },

        logout() {
            let that = this;
            that.$api.user
                .logout()
                .then(res => {
                    // that.$store.commit("clearUserInfo");

                    //直接重刷页面，清除所有的router
                    //因为vue-router不支持动态删除路由
                    window.location.reload();
                    // that.$router.replace("/login");
                })
                .catch(res => {
                    that.$message.error("Logout failed, please try again.");
                });
        }
    }
};
</script>

<style lang="less" scoped>
@header-color: #7266ba;
@title-color: #6453ca;
@nav-color: #eff1f6;

.content {
    position: absolute;
    top: 0;
    width: 100%;
    height: 100%;

    .title {
        position: absolute;
        font-size: 25px;
        line-height: 60px;
        background-color: @title-color !important;
        text-align: center;
    }

    .small-title {
        padding: 0;
    }

    .header {
        border-bottom: @header-color;
        background-color: @header-color;
        color: white;
        width: 100%;

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
            height: 40px;
            width: 40px;
            vertical-align: middle;
            margin-right: 4px;
            border-radius: 50%;
            background-size: 100% 100%;
        }
    }

    .main {
        position: relative;

        .header-left {
            text-align: left;
            line-height: 60px;
            .shrink {
                cursor: pointer;
                transition: all 0.3s;

                &:hover {
                    color: #ec8e00;
                }
            }

            .collapse {
                transform: rotate(90deg);
            }
        }

        .header {
            text-align: right;
        }

        .main-content {
            position: relative;
            padding: 0;

            .main-header,
            .main-body,
            .main-footer {
                padding: 20px;
                position: absolute;
                left: 0;
                right: 0;
                box-sizing: border-box;
            }

            .main-header {
                background-color: #f7f7f7;
                border-bottom: 1px solid #dcdfe6;

                .full-screen {
                    position: absolute;
                    top: 14px;
                    right: 20px;
                    i {
                        font-size: 24px;
                        color: #8a8d92;
                        cursor: pointer;

                        &:hover {
                            color: @header-color;
                        }
                    }
                }
            }

            .main-body {
                position: absolute;
                overflow: auto;
                top: 55px;
                bottom: 0px;
            }
        }
    }

    .aside {
        position: relative;
        overflow-x: hidden;
        transition: all 0.3s ease;

        .navbar {
            position: absolute;
            top: 60px;
            bottom: 0;
            width: 100%;
            background-color: @nav-color;
            border: 1px solid #e6e6e6;
            overflow-x: hidden !important;
            box-sizing: border-box;
        }
    }
}
</style>