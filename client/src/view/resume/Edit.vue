<template>
    <div>
        <el-dialog
            title="修改简历"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            top="2vh"
            width="60%"
            v-dialog-drag
        >
            <el-form
                :model="form"
                label-width="90px"
                class="form-container mb-20"
                ref="form"
                :rules="formRules"
            >
                <el-row :gutter="20">
                    <!-- *****************基础信息******************* -->
                    <el-col :span="12">
                        <div class="grid-content bg-purple left-container">
                            <div
                                class="sub-header"
                                style="margin-bottom: 10px;"
                            >基本信息</div>

                            <div class="div-row">
                                <el-form-item
                                    label="姓名"
                                    prop="name"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.name"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="年龄"
                                    prop="age"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.age"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>
                            <div class="div-row">
                                <el-form-item
                                    label="性别"
                                    prop="sex"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.sex"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <!-- <el-form-item
                                    label="学历"
                                    prop="educational"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.educational"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item> -->

                                <el-form-item
                                    label="毕业时间"
                                    prop="graduation_time"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.graduation_time"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>

                            <div class="div-row">
                                <el-form-item
                                    label="专业"
                                    prop="speciality"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.speciality"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="生日"
                                    prop="birthday"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.birthday"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>

                            <div class="div-row">
                                <el-form-item
                                    label="期望薪资"
                                    prop="expected_money"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.expected_money"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="移动电话"
                                    prop="phone"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.phone"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>

                            <div class="div-row">
                                <el-form-item
                                    label="户口所在地"
                                    prop="native_place"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.native_place"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="工作年限"
                                    prop="work_year"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.work_year"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>

                            <div class="div-row">
                                <el-form-item
                                    label="英语水平"
                                    prop="english"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.english"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="期望工作地"
                                    prop="expected_address"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.expected_address"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>
                            <!-- <div class="div-row">
                                <el-form-item
                                    label="毕业时间"
                                    prop="graduation_time"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.graduation_time"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="简历来源"
                                    prop="source"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.source"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div> -->

                            <el-form-item
                                label="简历来源"
                                prop="source"
                            >

                                <el-select
                                    clearable
                                    v-model.trim="form.source"
                                    filterable
                                    placeholder=""
                                    size="small"
                                    style="width: 100%;"
                                >
                                    <el-option
                                        v-for="item in source"
                                        :key="item"
                                        :label="item"
                                        :value="item"
                                    ></el-option>
                                </el-select>

                            </el-form-item>
                            <el-form-item
                                label="学历"
                                prop="educational"
                            >
                                <el-select
                                    clearable
                                    v-model.trim="form.educational"
                                    filterable
                                    placeholder=""
                                    size="small"
                                    style="width: 100%;"
                                >
                                    <el-option
                                        v-for="item in edu"
                                        :key="item"
                                        :label="item"
                                        :value="item"
                                    ></el-option>
                                </el-select>
                            </el-form-item>

                            <el-form-item
                                label="岗位"
                                prop="expected_job"
                            >
                                <!-- <el-input size="mini" v-model.trim="form.expected_job" autocomplete="off"></el-input> -->
                                <el-select
                                    clearable
                                    v-model.trim="form.expected_job"
                                    filterable
                                    placeholder="请选择岗位"
                                    style="width: 100%;"
                                    size="small"
                                >
                                    <el-option
                                        v-for="item in positionOptions"
                                        :key="item"
                                        :label="item"
                                        :value="item"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item
                                label="当前状态"
                                prop="status"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.status"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>

                            <el-form-item
                                label="电子邮箱"
                                prop="email"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.email"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>

                            <el-form-item
                                label="毕业院校"
                                prop="school"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.school"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>

                            <el-form-item
                                label="最近单位"
                                prop="nearest_unit"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.nearest_unit"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>

                            <el-form-item
                                label="最近职位"
                                prop="nearest_job"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.nearest_job"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>

                            <el-form-item
                                label="公司来源"
                                prop="company_type"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.company_type"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>
                    </el-col>

                    <!-- *****************基础信息******************* -->
                    <el-col
                        :span="12"
                        class="right-container"
                    >
                        <el-tabs v-model="activeName2">
                            <el-tab-pane
                                label="用户管理"
                                name="first"
                            >
                                <el-row
                                    align="top"
                                    class="row-height"
                                >
                                    <el-col class="right-item">
                                        <div class="sub-header">教育背景</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.educational_background"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                    <el-col class="right-item">
                                        <div class="sub-header">个人技能</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.skillExpertise"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                    <el-col class="right-item">
                                        <div class="sub-header">自我评价</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.selfEvaluation"
                                            resize="none"
                                        ></el-input>
                                    </el-col>

                                    <el-col class="right-item">
                                        <div class="sub-header">所获证书</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.certificate"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                </el-row>
                            </el-tab-pane>

                            <el-tab-pane
                                label="工作经历"
                                name="second"
                            >
                                <el-row
                                    align="top"
                                    class="row3-height"
                                >
                                    <el-col class="right2-item">
                                        <el-input
                                            type="textarea"
                                            v-model="form.workExperience"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                </el-row>
                            </el-tab-pane>

                            <el-tab-pane
                                label="项目经历"
                                name="third"
                            >
                                <el-row
                                    align="top"
                                    class="row3-height"
                                >
                                    <el-col class="right2-item">
                                        <el-input
                                            type="textarea"
                                            v-model="form.projectExperience"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                </el-row>
                            </el-tab-pane>

                            <el-tab-pane
                                label="自定义信息"
                                name="fourth"
                            >
                                <el-row
                                    align="top"
                                    class="row2-height"
                                >
                                    <el-col class="right2-item">
                                        <div class="sub2-header">自定义01</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.custom1"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                    <el-col class="right2-item">
                                        <div class="sub2-header">自定义02</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.custom2"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                    <el-col class="right2-item">
                                        <div class="sub2-header">自定义03</div>
                                        <el-input
                                            type="textarea"
                                            v-model="form.custom3"
                                            resize="none"
                                        ></el-input>
                                    </el-col>
                                </el-row>
                            </el-tab-pane>
                        </el-tabs>
                    </el-col>
                </el-row>
            </el-form>

            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="closeDialog">关闭</el-button>
                <el-button
                    type="primary"
                    @click="editCommit"
                    :disabled="!$check_pm('resume_edit')"
                    :loading="commitLoading"
                >确 定</el-button>
            </div>
        </el-dialog>

        <resume-check
            :show.sync="resumeCheckDialog"
            :resumeData="resumeData"
            @continue-commit="commit"
            @cancel-commit="cancelCommit"
        ></resume-check>
    </div>
</template>

<script>
import EditDialogForm from "@view/base/EditDialogForm";
import ResumeCheck from "./ResumeCheck";

export default {
    name: "Edit",
    mixins: [EditDialogForm],
    components: {
        ResumeCheck
    },

    // watch: {
    //     show(newValue, oldValue) {
    //         let that = this;
    //         // console.log(newValue, oldValue);
    //         // if (newValue) {
    //         //     that.getResume();
    //         // }
    //     }
    // },

    data() {
        return {
            apiType: "resume",
            form: {
                id: 0,
                name: "",
                age: "",
                sex: "",
                birthday: "",
                expected_money: "",
                phone: "",
                native_place: "",
                work_year: "",
                english: "",
                status: "",
                expected_job: "",
                email: "",
                source: "",

                //毕业时间
                graduation_time: "",
                projectExperience: "",
                educational: "",
                speciality: "",
                expected_address: "",
                school: "",

                //  educationalBackground:"",
                educational_background: "",
                nearest_unit: "",
                nearest_job: "",

                skillExpertise: "",
                selfEvaluation: "",
                certificate: "",

                workExperience: "",

                //工作技能
                // personalskills: "",

                //自定义
                custom1: "",
                custom2: "",
                custom3: ""
            },

            edu: [
                "初中",
                "高中",
                "大专",
                "统招大专",
                "自考大专",
                "民办大专",
                "本科",
                "统招本科",
                "自考本科",
                "民办本科",
                "硕士",
                "博士",
                "研究生",
                "无学历"
            ],
            source: [
                "前程无忧",
                "中国人才",
                "智联招聘",
                "拉勾网",
                "BOSS直聘",
                "校园招聘",
                "内部推荐",
                "外部推荐",
                "协调转入",
                "实习憎",
                "自动投递",
                "返聘",
                "其他渠道"
            ],

            //是否已经弹出了errorMessage,用于直接失去焦点，然后点击确定的时候使用
            hasShowErrorMessage: false,

            formRules: {
                name: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(that.checkField["name"]);
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],
                expected_job: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(
                                    that.checkField["expected_job"]
                                );
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],
                phone: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(that.checkField["phone"]);
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],

                email: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(that.checkField["email"]);
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],
                school: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(that.checkField["school"]);
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],

                educational: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(
                                    that.checkField["educational"]
                                );
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],

                source: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(that.checkField["source"]);
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],

                work_year: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(
                                    that.checkField["work_year"]
                                );
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ],
                graduation_time: [
                    {
                        required: true,
                        validator: (rule, value, callback) => {
                            let that = this;

                            if (value === "") {
                                that.setHasErrorMessage();

                                that.$message.error(
                                    that.checkField["graduation_time"]
                                );
                            }
                            callback();
                        },
                        trigger: "blur"
                    }
                ]
            },

            activeName: "second",
            activeName2: "first",

            //检查重名的数据
            resumeData: [],
            resumeCheckDialog: false,

            positionOptions: [],

            //要检查的字段
            checkField: {
                name: "请输入姓名.",
                expected_job: "请选择岗位.",
                phone: "请填写移动电话.",
                email: "请填写电子邮箱.",
                school: "请填写毕业院校.",
                educational: "请填写学历.",
                source: "请填写简历来源.",
                work_year: "请填写工作年限.",
                graduation_time: "请填写毕业时间."
            }
        };
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;
                that.getPosition();
            }
        }
    },

    methods: {
        setHasErrorMessage() {
            let that = this;
            that.hasShowErrorMessage = true;

            setTimeout(() => {
                that.hasShowErrorMessage = false;
            }, 3e3);
        },

        getPosition() {
            let that = this;

            that.$api.position_cate
                .getAll()
                .then(res => {
                    if (res.code == 0) {
                        that.positionOptions = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取岗位列表数据失败，请重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取岗位列表失败，请重试.");
                });
        },

        cancelCommit() {
            this.commitLoading = false;
        },

        commit() {
            let that = this;

            //清空重名表格的数据
            that.resumeData = [];

            that.$refs["form"].validate(valid => {
                if (valid) {
                    //提交之前
                    that.beforeEdit(that.form);

                    that.$api[that.apiType]
                        .edit(that.form)
                        .then(res => {
                            if (res.code == 0) {
                                //修改成功后
                                that.afterEdit(res.data);

                                that.$emit(
                                    "edit-item",
                                    JSON.parse(JSON.stringify(res.data))
                                );

                                that.$message({
                                    message: "修改成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.closeDialog();
                            } else {
                                that.$message.error(res.msg);
                            }

                            that.commitLoading = false;
                        })
                        .catch(res => {
                            that.commitLoading = false;
                            that.$message.error("修改失败，请重试.");
                        });
                }
            });
        },

        editCommit() {
            let that = this,
                notPass = false;

            //校验必填的数据
            for (var key in that.form) {
                let value = that.form[key];

                if (that.checkField[key] && value == "") {
                    that.hasShowErrorMessage ||
                        that.$message.error(that.checkField[key]);

                    notPass = true;
                    break;
                }
            }

            //如果校验不通过的就返回
            if (notPass) {
                return;
            }

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.commitLoading = true;

                    that.$api[that.apiType]
                        .checkName({
                            id: that.form.id,
                            name: that.form.name
                        })
                        .then(res => {
                            if (res.code == 0) {
                                if (res.data.length) {
                                    that.resumeData = JSON.parse(
                                        JSON.stringify(res.data)
                                    );
                                    that.resumeCheckDialog = true;
                                } else {
                                    that.commit();
                                }
                            } else {
                                that.$message.error(
                                    res.msg || "检查重名失败，请刷新重试."
                                );

                                that.commitLoading = false;
                            }
                        })
                        .catch(res => {
                            that.commitLoading = false;
                            that.$message.error("检查重名失败，请刷新重试.");
                        });
                }
            });
        },

        afterClose() {
            let that = this;
            that.activeName = "second";
            that.activeName2 = "first";
            that.analyzeContent = "";

            //清空重名表格的数据
            that.resumeData = [];

            for (var key in that.form) {
                var item = that.form[key];

                if (item) {
                    that.form[key] = "";
                }
            }
        }
    }
};
</script>

<style lang="less" scoped>
.left-container {
    padding: 10px;
    margin-bottom: 5px;
}

.sub-header {
    text-align: center;
    background: #ccc;
    padding: 10px;
    font-size: 14px;
}

.form-container {
    position: relative;

    .right-container {
        position: absolute;
        left: 50%;
        top: 0;
        bottom: 0;

        .el-tabs {
            height: 100%;

            .el-tabs__content {
                position: absolute;
                top: 54px;
                bottom: 0;
                left: 10px;
                right: 10px;
                display: flex;

                .el-tab-pane {
                    width: 100%;
                }
            }
        }
    }
}

.sub2-header {
    text-align: center;
    background: #ccc;
    padding: 10px;
    font-size: 14px;
}

.row2-height {
    height: 100%;

    .right2-item {
        height: 33.57%;
        position: relative;

        .el-textarea {
            position: absolute;
            top: 34px;
            bottom: 10px;

            textarea {
                height: 100%;
            }
        }
    }
}
.row-height {
    height: 100%;

    .right-item {
        height: 25.19%;
        position: relative;

        .el-textarea {
            position: absolute;
            top: 34px;
            bottom: 10px;

            textarea {
                height: 100%;
            }
        }
    }
}

.bg-purple {
    background: #fff;
    border: 1px solid #ccc;
}

.grid-content {
    border-radius: 4px;
    min-height: 36px;
    .el-form-item {
        margin-bottom: 6px;
    }
}
.row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
}
.div-row {
    display: flex;
}
</style>