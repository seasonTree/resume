<template>
    <el-dialog
        title="查看简历"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        top="2vh"
        width="80%"
        v-dialog-drag
    >

        <el-form
            :model="form"
            label-width="90px"
            class="form-container mb-20"
            ref="form"
            :disabled="true"
        >
            <el-row :gutter="20">
                <!-- *****************基础信息******************* -->
                <el-col :span="12">
                    <div class="grid-content bg-purple left-container">
                        <div class="sub-header mb-10">
                            基本信息
                        </div>

                        <div class="base-info">
                            <el-form-item
                                label="姓名"
                                prop="name"
                                class="split-row"
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
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.age"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>
                        <div class="base-info">
                            <el-form-item
                                label="性别"
                                prop="sex"
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.sex"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>

                            <el-form-item
                                label="毕业时间"
                                prop="graduation_time"
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.graduation_time"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>

                        <div class="base-info">
                            <el-form-item
                                label="专业"
                                prop="speciality"
                                class="split-row"
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
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.birthday"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>

                        <div class="base-info">
                            <el-form-item
                                label="期望薪资"
                                prop="expected_money"
                                class="split-row"
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
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.phone"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>
                        <div class="base-info">
                            <el-form-item
                                label="户口所在地"
                                prop="native_place"
                                class="split-row"
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
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.work_year"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>
                        <div class="base-info">
                            <el-form-item
                                label="英语水平"
                                prop="english"
                                class="split-row"
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
                                class="split-row"
                            >
                                <el-input
                                    size="mini"
                                    v-model.trim="form.expected_address"
                                    autocomplete="off"
                                ></el-input>
                            </el-form-item>
                        </div>
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
                        <el-form-item
                            label="学历"
                            prop="educational"
                        >
                            <el-input
                                size="mini"
                                v-model.trim="form.educational"
                                autocomplete="off"
                            ></el-input>
                        </el-form-item>

                        <el-form-item
                            label="岗位"
                            prop="expected_job"
                        >
                            <el-input
                                size="mini"
                                v-model.trim="form.expected_job"
                                autocomplete="off"
                            ></el-input>
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

                    <!-- *****************基础信息******************* -->
                </el-col>

                <el-col
                    :span="12"
                    class="right-container"
                >
                    <el-tabs v-model="activeName2">
                        <el-tab-pane
                            label="用户信息"
                            name="first"
                        >
                            <el-row
                                align="top"
                                class="user-info"
                            >
                                <el-col class="user-item">
                                    <div class="sub-header">教育背景</div>
                                    <el-input
                                        type="textarea"
                                        v-model="form.educational_background"
                                        resize="none"
                                    ></el-input>
                                </el-col>

                                <el-col class="user-item">
                                    <div class="sub-header">个人技能</div>
                                    <el-input
                                        type="textarea"
                                        v-model="form.skillExpertise"
                                        resize="none"
                                    ></el-input>
                                </el-col>
                                <el-col class="user-item">
                                    <div class="sub-header">自我评价</div>
                                    <el-input
                                        type="textarea"
                                        v-model="form.selfEvaluation"
                                        resize="none"
                                    ></el-input>
                                </el-col>
                                <el-col class="last-user-item">
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
                                class="other"
                            >
                                <el-col class="other-item">
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
                                class="other"
                            >
                                <el-col class="other-item">
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
                                justify="start"
                                class="custom-container"
                            >
                                <el-col class="custom-item">
                                    <div class="sub2-header">自定义01</div>
                                    <el-input
                                        type="textarea"
                                        v-model="form.custom1"
                                        resize="none"
                                    ></el-input>
                                </el-col>
                                <el-col class="custom-item">
                                    <div class="sub2-header">自定义02</div>
                                    <el-input
                                        type="textarea"
                                        v-model="form.custom2"
                                        resize="none"
                                    ></el-input>
                                </el-col>
                                <el-col class="last-custom-item">
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
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "@view/base/DialogForm";
export default {
    name: "ViewResume",
    mixins: [DialogForm],

    props: {
        id: {
            type: Number,
            required: true
        }
    },

    watch: {
        show(newValue, oldValue) {
            let that = this;

            if (newValue) {
                that.getResume();
            }
        }
    },

    data() {
        return {
            activeName2: "first",

            form: {
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
                educational_background: "",
                //  educationalBackground:"",

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
            }
        };
    },

    methods: {
        getResume() {
            let that = this;
            that.$api.resume
                .getByID({
                    id: that.id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.form = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取简历信息失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取简历信息失败，请刷新后重试.");
                });
        },

        afterClose() {
            let that = this;
            that.activeName2 = "first";

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

        /deep/ .el-tabs {
            height: 100%;

            /deep/ .el-tabs__content {
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

.custom-container {
    height: 100%;

    .custom-item,
    .last-custom-item {
        height: 33.33%;
        position: relative;

        /deep/ textarea {
            position: absolute;
            top: 34px;
            bottom: 10px;
        }
    }

    .last-custom-item {
        /deep/ textarea {
            bottom: 0;
        }
    }
}

.user-info {
    height: 100%;

    .user-item,
    .last-user-item {
        height: 25%;
        position: relative;

        /deep/ textarea {
            position: absolute;
            top: 34px;
            bottom: 10px;
        }
    }

    .last-user-item {
        /deep/ textarea {
            bottom: 0;
        }
    }
}

.other {
    height: 100%;

    .other-item {
        height: 100%;
        position: relative;

        /deep/ .el-textarea {
            height: 100%;

            /deep/ textarea {
                height: 100%;
            }
        }
    }
}

.base-info {
    width: 100%;
    display: flex;
    .split-row {
        width: 50%;
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
</style>