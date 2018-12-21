<template>
    <div class="wrapper">

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
                        prop="name"
                        label="文件名"
                    >

                        <template slot-scope="scope">
                            <a
                                :href="scope.row.url"
                                :title="scope.row.name"
                            >{{scope.row.name}}</a>
                        </template>
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="address"
                        label="上传时间"
                    >
                    </el-table-column>

                    <el-table-column
                        align="center"
                        prop="address"
                        label="上传人"
                    >
                    </el-table-column>

                    <el-table-column
                        fixed="right"
                        label="操作"
                        width="180"
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
                                @click.stop="delFile(scope.row.id, scope.$index)"
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
                    action="/api/resume/upload_file"
                    multiple
                    :on-success="uploadSuccess"
                    :on-error="uploadError"
                    :show-file-list="false"
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
        delFile(id) {
            let that = this;

            that.$confirm("是否删除当前附件吗?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    that.$api.resume
                        .del({
                            id
                        })
                        .then(res => {
                            if (res.code == 0) {
                                that.$message({
                                    message: "删除成功.",
                                    type: "success",
                                    duration: 800
                                });

                                let delItem = null;
                                for (var i = 0; i < that.tdata.length; i++) {
                                    var item = that.tdata[i];

                                    if (item.id == id) {
                                        that.tdata.splice(i, 1);
                                        break;
                                    }
                                }
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
        uploadSuccess(response, file, fileList) {
            let that = this;

            if (res.code == 0) {
                that.$message({
                    message: "删除成功.",
                    type: "success",
                    duration: 800
                });

                let copyData = JSON.parse(JSON.stringify(res.data));

                that.tdata.unshift(...copyItem);
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
            that.uploadList = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>