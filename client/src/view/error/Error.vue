<template>
    <div class="error">
        <el-button
            type="primary"
            class="home-btn"
            @click="home"
        >返回首页</el-button>
    </div>
</template>

<script>
export default {
    methods: {
        home() {
            this.$router.replace("/dashboard");
        }
    }
};
</script>
<style lang="less" scoped>
.error {
    background: url("../../image/error.jpg");
    position: absolute;
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-size: 100% 100%;
}

.home-btn {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
}
</style>