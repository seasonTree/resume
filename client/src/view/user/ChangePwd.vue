<template>
    <el-dialog>
        <el-form
            :model="changePwd"
            ref="changePwdForm"
            label-width="100px"
            :rules="rules"
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
export default {
    name: "ChangePwd",
    components: {},
    props: {
        id: {
            type: Number,
            default: 0
        }
    },
    data() {
        return {};
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