<template>
    <el-dialog
        title="客户推荐记录"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog view-dialog"
        :close-on-click-modal="true"
        v-dialog-drag
        top="5vh"
        width="1000px"
    >
        <div class="mb-20">
            <el-table
                border
                stripe
                :data="tdata"
                style="width: 100%"
                :height="360"
                v-loading="tableLoading"
                class="mb-10"
            >
                <el-table-column
                    prop="ct_user"
                    label="招聘负责人"
                    align="center"
                    fixed
                >
                </el-table-column>
                <el-table-column
                    prop="ct_time"
                    label="推荐日期"
                    align="center"
                    fixed
                >
                </el-table-column>
                <el-table-column
                    prop="name"
                    label="候选人"
                    align="center"
                >
                </el-table-column>
                <el-table-column
                    prop="expected_job"
                    label="岗位"
                    align="center"
                >
                </el-table-column>
                <el-table-column
                    prop="phone"
                    label="联系电话"
                    align="center"
                >
                </el-table-column>
                <el-table-column
                    prop="school"
                    label="学历"
                    align="center"
                >
                </el-table-column>
                <el-table-column
                    prop="graduation_time"
                    label="毕业年份"
                    align="center"
                >
                </el-table-column>
                <el-table-column
                    prop="source"
                    label="简历来源"
                    align="center"
                >
                </el-table-column>
                <el-table-column
                    prop="company_type"
                    label="公司来源"
                    align="center"
                >
                </el-table-column>
            </el-table>
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
        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-button
                @click="closeDialog"
                :disabled="commitLoading"
            >Close</el-button>
        </div>
    </el-dialog>
</template>

<script>
import DialogForm from "@view/base/DialogForm";

export default {
    name: "ClientDetail",

    mixins: [DialogForm],

    props: {
        searchData: {
            required: true
        }
    },

    watch: {
        show(newValue, oldValue) {
            let that = this;

            if (newValue) {
                that.getClientDetail();
            }
        }
    },

    data() {
        return {
            tableLoading: false,

            pager: {
                total: 0,
                current: 1,
                size: 10
            },

            tdata: []
        };
    },

    methods: {
        changePage(index) {
            let that = this;

            that.pager.current = index;
            that.getClientDetail();
        },

        pageSizeChange(val) {
            let that = this;

            that.pager.size = val;
            that.getClientDetail();
        },

        getClientDetail() {
            let that = this,
                params = {
                    ...that.searchData,
                    pageIndex: that.pager.current,
                    pageSize: that.pager.size
				};
				
            that.$api.report
                .client_statistics_detail(params)
                .then(res => {
                    if (res.code == 200) {
                        that.tdata = res.data.row;
                        that.pager.total = res.data.total || 0;
                    } else {
                        that.$message.error(
                            res.message || "获取客户明细数据失败，请重试."
                        );
                    }

                    that.tableLoading = false;
                })
                .catch(res => {
                    that.tableLoading = false;
                    that.$message.error("获取客户明细数据失败，请重试.");
                });
        },

        //关闭窗口后调用
        afterClose() {
            let that = this;

            that.pager.total = 0;
            that.pager.current = 1;
            that.pager.size = 10;
            that.tdata = [];
        }
    }
};
</script>
<style lang="less" scoped>
</style>