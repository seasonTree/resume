<template>
    <el-dialog
        title="设置权限"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="400px"
        v-dialog-drag
    >
        <div class="role-permission mb-20">
            <el-tree
                :data="permissionData"
                :default-checked-keys="defaultCheckKey"
                show-checkbox
                node-key="id"
                :check-strictly="true"
                :props="defaultProps"
                ref="tree"
            >
                <span
                    class="custom-tree-node"
                    slot-scope="{ node, data }"
                >
                    <span>{{ node.label }}</span>

                    <span>
                        <el-button
                            type="text"                            
                            v-if="data.children && data.children.length"
                            @click.stop="() => selectChild(data, true)"
                        >
                            全选
                        </el-button>
                        <el-button
                            type="text"                            
                            v-if="data.children && data.children.length"
                            @click.stop="() => selectChild(data, false)"
                        >
                            取消
                        </el-button>
                    </span>
                </span>
            </el-tree>
        </div>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button
                @click="closeDialog"
                :disabled="commitLoading"
            >取 消</el-button>
            <el-button
                type="primary"
                @click="permissionCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('role_permiss_set')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "Permission",
    mixins: [DialogForm],

    props: {
        id: {
            type: Number,
            required: true
        }
    },

    data() {
        return {
            permissionData: [
                // {
                //     id: 1,
                //     p_name: "一级 1",
                //     children: [
                //         {
                //             id: 4,
                //             p_name: "二级 1-1",
                //             children: [
                //                 {
                //                     id: 9,
                //                     p_name: "三级 1-1-1"
                //                 },
                //                 {
                //                     id: 10,
                //                     p_name: "三级 1-1-2"
                //                 }
                //             ]
                //         }
                //     ]
                // },
                // {
                //     id: 2,
                //     p_name: "一级 2",
                //     children: [
                //         {
                //             id: 5,
                //             p_name: "二级 2-1"
                //         },
                //         {
                //             id: 6,
                //             p_name: "二级 2-2"
                //         }
                //     ]
                // },
                // {
                //     id: 3,
                //     p_name: "一级 3",
                //     children: [
                //         {
                //             id: 7,
                //             p_name: "二级 3-1"
                //         },
                //         {
                //             id: 8,
                //             p_name: "二级 3-2"
                //         }
                //     ]
                // }
            ],

            // defaultCheckKey: [1,7],
            defaultCheckKey: [],

            defaultProps: {
                children: "children",
                label: "p_name"
            }
        };
    },
    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;

                //获取所有的权限列表
                that.getPermissionData();
                //获取当前角色的权限
                that.getCheckPermission();
            }
        }
    },
    methods: {
        //选中子节点
        selectChild(node, checkAll) {
            let that = this,
                treeInstance = that.$refs.tree,
                keys = [],
                checkNode = node => {
                    for (var i = 0; i < node.length; i++) {
                        var item = node[i];
                        keys.push(item.id);

                        treeInstance.setChecked(item, checkAll);

                        if (item.children && item.children.length) {
                            checkNode(item.children);
                        }
                    }
                };

            checkNode([node]);
        },

        //获取所有的权限列表
        getPermissionData() {
            let that = this;

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

        //获取当前角色的权限
        getCheckPermission() {
            let that = this;

            that.$api.role
                .getCheckPermission({
                    id: that.id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.defaultCheckKey = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获当前角色权限失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获当前角色权限失败，请刷新后重试.");
                });
        },

        //设置角色权限
        permissionCommit() {
            let that = this,
                checkNodes = that.$refs["tree"].getCheckedNodes(false, true),
                checkIDs = checkNodes.map(item => {
                    return item.id;
                });

            that.$api.role
                .setRolePermission({
                    id: that.id,
                    //直接返回权限id数组
                    permission: checkIDs
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
                            res.msg || "设置角色权限失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("设置角色权限失败，请刷新后重试.");
                });
        },

        //关闭窗口后调用
        afterClose() {
            let that = this;
            that.permissionData = [];
            that.defaultCheckKey = [];
        }
    }
};
</script>
<style lang="less" scoped>
.role-permission {
    min-height: 300px;
    // max-height: 600px;
    padding: 10px;
    border: 1px solid #dcdfe6;

    .custom-tree-node {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        padding-right: 8px;
    }
}
</style>