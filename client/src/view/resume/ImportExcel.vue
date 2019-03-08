<template>
    <div>
        <el-dialog
            title="批量导入"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            :close-on-click-modal="false"
            width="85%"
            v-dialog-drag
        >
            <div class="mb-20">
                <el-table
                    height="400"
                    border
                    style="width: 100%;margin-bottom: 10px;"
                    :data="tdata"
                    stripe
                    v-loading="uploadLoading"
                >
                    <el-table-column
                        align="center"
                        prop="personal_name"
                        label="招聘负责人"
                        width="100"
                    ></el-table-column>

                    <el-table-column
                        align="center"
                        prop="ct_time"
                        label="日期"
                    ></el-table-column>

                    <el-table-column
                        align="center"
                        prop="expected_job"
                        label="岗位"
                    ></el-table-column>

                    <el-table-column
                        align="center"
                        prop="name"
                        label="候选人"
                    ></el-table-column>
                    <el-table-column
                        align="center"
                        prop="phone"
                        label="联系电话"
                    ></el-table-column>

                    <el-table-column
                        align="center"
                        prop="email"
                        label="邮件"
                    ></el-table-column>
                    <el-table-column
                        align="center"
                        prop="school"
                        label="毕业学校"
                    ></el-table-column>

                    <el-table-column
                        align="center"
                        prop="graduation_time"
                        label="毕业年份"
                    ></el-table-column>
                    <el-table-column
                        align="center"
                        prop="work_year"
                        label="工作年限"
                    ></el-table-column>
                    <el-table-column
                        align="center"
                        prop="source"
                        label="简历来源 "
                    ></el-table-column>
                    <el-table-column
                        align="center"
                        prop="custom1"
                        label="首次沟通"
                    >
                        <template slot-scope="scope">
                            <el-tooltip
                                effect="dark"
                                :content="scope.row.custom1"
                                placement="top"
                            >
                                <div class="cell">{{scope.row.custom1}}</div>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column
                        align="center"
                        width="130"
                        prop="custom2"
                        label="二次沟通&面试安排&Offer"
                    >
                        <template slot-scope="scope">
                            <el-tooltip
                                effect="dark"
                                :content="scope.row.custom2"
                                placement="top"
                            >
                                <div class="cell">{{scope.row.custom2}}</div>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                    <el-table-column
                        align="center"
                        prop="personal_name"
                        label="上传人"
                    ></el-table-column>
                    <el-table-column
                        fixed="right"
                        label="操作"
                        width="60"
                        align="center"
                    >
                        <template slot-scope="scope">
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
                                    @click.stop="delRow(scope.row.row_id, scope.$index)"
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
                </el-table>

                <el-row
                    class="pager"
                    type="flex"
                    justify="end"
                >
                    <el-pagination
                        @current-change="changePage"
                        @size-change="pageSizeChange"
                        background
                        layout="total, sizes, prev, pager, next, jumper"
                        :page-sizes="[10, 20, 50, 100]"
                        :page-size="pager.size"
                        :total="pager.total"
                        :current-page="pager.current"
                    ></el-pagination>
                </el-row>
            </div>
            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-upload
                    action="/api/resume/upload_excel"
                    :on-success="uploadSuccess"
                    :on-error="uploadError"
                    :show-file-list="false"
                    accept="application/vnd.ms-excel, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    :before-upload="beforeUpload"
                >
                    <el-button
                        type="success"
                        :disabled="!$check_pm('resume_excel_upload') || commitLoading"
                        :loading="uploadLoading"
                    >上传Excel</el-button>
                </el-upload>
                <el-button
                    type="primary"
                    @click="batchAdd"
                    :loading="commitLoading"
                    :disabled="!$check_pm('resume_batch_add') || uploadLoading"
                >批量导入</el-button>
                <el-button
                    @click="closeDialog"
                    :disabled="commitLoading || uploadLoading"
                >关 闭</el-button>
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
            tdata: [],
            uploadLoading: false,

            excelData: [],

            pager: {
                total: 0,
                current: 1,
                size: 10
            },

            deleteID: [],
            excelFile: null
        };
    },
    methods: {
        batchAdd() {
            let that = this,
                params = new FormData();

            that.commitLoading = true;

            params.append("excelFile", that.excelFile);
            params.append("deleteID", that.deleteID);

            that.$api.resume
                .batchAdd(params)
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "批量导入成功，正在重新刷新表格数据...",
                            type: "success",
                            duration: 800
                        });

                        //通知外表格刷新数据
                        that.$emit("refresh-data");

                        that.closeDialog();
                    } else {
                        that.$message.error(
                            res.msg || "批量导入失败，请刷新后重试."
                        );
                    }

                    that.commitLoading = false;
                })
                .catch(res => {
                    that.commitLoading = false;
                    that.$message.error("批量导入失败，请刷新后重试.");
                });
        },

        //删除当前行
        delRow(row_id, index) {
            let that = this;

            that.deleteID.push(row_id);

            that.tdata.splice(index, 1);
        },

        beforeUpload() {
            this.uploadLoading = true;
            this.resetField();
        },

        //上传成功
        uploadSuccess(res, file, fileList) {
            let that = this;

            that.uploadLoading = false;

            if (res.code == 0) {
                that.$message({
                    message: "上传成功.",
                    type: "success",
                    duration: 800
                });

                that.excelFile = file.raw;

                let copyData = JSON.parse(JSON.stringify(res.data));

                that.excelData = copyData;
                that.pager.total = that.excelData.length;

                that.changePage(1);
            } else {
                that.$message.error(res.msg || "上传失败，请重试.");
            }
        },

        pageSizeChange(val) {
            let that = this;
            that.pager.size = val;
            that.changePage(1);
        },

        changePage(index) {
            let that = this;

            that.pager.current = index;

            //更新表格的内容
            that.tdata = that.excelData.slice(
                that.pager.size * (index - 1),
                that.pager.size * (index - 1) + that.pager.size
            );
        },

        //上传失败
        uploadError(err, file, fileList) {
            this.uploadLoading = false;
            this.$message.error("上传失败，请重试.");
        },

        resetField() {
            let that = this;

            that.tdata = [];
            that.uploadLoading = false;
            that.excelData = [];
            that.pager = {
                total: 0,
                current: 1,
                size: 10
            };
            that.deleteID = [];
            that.excelFile = null;
        },

        //关闭窗口后调用
        afterClose() {
            let that = this;
            that.resetField();
        }
    }
};
</script>
<style lang="less" scoped>
</style>