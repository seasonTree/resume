<template>
    <div>

        <el-dialog
            title="添加附件"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            :close-on-click-modal="false"
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
                        prop="file_name"
                        label="文件名"
                    >

                        <template slot-scope="scope">
                            <a
                                :href="scope.row.download_url"
                                :title="scope.row.file_name"
                            >{{scope.row.file_name}}</a>
                        </template>
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="ct_time"
                        label="上传时间"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="personal_name"
                        label="上传人"
                    >
                    </el-table-column>

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
                                    icon="el-icon-delete"
                                    size="mini"
                                    circle
                                    @click.stop="delFile(scope.row, scope.$index)"
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>

                </el-table>
            </template>

            <div
                slot="footer"
                class="dialog-footer"
            >

                <el-upload
                    action="/api/resume/upload_file"
                    multiple
                    :on-success="uploadSuccess"
                    :on-error="uploadError"
                    :show-file-list="false"
                    :data="otherParams"
                    accept="application/vnd.ms-excel,.csv,
                        application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
                        application/msword,
                        application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                        text/html,message/rfc822"
                >
                    <el-button type="primary">上传附件</el-button>
                </el-upload>
                <el-button @click="closeDialog">关 闭</el-button>
            </div>
        </el-dialog>

    </div>

</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "UploadFile",

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
                that.getData();
            }
        }
    },

    computed: {
        otherParams() {
            return { resume_id: this.resume_id };
        }
    },

    data() {
        return {
            tdata: []
        };
    },
    methods: {
        getData() {
            let that = this;

            that.$api.resume
                .getUploadFile({
                    resume_id: that.resume_id
                })
                .then(res => {
                    if (res.code == 0) {
                        for(var i = 0; i < res.data.length; i++){
                            var item = res.data[i];

                            item.download_url = '/api/resume/download?url=' + decodeURIComponent(item.url);
                        }


                        that.tdata = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取上传文件列表失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取上传文件列表失败，请刷新后重试.");
                });
        },

        //删除文件
        delFile(row, index) {
            let that = this;

            that.$confirm("是否删除当前附件吗?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    that.$api.resume
                        .delFile({
                            id: row.id,
                            resume_url: row.resume_url
                        })
                        .then(res => {
                            if (res.code == 0) {
                                that.$message({
                                    message: "删除成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.tdata.splice(index, 1);
                            } else {
                                that.$message.error(res.msg);
                            }
                        })
                        .catch(res => {
                            that.$message.error("删除失败，请刷新后重试.");
                        });
                })
                .catch(() => {});
        },

        //上传成功
        uploadSuccess(res, file, fileList) {
            let that = this;

            if (res.code == 0) {
                that.$message({
                    message: "删除成功.",
                    type: "success",
                    duration: 800
                });

                let copyData = JSON.parse(JSON.stringify(res.data));

                that.tdata.unshift(copyData);
            } else {
                that.$message.error(res.msg || "上传失败，请重试.");
            }
        },

        //上传失败
        uploadError(err, file, fileList) {
            this.$message.error("上传失败，请重试.");
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