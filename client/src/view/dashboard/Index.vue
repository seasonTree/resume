<template>
    <div class="dashboard">
        <el-row :gutter="20">
            <el-col
                :span="24"
                class="block-row"
            >
                <el-col
                    class="block"
                    :span="12"
                >
                    <div
                        class="block-content"
                        ref="blockContent"
                    >
                        <div class="chart-title">个人最近7天招聘的情况</div>

                        <ve-histogram
                            :data="personChartData"
                            :height="chartHeight"
                            :settings="barChartSetting"
                            :extend="extend"
                        ></ve-histogram>

                        <!-- <div>昨天简历更新了{{yesterday_resume}}份</div> -->
                    </div>
                </el-col>
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content text-block">
                        <!-- <div>昨天简历沟通了{{yesterday_communicate}}份</div> -->

                        <!-- <div class="chart-title">个人最近7天沟通情况</div> -->

                        <div class="text-content">
                            <div class="chart-title">个人最近7天招聘情况</div>

                            <div class="text-row">推荐总数：
                                <span class="text-tip">{{person_comm.screen}}</span>
                                ， 占团队比率：<span class="text-tip">{{person_comm.screen_percentage}}</span>
                            </div>
                            <div class="text-row">安排总数：
                                <span class="text-tip">{{person_comm.arrange_interview}}</span>
                                ， 占团队比率：<span class="text-tip">{{person_comm.arrange_interview_percentage}}</span>
                            </div>
                            <div class="text-row">到场总数：
                                <span class="text-tip">{{person_comm.arrive}}</span>
                                ， 占团队比率：<span class="text-tip">{{person_comm.arrive_percentage}}</span>
                            </div>
                            <div class="text-row">通过总数：
                                <span class="text-tip">{{person_comm.approved_interview}}</span>
                                ， 占团队比率：<span class="text-tip">{{person_comm.approved_interview_percentage}}</span>
                            </div>
                            <div class="text-row">入职总数：
                                <span class="text-tip">{{person_comm.entry}}</span>
                                ， 占团队比率：<span class="text-tip">{{person_comm.entry_percentage}}</span>
                            </div>
                        </div>

                        <!-- <ve-pie
                            :data="personPieData"
                            :height="chartHeight"
                        ></ve-pie> -->
                    </div>
                </el-col>
            </el-col>

            <el-col
                :span="24"
                class="block-row"
            >
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content">
                        <div class="chart-title">团队最近7天招聘情况</div>

                        <ve-histogram
                            :data="totalChartData"
                            :height="chartHeight"
                            :settings="barChartSetting"
                            :extend="extend"
                        ></ve-histogram>
                        <!-- <div>上周简历更新了{{last_week_resume}}份</div> -->
                    </div>
                </el-col>
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content text-block">
                        <!-- <div>上周简历总沟通了{{last_week_communicate}}次</div> -->

                        <!-- <div class="chart-title">团队最近7天沟通汇总情况</div> -->
                        <div class="text-content">
                            <div class="chart-title">团队最近7天招聘情况</div>

                            <div class="text-row">推荐总数： <span class="text-tip">{{total_comm.screen}}</span></div>
                            <div class="text-row">安排总数： <span class="text-tip">{{total_comm.arrange_interview}}</span></div>
                            <div class="text-row">到场总数： <span class="text-tip">{{total_comm.arrive}}</span></div>
                            <div class="text-row">通过总数： <span class="text-tip">{{total_comm.approved_interview}}</span></div>
                            <div class="text-row">入职总数： <span class="text-tip">{{total_comm.entry}}</span></div>

                        </div>
                        <!-- <ve-pie
                            :data="totalPieData"
                            :height="chartHeight"
                        ></ve-pie> -->
                    </div>
                </el-col>
            </el-col>
        </el-row>

    </div>
</template>

<script>
export default {
    data() {
        return {
            // yesterday_resume: 0,
            // yesterday_communicate: 0,
            // last_week_resume: 0,
            // last_week_communicate: 0,

            chartHeight: "300px",

            chartTimer: null,

            barChartSetting: {
                labelMap: {
                    resume: "简历",
                    commun: "沟通"
                }
            },

            extend: {
                series: {
                    label: { show: true, position: "top" }
                },

                yAxis: {
                    type: "value",
                    minInterval: 1,
                    axisLabel: {
                        formatter: "{value}"
                    },
                    boundaryGap: [0, 0.1]
                }
            },

            //个人------------------------
            personChartData: {
                columns: ["date", "resume", "commun"],
                rows: [
                    // {
                    //     date: "1/1",
                    //     resume: 1393,
                    //     commun: 1093
                    // },
                    // {
                    //     date: "1/2",
                    //     resume: 3530,
                    //     commun: 3230
                    // },
                    // {
                    //     date: "1/3",
                    //     resume: 2923,
                    //     commun: 2623
                    // },
                    // {
                    //     date: "1/4",
                    //     resume: 1723,
                    //     commun: 1423
                    // },
                    // {
                    //     date: "1/5",
                    //     resume: 3792,
                    //     commun: 3492
                    // },
                    // {
                    //     date: "1/6",
                    //     resume: 4593,
                    //     commun: 4293
                    // }
                ]
            },

            // personPieData: {
            //     columns: ["type", "total"],
            //     rows: [
            //         // {
            //         //     type: "推荐",
            //         //     total: 1393
            //         // },
            //         // {
            //         //     type: "安排",
            //         //     total: 3530
            //         // },
            //         // {
            //         //     type: "到场",
            //         //     total: 2923
            //         // },
            //         // {
            //         //     type: "通过",
            //         //     total: 1723
            //         // },
            //         // {
            //         //     type: "入职",
            //         //     total: 3792
            //         // }
            //     ]
            // },
            //------------------------

            //汇总-----------------------

            totalChartData: {
                columns: ["date", "resume", "commun"],
                rows: [
                    // {
                    //     date: "1/1",
                    //     resume: 1393,
                    //     commun: 1093
                    // },
                    // {
                    //     date: "1/2",
                    //     resume: 3530,
                    //     commun: 3230
                    // },
                    // {
                    //     date: "1/3",
                    //     resume: 2923,
                    //     commun: 2623
                    // },
                    // {
                    //     date: "1/4",
                    //     resume: 1723,
                    //     commun: 1423
                    // },
                    // {
                    //     date: "1/5",
                    //     resume: 3792,
                    //     commun: 3492
                    // },
                    // {
                    //     date: "1/6",
                    //     resume: 4593,
                    //     commun: 4293
                    // }
                ]
            },

            total_comm: {},
            person_comm: {}

            // totalPieData: {
            //     columns: ["type", "total"],
            //     rows: [
            //         // {
            //         //     type: "推荐",
            //         //     total: 1393
            //         // },
            //         // {
            //         //     type: "安排",
            //         //     total: 3530
            //         // },
            //         // {
            //         //     type: "到场",
            //         //     total: 2923
            //         // },
            //         // {
            //         //     type: "通过",
            //         //     total: 1723
            //         // },
            //         // {
            //         //     type: "入职",
            //         //     total: 3792
            //         // }
            //     ]
            // }

            //------------------------
        };
    },

    created() {
        let that = this;
        that.getData();
    },

    mounted() {
        let that = this;

        that.$nextTick(() => {
            that.resize();
        });

        //监听事件,由layout那边的resize抛出的
        if (window.addEventListener) {
            window.addEventListener("bodyChange", that.resize);
        } else {
            window.attachEvent("bodyChange", that.resize);
        }
    },

    destroyed() {
        let that = this;

        //销毁的时候删除事件
        if (window.removeEventListener) {
            window.removeEventListener("bodyChange", that.resize);
        } else {
            window.removeEvent("bodyChange", that.resize);
        }
    },

    methods: {
        resize() {
            let that = this;

            clearTimeout(that.chartTimer);

            that.chartTimer = setTimeout(() => {
                that.chartHeight = that.$refs.blockContent.offsetHeight + "px";
            }, 100);
        },

        getData() {
            let that = this;

            that.$api.dashboard
                .get()
                .then(res => {
                    if (res.code == 0) {
                        that.personChartData.rows = res.data.per_bar_data;
                        that.totalChartData.rows = res.data.total_bar_data;
                        that.total_comm = res.data.total_comm;
                        that.person_comm = res.data.person_comm;

                        // that.yesterday_resume = res.data.yesterday_resume;
                        // that.yesterday_communicate =
                        //     res.data.yesterday_communicate;
                        // that.last_week_resume = res.data.last_week_resume;
                        // that.last_week_communicate =
                        //     res.data.last_week_communicate;
                    } else if (res.code) {
                        that.$message.error(
                            res.msg || "获取数据失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请刷新后重试.");
                });
        }
    }
};
</script>
<style lang="less" scoped>
.dashboard {
    height: 100%;
    min-width: 820px;

    > div {
        height: 100%;
    }
}
.block-row {
    // padding: 10px 0;
    height: 50%;
    box-sizing: border-box;

    &:first-of-type {
        padding-bottom: 20px;
    }

    .block {
        height: 100%;
        // min-height: 360px;
        box-sizing: border-box;
        position: relative;

        .text-block {
            overflow: auto;
        }

        .block-content {
            // width: 100%;
            // height: 100%;
            border: 1px solid #bbbbbb;
            border-radius: 6px;
            // color: white;
            text-align: center;
            line-height: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 10px;
            right: 10px;
            // > div {

            // }

            .chart-title {
                font-size: 18px;
                margin: 10px 0;
            }

            .text-content {
                // padding: 10px;
                position: absolute;
                top: 50%;
                // left: 50%;
                transform: translate(0, -50%);
                width: 100%;

                .text-row {
                    margin: 10px 0;

                    .text-tip {
                        color: #f92301;
                    }
                }
            }

            // .chart-content{
            //     position: absolute;
            //     left: 10px;
            //     right: 10px;;
            //     top: 40px;
            //     bottom: 10px;
            // }
        }
    }

    // &:nth-child(odd) {
    //     padding-bottom: 10px;
    //     .block-content {
    //         background-color: #ff6666;
    //     }
    // }
    // &:nth-child(even) {
    //     padding-top: 10px;
    //     .block-content {
    //         background-color: #99cc33;
    //     }
    // }
}
</style>