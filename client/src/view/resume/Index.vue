<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">
                <el-button
                    type="primary"
                    @click="addDialog = true"
                >新增简历</el-button>
            </div>

            <div class="search">
                <form>
                    <el-row :gutter="20">
                        <el-col :span="4">
                            <el-input
                                placeholder="姓名"
                                size="small"
                            >
                            </el-input>
                        </el-col>
                        <el-col :span="4">
                            <el-select
                                size="small"
                                v-model="value"
                                placeholder="性别"
                            >
                                <el-option
                                    v-for="item in options"
                                    :key="item.value"
                                    :label="item.label"
                                    :value="item.value"
                                >
                                </el-option>
                            </el-select>
                        </el-col>
                        <el-col :span="4">
                            <el-input
                                placeholder="姓名"
                                size="small"
                            >
                            </el-input>
                        </el-col>
                        <el-col :span="4">
                            <el-input
                                placeholder="姓名"
                                size="small"
                            >
                            </el-input>
                        </el-col>
                        <el-col :span="4">
                            <el-input
                                placeholder="姓名"
                                size="small"
                            ></el-input>
                        </el-col>
                        <el-col :span="3.5">
                            <el-button
                                type="primary"
                                size="mini"
                            >搜索</el-button>
                        </el-col>
                    </el-row>

                    <div
                        class="dropdown"
                        id="dropdown"
                    >
                        <el-row :gutter="20">
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="姓名"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="性别"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="姓名"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="性别"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="姓名"
                                    size="small"
                                >
                                </el-input>
                            </el-col>

                            <el-col :span="4.5">
                                <el-input size="small"></el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input size="small"></el-input>
                            </el-col>
                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="姓名"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="性别"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="姓名"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="性别"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input
                                    placeholder="姓名"
                                    size="small"
                                >
                                </el-input>
                            </el-col>

                            <el-col :span="4.5">
                                <el-input size="small"></el-input>
                            </el-col>
                            <el-col :span="4.5">
                                <el-input size="small"></el-input>
                            </el-col>
                        </el-row>
                    </div>

                    <el-row
                        :gutter="20"
                        style="background:#eee;margin:10px 1px;text-align:center;height:20px;display:block"
                    >
                        <div
                            width="100%"
                            @click="dropDown"
                             class="fa status-icon el-icon-caret-bottom"
                        >收起搜索条件</div>
                    </el-row>

                </form>
            </div>

            <el-table
                :data="tdata"
                stripe
                border
                style="width: 100%"
                :height="tabelHeight"
                @row-click="showViewDialog"
                class="resume-table"
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
                    prop="educational"
                    label="学历"
                    width="100"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="work_year"
                    label="工作年限"
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
                    prop="expected_money"
                    label="期望薪资"
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
                    prop="nearest_unit"
                    label="最近单位"
                    width="220"
                >
                </el-table-column>
                <el-table-column
                    prop="nearest_job"
                    label="最近职位"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="school"
                    label="毕业院校"
                    width="300"
                >
                </el-table-column>
                <el-table-column
                    prop="speciality"
                    label="专业"
                    width="150"
                >
                </el-table-column>

                <el-table-column
                    prop="english"
                    label="英语等级"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="expected_address"
                    label="期望工作地"
                    width="200"
                >
                </el-table-column>
                <el-table-column
                    fixed="right"
                    label="操作"
                    width="180"
                >

                    <template slot-scope="scope">
                        <el-tooltip
                            effect="dark"
                            content="添加附件"
                            placement="top"
                        >
                            <el-button
                                type="info"
                                size="mini"
                                icon="fa fa-folder-open"
                                circle
                                @click.stop="showUploadFile(scope.row.id)"
                            ></el-button>
                        </el-tooltip>
                        <el-tooltip
                            effect="dark"
                            content="沟通管理"
                            placement="bottom"
                        >
                            <el-button
                                type="success"
                                size="mini"
                                icon="fa fa-user-astronaut"
                                circle
                                @click.stop="showCommunicationDialog(scope.row.id)"
                            ></el-button>
                        </el-tooltip>
                        <el-tooltip
                            effect="dark"
                            content="修改"
                            placement="top"
                        >
                            <el-button
                                type="primary"
                                size="mini"
                                icon="el-icon-edit"
                                circle
                                @click.stop="showEditDialog(scope.row.id)"
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
                                @click.stop="del(scope.row.id, scope.$index)"
                            ></el-button>
                        </el-tooltip>

                    </template>
                </el-table-column>
            </el-table>
            <!-- </div> -->

        </el-row>

        <el-row class="pager">
            <el-pagination
                @current-change="changePage"
                background
                layout="prev, pager, next"
                :page-size="pager.size"
                :total="pager.total"
                :current-page="pager.current"
            >
            </el-pagination>
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

        <view-resume
            :show.sync="viewDialog"
            :id="viewID"
        ></view-resume>

        <communication
            :show.sync="communicationDialog"
            :resume_id="communicationID"
        >
        </communication>

        <upload-file
            :show.sync="uploadFileDialog"
            :resume_id="uploadFileID"
        >
        </upload-file>

    </div>
</template>


<script>
import Add from "./Add";
import Edit from "./Edit";
import ViewResume from "./ViewResume";
import Communication from "./Communication";
import TabelBase from "@view/base/TabelBase";
import UploadFile from "./UploadFile";

export default {
    mixins: [TabelBase],
    components: {
        Add,
        Edit,
        ViewResume,
        Communication,
        UploadFile
    },
    methods: {
        //点击搜索条件缩放
        dropDown() {
            let dropdown = document.getElementById("dropdown");
            if (this.flag) {
                dropdown.style.display = "none";
                this.flag = false;
            } else {
                dropdown.style.display = "block";
                this.flag = true;
            }
        },
        //点击行查看简历
        showViewDialog(row) {
            let that = this;
            that.viewID = row.id;
            that.viewDialog = true;
        },

        //上传弹出窗
        showUploadFile(id) {
            let that = this;
            that.uploadFileID = id;
            that.uploadFileDialog = true;
        },

        // showEditDialog(id) {
        //     console.log(id);
        //     let that = this;
        //     that.editDialog = true;
        // },

        //沟通管理
        showCommunicationDialog(id) {
            let that = this;
            that.CommunicationID = id;
            that.communicationDialog = true;
        }
    },

    data() {
        return {
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: "resume",
            options: [
                {
                    value: "选项1",
                    label: "男"
                },
                {
                    value: "选项2",
                    label: "女"
                }
            ],
            value: "",

            // tdata: [
            //     {
            //         id: 1,
            //         name: "王小虎",
            //         inPosition: "程序员",
            //         sex: "男",
            //         age: "11",
            //         edu: "本科",
            //         workAge: "三年",
            //         objective: "Java工程师",
            //         expectPay: "18k",
            //         recentlyUnit: "中软",
            //         recentPosition: "java工程师",
            //         graduateSchool: "北京大学",
            //         professional: "计算机",
            //         phone: "13912349974",
            //         email: "723403639@qq.com",
            //         englishLevel: "四级",
            //         workingPlace: "深圳"
            //     },
            //     {
            //         id: 2,
            //         name: "王小虎",
            //         inPosition: "程序员",
            //         sex: "男",
            //         age: "11",
            //         edu: "本科",
            //         workAge: "三年",
            //         objective: "Java工程师",
            //         expectPay: "18k",
            //         recentlyUnit: "中软",
            //         recentPosition: "java工程师",
            //         graduateSchool: "北京大学",
            //         professional: "计算机",
            //         phone: "13912349974",
            //         email: "723403639@qq.com",
            //         englishLevel: "四级",
            //         workingPlace: "深圳"
            //     }
            // ],

            //沟通情况
            communicationDialog: false,
            communicationID: 0,

            //上传文件
            uploadFileDialog: false,
            uploadFileID: 0,

            //查看
            viewDialog: false,
            viewID: 0,
            //dropdown flag
            flag: true
        };
    }
};
</script>
<style lang="less" scoped>
.resume-table {
    tbody {
        tr {
            cursor: pointer;
        }
    }
}

.margin {
    margin: 5px;
}
.search {
    .el-row {
        margin-bottom: 10px;
        &:last-child {
            margin-bottom: 5px;
        }
    }
}
</style>