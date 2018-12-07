<template>

    <div>

        <el-row class="table-container">
            <div class="action-bar">
                <el-button
                    type="primary"
                    @click="addDialog = true"
                >新增简历</el-button>

            </div>
            <div @click="handleClick()">
                <el-table
                    :data="tdata"
                    border
                    style="width: 100%"
                >

                    <el-table-column
                        fixed
                        prop="name"
                        label="姓名"
                        width="100"
                    >

                    </el-table-column>
                    <el-table-column
                        fixed
                        prop="sex"
                        label="性别"
                        width="50"
                    >
                    </el-table-column>
                    <el-table-column
                        fixed
                        prop="age"
                        label="年龄"
                        width="50"
                    >
                    </el-table-column>
                    <el-table-column
                        fixed
                        prop="phone"
                        label="手机"
                        width="150"
                    >
                    </el-table-column>
                    <el-table-column
                        fixed
                        prop="edu"
                        label="学历"
                        width="100"
                    >
                    </el-table-column>
                    <el-table-column
                        fixed
                        prop="workAge"
                        label="工作年限"
                        width="120"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="objective"
                        label="求职意向"
                        width="120"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="email"
                        label="电子邮箱"
                        width="200"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="expectPay"
                        label="期望薪水"
                        width="150"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="inPosition"
                        label="所处职位"
                        width="120"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="recentlyUnit"
                        label="最近单位"
                        width="200"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="recentPosition"
                        label="最近职位"
                        width="120"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="graduateSchool"
                        label="毕业院校"
                        width="300"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="professional"
                        label="专业"
                        width="150"
                    >
                    </el-table-column>

                    <el-table-column
                        prop="englishLevel"
                        label="英语等级"
                        width="120"
                    >
                    </el-table-column>
                    <el-table-column
                        prop="workingPlace"
                        label="工作地点"
                        width="200"
                    >
                    </el-table-column>
                    <el-table-column
                        fixed="right"
                        label="操作"
                        width="145"
                    >

                       <template slot-scope="scope">
                           <el-tooltip
                            effect="dark"
                            content="沟通管理"
                            placement="bottom"
                        >
                            <el-button
                                type="primary"
                                size="mini"
                                icon="fa fa-crosshairs"
                                circle
                                @click="showCommunicationDialog(scope.row.id)"
                            ></el-button>
                        </el-tooltip>
                        <el-tooltip
                            effect="dark"
                            content="修改密码"
                            placement="top"
                        >
                            <el-button
                                type="primary"
                                size="mini"
                                icon="el-icon-edit"
                                circle
                                @click="showEditDialog(scope.row.id)"
                            ></el-button>
                        </el-tooltip>
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
                                @click="del(scope.row.id, scope.$index)"
                            ></el-button>
                        </el-tooltip>
                        
                    </template>
                    </el-table-column>
                </el-table>
            </div>
           
        </el-row>

        <add
            :show.sync="addDialog"
            @add-item="addItem"
        ></add>

        <edit
            :show.sync="editDialog"
            :edit-item="currentEditItem"
            @edit-item="editItem"
        ></edit>

        <communication
            :show.sync="communicationDialog"
            :id="CommunicationID"
        >
        </communication>

    </div>
</template>


<script>
import Add from "./Add";
import Edit from "./Edit";
import Communication from "./Communication";
import TabelBase from "@view/base/TabelBase";

export default {
    mixins: [TabelBase],
    components: {
        Add,
        Edit,
        Communication
    },
    props: {},

    created() {},
    mounted() {},
    watch: {},
    computed: {},
    methods: {
        handleClick() {
            let tr = event.target;

            while (tr.nodeName != "TR") {
                tr = tr.parentNode;
            }
            let button = tr.lastElementChild.children[0].children[0];
            button.click();
        },
        showEditDialog(row) {
            console.log(row);
            console.log(TabelBase.methods.showEditDialog);
        },
        editItem() {
            console.log(1);
        },

        //沟通管理
        showCommunicationDialog(id){
            let that = this;
            that.CommunicationID = id;
            that.communicationDialog = true;
        }
    },

    data() {
        return {
            tdata: [
                {
                    id: 1,
                    name: "王小虎",
                    inPosition: "程序员",
                    sex: "男",
                    age: "11",
                    edu: "本科",
                    workAge: "三年",
                    objective: "Java工程师",
                    expectPay: "18k",
                    recentlyUnit: "中软",
                    recentPosition: "java工程师",
                    graduateSchool: "北京大学",
                    professional: "计算机",
                    phone: "13923819974",
                    email: "775803639@qq.com",
                    englishLevel: "四级",
                    workingPlace: "深圳"
                },
                {
                    id: 2,
                    name: "王小虎",
                    inPosition: "程序员",
                    sex: "男",
                    age: "11",
                    edu: "本科",
                    workAge: "三年",
                    objective: "Java工程师",
                    expectPay: "18k",
                    recentlyUnit: "中软",
                    recentPosition: "java工程师",
                    graduateSchool: "北京大学",
                    professional: "计算机",
                    phone: "13923819974",
                    email: "775803639@qq.com",
                    englishLevel: "四级",
                    workingPlace: "深圳"
                }
            ],

            //沟通情况
            communicationDialog: false,
            CommunicationID: 0
        };
    }
};
</script>
<style lang="less" scoped>
</style>