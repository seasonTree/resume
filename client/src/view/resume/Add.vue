<template>
    <el-dialog
        title="添加简历"
        :visible.sync="show"
        :show-close="false"
        class="custom-dialog"
        :close-on-click-modal="false"
        :class="dialog"
    >

        <el-tabs
            v-model="activeName"
            @tab-click="handleClick"
        >
            <el-tab-pane
                label="粘贴简历"
                name="first"
            >
                <el-form
                    :model="form"
                    :rules="formRules"
                    label-width="100px"
                    ref="form"
                >
                    <textarea
                        style="width:1400px; height:800px; resize:none; font-size: 25px;"
                        name=""
                        id=""
                    ></textarea>

                </el-form>
            </el-tab-pane>
            <el-tab-pane
                label="手工输入"
                name="second"
            >
                <el-form
                    :model="form"
                    :rules="formRules"
                    label-width="80px"
                    ref="form"
                >
                    <el-row :gutter="20">
                        <el-col :span="12" >
                            <div
                                class="grid-content bg-purple"
                                :class="elRoW"
                            >

                                <!-- /* 888888888888888888888888888888888888888888888888888888888 */ -->
                                <div style="text-align:center;background:#ccc">
                                    基本信息
                                </div>
                                <el-form-item>

                                </el-form-item>

                                <div class="divRow">
                                    <el-form-item
                                        label="姓名"
                                        prop="uname"
                                    >
                                        <el-input
                                            v-model.trim="form.uname"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="年龄"
                                        prop="age"
                                    >
                                        <el-input
                                            v-model.trim="form.age"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                </div>
                                <div class="divRow">
                                    <el-form-item
                                        label="性别"
                                        prop="sex"
                                    >
                                        <el-input
                                            v-model.trim="form.sex"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>

                                    <el-form-item
                                        label="学历"
                                        prop="formalSchooling"
                                    >
                                        <el-input
                                            v-model.trim="form.formalSchooling"
                                            autocomplete="off"
                                        ></el-input>
                                    </el-form-item>
                                </div>
                                 <div class="divRow">
                                <el-form-item
                                    label="工作年限"
                                    prop="workingYear"
                                >
                                    <el-input
                                        v-model.trim="form.workingYear"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                                </div>

                                <el-form-item
                                    label="移动电话"
                                    prop="phone"
                                >
                                    <el-input
                                        v-model.trim="form.phone"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="身高"
                                    prop="height"
                                >
                                    <el-input
                                        v-model.trim="form.height"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="民族"
                                    prop="national"
                                >
                                    <el-input
                                        v-model.trim="form.national"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="出生年月"
                                    prop="dateOfBirth"
                                >
                                    <el-input
                                        v-model.trim="form.dateOfBirth"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="政治面貌"
                                    prop="politicalLandscape"
                                >
                                    <el-input
                                        v-model.trim="form.politicalLandscape"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="籍贯"
                                    prop="nativePlace"
                                >
                                    <el-input
                                        v-model.trim="form.nativePlace"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="住址"
                                    prop="address"
                                >
                                    <el-input
                                        v-model.trim="form.address"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="邮编"
                                    prop="zipCode"
                                >
                                    <el-input
                                        v-model.trim="form.zipCode"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="电子邮箱"
                                    prop="email"
                                >
                                    <el-input
                                        v-model.trim="form.email"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="毕业院校"
                                    prop="graduateSchool"
                                >
                                    <el-input
                                        v-model.trim="form.graduateSchool"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="专业"
                                    prop="professional"
                                >
                                    <el-input
                                        v-model.trim="form.professional"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="英语水平"
                                    prop="englishLevel"
                                >
                                    <el-input
                                        v-model.trim="form.englishLevel"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>

                                <el-form-item
                                    label="计算机水平"
                                    prop="computerSkills"
                                >
                                    <el-input
                                        v-model.trim="form.computerSkills"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                                
                                <el-form-item
                                    label="计算机水平"
                                    prop="computerSkills"
                                >
                                    <el-input
                                        v-model.trim="form.computerSkills"
                                        autocomplete="off"
                                    ></el-input>
                                </el-form-item>
                            </div>
                            
                            <!-- /* 888888888888888888888888888888888888888888888888888888888 */ -->
                        </el-col>

                        <el-col :span="12">
                            <el-tabs
                                v-model="activeName2"
                                @tab-click="handleClick"

                            >
                                <el-tab-pane
                                    label="用户管理"
                                    name="first"
                                    
                                >
                               
    
    <el-container style="margin-left:-1px">
        <el-main >
                <div style="text-align: center; font-size: 12px;height:20px;background:#ccc;"> 教育背景</div>
            <el-input type="textarea" v-model="form.desc1" rows="10%" resize=none></el-input> 
        
            </el-main>
    </el-container>
     <el-container style="margin-left:-1px">
        <el-main >
                <div style="text-align: center; font-size: 12px;height:20px;background:#ccc;"> 培训经历</div>
            <el-input type="textarea" v-model="form.desc2" rows="10%" resize=none></el-input> 
        
            </el-main>
    </el-container>
    <el-container style="margin-left:-1px">
        <el-main >
                <div style="text-align: center; font-size: 12px;height:20px;background:#ccc;"> 所修课程</div>
            <el-input type="textarea" v-model="form.desc3" rows="8%" resize=none></el-input> 
        
            </el-main>
    </el-container>
    <el-container style="margin-left:-1px">
        <el-main >
                <div style="text-align: center; font-size: 12px;height:20px;background:#ccc;"> 所获证书</div>
            <el-input type="textarea" v-model="form.desc4" rows="5%" resize=none></el-input> 
        
            </el-main>
    </el-container>
    

                     
                                </el-tab-pane>
                                <el-tab-pane
                                    label="配置管理"
                                    name="second"
                                >配置管理</el-tab-pane>
                                <el-tab-pane
                                    label="角色管理"
                                    name="third"
                                >角色管理</el-tab-pane>
                            </el-tabs>
                        </el-col>

                    </el-row>

                </el-form>
            </el-tab-pane>

        </el-tabs>

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
import AddDialogForm from "@view/base/AddDialogForm";
export default {
    name: "Add",
    mixins: [AddDialogForm],

    data() {
        return {
            activeName: "second",
            activeName2: "first",
            dialog: "dialog",
            elRoW: "elRoW",
            //     apiType: "user",

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
                computerSkills: ""
            }
        };
    },

    methods: {
        handleClick(tab, event) {
            console.log(tab, event);
        }
    }
};
</script>

<style>
* {
    padding: 0;
    margin: 0;
}
.el-row {
    margin-bottom: 20px;
    &:last-child {
        margin-bottom: 0;
    }
}

.el-col {
    border-radius: 4px;
}
.bg-purple-dark {
    background: #99a9bf;
}
.bg-purple {
    background: #fff;
    border: 1px solid #ccc;
}
.bg-purple-light {
    background: #e5e9f2;
}
.grid-content {
    border-radius: 4px;
    min-height: 36px;
}
.row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
}
.dialog {
    width: 130%;
    transform: translateX(-130px);
}
.elRoW {
    padding: 10px;
}
.divRow {
    display: flex;
}
</style>