<template>
    <div class="login-container">

        <el-form ref="form" :model="form" class="login-form">
            <div class="login-title">管理系统</div>
            <el-input v-model.trim="form.username" placeholder="请输入用户名" auto-complete="off"></el-input>
            <el-input @keyup.native.enter="submitHandler" v-model.trim="form.password" type="password" placeholder="请输入用户密码" autocomplete="off"></el-input>
            <el-button type="primary" :loading="loading" @click="submitHandler">登录</el-button>
        </el-form>
    </div>
</template>

<script>
export default {
    name: "login",

    data() {
        return {
            form: {
                username: "",
                password: ""
            },

            loading: false
        };
    },

    methods: {
        submitHandler() {
            let that = this;

            if (!that.form.username) {
                this.$message.error("请输入用户名.");
                return;
            }

            if (!that.form.password) {
                this.$message.error("请输入密码.");
                return;
            }

            that.loading = true;

            that.$api.users
                .login(that.form)
                .then(res => {
                    if (res.error == 0) {
                        this.$message({
                            message: "登录成功",
                            type: "success",
                            duration: 800
                        });

                        that.$store.commit("setUserInfo", res.data);

                        setTimeout(() => {
                            let redirect = decodeURIComponent(
                                that.$route.query.redirect || "/test"
                            );

                            that.$router.push({
                                //你需要接受路由的参数再跳转
                                path: redirect
                            });

                        }, 1200);

                    } else {
                        that.$message.error("用户或密码错误,请重试.");
                        that.loading = false;
                    }
                })
                .catch(res => {
                    that.$message.error("用户或密码错误,请重试.");
                    that.loading = false;
                });
        }
    }
};
</script>
<style lang='less'>
.login-container {
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: #12afe3;
    background-image: url(../image/login_bg.jpg);
    background-size: 100% 100%;

    .login-form {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 35px 15px;
        background-color: white;
        border: 1px solid #d2cece;
        border-radius: 6px;
        max-width: 350px;

        input,
        div {
            margin-bottom: 10px;
        }

        button {
            width: 100%;
        }

        .login-title {
            text-align: center;
            font-size: 1.2em;
        }
    }
}
</style>