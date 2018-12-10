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
                    name="first">
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
                    name="second">
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
                                            prop="uname"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.uname"
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

                                        <el-form-item
                                            label="学历"
                                            prop="formalSchooling"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.formalSchooling"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>
                                    </div>
                                    <div class="div-row">
                                        <el-form-item
                                            label="身高"
                                            prop="height"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.height"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>

                                        <el-form-item
                                            label="民族"
                                            prop="national"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.national"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>
                                    </div>

                                    <div class="div-row">
                                        <el-form-item
                                            label="籍贯"
                                            prop="nativePlace"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.nativePlace"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>

                                        <el-form-item
                                            label="邮编"
                                            prop="zipCode"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.zipCode"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>
                                    </div>

                                    <div class="div-row">
                                        <el-form-item
                                            label="专业"
                                            prop="professional"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.professional"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>

                                        <el-form-item
                                            label="期望薪水"
                                            prop="ExpectToPay"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.computerSkills"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>
                                    </div>

                                    <div class="div-row">
                                        <el-form-item
                                            label="工作年限"
                                            prop="workingYear"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.workingYear"
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
                                            label="出生年月"
                                            prop="dateOfBirth"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.dateOfBirth"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>

                                        <el-form-item
                                            label="政治面貌"
                                            prop="politicalLandscape"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.politicalLandscape"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>
                                    </div>

                                    <div class="div-row">
                                        <el-form-item
                                            label="英语水平"
                                            prop="englishLevel"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.englishLevel"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>

                                        <el-form-item
                                            label="计算机水平"
                                            prop="computerSkills"
                                        >
                                            <el-input
                                                size="mini"
                                                v-model.trim="form.computerSkills"
                                                autocomplete="off"
                                            ></el-input>
                                        </el-form-item>
                                    </div>

                                    <el-form-item
                                        label="住址"
                                        prop="address"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.address"
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
                                        prop="graduateSchool"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.graduateSchool"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="求职意向"
                                        prop="computerSkills"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.computerSkills"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="工作地点"
                                        prop="workingPlace"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.workingPlace"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="最近单位"
                                        prop="recentlyTheUnit"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.computerSkills"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="最近职位"
                                        prop="recentPosition"
                                    >
                                        <el-input
                                            size="mini"
                                            v-model.trim="form.recentPosition"
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
                                <el-tabs
                                    v-model="activeName2"
                                >
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
                                                    v-model="form.desc1"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right-item">
                                                <div class="sub-header"> 所修课程</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc3"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right-item">
                                                <div class="sub-header"> 所获证书</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc4"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right-item">
                                                <div class="sub-header"> 所获证书</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc4"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                        </el-row>
                                    </el-tab-pane>

                                    <el-tab-pane
                                        label="配置管理"
                                        name="second"
                                    >
                                        <el-row
                                            align="top"
                                            class="row-height"
                                        >
                                            <el-col class="right-item">
                                                <div class="sub-header"> 工作经历</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc1"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right-item">
                                                <div class="sub-header"> 项目经验</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc3"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right-item">
                                                <div class="sub-header"> 个人技能</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc4"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right-item">
                                                <div class="sub-header"> 自我评价</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc4"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>

                                        </el-row>
                                    </el-tab-pane>
                                    <el-tab-pane
                                        label="自定义信息"
                                        name="third"
                                    >
                                        <el-row
                                            align="top"
                                            class="row2-height"
                                        >
                                            <el-col class="right2-item">
                                                <div class="sub2-header"> 自定义01</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc1"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right2-item">
                                                <div class="sub2-header"> 自定义02</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc3"
                                                    resize="none"
                                                ></el-input>
                                            </el-col>
                                            <el-col class="right2-item">
                                                <div class="sub2-header"> 自定义03</div>
                                                <el-input
                                                    type="textarea"
                                                    v-model="form.desc4"
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
            <el-button @click="analyze">自动识别</el-button>
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
                uname: "",
                age: "",
                sex: "",
                formalSchooling: "",
                workingYear: "",
                phone: "",
                height: "",
                national: "",
                dateOfBirth: "",
                politicalLandscape: "",
                nativePlace: "",
                address: "",
                zipCode: "",
                email: "",
                graduateSchool: "",
                professional: "",
                englishLevel: "",
                computerSkills: "",
                computerSkills: "",
                workingPlace: "",
                ExpectToPay: "",
                recentlyTheUnit: "",
                recentPosition: ""
            }
        };
    },

    methods: {
        //分析简历
        analyze() {}
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

.analyze-content {
    height: 740px;
    width: 100%;
    box-sizing: border-box;

    textarea {
        height: 100%;
    }
}
</style>