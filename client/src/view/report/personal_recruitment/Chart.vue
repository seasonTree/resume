<template>
    <el-dialog
        title="入职图表"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        width="600px"
    >
        <div class="dialog-content mb-20">
            <ve-pie :data="chartData"></ve-pie>
        </div>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button @click="closeDialog">关闭</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "../../base/DialogForm";
import VePie from "v-charts/lib/pie.common";
export default {
    name: "Chart",
    mixins: [DialogForm],

    components: { VePie },

    props: {
        pieData: {
            type: [],
            required: true
        }
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;
                that.chartData.rows = that.pieData;
            }
        }
    },

    data() {
        return {
            chartData: {
                columns: ["uname", "count"],
                rows: []
            }
        };
    },

    methods: {
        //关闭窗口后调用
        afterClose() {
            let that = this;
            that.users = [];
            that.roleUser = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>