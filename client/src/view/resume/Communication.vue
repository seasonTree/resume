<template>
    <el-dialog
        title="沟通管理"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
    >

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">取 消</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "component",
    components: {},
    mixins: [DialogForm],

    props: {
        id: {
            required: true,
            type: Number
        }
    },

    watch: {
        id(newValue, oldValue) {
            that.getCommunication();
        }
    },

    data() {
        return {
            commData: []
        };
    },
    methods: {
        getCommunication() {
            let that = this;

            that.$api.resume
                .getCommunication({
                    id: that.id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.commData = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取沟通信息失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取沟通信息失败，请刷新后重试.");
                });
        },

        //关闭后调用
        afterClose(){
            let that = this;
            that.commData = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>