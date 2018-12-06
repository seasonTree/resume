<template>
    <el-dialog
        title="新增角色"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
    >
        <el-form
            :model="form"
            :rules="formRules"
            label-width="80px"
            ref="form"
        >
            <el-form-item
                label="父级"
                prop="p_name"
            >
                <el-select
                    v-model="form.p_type"
                    placeholder="请选择类型"
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

            <el-form-item label="类型">
                <el-select
                    v-model="form.p_type"
                    placeholder="请选择类型"
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
                    v-model.trim="form.role_name"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

            <el-form-item
                label="菜单图标"
                prop="p_icon"
                v-if="form.p_type == 0"
            >
                <el-input
                    v-model.trim="form.p_icon"
                    autocomplete="off"
                ></el-input>
            </el-form-item>
            <el-form-item
                label="Api1"
                prop="api1"
            >
                <el-input
                    v-model.trim="form.api1"
                    autocomplete="off"
                ></el-input>
            </el-form-item>
            <el-form-item
                label="Api2"
                prop="api2"
            >
                <el-input
                    v-model.trim="form.api2"
                    autocomplete="off"
                ></el-input>
            </el-form-item>
            <el-form-item
                label="Api3"
                prop="api3"
            >
                <el-input
                    v-model.trim="form.api3"
                    autocomplete="off"
                ></el-input>
            </el-form-item>
            <el-form-item
                label="Api4"
                prop="api4"
            >
                <el-input
                    v-model.trim="form.api4"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

        </el-form>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">取 消</el-button>
            <el-button
                type="primary"
                @click="addCommit"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import AddDialogForm from "@view/base/AddDialogForm";
export default {
    name: "Add",
    mixins: [AddDialogForm],
    data() {
        return {
            apiType: "permission",

            form: {
                parent_id: 0,
                p_type: 0,
                p_icon: "",
                api1: "",
                api2: "",
                api3: "",
                api4: ""
            },

            formRules: {}
        };
    }
};
</script>
<style lang="less" scoped>
</style>