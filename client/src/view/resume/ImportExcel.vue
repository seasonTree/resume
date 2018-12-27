<template>
    <div>

        <el-dialog
            title="批量导入"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            :close-on-click-modal="false"
            width="85%"
        >

            <template>
                <el-table
                    class="mb-20"
                    height="400"
                    border
                    style="width: 100%"
                    :data="tdata"
                    stripe
                >
                    <el-table-column
                        align="center"
                        prop="personal_name"
                        label="招聘负责人"
                        width="100"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="ct_time"
                        label="日期"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="nearest_job"
                        label="最近岗位"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="name"
                        label="候选人"
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="phone"
                        label="联系电话"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="email"
                        label="邮件"
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="school"
                        label="毕业学校"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="graduation_time"
                        label="毕业年份"
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="work_year"
                        label="工作年限"
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="source"
                        label="简历来源 "
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="custom1"
                        label="首次沟通"
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="personal_name"
                        label="上传人"
                    >
                    </el-table-column>
                    <el-table-column
                        align="center"
                        width="130"
                        prop="custom2"
                        label="二次沟通&面试安排&Offer"
                    >
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        label="操作"
                        width="60"
                        align="center"
                    >

                        <el-tooltip
                            effect="dark"
                            content="删除"
                            placement="bottom"
                        >
                            <el-button
                                type="danger"
                                size="mini"
                                icon="el-icon-delete"
                                circle
                                @click.stop="delRow(scope.row.id, scope.$index)"
                            ></el-button>
                        </el-tooltip>
                    </el-table-column>

                </el-table>
            </template>

            <div
                slot="footer"
                class="dialog-footer"
            >

                <el-upload
                    action="/api/resume/upload_excel"
                    :on-success="uploadSuccess"
                    :on-error="uploadError"
                    :show-file-list="false"
                    accept="application/vnd.ms-excel,.csv,
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                >
                    <el-button type="success"
                        :disabled="!$check_pm('resume_excel_upload')"
                    >上传Excel</el-button>
                </el-upload>
                <el-button
                    type="primary"
                    @click="batchAdd"
                    :disabled="!$check_pm('resume_batch_add')"
                >批量导入</el-button>
                <el-button @click="closeDialog">关 闭</el-button>
            </div>
        </el-dialog>

    </div>

</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "ImportExcel",

    mixins: [DialogForm],

    data() {
        return {
            tdata: []
        };
    },
    methods: {
        batchAdd() {
            let that = this;

            that.$api.resume
                .batchAdd({
                    data: that.tdata
                })
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "批量导入成功，正在重新刷新表格数据...",
                            type: "success",
                            duration: 800
                        });

                        //通知外表格刷新数据
                        that.$emit('refresh-data');

                        that.closeDialog();
                    } else {
                        that.$message.error(
                            res.msg || "批量导入失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("批量导入失败，请刷新后重试.");
                });
        },

        //删除当前行
        delRow(id, index) {
            let that = this;
            that.tdata.splice(index, 1);
        },

        //上传成功
        uploadSuccess(res, file, fileList) {
            let that = this;

            if (res.code == 0) {
                that.$message({
                    message: "上传成功.",
                    type: "success",
                    duration: 800
                });

                let copyData = JSON.parse(JSON.stringify(res.data));

                that.tdata = copyData;
            } else {
                that.$message.error(res.msg || "上传失败，请重试.");
            }
        },

        //上传失败
        uploadError(err, file, fileList) {
            this.$message.error("上传失败，请重试.");
        }
    }
};
</script>
<style lang="less" scoped>
</style>