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
                    height="400"
                    border
                    style="width: 100%"
                >
                    <el-table-column
                        align="center"
                        prop="name"
                        label="文件名"
                    >
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

                </el-table>
            </template>

            <div
                slot="footer"
                class="dialog-footer"
            >
                <!-- <el-button  type="primary">上传</el-button> -->
                <el-upload
                    action="https://jsonplaceholder.typicode.com/posts/"
                    multiple
                    :file-list="fileList"
                >
                    <el-button
                        size="small"
                        type="primary"
                    >点击上传</el-button>
                    <div
                        slot="tip"
                        class="el-upload__tip"
                    >只能上传jpg/png文件，且不超过500kb</div>
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
        id(newValue, oldValue) {
            that.getData();
        }
    },

    data() {
        return {
            uploadList: []
            
            
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
                        that.uploadList = res.data;
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

        //关闭后调用
        afterClose() {
            let that = this;
            that.uploadList = [];
        },

        
    }
};
</script>
<style lang="less" scoped>
</style>