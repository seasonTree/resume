<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">
                <div class="search-item">
                    <el-button
                        type="primary"
                        @click="addDialog = true"
                        :disabled="!$check_pm('resume_add')"
                    >新增简历</el-button>
                    <el-button
                        type="primary"
                        @click="ImportExcelDialog = true"
                        :disabled="!$check_pm('resume_batch_add')"
                    >批量导入</el-button>
                </div>
            </div>

            <div
                class="search"
                ref="search"
            >
                <el-row :gutter="20">
                    <el-col :span="4">
                        <el-input
                            v-model="search.name"
                            size="small"
                            placeholder="姓名"
                            clearable
                            @keyup.native.enter="getData(true)"
                        ></el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-select
                            class="width100"
                            v-model="search.sex"
                            size="small"
                            placeholder="性别"
                            clearable
                        >
                            <el-option
                                v-for="item in sex"
                                :key="item"
                                :label="item"
                                :value="item"
                            ></el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="4">
                        <el-select
                            class="width100"
                            v-model="search.educational"
                            size="small"
                            placeholder="学历"
                            clearable
                            filterable
                        >
                            <el-option
                                v-for="item in edu"
                                :key="item"
                                :label="item"
                                :value="item"
                            ></el-option>
                        </el-select>
                    </el-col>
                    <el-col :span="4">
                        <el-input
                            v-model="search.phone"
                            size="small"
                            placeholder="移动电话"
                            @keyup.native.enter="getData(true)"
                        ></el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-input
                            v-model="search.email"
                            size="small"
                            placeholder="电子邮箱"
                            @keyup.native.enter="getData(true)"
                        ></el-input>
                    </el-col>
                    <el-col :span="4">
                        <el-button
                            size="small"
                            type="primary"
                            @click="getData(true)"
                            :loading="tableLoading"
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
                            <el-col :span="8">
                                <el-input
                                    v-model="search.ct_user"
                                    placeholder="招聘负责人"
                                    size="small"
                                    @keyup.native.enter="getData(true)"
                                ></el-input>
                            </el-col>
                            <el-col :span="4">
                                <el-date-picker
                                    v-model="search.ct_time"
                                    size="small"
                                    type="date"
                                    placeholder="选择日期"
                                    class="width100"
                                    format="yyyy-MM-dd"
                                    value-format="yyyy-MM-dd"
                                    @change="getData(true)"
                                >
                                </el-date-picker>

                                <!-- <el-input
                                    v-model="search.ct_time"
                                    placeholder="日期"
                                    size="small"
                                    @keyup.native.enter="getData(true)"
                                ></el-input> -->
                            </el-col>
                            <el-col :span="4">
                                <el-input-number
                                    clearable
                                    placeholder="最小工作年限要求"
                                    size="small"
                                    class="width100"
                                    v-model="search.work_year_min"
                                    controls-position="right"
                                    :min="0"
                                    :max="99"
                                    @keyup.native.enter="getData(true)"
                                ></el-input-number>
                            </el-col>
                            <el-col :span="4">
                                <el-input-number
                                    placeholder="最大工作年限要求"
                                    size="small"
                                    class="width100"
                                    v-model="search.work_year_max"
                                    controls-position="right"
                                    :min="0"
                                    :max="99"
                                    @keyup.native.enter="getData(true)"
                                ></el-input-number>
                            </el-col>

                            <el-col :span="4">
                                <el-input
                                    v-model="search.expected_job"
                                    placeholder="岗位"
                                    size="small"
                                    @keyup.native.enter="getData(true)"
                                ></el-input>
                            </el-col>
                        </el-row>
                        <el-row :gutter="20">
                            <el-col :span="24">
                                <el-input
                                    v-model="search.other"
                                    prop
                                    placeholder="其他条件..."
                                    size="small"
                                    @keyup.native.enter="getData(true)"
                                ></el-input>
                            </el-col>
                        </el-row>
                    </div>
                </transition>

                <el-row :gutter="20">
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
                class="table width100"
                :height="tabelHeight"
                @row-click="showViewDialog"
                v-loading="tableLoading"
            >
                <el-table-column
                    fixed
                    prop="ct_user"
                    label="招聘负责人"
                    width="100"
                    align="center"
                ></el-table-column>
                <el-table-column
                    fixed
                    prop="ct_time"
                    label="日期"
                    width="100"
                    align="center"
                    :formatter="formatterDate"
                ></el-table-column>
                <el-table-column
                    fixed
                    prop="expected_job"
                    label="岗位"
                    width="80"
                    align="center"
                >
                    <template slot-scope="scope">
                        <el-tooltip
                            effect="dark"
                            :content="scope.row.expected_job"
                            placement="top"
                        >
                            <div class="cell">{{scope.row.expected_job}}</div>
                        </el-tooltip>
                    </template>
                </el-table-column>
                <el-table-column
                    fixed
                    prop="name"
                    label="姓名"
                    width="80"
                    align="center"
                ></el-table-column>
                <el-table-column
                    fixed
                    prop="phone"
                    label="手机"
                    width="100"
                    align="center"
                ></el-table-column>
                <el-table-column
                    fixed
                    prop="email"
                    label="电子邮箱"
                    width="180"
                    align="center"
                >
                    <template slot-scope="scope">

                        <el-tooltip
                            effect="dark"
                            :content="scope.row.email"
                            placement="top"
                        >
                            <div class="cell">{{scope.row.email}}</div>
                        </el-tooltip>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="school"
                    label="毕业院校"
                    width="200"
                    align="center"
                ></el-table-column>
                <el-table-column
                    prop="educational"
                    label="学历"
                    width="80"
                    align="center"
                ></el-table-column>
                <el-table-column
                    prop="graduation_time"
                    label="毕业年份"
                    width="100"
                    align="center"
                ></el-table-column>
                <el-table-column
                    prop="work_year"
                    label="工作年限"
                    width="80"
                    align="center"
                ></el-table-column>
                <el-table-column
                    prop="source"
                    label="简历来源"
                    width="80"
                    align="center"
                ></el-table-column>
                <el-table-column
                    prop="company_type"
                    label="同和/大展"
                    width="100"
                    align="center"
                ></el-table-column>
                <el-table-column
                    fixed="right"
                    label="操作"
                    width="220"
                >
                    <template slot-scope="scope">
                        <el-tooltip
                            effect="dark"
                            content="导出简历"
                            placement="top"
                        >
                            <el-button
                                type="success"
                                size="mini"
                                icon="fa fa-file-export"
                                circle
                                @click.stop="showExportResume(scope.row.id)"
                                :disabled="!$check_pm('resume_export')"
                            ></el-button>
                        </el-tooltip>
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
                                :disabled="!$check_pm('resume_file_list')"
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
                                :disabled="!$check_pm('resume_commu_list')"
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
                                @click.stop="showEditDialog(scope.row)"
                                :disabled="!$check_pm('resume_edit')"
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
                                @click.stop="del(scope.row, scope.$index)"
                                :disabled="!$check_pm('resume_del')"
                            ></el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>

        <el-row
            class="pager"
            type="flex"
            justify="end"
        >
            <el-pagination
                @current-change="changePage"
                @size-change="pageSizeChange"
                background
                layout="total, sizes, prev, pager, next, jumper"
                :page-sizes="[10, 20, 50, 100]"
                :page-size="pager.size"
                :total="pager.total"
                :current-page="pager.current"
            ></el-pagination>
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
        ></communication>

        <upload-file
            :show.sync="uploadFileDialog"
            :resume_id="uploadFileID"
        ></upload-file>

        <import-excel
            :show.sync="ImportExcelDialog"
            @refresh-data="getData"
        ></import-excel>
        <export-resume
            :show.sync="exportResumeDialog"
            :resume_id="resumeID"
        ></export-resume>
    </div>
</template>


<script>
import Add from "./Add";
import Edit from "./Edit";
import ViewResume from "./ViewResume";
import Communication from "./Communication";
import TableBase from "@view/base/TableBase";
import UploadFile from "./UploadFile";
import ImportExcel from "./ImportExcel";
import ExportResume from "./ExportResume";
import { addClass, removeClass } from "@common/util";
import { formatDate } from "@common/util";

export default {
    mixins: [TableBase],
    components: {
        Add,
        Edit,
        ViewResume,
        Communication,
        UploadFile,
        ImportExcel,
        ExportResume
    },

    methods: {
        //格式化yyyy-MM-dd
        formatterDate(row, column, cellValue, index) {
            return formatDate(cellValue, "yyyy-MM-dd");
        },

        //点击行查看简历
        showViewDialog(row) {
            let that = this;

            //检查是否有查看的权限
            if (!that.$check_pm("resume_get_row")) {
                return;
            }

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

        //沟通管理
        showCommunicationDialog(id) {
            let that = this;
            that.communicationID = id;
            that.communicationDialog = true;
        },

        //导出简历
        showExportResume(id) {
            let that = this;

            that.resumeID = id;
            that.exportResumeDialog = true;
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

            search: {
                name: "",
                sex: "",
                educational: "",
                phone: "",
                email: "",
                expected_money_st: undefined,
                expected_money_ed: undefined,
                age_min: undefined,
                age_max: undefined,
                work_year_min: undefined,
                work_year_max: undefined,
                expected_job: "",
                expected_address: "",
                status: "",
                school: "",
                speciality: "",
                english: "",
                other: ""
            },

            // search.name

            // tdata: [
            //     {
            //         name: "丁锋",
            //         phone: "13560727727",
            //         sex: "男",
            //         age: 28,
            //         work_year: 6,
            //         email: "sheissosex@163.com",
            //         expected_money_start: 15000,
            //         expected_money_end: 19999,
            //         expected_money: "15000-19999元/月",
            //         nearest_unit:
            //             "深圳飞钛科技和东莞小黄狗环保企业(团贷网旗下公司",
            //         nearest_job: "Java开发工程师",
            //         english: "",
            //         expected_job: "Java",
            //         expected_address: "深圳",
            //         school: "广东石油化工学院",
            //         educational: "本科",
            //         speciality: "计算机科学与技术",
            //         mfy_time: "2019-02-26 09:54:34",
            //         id: 464,
            //         ct_time: '2019-02-10'
            //     },
            //     {
            //         name: "阳鹏",
            //         phone: "18824278483",
            //         sex: "男",
            //         age: 26,
            //         work_year: 5,
            //         email: "958576496@qq.com",
            //         expected_money_start: 17000,
            //         expected_money_end: 17000,
            //         expected_money: "17000",
            //         nearest_unit: "",
            //         nearest_job: "",
            //         english: "",
            //         expected_job: "Java",
            //         expected_address: "深圳",
            //         school: "湖南机电学院",
            //         educational: "大专",
            //         speciality: "计算机应用专科",
            //         mfy_time: "2019-02-26 09:52:44",
            //         id: 463,
            //         ct_time: '2019-02-10'
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

            showOtherSearch: false,

            //展开导出简历对话框
            exportResumeDialog: false,
            resumeID: 0
        };
    }
};
</script>
<style lang="less" scoped>
.table {
    tbody {
        tr {
            cursor: pointer;

            td {
                .cell {
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
            }
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
</style>