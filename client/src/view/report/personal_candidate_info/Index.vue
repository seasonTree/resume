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
    },

    data() {
        return {
            //招聘负责人明细
            thead: [
                { prop: "ct_user", label: "招聘负责人", fixed: "left" },
                { prop: "name", label: "候选人", fixed: "left" },
                { prop: "screen", label: "是否推荐", fixed: "left" },
                { prop: "arrange_interview", label: "是否安排", fixed: "left" },
                { prop: "arrive", label: "是否到场", fixed: "left" },
                {
                    prop: "approved_interview",
                    label: "是否通过",
                    fixed: "left"
                },
                { prop: "entry", label: "是否入职", fixed: "left" }
            ],

            search: {
                dateRange: []
            },

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
                    dtto: that.search.dateRange[1]
                };

            that.pager.current = 1;

            if (that.$check_pm("report_person_candidate_info")) {
                //权限
                that.getPersonalCandidateInfo(params);
            } else {
                that.$message.error("无此权限.");
            }
        },

        //获取 招聘负责人明细的报表
        getPersonalCandidateInfo(params) {
            let that = this;

            that.$api.report
                .personal_candidate_info(params)
                .then(res => {
                    if (res.code == 0) {
                        for (var i = 0; i < res.data.length; i++) {
                            var item = res.data[i];

                            item.approved_interview =
                                item.approved_interview == 0 ? "否" : "是";
                            item.arrange_interview =
                                item.arrange_interview == 0 ? "否" : "是";
                            item.arrive = item.arrive == 0 ? "否" : "是";
                            item.entry = item.entry == 0 ? "否" : "是";
                            item.screen = item.screen == 0 ? "否" : "是";
                        }

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
                    dtto: that.search.dateRange[1]
                },
                pArr = [];

            if (that.$check_pm("report_person_candidate_info_export")) {
                for (var key in pObj) {
                    var item = pObj[key];

                    if (item !== null && item !== undefined && item !== "") {
                        pArr.push(key + "=" + item);
                    }
                }

                let params = pArr.join("&");

                window.open(
                    `/api/report/person_candidate_info/export?${params}`,
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