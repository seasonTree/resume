<template>
    <el-dialog title="新增用户" :visible.sync="show" :show-close="false" class="custom-dialog" :close-on-click-modal="false">
        <el-form :model="form" :rules="formRules" label-width="80px" ref="form">
            <el-form-item label="用户名" prop="uname">
                <el-input v-model.trim="form.uname" autocomplete="off"></el-input>
            </el-form-item>

            <el-form-item label="姓名" prop="pesonal_name">
                <el-input v-model.trim="form.pesonal_name" autocomplete="off"></el-input>
            </el-form-item>

            <el-form-item label="密码" prop="passwd">
                <el-input type="password" v-model="form.passwd" autocomplete="off"></el-input>
            </el-form-item>

            <el-form-item label="确认密码" prop="repasswd">
                <el-input type="password" v-model="form.repasswd" autocomplete="off"></el-input>
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
                uname: '',
                pesonal_name: '',
                passwd: '',
                repasswd: ''
            },

            formRules: {
                uname: [
                    { required: true, message: '请输入用户名', trigger: 'blur' },
                    { min: 6, max: 16, message: '长度在6到16个字符', trigger: 'blur' },
                    { validator: (rule, value, callback) =>{
                        if (!/^[a-zA-Z][\da-zA-Z]/.test(value)) {
                            callback(new Error('用户必须是字母或数字，并且以字母开头.'));
                        }
                    }, trigger: 'blur' }
                ],
                passwd: [
                    { required: true, message: '请输入密码', trigger: 'blur' },
                    { min: 6, max: 16, message: '密码最少6个字符', trigger: 'blur' }
                ],
                repasswd: [
                    { required: true, message: '请再次输入密码', trigger: 'blur' },
                    { min: 6, max: 16, message: '密码最少6个字符', trigger: 'blur' },
                    { validator: (rule, value, callback) =>{
                        let that = this;

                        if (that.passwd !== value) {
                            callback(new Error('两次的密码不一致，请重新输入.'));
                        }
                    }, trigger: 'blur' }
                ]
            }
        };
    },

    methods: {
        addCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.$api.user.add(that.form).then((res) =>{

                    }).catch((res)=>{

                    })
                }
            });
        }
    }
};
</script>