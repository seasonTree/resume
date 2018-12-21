<template>
    <el-dialog
        title="选择查找的用户"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="560px"
    >
        <div class="dialog-content mb-20">
            <el-transfer
                filterable
                filter-placeholder="请输入用户名"
                :titles="['所有用户', '选择的用户']"
                v-model="slUser"
                :props="{
                    key: 'uname',
                    label: 'personal_name'
                }"
                :data="users"
            >
            </el-transfer>
        </div>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">取 消</el-button>
            <el-button
                type="primary"
                @click="selectCommit"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../../base/DialogForm";
import { deepClone } from "@common/util";
export default {
    name: "User",
    mixins: [DialogForm],

    props: {
        selectUser: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            users: [],
            slUser: []
        };
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;
                that.getUsers();

                that.slUser = deepClone(that.selectUser);

                // that.slUser  = that.selectUser == ""? [] : that.selectUser.split(',')
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
                        //删除admin用户
                        for (var i = 0; i < res.data.length; i++) {
                            var item = res.data[i];

                            item.personal_name = item.personal_name || item.uname;

                            if (item.id == 1) {
                                res.data.splice(i, 1);
                                break;
                            }
                        }

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
        selectCommit() {
            let that = this,
                quickArray = [i],
                personalArray = [];

            for (var i = 0; i < that.slUser.length; i++) {
                var item = that.slUser[i];

                quickArray[item] = true;
            }

            for (var i = 0; i < that.users.length; i++) {
                var item = that.users[i];

                if (quickArray[item.uname]) {
                    personalArray.push(item.personal_name);
                }
            }

            that.$emit("user-list", {
                slUser: that.slUser,
                slUserPerson: personalArray
            });
            that.closeDialog();
        },

        //关闭窗口后调用
        afterClose() {
            let that = this;
            that.users = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>