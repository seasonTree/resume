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
                        format="yyyy-MM-dd"
                        value-format="yyyy-MM-dd"
                        :clearable="false"
                    >
                    </el-date-picker>
                </div>

                <div class="search-item">
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
                            :label="item.uname"
                            :value="item.uname"
                        >
                        </el-option>
                    </el-select>
                </div>

                <div class="search-item">
                    <el-select
                        v-model="selectClient"
                        multiple
                        collapse-tags
                        placeholder="选择客户"
                        clearable
                        autocomplete
                        filterable
                    >
                        <el-option
                            v-for="item in client"
                            :key="item.id"
                            :label="item.client_name"
                            :value="item.id"
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
                        :disabled="!$check_pm('report_person_recru_export_excel')"
                    >导出</el-button>
                </div>
            </div>

            <el-table
                border
                stripe
                :data="tdata"
                class="width100"
                :height="tabelHeight"
                v-loading="tableLoading"
            >
                <el-table-column
                    v-for="(item, index) in thead"
                    :key="index"
                    :prop="item.prop"
                    :label="item.label"
                    :fixed="item.fixed"
                    :formatter="item.formatter"
                ></el-table-column>

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

    </div>
</template>

<script>
import ReportBase from "@view/base/ReportBase";
import { getLtWeek, getLtMonth } from "@common/util";

export default {
    mixins: [ReportBase],

    created() {
        let that = this,
            ltWeek = getLtWeek();

        that.search.dateRange = [ltWeek.ltWeekStart, ltWeek.ltWeekEnd];

        that.getUsers();
        that.getClientData();
    },

    data() {
        return {
            //候选人信息统计
            thead: [
                { prop: "ct_user", label: "推荐时间", fixed: "left" },
                { prop: "name", label: "姓名", fixed: "left" },
                { prop: "screen", label: "联系方式", fixed: "left" },
                { prop: "arrange_interview", label: "客户", fixed: "left" },
                { prop: "arrive", label: "跟踪人", fixed: "left" },
                { prop: "entry", label: "岗位", fixed: "left" },
                { prop: "entry", label: "学历", fixed: "left" },
                { prop: "entry", label: "毕业年份", fixed: "left" },
                { prop: "entry", label: "毕业院校", fixed: "left" },
                { prop: "entry", label: "公司", fixed: "left" },
                { prop: "entry", label: "是否推荐", fixed: false },
                { prop: "entry", label: "是否安排", fixed: false },
                { prop: "entry", label: "是否到场", fixed: false },
                { prop: "entry", label: "是否通过", fixed: false },
                { prop: "entry", label: "是否入职", fixed: false }
            ],

            search: {
                dateRange: []
            },

            selectUser: [],
            users: [],

            client: [],
            selectClient: [],

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
        getData() {
            let that = this,
                params = {
                    dtfm: that.search.dateRange[0],
                    dtto: that.search.dateRange[1],
                    ur: that.selectUser.join(","),
                    client: that.selectClient.join(",")
                };

            that.pager.current = 1;

            if (that.$check_pm("report_candidate_info_statistics")) {
                //权限
                that.getCandidateInfoStatistics(params);
            } else {
                that.$message.error("无此权限.");
            }
        },

        //获取所用的用户
        getUsers() {
            let that = this;

            that.$api.user
                .getAll()
                .then(res => {
                    if (res.code == 0) {
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

        getClientData() {
            let that = this;

            that.$api.client
                .getAll()
                .then(res => {
                    if (res.code == 0) {
                        that.client = res.data;
                    } else {
                        that.$message.error(
                            res.msg || "获取客户列表失败，请重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取客户列表失败，请重试.");
                });
        },

        //获取 候选人信息统计
        getCandidateInfoStatistics(params) {
            let that = this;

            that.$api.report
                .candidate_info_statistics(params)
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

        //导出excel
        exportExcel() {
            let that = this,
                pObj = {
                    dtfm: that.search.dateRange[0],
                    dtto: that.search.dateRange[1],
                    ur: that.selectUser.join(","),
                    client: that.selectClient.join(",")
                },
                pArr = [];

            if (that.$check_pm("report_candidate_info_statistics_export")) {
                for (var key in pObj) {
                    var item = pObj[key];

                    if (item !== null && item !== undefined && item !== "") {
                        pArr.push(key + "=" + item);
                    }
                }

                let params = pArr.join("&");

                window.open(
                    `/api/report/candidate_info_statistics/export?${params}`,
                    "_blank"
                );
            } else {
                that.$message.error("无此权限.");
            }
        }
    }
};
</script>
<style lang="less" scoped>
</style>