<template>
    <el-dialog
        title="修改用户"
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
                label="姓名"
                prop="personal_name"
            >
                <el-input
                    v-model.trim="form.personal_name"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

            <el-form-item
                label="电话"
                prop="phone"
            >
                <el-input
                    v-model.trim="form.phone"
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
                @click="editCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('user_edit')"
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
            apiType: "user",
            form: {
                id: 0,
                personal_name: '',
                phone: ''
            }
        };
    },

    methods: {}
};
</script>