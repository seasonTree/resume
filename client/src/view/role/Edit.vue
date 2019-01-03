<template>
    <el-dialog
        title="修改角色"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="500px"
        v-dialog-drag
    >
        <el-form
            :model="form"
            :rules="formRules"
            label-width="80px"
            ref="form"
        >
            <el-form-item
                label="角色名称"
                prop="role_name"
            >
                <el-input
                    v-model.trim="form.role_name"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

            <!-- <el-form-item label="状态">
                <el-switch
                    v-model="form.status"
                    active-color="#00c100"
                    inactive-color="#f56c6c"
                    :active-value="0"
                    :inactive-value="1"
                ></el-switch>
            </el-form-item> -->
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
                :disabled="!$check_pm('role_edit')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import EditDialogForm from "@view/base/EditDialogForm";
export default {
    name: "Edit",
    mixins: [EditDialogForm],

    data() {
        return {
            apiType: "role",

            form: {
                id: 0,
                role_name: "",
                // status: 0
            },

            formRules: {
                role_name: [
                    {
                        required: true,
                        message: "请输入角色名称",
                        trigger: "blur"
                    }
                ]
            }
        };
    },

    methods: {}
};
</script>
<style lang="less" scoped>
</style>