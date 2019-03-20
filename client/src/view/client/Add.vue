<template>
    <el-dialog
        title="新增客户"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        v-dialog-drag
    >
        <el-form
            :model="form"
            :rules="formRules"
            label-width="80px"
            ref="form"
        >
            <el-form-item
                label="客户名称"
                prop="client_name"
            >
                <el-input
                    v-model.trim="form.client_name"
                    autocomplete="off"
                ></el-input>
            </el-form-item>
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
                :disabled="!$check_pm('user_add')"
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
            apiType: "client",

            form: {
                client_name: "",
            },

            formRules: {
                client_name: [
                    {
                        required: true,
                        message: "请输入客户名称",
                        trigger: "blur"
                    }
                ]        
            }
        };
    },

    methods: {}
};
</script>