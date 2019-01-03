<template>
    <div>
        <el-dialog
            title="重名列表"
            :visible.sync="show"
            :before-close="closeDialog"
            class="custom-dialog resume-check"
            :close-on-click-modal="false"
            width="80%"
            v-dialog-drag
        >

            <el-table
                :data="resumeData"
                stripe
                border
                style="width: 100%"
                @row-click="showViewDialog"
                class="table mb-20"
            >

                <el-table-column
                    fixed
                    prop="name"
                    label="姓名"
                    width="100"
                >

                </el-table-column>
                <el-table-column
                    fixed
                    prop="sex"
                    label="性别"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="age"
                    label="年龄"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="phone"
                    label="手机"
                    width="150"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="educational"
                    label="学历"
                    width="100"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="work_year"
                    label="工作年限"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="email"
                    label="电子邮箱"
                    width="200"
                >
                </el-table-column>
                <el-table-column
                    prop="expected_money"
                    label="期望薪资"
                    width="150"
                >
                </el-table-column>
                <el-table-column
                    prop="inPosition"
                    label="所处职位"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="nearest_unit"
                    label="最近单位"
                    width="220"
                >
                </el-table-column>
                <el-table-column
                    prop="nearest_job"
                    label="最近职位"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="school"
                    label="毕业院校"
                    width="300"
                >
                </el-table-column>
                <el-table-column
                    prop="speciality"
                    label="专业"
                    width="150"
                >
                </el-table-column>

                <el-table-column
                    prop="english"
                    label="英语等级"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="expected_address"
                    label="期望工作地"
                    width="200"
                >
                </el-table-column>
            </el-table>

            <div
                slot="footer"
                class="dialog-footer"
            >
                <el-button @click="cancelCommit">取消录入</el-button>
                <el-button
                    type="primary"
                    @click="continueCommit"
                >继续录入</el-button>
            </div>
        </el-dialog>

        <view-resume
            :show.sync="viewDialog"
            :id="viewID"
        ></view-resume>
    </div>
</template>

<script>
import DialogForm from "../base/DialogForm";
import ViewResume from "./ViewResume";
export default {
    name: "ResumeCheck",
    mixins: [DialogForm],

    components: {
        ViewResume
    },

    props: {
        resumeData: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            viewDialog: false,
            viewID: 0
        };
    },

    methods: {
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

        //向上抛出事件
        continueCommit() {
            let that = this;
            that.$emit("continue-commit");
            that.closeDialog();
        },

        cancelCommit(){
            let that = this;
            that.$emit('cancel-commit');
            that.closeDialog();
        }        
    }
};
</script>
<style lang="less" scoped>
.resume-check {
    .table {
        tbody {
            tr {
                cursor: pointer;
            }
        }
    }
}
</style>