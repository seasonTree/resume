<template>
    <div>
        <el-dialog
            title="沟通管理"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            :close-on-click-modal="false"
            width="60%"
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
                    :formatter="formatterDateDetail"
                >
                </el-table-column>

                <el-table-column
                    align="center"
                    prop="ct_user"
                    label="招聘负责人"
                    width="100"
                >
                </el-table-column>

                <!-- <el-table-column
                    align="center"
                    prop="screen"
                    label="通过筛选"
                >
                    <template slot-scope="scope">
                        <i
                            v-if="scope.row.screen == 1"
                            class="fa fa-check right status-icon"
                            @click="changeStatus(scope.row.id, 1, scope.row)"
                        ></i>
                    </template>
                </el-table-column> -->

                <!-- <el-table-column
                    align="center"
                    prop="arrange_interview "
                    label="安排面试"
                >
                    <template slot-scope="scope">
                        <i
                            v-if="scope.row.arrange_interview == 1"
                            class="fa fa-check right status-icon"
                            @click="changeStatus(scope.row.id, 1, scope.row)"
                        ></i>
                    </template>
                </el-table-column> -->

                <!-- <el-table-column
                    align="center"
                    prop="arrive "
                    label="到场"
                >
                    <template slot-scope="scope">
                        <i
                            v-if="scope.row.arrive == 1"
                            class="fa fa-check right status-icon"
                            @click="changeStatus(scope.row.id, 1, scope.row)"
                        ></i>

                    </template>
                </el-table-column> -->

                <!-- <el-table-column
                    align="center"
                    prop="approved_interview "
                    label="通过面试"
                >
                    <template slot-scope="scope">
                        <i
                            v-if="scope.row.approved_interview == 1"
                            class="fa fa-check right status-icon"
                            @click="changeStatus(scope.row.id, 1, scope.row)"
                        ></i>

                    </template>
                </el-table-column> -->

                <!-- <el-table-column
                    align="center"
                    prop="entry"
                    label="入职"
                >
                    <template slot-scope="scope">
                        <i
                            v-if="scope.row.entry == 1"
                            class="fa fa-check right status-icon"
                            @click="changeStatus(scope.row.id, 1, scope.row)"
                        ></i>

                    </template>
                </el-table-column> -->

                <el-table-column
                    align="center"
                    prop="content"
                    label="沟通内容"
                >
                </el-table-column>

                <el-table-column
                    align="center"
                    prop="address"
                    label="操作"
                    width="50"
                >
                    <template slot-scope="scope">
                        <el-tooltip
                            effect="dark"
                            content="修改"
                            placement="top"
                        >
                            <el-button
                                type="primary"
                                size="mini"
                                icon="el-icon-edit"
                                circle
                                @click.stop="showEditDialog(scope.row.id)"
                                :disabled="!$check_pm('resume_commu_edit')"
                            ></el-button>
                        </el-tooltip>

                    </template>

                </el-table-column>
            </el-table>

            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button
                    @click="addDialog = true"
                    type="primary"
                    :disabled="!$check_pm('resume_commu_add')"
                >新增沟通</el-button>
                <el-button @click="closeDialog">关 闭</el-button>
            </div>
        </el-dialog>

        <add-communication
            :show.sync="addDialog"
            :resume_id="resume_id"
            @add-item="addItem"
        >
        </add-communication>

        <edit-communication
            :show.sync="editDialog"
            :edit-item="currentEditItem"
            @edit-item="editItem"
        >
        </edit-communication>
    </div>

</template>

<script>
import DialogForm from "../base/DialogForm";
import AddCommunication from "./AddCommunication";
import EditCommunication from "./EditCommunication";
export default {
    name: "Communication",
    components: {
        AddCommunication,
        EditCommunication
    },
    mixins: [DialogForm],

    props: {
        //简历的id
        resume_id: {
            required: true,
            type: Number
        }
        // show:{
        //     type:Boolean,
        //     default:false,
        // }
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
            dialogVisible: false,

            //新增沟通
            addDialog: false,

            //修改窗口
            editDialog: false,
            currentEditItem: {},

            tableLoading: false
        };
    },
    methods: {
        //新增沟通
        addItem(item) {
            let that = this;
            // console.log(item);
            that.tdata.unshift(item);
        },

        editItem(item) {
            let that = this;

            for (var i = 0; i < that.tdata.length; i++) {
                var citem = that.tdata[i];

                if (citem.id == item.id) {
                    for (var key in item) {
                        if (key != "id") {
                            citem[key] = item[key];
                        }
                    }
                    break;
                }
            }
        },

        showEditDialog(id) {
            let that = this;
          
            that.$api.communication
                .getByID({
                    id
                })
                .then(res => {
                
                    if (res.code == 0) {
                        that.currentEditItem = res.data;
                    } else {
                        that.$message.error(res.msg || "获取数据失败，请重试.");
                    }

                    that.editDialog = true;
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请重试.");
                });
        },
        
        //判断
        // changeStatus(id, status, item) {
        //     let that = this;

        //     that.$api.user
        //         .changeStatus({
        //             id,
        //             status
        //         })
        //         .then(res => {
        //             if (res.code == 0) {
        //                 that.$message({
        //                     message: "更新状态成功.",
        //                     type: "success",
        //                     duration: 800
        //                 });

        //                 item.status = status;
        //             } else {
        //                 that.$message.error(res.msg || "更新状态失败，请重试.");
        //             }
        //         })
        //         .catch(res => {
        //             that.$message.error("更新状态失败，请重试.");
        //         });
        // },

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
        }
    }
};
</script>
<style lang="less" scoped>
</style>