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
                prop="communicate_time"
            >
                <el-date-picker
                    v-model="form.communicate_time"
                    type="date"
                    placeholder="选择日期"
                    forma="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                >
                </el-date-picker>

                <el-row>

                    <div class="addcom_div-row">
                        <el-form-item
                            style="display: flex"
                            label="通过筛选："
                            prop="screen"
                        >
                            <el-switch
                                v-model="form.screen"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                            >
                            </el-switch>
                        </el-form-item>

                        <el-form-item
                            label="安排面试："
                            prop="arrange_interview"
                            style="display: flex"
                        >
                            <el-switch
                                v-model="form.arrange_interview"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                            >
                            </el-switch>
                        </el-form-item>
                        <el-form-item
                            label="是否入职："
                            prop="entry"
                            style="display: flex"
                        >
                            <el-switch
                                v-model="form.entry"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                            >
                            </el-switch>
                        </el-form-item>
                    </div>
                    <div class="addcom_div-row2">
                        <el-form-item
                            style="display: flex"
                            label="是否到场："
                            prop="arrive"
                        >
                            <el-switch
                                v-model="form.arrive"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                            >
                            </el-switch>
                        </el-form-item>

                        <el-form-item
                            label="通过面试："
                            prop="approved_interview"
                            style="display: flex"
                        >
                            <el-switch
                                v-model="form.approved_interview"
                                active-color="#13ce66"
                                inactive-color="#ff4949"
                            >
                            </el-switch>
                        </el-form-item>
                    </div>

                </el-row>
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
                    v-model="form.content"
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
                @click="editCommit"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import EditDialogForm from "../base/EditDialogForm";
export default {
    name: "EditCommunication",
    mixins: [EditDialogForm],

    props: {
        //简历的id
        // resume_id: {
        //     required: true,
        //     type: Number
        // }
    },

    data() {
        return {
            apiType: "resume",
            form: {
                screen: false,
                arrange_interview: false,
                arrive: false,
                approved_interview: false,
                entry: false,
                content: "",
                communicate_time: new Date()
            }
        };
    },

    methods: {
        editCommit() {
            let that = this;
            that.form.resume_id = that.resume_id;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.$api[that.apiType]
                        .editCommunication(that.form)
                        .then(res => {
                            if (res.code == 0) {
                                that.$emit(
                                    "add-item",
                                    JSON.parse(JSON.stringify(res.data))
                                );

                                that.$message({
                                    message: "修改成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.closeDialog();
                            } else {
                                that.$message.error(
                                    res.msg || "修改失败，请重试."
                                );
                            }
                        })
                        .catch(res => {
                            that.$message.error("修改失败，请重试.");
                        });
                }
            });
        }
    }
};
</script>
<style lang="less" scoped>
.addcom_div-row {
    display: flex;

    justify-content: space-between;
}
.addcom_div-row2 {
    display: flex;
    width: 63%;
    justify-content: space-between;
}
</style>