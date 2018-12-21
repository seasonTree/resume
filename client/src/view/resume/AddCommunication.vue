<template>
    <el-dialog
        top="20vh"
        title="新增沟通信息"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="500px"
    >
        <el-form
            :model="form"
            :rules="formRules"
            class="form-container"
            ref="form"
        >
            <el-form-item
                label="沟通日期"
                prop="value1"
            >
                <el-date-picker
                    v-model="date"
                    type="date"
                    placeholder="选择日期"
                    forma="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                >
                </el-date-picker>
            </el-form-item>

            <el-form-item
                label=""
                prop="content"
            >
                <el-input
                    resize="none"
                    type="textarea"
                    :autosize="{ minRows:10, maxRows: 16}"
                    placeholder="请输入内容"
                    v-model="content"
                >
                </el-input>
            </el-form-item>
        </el-form>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">取 消</el-button>
            <el-button
                type="primary"
                @click="addCommit"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import AddDialogForm from "../base/AddDialogForm";
export default {
    name: "AddCommunication",
    components: {},
    mixins: [AddDialogForm],

    props: {
        //简历的id
        resume_id: {
            required: true,
            type: Number
        }
    },

    data() {
        return {
            apiType: "resume",

            date: "",
            content: ""
        };
    },
    methods: {
        addCommit() {
            let that = this;
            that.form.resume_id = that.resume_id;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.$api[that.apiType]
                        .addCommunication(that.form)
                        .then(res => {
                            if (res.code == 0) {
                                that.$emit(
                                    "add-item",
                                    JSON.parse(JSON.stringify(res.data))
                                );

                                that.$message({
                                    message: "新增成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.closeDialog();
                            } else {
                                that.$message.error(
                                    res.msg || "新增失败，请重试."
                                );
                            }
                        })
                        .catch(res => {
                            that.$message.error("新增失败，请重试.");
                        });
                }
            });
        }
    }
};
</script>
<style lang="less" scoped>
</style>