<template>
    <el-dialog
        title="修改权限"
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
                @click="editCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('permiss_edit')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import EditDialogForm from "@view/base/EditDialogForm";
export default {
    name: "Edit",
    mixins: [EditDialogForm],

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
        //在设置数据之前操作
        beforeSetData(item) {
            let top_class = item.top_class;

            //如果选中了，并且超过了一个，父的第一个id为0会对应不上，另外处理
            if (top_class.length && top_class.length > 1 && top_class[0] == 0) {
                top_class.shift();
            }
        },

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

        //修改提交之前处理下数据
        beforeEdit(item) {
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