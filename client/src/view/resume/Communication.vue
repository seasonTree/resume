<template>
    <div class="wrapper">

        <el-dialog
            top="34px"
            title="沟通管理"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            :close-on-click-modal="false"
        >

            <template>
                <el-table
                    height="700"
                    border
                    style="width: 100%"
                >
                    <el-table-column
                        align="center"
                        prop="name"
                        label="客户"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="address"
                        label="地址"
                    >

                    </el-table-column>

                </el-table>
            </template>

            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="dialogVisible = true">新增沟通</el-button>
                <el-button @click="closeDialog">关 闭</el-button>
            </div>
        </el-dialog>
        <el-dialog
            top="34px"
            title="提示提示"
            :visible.sync="dialogVisible"
            width="50%"
            :before-close="handleClose"
            class="custom-dialog"
            :close-on-click-modal="false"
            :class="dialog"
        >

            <el-form>
                <el-form-item
                    label="沟通日期"
                    prop="value1"
                >
                    <el-date-picker
                        v-model="value1"
                        type="date"
                        placeholder="选择日期"
                        class="input-width"
                    >
                    </el-date-picker>
                </el-form-item>
                <el-input
                    type="textarea"
                    :autosize="{ minRows: 2, maxRows: 4}"
                    placeholder="请输入内容"
                    v-model="textarea3"
                >
                </el-input>

            </el-form>
            <!-- <div
                class="block"
                style="text-align:center"
            >
                <span class="demonstration">日期</span>
                <el-date-picker
                    size="mini"
                    v-model="value1"
                    type="date"
                    placeholder="选择日期"
                >
                </el-date-picker>
            </div>
            <span style="width:900%">
                <textarea style=" width:792px; 
                            height:613px; 
                            resize:none; 
                            font-size: 20px;"></textarea>
            </span> -->
            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button
                    type="primary"
                    @click="dialogVisible = false"
                >确 定</el-button>
            </div>
        </el-dialog>
    </div>

</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "communication",
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
            commData: [],
            dialogVisible: false,
            value1: "",
            textarea3: ""
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
        afterClose() {
            let that = this;
            that.commData = [];
        },
        //新增沟通
        handleClose(done) {
            this.$confirm("确认关闭？")
                .then(_ => {
                    done();
                })
                .catch(_ => {});
        }
    }
};
</script>
<style lang="less" scoped>
.input-width {
    // width: 100%;
}

.custom-dialog {
    .el-dialog__body {
        padding-bottom: 20px !important;
    }
}
</style>