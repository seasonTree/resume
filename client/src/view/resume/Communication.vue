<template>
    <div class="wrapper">

        <el-dialog
            title="沟通管理"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            :close-on-click-modal="false"
        >

            <template>
                <el-table
                    height="400"
                    border
                    style="width: 100%"
                >
                    <el-table-column
                        align="center"
                        prop="name"
                        label="时间"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="address"
                        label="沟通内容"
                    >
                    </el-table-column>

                </el-table>
            </template>

            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="addDialog = true"  type="primary">新增沟通</el-button>
                <el-button @click="closeDialog">关 闭</el-button>
            </div>
        </el-dialog>

        <add-communication
            :show.sync="addDialog"
            :resume_id="resume_id" 
            @add-item="addItem">
        </add-communication>
    </div>

</template>

<script>
import DialogForm from "../base/DialogForm";
import AddCommunication from "./AddCommunication";
export default {
    name: "Communication",
    components: {
        AddCommunication
    },
    mixins: [DialogForm],

    props: {

        //简历的id
        resume_id: {
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
            commData: [],
            dialogVisible: false,

            //新增沟通
            addDialog: false,
        };
    },
    methods: {
        //新增沟通
        addItem(item) {
            let that = this;
            that.tdata.unshift(item);
        },

        getCommunication() {
            let that = this;

            that.$api.resume
                .getCommunication({
                    resume_id: that.resume_id
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
        afterClose() {
            let that = this;
            that.commData = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>