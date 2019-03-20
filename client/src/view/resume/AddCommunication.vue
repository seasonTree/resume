<template>
    <el-dialog
        top="2vh"
        title="新增沟通信息"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        width="800px"
        v-dialog-drag
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
                    format="yyyy-MM-dd"
                    value-format="yyyy-MM-dd"
                >
                </el-date-picker>
            </el-form-item>

            <el-row
                :gutter="20"
                class="comm-row"
            >
                <el-col
                    :span="12"
                    class="flex"
                >
                    <el-form-item
                        label="是否推荐："
                        prop="screen"
                        class="flex-item form-el"
                    >
                        <el-switch
                            v-model="form.screen"
                            active-color="#13ce66"
                            :inactive-value="0"
                            :active-value="1"
                        >
                        </el-switch>
                    </el-form-item>

                    <el-select
                        v-model="form.screen_client"
                        multiple
                        placeholder="请选择客户"
                        class="flex-item"
                        :disabled="form.screen == 0"
                        filterable
                    >
                        <el-option
                            v-for="item in client"
                            :key="item.id"
                            :label="item.client_name"
                            :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </el-col>
                <el-col
                    :span="12"
                    class="flex"
                >
                    <el-form-item
                        label="是否安排："
                        prop="arrange_interview"
                        class="flex-item form-el"
                    >
                        <el-switch
                            v-model="form.arrange_interview"
                            active-color="#13ce66"
                            :inactive-value="0"
                            :active-value="1"
                        >
                        </el-switch>
                    </el-form-item>

                    <el-select
                        v-model="form.arrange_interview_client"
                        multiple
                        placeholder="请选择客户"
                        class="flex-item"
                        :disabled="form.arrange_interview == 0"
                        filterable
                    >
                        <el-option
                            v-for="item in client"
                            :key="item.id"
                            :label="item.client_name"
                            :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>

            <el-row
                :gutter="20"
                class="comm-row"
            >
                <el-col
                    :span="12"
                    class="flex"
                >
                    <el-form-item
                        label="是否到场："
                        prop="arrive"
                        class="flex-item form-el"
                    >
                        <el-switch
                            v-model="form.arrive"
                            active-color="#13ce66"
                            :inactive-value="0"
                            :active-value="1"
                        >
                        </el-switch>
                    </el-form-item>

                    <el-select
                        v-model="form.arrive_client"
                        multiple
                        placeholder="请选择客户"
                        class="flex-item"
                        :disabled="form.arrive == 0"
                        filterable
                    >
                        <el-option
                            v-for="item in client"
                            :key="item.id"
                            :label="item.client_name"
                            :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </el-col>
                <el-col
                    :span="12"
                    class="flex"
                >
                    <el-form-item
                        label="是否通过："
                        prop="approved_interview"
                        class="flex-item form-el"
                    >
                        <el-switch
                            v-model="form.approved_interview"
                            active-color="#13ce66"
                            :inactive-value="0"
                            :active-value="1"
                        >
                        </el-switch>
                    </el-form-item>

                    <el-select
                        v-model="form.approved_interview_client"
                        multiple
                        placeholder="请选择客户"
                        class="flex-item"
                        :disabled="form.approved_interview == 0"
                        filterable
                    >
                        <el-option
                            v-for="item in client"
                            :key="item.id"
                            :label="item.client_name"
                            :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>

            <el-row
                :gutter="20"
                class="comm-row"
            >
                <el-col
                    :span="12"
                    class="flex"
                >
                    <el-form-item
                        label="是否入职："
                        prop="entry"
                        class="flex-item form-el"
                    >
                        <el-switch
                            v-model="form.entry"
                            active-color="#13ce66"
                            :inactive-value="0"
                            :active-value="1"
                        >
                        </el-switch>
                    </el-form-item>

                    <el-select
                        v-model="form.entry_client"
                        multiple
                        placeholder="请选择客户"
                        class="flex-item"
                        :disabled="form.entry == 0"
                        filterable
                    >
                        <el-option
                            v-for="item in client"
                            :key="item.id"
                            :label="item.client_name"
                            :value="item.id"
                        >
                        </el-option>
                    </el-select>
                </el-col>
            </el-row>

            <!-- <div class="addcom_div-row">
                        <el-form-item
                            label="是否推荐："
                            prop="screen"
                        >
                            <el-switch
                                v-model="form.screen"
                                active-color="#13ce66"
                                :inactive-value="0"
                                :active-value="1"
                            >
                            </el-switch>
                        </el-form-item>

                        <el-form-item
                            label="是否安排："
                            prop="arrange_interview"
                            style="display: flex"
                        >
                            <el-switch
                                v-model="form.arrange_interview"
                                active-color="#13ce66"
                                :inactive-value="0"
                                :active-value="1"
                            >
                            </el-switch>
                        </el-form-item>
                        <el-form-item
                            style="display: flex"
                            label="是否到场："
                            prop="arrive"
                        >
                            <el-switch
                                v-model="form.arrive"
                                active-color="#13ce66"
                                :inactive-value="0"
                                :active-value="1"
                            >
                            </el-switch>
                        </el-form-item>
                    </div>
                    <div class="addcom_div-row2">
                        <el-form-item
                            label="是否通过："
                            prop="approved_interview"
                            style="display: flex"
                        >
                            <el-switch
                                v-model="form.approved_interview"
                                active-color="#13ce66"
                                :inactive-value="0"
                                :active-value="1"
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
                                :inactive-value="0"
                                :active-value="1"
                            >
                            </el-switch>
                        </el-form-item>
                    </div>

                </el-row> -->
            <!-- </el-form-item> -->

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
            <el-button
                @click="closeDialog"
                :disabled="commitLoading"
            >取 消</el-button>
            <el-button
                type="primary"
                @click="addCommit"
                :loading="commitLoading"
                :disabled="!$check_pm('resume_commu_add')"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import AddDialogForm from "../base/AddDialogForm";
export default {
    name: "AddCommunication",
    mixins: [AddDialogForm],

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
                that.getClietData();
            }
        }
    },

    data() {
        return {
            apiType: "communication",
            form: {
                screen: 0,
                screen_client: [],
                arrange_interview: 0,
                arrange_interview_client: [],
                arrive: 0,
                arrive_client: [],
                approved_interview: 0,
                approved_interview_client: [],
                entry: 0,
                entry_client: [],
                content: "",
                communicate_time: new Date()
            },

            client: []
        };
    },
    methods: {
        beforeAdd() {
            let that = this;
            that.form.resume_id = that.resume_id;
        },

        getClietData() {
            let that = this;

            that.$api.client
                .getAll()
                .then(res => {
                    if (res.code == 0) {
                        that.client = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取客户列表失败，请重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取客户列表失败，请重试.");
                });
        }

        // addCommit() {
        //     let that = this;
        //     that.form.resume_id = that.resume_id;

        //     that.$refs["form"].validate(valid => {
        //         if (valid) {
        //             that.$api[that.apiType]
        //                 .addCommunication(that.form)
        //                 .then(res => {
        //                     if (res.code == 0) {
        //                         that.$emit(
        //                             "add-item",
        //                             JSON.parse(JSON.stringify(res.data))
        //                         );

        //                         that.$message({
        //                             message: "新增成功.",
        //                             type: "success",
        //                             duration: 800
        //                         });

        //                         that.closeDialog();
        //                     } else {
        //                         that.$message.error(
        //                             res.msg || "新增失败，请重试."
        //                         );
        //                     }
        //                 })
        //                 .catch(res => {
        //                     that.$message.error("新增失败，请重试.");
        //                 });
        //         }
        //     });
        // }
    }
};
</script>
<style lang="less" scoped>
.flex {
    display: flex;
}

.form-el {
    margin-bottom: 0;
}

.flex-item {
    .flex;
    flex: 1 auto;
}

.comm-row {
    margin-bottom: 16px;
}

// .comm-row {
//     .flex;
//     width: 100%;
//     justify-content: start;

//     .sub-item {
//         .flex;
//         flex: 1;
//         padding-right: 20px;

//         .form-el {
//             .flex;
//             width: 240px;
//             padding-right: 10px;
//         }
//     }
// }

// .addcom_div-row {
//     display: flex;

//     justify-content: space-between;
// }
// .addcom_div-row2 {
//     display: flex;
//     width: 63%;
//     justify-content: space-between;
// }
</style>