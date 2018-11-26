<template>
    <el-dialog title="测试" :visible.sync="show" :show-close="false" class="custom-dialog" :close-on-click-modal="false">
        <el-form :model="form" :rules="formRules" label-width="80px" ref="form">
            <el-form-item label="测试" prop="ty_name">
                <el-input v-model.trim="form.ty_name" autocomplete="off"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="closeDialog">取 消</el-button>
            <el-button type="primary" @click="addCommit">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "@view/base/DialogForm";
export default {
    name: "Add",
    mixins: [DialogForm],

    data() {
        return {
            form: {
                ty_name: ""
            },

            formRules: {}
        };
    },

    methods: {
        addCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                }
            });
        }
    }
};
</script>