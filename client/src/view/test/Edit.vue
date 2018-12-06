<template>
    <el-dialog title="测试服务" :visible.sync="show" :show-close="false" class="custom-dialog" :close-on-click-modal="false">
        <el-form :model="form" :rules="formRules" label-width="80px" ref="form">
            <el-form-item label="测试服务" prop="ty_name">
                <el-input v-model.trim="form.ty_name" autocomplete="off"></el-input>
            </el-form-item>

        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="closeDialog">取 消</el-button>
            <el-button type="primary" @click="editCommit">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "@view/base/DialogForm";
export default {
    name: "Edit",
    mixins: [DialogForm],

    props: {
        editItem: {
            type: Object,
            default: {
                id: 0,
                ty_name: ""
            }
        }
    },

    data() {
        return {
            form: {
                ty_name: ""
            },

            formRules: {}
        };
    },

    methods: {
        editCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {

                }
            });
        }
    }
};
</script>