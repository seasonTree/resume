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
                    <div class="block-content">
                        <div>昨天简历更新了{{yesterday_resume}}份</div>
                    </div>
                </el-col>
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content">
                        <div>昨天简历沟通了{{yesterday_communicate}}份</div>
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
                        <div>上周简历更新了{{last_week_resume}}份</div>
                    </div>
                </el-col>
                <el-col
                    class="block"
                    :span="12"
                >
                    <div class="block-content">
                        <div>上周简历总沟通了{{last_week_communicate}}次</div>
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
            yesterday_resume: 0,
            yesterday_communicate: 0,
            last_week_resume: 0,
            last_week_communicate: 0
        };
    },
    created() {
        let that = this;
        that.getData();
    },
    methods: {
        getData() {
            let that = this;
            that.$api.dashboard
                .get()
                .then(res => {
                    if (res.code == 0) {
                        that.yesterday_resume = res.data.yesterday_resume;
                        that.yesterday_communicate = res.data.yesterday_communicate;
                        that.last_week_resume = res.data.last_week_resume;
                        that.last_week_communicate = res.data.last_week_communicate;
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
    > div {
        height: 100%;
    }
}
.block-row {
    height: 50%;
    .block {
        height: 100%;
    }
    .block-content {
        width: 100%;
        height: 100%;
        border-radius: 6px;
        color: white;
        text-align: center;
        line-height: 100%;
        font-size: 26px;
        font-weight: bolder;
        position: relative;
        > div {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    }
    &:nth-child(odd) {
        padding-bottom: 10px;
        .block-content {
            background-color: #ff6666;
        }
    }
    &:nth-child(even) {
        padding-top: 10px;
        .block-content {
            background-color: #99cc33;
        }
    }
}
</style>