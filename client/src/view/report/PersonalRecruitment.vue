<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">

                <el-row
                    type="flex"
                    justify="space-around"
                    :gutter="20"
                >
                    <el-col :span="18">

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
                        >
                        </el-date-picker>
                        <el-button
                            type="primary"
                            circle
                            icon="el-icon-search"
                            @click="getData"
                        ></el-button>
                    </el-col>
                    <el-col :span="6">
                        <el-row
                            type="flex"
                            justify="end"
                        >
                            <el-button
                                type="primary"
                                @click="exportExcel"
                            >导出</el-button>
                        </el-row>
                    </el-col>
                </el-row>
            </div>

            <el-table
                border
                stripe
                :data="tdata"
                style="width: 100%"
                :height="tabelHeight"
            >
                <el-table-column
                    prop="id"
                    label="招聘负责人"
                    fixed="left"
                    width="100"
                ></el-table-column>

                <el-table-column
                    prop="uname"
                    label="日期"
                    fixed="left"
                    width="100"
                ></el-table-column>

                <el-table-column
                    prop="pesonal_name"
                    label="岗位"
                    width="80"
                ></el-table-column>

                <el-table-column
                    prop="phone"
                    label="候选人"
                    width="80"
                ></el-table-column>

                <el-table-column
                    prop="ct_user"
                    label="联系电话"
                    width="100"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="邮件"
                    width="100"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="毕业学校"
                    width="120"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="学历"
                    width="80"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="毕业年份"
                    width="80"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="工作年限"
                    width="100"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="简历来源"
                    width="80"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="同和/大展"
                    fixed="right"
                    width="100"
                ></el-table-column>

                <el-table-column
                    prop="ct_time"
                    label="沟通次数"
                    fixed="right"
                    width="100"
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
    </div>
</template>

<script>
import ReportBase from '@view/base/ReportBase'
import { getLtWeek, getLtMonth } from "../../common/util";
export default {
    mixins: [ReportBase],

    components: {},

    data() {
        return {
            search: {
                dateRange: []
            },

            pickerOptions: {
                shortcuts: [
                    {
                        text: "最近一周",
                        onClick(picker) {
                            let ltWeed = getLtWeek();

                            console.log(ltWeed);

                            picker.$emit("pick", [
                                ltWeed.ltWeekStart,
                                ltWeed.ltWeekEnd
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

    methods:{

        //导出excel
        exportExcel(){
            window.open('http://www.baidu.com', '_blank');
        }
    }
};
</script>
<style lang="less" scoped>
</style>