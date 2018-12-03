<template>
    <el-dialog
        title="修改用户密码"
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
                label="新密码"
                prop="newPass"
            >
                <el-input
                    v-model="changePwd.newPass"
                    type="password"
                    autocomplete="off"
                    placeholder="请输入新密码"
                ></el-input>
            </el-form-item>
            <el-form-item
                label="请确认密码"
                prop="newPassRe"
            >
                <el-input
                    v-model="changePwd.newPassRe"
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
            <el-button @click="resetForm">取 消</el-button>
            <el-button
                :loading="changLoading"
                type="primary"
                @click="changePassword"
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
            default: 0
        }
    },

    data() {
        return {
            formRules: [
                { required: true, message: "请输入密码", trigger: "blur" },
                {
                    min: 6,
                    max: 16,
                    message: "密码最少6个字符",
                    trigger: "blur"
                }
            ]
        };
    },
    created() {},
    mounted() {},
    watch: {},
    computed: {},
    methods: {}
};
</script>
<style lang="less" scoped>
</style>