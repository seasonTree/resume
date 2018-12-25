<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">
                <div class="search-item">
                    <el-date-picker
                        v-model="search.dateRange"
                        type="daterange"
                        align="right"
                        unlink-panels
                        range-separator="至"
                        start-placeholder="开始日期"
                        end-placeholder="结束日期"
                        :picker-options="pickerOptions"
                        forma="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        :clearable="false"
                    >
                    </el-date-picker>
                </div>
                <div class="search-item">
                    <el-select
                        v-model="search.type"
                        placeholder="请选择统计类型"
                        class="report-person-select-width"
                    >
                        <el-option
                            label="招聘负责人统计"
                            :value="0"
                        ></el-option>
                        <el-option
                            label="候选人跟踪表"
                            :value="1"
                        ></el-option>
                    </el-select>
                </div>

                <div
                    class="search-item"
                    v-if="search.type == 1"
                >
                    <!-- <el-input
                        placeholder="选择负责人"
                        v-model="search.recru"
                        clearable
                        :focus="handleRecruFind"
                        :disabled="true"
                    >

                        <el-button
                            slot="append"
                            icon="el-icon-search"
                            @click="handleRecruFind"
                        ></el-button>

                    </el-input> -->

                    <el-select
                        v-model="selectUser"
                        multiple
                        collapse-tags
                        placeholder="选择负责人"
                        clearable
                        autocomplete
                        filterable
                    >
                        <el-option
                            v-for="item in users"
                            :key="item.uname"
                            :label="item.personal_name"
                            :value="item.uname"
                        >
                        </el-option>
                    </el-select>
                </div>

                <div class="search-item">
                    <el-button
                        type="primary"
                        circle
                        icon="el-icon-search"
                        @click="getData"
                    ></el-button>
                </div>

                <div class="action-bar-right">
                    <el-button
                        type="primary"
                        @click="exportExcel"
                    >导出</el-button>
                </div>
            </div>

            <el-table
                border
                stripe
                :data="tdata"
                style="width: 100%"
                :height="tabelHeight"
            >
                <el-table-column
                    v-for="(item, index) in thead"
                    :key="index"
                    :prop="item.prop"
                    :label="item.label"
                    :fixed="item.fixed"
                ></el-table-column>
            </el-table>
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

        <!-- <user
            :show.sync="showSelectUser"
            :selectUser="selectUser"
            @user-list="setSelectUser"
        ></user> -->

    </div>
</template>

<script>
import ReportBase from "@view/base/ReportBase";
// import User from "./User";
import { getLtWeek, getLtMonth } from "@common/util";
export default {
    mixins: [ReportBase],

    components: {
        // User
    },

    created() {
        let that = this,
            ltWeek = getLtWeek();

        that.search.dateRange = [ltWeek.ltWeekStart, ltWeek.ltWeekEnd];

        that.getUsers();
    },

    watch: {
        "search.type": {
            handler: function(newValue, oldValue) {
                let that = this;
                if (newValue == 0) {
                    that.thead = that.recruHead;
                } else {
                    that.thead = that.personHead;
                }

                that.tdata = [];
                that.pager.current = 1;
                that.pager.total = 1;
            },
            immediate: true
        }
    },

    data() {
        return {
            thead: [],

            recruHead: [
                { prop: "personal_name", label: "招聘负责人", fixed: "left" },
                { prop: "name", label: "候选人", fixed: "left" },
                { prop: "screen", label: "通过筛选", fixed: "left" },
                { prop: "arrange_interview", label: "安排面试", fixed: "left" },
                { prop: "arrive", label: "到场", fixed: "left" },
                {
                    prop: "approved_interview",
                    label: "通过面试",
                    fixed: "left"
                },
                { prop: "entry", label: "入职", fixed: "left" }
            ],

            personHead: [
                { prop: "personal_name", label: "招聘负责人", fixed: "left" },
                { prop: "name", label: "候选人", fixed: "left" },
                { prop: "phone", label: "联系电话", fixed: "left" },
                { prop: "email", label: "邮件", fixed: "left" },
                { prop: "school", label: "毕业院校", fixed: "left" },
                { prop: "educational", label: "学历", fixed: "left" },
                { prop: "graduation_time", label: "毕业年份", fixed: "left" },
                { prop: "work_year", label: "工作年限", fixed: "left" },
                { prop: "source", label: "简历来源", fixed: "left" },
                { prop: "communicate_count", label: "沟通次数", fixed: "left" }
            ],

            search: {
                dateRange: [],
                type: 0,
                recru: ""
            },

            //选中的条件
            // selectUser: [],
            // showSelectUser: false,

            // pager: {
            //     total: 1,
            //     current: 1,
            //     size: 2
            // },

            selectUser: [],
            users: [],

            pickerOptions: {
                shortcuts: [
                    {
                        text: "最近一周",
                        onClick(picker) {
                            let ltWeek = getLtWeek();

                            picker.$emit("pick", [
                                ltWeek.ltWeekStart,
                                ltWeek.ltWeekEnd
                            ]);
                        }
                    },
                    {
                        text: "上个月",
                        onClick(picker) {
                            let ltMonth = getLtMonth();
                            picker.$emit("pick", [
                                ltMonth.ltMonStart,
                                ltMonth.ltMonEnd
                            ]);
                        }
                    }
                ]
            }
        };
    },

    methods: {
        //获取所用的用户
        getUsers() {
            let that = this;

            that.$api.user
                .getAll()
                .then(res => {
                    if (res.code == 0) {
                        for (var i = 0; i < res.data.length; i++) {
                            var item = res.data[i];

                            item.personal_name =
                                item.personal_name || item.uname;
                        }

                        that.users = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取所有用户失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取所有用户失败，请刷新后重试.");
                });
        },

        getData() {
            let that = this,
                params = {
                    dtfm: that.search.dateRange[0],
                    dtto: that.search.dateRange[1]
                };

            that.pager.current = 1;
            // that.pager.total = 1;

            if (that.search.type == 0) {
                that.getRecruitmentList(params);
            } else if (that.search.type == 1) {
                let urs = that.selectUser.join(",");

                if (urs) {
                    params["ur"] = urs;
                }

                that.getCandidateList(params);
            }
        },

        //获取 招聘负责人统计的报表
        getRecruitmentList(params) {
            let that = this;

            that.$api.person_recru
                .recruitment_list(params)
                .then(res => {
                    if (res.code == 0) {
                        that.reportData = res.data;

                        if (that.pager) {
                            that.pager.total = that.reportData.length;

                            that.tdata = that.reportData.slice(
                                0,
                                that.pager.size
                            );
                        }
                    } else {
                        that.$message.error(
                            res.msg || "获取数据失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请刷新后重试.");
                });
        },

        //获取个人招聘统计候选人跟踪报表
        getCandidateList(params) {
            let that = this;

            that.$api.person_recru
                .candidate_list(params)
                .then(res => {
                    if (res.code == 0) {
                        that.reportData = res.data;

                        if (that.pager) {
                            that.pager.total = that.reportData.length;

                            that.tdata = that.reportData.slice(
                                0,
                                that.pager.size
                            );
                        }
                    } else {
                        that.$message.error(
                            res.msg || "获取数据失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请刷新后重试.");
                });
        },

        setSelectUser(users) {
            let that = this;
            that.search.recru = users.slUserPerson.join(",");
            that.selectUser = users.slUser;
        },

        handleRecruFind() {
            let that = this;
            // that.selectUser = that.search.recru;
            that.showSelectUser = true;
        },

        //导出excel
        exportExcel() {
            let that = this,
                pObj = {
                    dtfm: that.search.dateRange[0],
                    dtto: that.search.dateRange[1],
                    type: that.search.type,
                    ur: that.selectUser.join(",")
                },
                pArr = [];

            for (var key in pObj) {
                var item = pObj[key];

                if (item !== null && item !== undefined && item !== "") {
                    pArr.push(key + "=" + item);
                }
            }

            let params = pArr.join("&");

            window.open(`/person_recru/export?${params}`, "_blank");
        }
    }
};
</script>
<style lang="less" scoped>
.report-person-select-width {
    width: 160px;
}
</style>