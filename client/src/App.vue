<template>
  <router-view></router-view>
</template>

<script>
export default {
  name: "App",
  data() {
    return {};
  }
};
</script>
<style lang="less">
// @import (reference) 可以使用里面的变量
// ubuntu 系统导入less 要使用 ～@css，别名要添加～
// @import "~@css/less/reset";
@import "~@css/normalize";
@import "~@css/common";
@import "~@css/custom";
</style>