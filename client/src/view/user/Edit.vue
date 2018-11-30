<template>
    <el-dialog
        title="修改用户"
        :visible.sync="show"
        :show-close="false"
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
                label="姓名"
                prop="pesonal_name"
            >
                <el-input
                    v-model.trim="editItem.pesonal_name"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

            <el-form-item
                label="电话"
                prop="联系电话"
            >
                <el-input
                    v-model.trim="editItem.phone"
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
                @click="editCommit"
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
            formRules: {}
        };
    },

    methods: {}
};
</script>