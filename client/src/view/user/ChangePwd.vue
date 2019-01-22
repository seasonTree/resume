<template>
    <el-dialog
        title="修改用户密码"
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
                label="新密码"
                prop="passwd"
            >
                <el-input
                    v-model="form.passwd"
                    type="password"
                    autocomplete="off"
                    placeholder="请输入新密码"
                ></el-input>
            </el-form-item>
            <el-form-item
                label="确认密码"
                prop="repasswd"
            >
                <el-input
                    v-model="form.repasswd"
                    type="password"
                    autocomplete="off"
                    placeholder="请再次输入新密码"
                ></el-input>
            </el-form-item>
        </el-form>
        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button
                @click="closeDialog"
                :disabled="commitLoading"
            >取 消</el-button>
            <el-button
                type="primary"
                @click="changePwd"
                :loading="commitLoading"
                :disabled="!$check_pm('user_change_pass')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "ChangePwd",

    mixins: [DialogForm],

    props: {
        id: {
            type: Number,
            required: true
        }
    },

    data() {
        return {
            form: {
                id: 0,
                passwd: "",
                repasswd: ""
            },
            formRules: {
                passwd: [
                    { required: true, message: "请输入密码", trigger: "blur" },
                    {
                        min: 6,
                        max: 20,
                        message: "密码最少6个字符, 最大20个字符",
                        trigger: "blur"
                    }
                ],
                repasswd: [
                    {
                        required: true,
                        message: "请再次输入密码",
                        trigger: "blur"
                    },
                    {
                        min: 6,
                        max: 20,
                        message: "确认密码最少6个字符, 最大20个字符",
                        trigger: "blur"
                    },
                    {
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (that.form.passwd !== value) {
                                callback(
                                    new Error("两次的密码不一致，请重新输入.")
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
        changePwd() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.commitLoading = true;

                    that.form["id"] = that.id;

                    that.$api.user
                        .changeUserPasswd(that.form)
                        .then(res => {
                            if (res.code == 0) {
                                that.$message({
                                    message: "修改成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.closeDialog();
                            } else {
                                that.$message.error(res.msg);
                            }

                            that.commitLoading = false;
                        })
                        .catch(res => {
                            that.commitLoading = false;
                            that.$message.error("修改失败，请重试.");
                        });
                }
            });
        }
    }
};
</script>
<style lang="less" scoped>
</style>