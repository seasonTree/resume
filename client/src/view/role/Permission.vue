<template>
    <el-dialog
        title="设置权限"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
    >
        <!-- <el-form
            :model="form"
            :rules="formRules"
            label-width="80px"
            ref="form"
        >

        </el-form> -->

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">取 消</el-button>
            <el-button type="primary">确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "Permission",
    mixins: [DialogForm],

    props: {
        // id: {
        //     type: Number,
        //     required: true
        // }
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