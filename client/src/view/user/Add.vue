<template>
    <el-dialog
        title="新增用户"
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
                label="用户名"
                prop="uname"
            >
                <el-input
                    v-model.trim="form.uname"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

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

            <el-form-item
                label="密码"
                prop="passwd"
            >
                <el-input
                    type="password"
                    v-model="form.passwd"
                    autocomplete="off"
                ></el-input>
            </el-form-item>

            <el-form-item
                label="确认密码"
                prop="repasswd"
            >
                <el-input
                    type="password"
                    v-model="form.repasswd"
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
            apiType: "user",

            form: {
                uname: "",
                personal_name: "",
                phone: "",
                passwd: "",
                repasswd: ""
            },

            formRules: {
                uname: [
                    {
                        required: true,
                        message: "请输入用户名",
                        trigger: "blur"
                    },
                    {
                        min: 2,
                        // max: 16,
                        message: "长度在最小在2个字符",
                        trigger: "blur"
                    },
                    {
                        validator: (rule, value, callback) => {
                            if (!/^[a-zA-Z][\da-zA-Z]/.test(value)) {
                                callback(
                                    new Error(
                                        "用户必须是字母或数字，并且以字母开头."
                                    )
                                );
                            } else {
                                callback();
                            }
                        },
                        trigger: "blur"
                    }
                ],
                // phone: [{ type: "number", message: "电话必须为数字" }],
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

    methods: {}
};
</script>