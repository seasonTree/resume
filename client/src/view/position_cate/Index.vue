<template>
    <div>
        <div>
            <el-row class="table-container">
                <div class="action-bar">
                    <div class="search-item">
                        <el-button
                            type="primary"
                            @click="addDialog = true"
                            :disabled="!$check_pm('position_cate_add')"
                        >新增</el-button>
                    </div>

                    <div class="action-bar-right">
                        <div class="search-item">
                            <el-input
                                type="text"
                                class="search-input"
                                v-model.trim="search.name"
                                autocomplete="off"
                                placeholder="请输入职位名称"
                                @keyup.enter="getData(true)"
                            ></el-input>
                            <el-button
                                type="primary"
                                circle
                                icon="el-icon-search"
                                @click="getData(true)"
                                :loading="tableLoading"
                            ></el-button>
                        </div>
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
                        prop="id"
                        label="ID"
                    ></el-table-column>
                    <el-table-column
                        prop="job_name"
                        label="职位名称"
                    ></el-table-column>
                    <el-table-column
                        prop="ct_user"
                        label="创建人"
                    ></el-table-column>
                    <el-table-column
                        prop="ct_time"
                        label="创建时间"
                    ></el-table-column>

                    <el-table-column
                        fixed="right"
                        label="操作"
                        align="center"
                    >
                        <template slot-scope="scope">
                            <el-tooltip
                                effect="dark"
                                content="修改"
                                placement="top"
                            >
                                <el-button
                                    type="primary"
                                    size="mini"
                                    icon="el-icon-edit"
                                    circle
                                    @click="showEditDialog(scope.row)"
                                    :disabled="!$check_pm('position_cate_edit')"
                                ></el-button>
                            </el-tooltip>
                            <el-tooltip
                                effect="dark"
                                content="删除"
                                placement="bottom"
                            >
                                <el-button
                                    type="danger"
                                    size="mini"
                                    icon="el-icon-delete"
                                    circle
                                    @click="del(scope.row.id, scope.$index)"
                                    :disabled="!$check_pm('position_cate_del')"
                                ></el-button>
                            </el-tooltip>
                        </template>
                    </el-table-column>
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

            <add
                :show.sync="addDialog"
                @add-item="addItem"
            ></add>

            <edit
                :show.sync="editDialog"
                :edit-item="currentEditItem"
                @edit-item="editItem"
            ></edit>
        </div>
    </div>
</template>

<script>
import TableBase from "@view/base/TableBase";
import Add from "./Add";
import Edit from "./Edit";
export default {
    mixins: [TableBase],

    components: {
        Add,
        Edit
    },
    data() {
        return {
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: "position_cate",
            search: {
                name: ""
            },

            tdata: [
                // {
                //     id: 1,
                //     role_name: "123",
                //     status: 0,
                //     ct_user: "创建人",
                //     ct_time: "创建时间",
                //     mfy_user: "修改人",
                //     mfy_time: "修改时间"
                // }
            ]
        };
    },
    methods: {}
};
</script>
<style lang="less" scoped>
</style>