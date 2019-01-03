<template>
    <el-dialog
        title="排序"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="400px"
        v-dialog-drag
    >
        <div class="dialog-content mb-20">
            <el-tree
                :data="permissionData"
                node-key="id"
                :props="defaultProps"
                ref="tree"
                :draggable="true"
            >
            </el-tree>
        </div>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog" :disabled="commitLoading">取 消</el-button>
            <el-button
                type="primary"
                @click="sortCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('permiss_sort')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "Sort",
    mixins: [DialogForm],

    watch: {
        show(newValue, oldValue) {
            let that = this;

            if (newValue) {
                that.getPermissionData();
            }
        }
    },

    data() {
        return {
            permissionData: [],
            defaultProps: {
                children: "children",
                label: "p_name"
            }
        };
    },

    methods: {
        //获取所有的权限列表
        getPermissionData() {
            let that = this;

            // that.permissionData = [
            //     {
            //         id: 1,
            //         p_name: "菜单页面",
            //         api: "/ttttttt/ttttt",
            //         ct_user: "6666666",
            //         ct_time: "192.666245",
            //         children: [
            //             {
            //                 id: 2,
            //                 p_name: "功能页面",
            //                 api: "/44444/444",
            //                 ct_user: "44444444",
            //                 ct_time: "444444444444444"
            //             },
            //             {
            //                 id: 3,
            //                 p_name: "功能页面2222",
            //                 api: "/nnnnnn/nnn",
            //                 ct_user: "nnnnnnn",
            //                 ct_time: "nnnnnnnnnnnn",
            //                 children: [
            //                     {
            //                         id: 4,
            //                         p_name: "333333333333",
            //                         api: "/33333",
            //                         ct_user: "3333",
            //                         ct_time: "3333333"
            //                     }
            //                 ]
            //             }
            //         ]
            //     },
            //     {
            //         id: 16,
            //         p_name: "菜单33",
            //         api: "/vvvv/vvvvv",
            //         ct_user: "6666666",
            //         ct_time: "192.666245",
            //         children: [
            //             {
            //                 id: 2,
            //                 p_name: "功能页面",
            //                 api: "/44444/444",
            //                 ct_user: "44444444",
            //                 ct_time: "444444444444444"
            //             }
            //         ]
            //     }
            // ];

            that.$api.permission
                .get()
                .then(res => {
                    if (res.code == 0) {
                        that.permissionData = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取所有权限失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取所有权限失败，请刷新后重试.");
                });
        },

        genIndex(arr, result) {
            let that = this;

            for (var i = 0; i < arr.length; i++) {
                var item = arr[i];

                result.push(item.id);

                if (item.children && item.children.length) {
                    that.genIndex(item.children, result);
                }
            }
        },

        sortCommit() {
            let that = this,
                data = [];

            //生成排序
            that.genIndex(that.permissionData, data);

            that.$api.permission
                .sort({
                    data
                })
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "排序成功.",
                            type: "success",
                            duration: 800
                        });

                        that.$emit('refresh-data');

                        that.closeDialog();
                    } else {
                        that.$message.error(
                            res.msg || "排序失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("排序失败，请刷新后重试.");
                });
        },

        afterClose(){
            let that = this;
            that.permissionData = [];
        }
    }
};
</script>
<style lang="less" scoped>
.dialog-content {
    min-height: 300px;
    padding: 10px;
    border: 1px solid #dcdfe6;
}
</style>