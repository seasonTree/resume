<template>
    <el-dialog
        title="设置角色用户"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="560px"
        v-dialog-drag
    >
        <div class="dialog-content mb-20">
            <el-transfer
                filterable
                filter-placeholder="请输入用户名"
                :titles="['所有用户', '角色用户']"
                v-model="roleUser"
                :props="{
                    key: 'id',
                    label: 'uname'
                }"
                :data="users"
            >
            </el-transfer>
        </div>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog" :disabled="commitLoading">取 消</el-button>
            <el-button
                type="primary"
                @click="setRoleUserCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('role_user_set')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "User",
    mixins: [DialogForm],

    props: {
        id: {
            type: Number,
            required: true
        }
    },
    data() {
        return {
            roleUser: [],
            users: [
                // { id: 1, uname: "张三" }, { id: 2, uname: "李四" }
            ]
        };
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;
                that.getUsers();
                that.getRoleUser();
            }
        }
    },

    methods: {
        //获取所用的用户
        getUsers() {
            let that = this;

            that.$api.user
                .getAll()
                .then(res => {
                    if (res.code == 0) {
                        that.users = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取所有用户失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取所有用户失败，请刷新后重试.");
                });
        },

        //获取当前角色的用户
        getRoleUser() {
            let that = this;

            that.$api.role
                .getUser({
                    role_id: that.id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.roleUser = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取角色用户失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取角色用户失败，请刷新后重试.");
                });
        },

        //提交当前角色的用户
        setRoleUserCommit() {
            let that = this;

            that.$api.role
                .setRoleUser({
                    role_id: that.id,
                    role_user: that.roleUser
                })
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "设置成功.",
                            type: "success",
                            duration: 800
                        });

                        that.closeDialog();
                    } else {
                        that.$message.error(
                            res.msg || "设置角色用户失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("设置角色用户失败，请刷新后重试.");
                });
        },

        //关闭窗口后调用
        afterClose() {
            let that = this;
            that.users = [];
            that.roleUser = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>