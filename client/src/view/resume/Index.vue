<template>
    <div>
        <el-row class="table-container resume">
            <div class="action-bar">
                <el-button
                    type="primary"
                    @click="addDialog = true"
                >新增简历</el-button>
                <el-button
                    type="primary"
                    @click="ImportExcelDialog = true"
                >批量导入</el-button>
            </div>

            <div
                class="search"
                ref="search"
            >
                <el-row :gutter="20">
                    <el-col :span="4">
                        <el-input
                            prop="name"
                            v-model="search.name"
                            size="small"
                            placeholder="姓名"
                        >
                        </el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-select
                            prop="sex"
                            v-model="search.sex"
                            size="small"
                            placeholder="性别"
                        >
                            <el-option
                                v-for="item in sex"
                                :key="item"
                                :label="item"
                                :value="item"
                            >
                            </el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="4">
                        <el-select
                            prop="educational"
                            v-model="search.educational"
                            size="small"
                            placeholder="学历"
                        >
                            <el-option
                                v-for="item in edu"
                                :key="item"
                                :label="item"
                                :value="item"
                            >
                            </el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="4">
                        <el-input
                            prop="phone"
                            size="small"
                            placeholder="移动电话"
                        >
                        </el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-input
                            prop="email"
                            size="small"
                            placeholder="电子邮箱"
                        ></el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-button
                            size="medium"
                            type="primary"
                        >搜索</el-button>
                    </el-col>

                </el-row>

                <transition
                    @before-enter="searchCollapseBeforeEnter"
                    @enter="searchCollapseEnter"
                    @after-enter="searchCollapseAfterEnter"
                    @before-leave="searchCollapseBeforeLeave"
                    @leave="searchCollapseLeave"
                    @after-leave="searchCollapseAfterLeave"
                >
                    <div
                        v-show="showOtherSearch"
                        class="other-search-detail"
                    >
                        <el-row :gutter="20">
                            <el-col :span="4">
                                <!-- <el-input
                                    prop="expected_money"
                                    placeholder="最低薪资要求"
                                    size="small"
                                >
                                </el-input> -->
                                <el-input-number
                                    placeholder="最低薪资要求"
                                    size="small"
                                    style="width: 100%;"
                                    v-model="num8"
                                    controls-position="right"
                                    :min="1"
                                ></el-input-number>
                            </el-col>
                            <el-col :span="4">
                                <!-- <el-input
                                    prop="expected_money"
                                    placeholder="最高薪资要求"
                                    size="small"
                                >
                                </el-input> -->
                                <el-input-number
                                    placeholder="最高薪资要求"
                                    size="small"
                                    style="width: 100%;"
                                    v-model="num8"
                                    controls-position="right"
                                    :min="1"
                                ></el-input-number>
                            </el-col>
                            <el-col :span="4">
                                <el-input-number
                                    placeholder="最小年龄要求"
                                    size="small"
                                    style="width: 100%;"
                                    v-model="num8"
                                    controls-position="right"
                                    :min="1"
                                    :max="99"
                                ></el-input-number>
                            </el-col>
                            <el-col :span="4">
                                <el-input-number
                                    placeholder="最大年龄要求"
                                    size="small"
                                    style="width: 100%;"
                                    v-model="num8"
                                    controls-position="right"
                                    :min="1"
                                    :max="99"
                                ></el-input-number>
                            </el-col>

                            <el-col :span="4">
                                <el-input-number
                                    placeholder="最小工作年限要求"
                                    size="small"
                                    style="width: 100%;"
                                    v-model="num8"
                                    controls-position="right"
                                    :min="1"
                                    :max="99"
                                ></el-input-number>
                            </el-col>
                            <el-col :span="4">
                                <el-input-number
                                    placeholder="最大工作年限要求"
                                    size="small"
                                    style="width: 100%;"
                                    v-model="num8"
                                    controls-position="right"
                                    :min="1"
                                    :max="99"
                                ></el-input-number>
                            </el-col>

                        </el-row>

                        <el-row :gutter="20">
                            <el-col :span="4">
                                <el-input
                                    prop="status"
                                    placeholder="期望从事岗位"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-input
                                    prop="status"
                                    placeholder="期望工作地点"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-input
                                    prop="status"
                                    placeholder="状态"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-input
                                    prop="school"
                                    placeholder="毕业院校"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-input
                                    prop="speciality"
                                    placeholder="专业"
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-input
                                    prop="english"
                                    placeholder="英语水平"
                                    size="small"
                                >
                                </el-input>
                            </el-col>

                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="24">
                                <el-input
                                    prop=""
                                    placeholder="其他条件..."
                                    size="small"
                                >
                                </el-input>
                            </el-col>
                        </el-row>
                    </div>
                </transition>

                <el-row
                    :gutter="20"
                >
                    <div class="other-search-container">
                        <a
                            @click="showOtherSearch = !showOtherSearch"
                            href="javascript:void(0)"
                            class="other-search"
                        >
                            <i
                                class="fa fa-angle-double-down"
                                :class="{ 'up-icon' : showOtherSearch}"
                            ></i>
                            搜索条件
                        </a>
                    </div>
                </el-row>
            </div>

            <el-table
                :data="tdata"
                stripe
                border
                style="width: 100%"
                :height="tabelHeight"
                @row-click="showViewDialog"
                class="table"
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
                <!-- <el-table-column
                    prop="inPosition"
                    label="所处职位"
                    width="120"
                >
                </el-table-column> -->
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

        <importExcel
            :show.sync="ImportExcelDialog"
            @refresh-data="getData"
        >
        </importExcel>

    </div>
</template>


<script>
import Add from "./Add";
import Edit from "./Edit";
import ViewResume from "./ViewResume";
import Communication from "./Communication";
import TabelBase from "@view/base/TabelBase";
import UploadFile from "./UploadFile";
import ImportExcel from "./ImportExcel";
import { addClass, removeClass } from "@common/util";

export default {
    mixins: [TabelBase],
    components: {
        Add,
        Edit,
        ViewResume,
        Communication,
        UploadFile,
        ImportExcel
    },
    methods: {
        //点击搜索条件缩放
        // searchClick(e) {
        //    this.showOtherSearch = !this.showOtherSearch
        //    if(e.target.className == 'fa status-icon el-icon-caret-bottom'){
        //        e.target.className = 'fa status-icon el-icon-caret-top'
        //    }else{
        //        e.target.className = 'fa status-icon el-icon-caret-bottom'
        //    }
        // },

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

        //批量导入弹出窗
        showImportExcel() {
            let that = this;
            that.ImportExcelDialog = true;
        },

        // showEditDialog(id) {
        //     console.log(id);
        //     let that = this;
        //     that.editDialog = true;
        // },

        //沟通管理
        showCommunicationDialog(id) {
            let that = this;
            that.communicationID = id;
            that.communicationDialog = true;
        },

        //动画效果-----------------------

        //搜索框进入之前
        searchCollapseBeforeEnter(el) {
            addClass(el, "search-collapse");
            el.style.height = 0;
        },

        //搜索框进入
        searchCollapseEnter(el) {
            if (el.scrollHeight !== 0) {
                el.style.height = el.scrollHeight + "px";
            }

            el.style.overflow = "hidden";
        },

        //搜索框进入之后
        searchCollapseAfterEnter(el) {
            removeClass(el, "search-collapse");
        },

        //搜索框退出之前
        searchCollapseBeforeLeave(el) {
            el.style.height = el.scrollHeight + "px";
            el.style.overflow = "hidden";
        },

        //搜索框退出
        searchCollapseLeave(el) {
            if (el.scrollHeight !== 0) {
                // for safari: add class after set height, or it will jump to zero height suddenly, weired
                addClass(el, "search-collapse");
                el.style.height = 0;
            }
        },

        //搜索框退出之后
        searchCollapseAfterLeave(el) {
            removeClass(el, "search-collapse");
        }

        //动画效果-----------------------
    },

    data() {
        return {
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: "resume",
            sex: ["男", "女"],
            edu: ["初中", "高中", "大专", "本科", "硕士", "博士", "研究生"],

            search: {
                name: "",
                sex: "",
                educational: "",
                phone: "",
                email: "",
                expected_money: "",
                age: "",
                speciality: "",
                birthday: "",
                native_place: "",
                work_year: "",
                status: "",
                school: "",
                english: "",
                expected_address: "",
                nearest_unit: "",
                nearest_job: ""
            },

            // search.name

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

            //批量导入
            ImportExcelDialog: false,
            ImportExcelID: 0,

            //查看
            viewDialog: false,
            viewID: 0,
            //dropdown flag
            // flag: true

            showOtherSearch: false
        };
    }
};
</script>
<style lang="less" scoped>
.resume {
    .table {
        tbody {
            tr {
                cursor: pointer;
            }
        }
    }

    .search {
        .el-row {
            margin-bottom: 10px;
            &:last-child {
                margin-bottom: 5px;
            }
        }

        .other-search-detail {
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .other-search-container {
            margin-bottom: 10px;
            font-size: 15px;
            text-align: center;

            .other-search {
                text-decoration: none;
                color: #03b9b9;
                transition: all 0.2s;

                &:hover {
                    color: #06e2e2;
                }

                i {
                    transition: transform 0.2s;

                    &.up-icon {
                        transform: rotate(180deg);
                    }
                }
            }
        }
    }

    .search-collapse {
        transition: all 0.3s ease-in-out;
    }
}
</style>