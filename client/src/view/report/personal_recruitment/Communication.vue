<template>
    <el-dialog
        title="沟通管理"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="60%"
        v-dialog-drag
    >

        <el-table
            :data="tdata"
            height="400"
            border
            style="width: 100%"
            class="mb-20"
        >
            <el-table-column
                align="center"
                prop="communicate_time"
                label="时间"
                width="180"
                :formatter="formatterDate"
            >
            </el-table-column>

            <el-table-column
                align="center"
                prop="ct_user"
                label="招聘负责人"
                width="100"
            >
            </el-table-column>
            <el-table-column
                align="center"
                prop="content"
                label="沟通内容"
            >
            </el-table-column>
        </el-table>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">关 闭</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../../base/DialogForm";
import { formatDate } from "@common/util";

export default {
    name: "Communication",
    mixins: [DialogForm],

    props: {
        //简历的id
        resume_id: {
            required: true,
            type: Number
        }
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;
                that.getCommunication();
            }
        }
    },

    data() {
        return {
            tdata: [],
            tableLoading: false
        };
    },
    methods: {
        getCommunication() {
            let that = this;

            that.tableLoading = true;

            that.$api.communication
                .get({
                    resume_id: that.resume_id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.tdata = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取沟通信息失败，请刷新后重试."
                        );
                    }

                    that.tableLoading = false;
                })
                .catch(res => {
                    that.tableLoading = false;
                    that.$message.error("获取沟通信息失败，请刷新后重试.");
                });
        },

        //关闭后调用
        afterClose() {
            let that = this;
            that.tdata = [];
        },

        //格式化yyyy-MM-dd
        formatterDate(row, column, cellValue, index) {
            return formatDate(cellValue, "yyyy-MM-dd");
        }
    }
};
</script>
<style lang="less" scoped>
</style>