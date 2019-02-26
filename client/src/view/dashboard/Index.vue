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
                        <div class="chart-title">个人最近7天简历情况</div>

                        <ve-histogram
                            :data="personChartData"
                            :height="chartHeight"
                            :settings="barChartSetting"
                        ></ve-histogram>

                        <!-- <div>昨天简历更新了{{yesterday_resume}}份</div> -->
                    </div>
                </el-col>
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content">
                        <!-- <div>昨天简历沟通了{{yesterday_communicate}}份</div> -->

                        <div class="chart-title">个人最近7天沟通情况</div>
                        <ve-pie
                            :data="personPieData"
                            :height="chartHeight"
                        ></ve-pie>
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
                        <div class="chart-title">最近7天简历汇总情况</div>

                        <ve-histogram
                            :data="totalChartData"
                            :height="chartHeight"
                            :settings="barChartSetting"
                        ></ve-histogram>
                        <!-- <div>上周简历更新了{{last_week_resume}}份</div> -->
                    </div>
                </el-col>
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content">
                        <!-- <div>上周简历总沟通了{{last_week_communicate}}次</div> -->

                        <div class="chart-title">最近7天沟通汇总情况</div>
                        <ve-pie
                            :data="totalPieData"
                            :height="chartHeight"
                        ></ve-pie>
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

            personPieData: {
                columns: ["type", "total"],
                rows: [
                    // {
                    //     type: "推荐",
                    //     total: 1393
                    // },
                    // {
                    //     type: "安排",
                    //     total: 3530
                    // },
                    // {
                    //     type: "到场",
                    //     total: 2923
                    // },
                    // {
                    //     type: "通过",
                    //     total: 1723
                    // },
                    // {
                    //     type: "入职",
                    //     total: 3792
                    // }
                ]
            },
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

            totalPieData: {
                columns: ["type", "total"],
                rows: [
                    // {
                    //     type: "推荐",
                    //     total: 1393
                    // },
                    // {
                    //     type: "安排",
                    //     total: 3530
                    // },
                    // {
                    //     type: "到场",
                    //     total: 2923
                    // },
                    // {
                    //     type: "通过",
                    //     total: 1723
                    // },
                    // {
                    //     type: "入职",
                    //     total: 3792
                    // }
                ]
            }

            //------------------------
        };
    },

    created() {
        let that = this;
        that.getData();
    },

    mounted() {
        let that = this;

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
                        that.personPieData.rows = res.data.per_pie_data;
                        that.totalPieData.rows = res.data.total_pei_data;                        

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
    padding: 10px 0;
    .block {
        height: 100%;
        min-height: 360px;
        box-sizing: border-box;
        position: relative;

        .block-content {
            // width: 100%;
            // height: 100%;
            border: 1px solid #e3e3e3;
            border-radius: 6px;
            // color: white;
            text-align: center;
            line-height: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 10px;
            right: 10px;
            overflow: hidden;
            // > div {
            //     position: absolute;
            //     top: 50%;
            //     left: 50%;
            //     transform: translate(-50%, -50%);
            // }

            .chart-title {
                font-size: 18px;
                margin: 10px 0;
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