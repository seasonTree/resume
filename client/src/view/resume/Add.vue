<template>
    <el-dialog
        title="添加简历"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        top="4.5vh"
        width="60%"
    >

        <el-tabs
            v-model="activeName"
            class="mb-20"
        >
            <el-tab-pane
                label="粘贴简历"
                name="first"
            >
                <el-input
                    type="textarea"
                    placeholder="请输入要分析的内容"
                    v-model="analyzeContent"
                    class="analyze-content"
                    resize="none"
                >
                </el-input>
            </el-tab-pane>
            <el-tab-pane
                label="手工输入"
                name="second"
            >
                <el-form
                    :model="form"
                    :rules="formRules"
                    label-width="90px"
                    class="form-container"
                    ref="form"
                >
                    <el-row :gutter="20">
                        <!-- *****************基础信息******************* -->
                        <el-col :span="12">
                            <div class="grid-content bg-purple left-container">
                                <div
                                    class="sub-header"
                                    style="margin-bottom: 10px;"
                                >
                                    基本信息
                                </div>

                                <div class="div-row">
                                    <el-form-item
                                        label="姓名"
                                        prop="name"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.basicData.name"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="年龄"
                                        prop="age"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.basicData.age"
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
                                            v-model.trim="form.basicData.sex"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="学历"
                                        prop="educational"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.educationalBackground.educational"
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
                                            v-model.trim="form.educationalBackground.speciality"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="生日"
                                        prop="birthday"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.basicData.birthday"
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
                                            v-model.trim="form.basicData.expected_money"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="移动电话"
                                        prop="phone"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.basicData.phone"
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
                                            v-model.trim="form.basicData.native_place"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="工作年限"
                                        prop="work_year"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.basicData.work_year"
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
                                            v-model.trim="form.basicData.english"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="期望工作地"
                                        prop="expected_address"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.educationalBackground.expected_address"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                </div>
                                <el-form-item
                                    label="状态"
                                    prop="status"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.basicData.status"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <!-- <el-form-item
                                        label="政治面貌"
                                        prop="politicalLandscape"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.politicalLandscape"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item> -->

                                <!-- <el-form-item
                                    label="住址"
                                    prop="address"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.address"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item> -->

                                <el-form-item
                                    label="电子邮箱"
                                    prop="email"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.basicData.email"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="毕业院校"
                                    prop="school"
                                >

                                    <el-input
                                        size="mini"
                                        v-model.trim="form.educationalBackground.school"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <!-- <el-form-item
                                    label="求职意向"
                                    prop="expected_industry"
                                >
                                    <el-input
                                        size="mini"
                                        v-model.trim="form.basicData.expected_industry"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item> -->

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
                                    <Math class="max apply"></Math>
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
                                    label="用户管理"
                                    name="first"
                                >
                                    <el-row
                                        align="top"
                                        class="row-height"
                                    >
                                        <el-col class="right-item">
                                            <div class="sub-header"> 教育背景</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.educational"
                                                resize="none"
                                            ></el-input>
                                        </el-col>
                                        <el-col class="right-item">
                                            <div class="sub-header"> 个人技能</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.skillExpertise"
                                                resize="none"
                                            ></el-input>
                                        </el-col>
                                        <el-col class="right-item">
                                            <div class="sub-header"> 自我评价</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.selfEvaluation"
                                                resize="none"
                                            ></el-input>
                                        </el-col>
                                        <!-- <el-col class="right-item">
                                            <div class="sub-header"> 培训经历</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.trainingexperience"
                                                resize="none"
                                            ></el-input>
                                        </el-col> -->
                                        <!-- <el-col class="right-item">
                                            <div class="sub-header"> 所修课程</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.coursescompleted"
                                                resize="none"
                                            ></el-input>
                                        </el-col> -->
                                        <el-col class="right-item">
                                            <div class="sub-header"> 所获证书</div>
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
                                            <div class="sub2-header"> 自定义01</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.custom01"
                                                resize="none"
                                            ></el-input>
                                        </el-col>
                                        <el-col class="right2-item">
                                            <div class="sub2-header"> 自定义02</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.custom02"
                                                resize="none"
                                            ></el-input>
                                        </el-col>
                                        <el-col class="right2-item">
                                            <div class="sub2-header"> 自定义03</div>
                                            <el-input
                                                type="textarea"
                                                v-model="form.custom03"
                                                resize="none"
                                            ></el-input>
                                        </el-col>
                                    </el-row>
                                </el-tab-pane>
                            </el-tabs>
                        </el-col>
                    </el-row>
                </el-form>
            </el-tab-pane>

            <!-- <el-tab-pane
                label="上传简历"
                name="third"
            >
                <div style="width:1068px; height:740px;display:flex;align-items:center;justify-content:center;" >
                    <el-upload
                        style=""
                        class="upload-demo"
                        drag
                        action="https://jsonplaceholder.typicode.com/posts/"
                        multiple
                    >
                        <i class="el-icon-upload"></i>
                        <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
                        <div
                            class="el-upload__tip"
                            slot="tip"
                        >只能上传jpg/png文件，且不超过500kb</div>
                    </el-upload>
                </div>
            </el-tab-pane>
 -->
        </el-tabs>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button
                @click="analyze"
                type="success"
            >自动识别</el-button>
            <el-button @click="closeDialog">取 消</el-button>
            <el-button
                type="primary"
                @click="addCommit"
            >确 定</el-button>
        </div>
    </el-dialog>
</template>

<script>
import AddDialogForm from "@view/base/AddDialogForm";
export default {
    name: "Add",
    mixins: [AddDialogForm],

    data() {
        return {
            activeName: "second",
            activeName2: "first",
            // dialog: "dialog",

            //要分析的内容
            analyzeContent: "",

            form: {
                basicData: {
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
                    email: ""
                },

                //教育背景
                educationalBackground: {
                    educational: "",
                    speciality: "",
                    expected_address: "",
                    school: ""
                },
                //  educationalBackground:"",

                nearest_unit: "",
                nearest_job: "",

                skillExpertise: "",
                selfEvaluation: "",
                certificate: "",

                workExperience: "",

                //工作技能
                personalskills: "",

                //自定义
                custom01: "",
                custom02: "",
                custom03: ""
            }
        };
    },

    methods: {
        //分析简历
        analyze() {
            let that = this;

            that.$api.resume
                .analyze({
                    content: that.analyzeContent
                })
                .then(res => {
                    console.log("***************");
                    // that.form.basicData = res.data.basicData;
                    // that.form.educationalBackground = res.data.educationalBackground;
                    // that.form.selfEvaluation = res.data.selfEvaluation;
                    that.form = res.data;
                    that.activeName = "second";
                    console.log(res);
                    console.log("***************");
                })
                .catch(res => {
                    that.$message.error("分析失败，请重试.");
                });
        },

        afterClose() {
            let that = this;
            that.activeName = "second";
            that.activeName2 = "first";
            that.analyzeContent = "";
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

.row3-height {
    height: 100%;

    .right2-item {
        height: 100%;
        position: relative;

        .el-textarea {
            position: absolute;
            top: 4px;
            bottom: 50px;

            textarea {
                height: 109.5%;
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

.analyze-content {
    height: 579px;
    width: 100%;
    box-sizing: border-box;

    textarea {
        height: 100%;
    }
}
</style>