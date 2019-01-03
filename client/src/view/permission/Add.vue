<template>
    <el-dialog
        title="新增权限"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        v-dialog-drag
    >
        <el-form
            :model="form"
            :rules="formRules"
            label-width="100px"
            ref="form"
        >

            <el-form-item
                label="父级"
                prop="top_class"
            >
                <el-cascader
                    v-model="form.top_class"
                    placeholder="菜单名称"
                    :options="permissionData"
                    :props="cascaderProps"
                    filterable
                    change-on-select
                    class="permission-select-width"
                ></el-cascader>
            </el-form-item>

            <el-form-item label="类型">
                <el-select
                    v-model="form.p_type"
                    placeholder="请选择类型"
                    class="permission-select-width"
                >
                    <el-option
                        label="菜单"
                        :value="0"
                    ></el-option>
                    <el-option
                        label="功能"
                        :value="1"
                    ></el-option>
                </el-select>
            </el-form-item>

            <el-form-item
                label="权限名称"
                prop="p_name"
            >
                <el-input
                    v-model.trim="form.p_name"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

            <div v-show="form.p_type == 0">
                <el-form-item
                    label="菜单图标"
                    prop="p_icon"
                >
                    <el-input
                        v-model.trim="form.p_icon"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>

                <el-form-item
                    label="模块名称"
                    prop="p_component"
                >
                    <el-input
                        v-model.trim="form.p_component"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>

                <el-form-item
                    label="菜单地址"
                    prop="url"
                >
                    <el-input
                        v-model.trim="form.url"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>
            </div>

            <div v-show="form.p_type == 1">
                <el-form-item
                    label="功能英文名称"
                    prop="p_act_name"
                >
                    <el-input
                        v-model.trim="form.p_act_name"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>

                <el-form-item
                    label="Api"
                    prop="api"
                >
                    <el-input
                        v-model.trim="form.api"
                        autocomplete="off"
                    ></el-input>
                </el-form-item>
            </div>

        </el-form>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog" :disabled="commitLoading">取 消</el-button>
            <el-button
                type="primary"
                @click="addCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('permiss_add')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import AddDialogForm from "@view/base/AddDialogForm";
export default {
    name: "Add",
    mixins: [AddDialogForm],

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
            apiType: "permission",

            permissionData: [],

            cascaderProps: {
                label: "p_name",
                value: "id",
                children: "children"
            },

            form: {
                top_class: [0],
                url: "",
                parent_id: 0,
                p_name: "",
                p_type: 0,
                p_act_name: "",
                p_icon: "",
                api: "",
                p_component: ""
            },

            formRules: {
                p_name: [
                    { required: true, message: "权限名称必填", trigger: "blur" }
                ],

                p_act_name: [
                    {
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (
                                that.form.p_type == 1 &&
                                !/[a-zA-z_]/.test(value)
                            ) {
                                callback(
                                    new Error(
                                        "功能英文名称必须是英文，并且不为空."
                                    )
                                );
                            } else {
                                callback();
                            }
                        },
                        trigger: "blur"
                    }
                ]
            }
        };
    },

    methods: {
        getPermissionData() {
            let that = this;
            //
            // let arrData = [
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
            //                         api1: "/33333",
            //                         api2: "/3333n3333nnn/nnn",
            //                         api3: "/333/nnnn",
            //                         api4: "/3333/nnnnnn",
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
            //
            // that.permissionData = arrData;

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

        //添加之前处理下数据
        beforeAdd(item) {
            //找到上级的id
            item.parent_id = item.top_class[item.top_class.length - 1];
        },

        afterClose() {
            let that = this;

            that.form.p_type = 0;
            that.permissionData = [];
        }
    }
};
</script>
<style lang="less" scoped>
.permission-select-width {
    width: 100%;
}
</style>