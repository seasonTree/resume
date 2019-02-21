<template>
    <div>
        <el-dialog
            title="导出简历"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog"
            v-dialog-drag
        >
            <div class="mb-20">
                <el-row :gutter="20">
                    <el-col :span="12">
                        <div class="export-resume-block avo-image" @click="exportWord('avo')"></div>
                    </el-col>
                    <el-col :span="12">
                        <div class="export-resume-block tonghe-image" @click="exportWord('tonghe')"></div>
                    </el-col>
                </el-row>
            </div>
            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="closeDialog">关 闭</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
import DialogForm from "../base/DialogForm";
export default {
    name: "ExportResume",
    mixins: [DialogForm],

    props: {
        //简历的id
        resume_id: {
            required: true,
            type: Number
        }
    },

    data() {
        return {};
    },
    methods: {
        exportWord(type) {
            let that = this;
            window.open(`/api/resume/export?resume_id=${that.resume_id}&type=${type}`, "_blank");
        }
    }
};
</script>
<style lang="less" scoped>
.avo-image {
    background-image: url(../../image/avo.jpg);
}

.tonghe-image {
    background-image: url(../../image/tonghe.jpg);
}

.export-resume-block {
    height: 200px;
    border: 1px solid #e3e3e3;
    background-size: 100% 100%;
    border-radius: 1em;
    transition: all .2s;

    &:hover {
        cursor: pointer;
        transform: translate(1px, 1px);
        box-shadow: #e3e3e3 6px 6px 8px;
    }
}
</style>