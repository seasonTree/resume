<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">

                <el-row
                    type="flex"
                    justify="space-around"
                    :gutter="20"
                >
                    <el-col :span="6">
                        <el-button
                            type="primary"
                            @click="addDialog = true"
                        >新增</el-button>
                    </el-col>
                    <el-col :span="18">
                        <el-row
                            type="flex"
                            justify="end"
                        >
                            <el-input
                                type="text"
                                class="search-input"
                                v-model="search.name"
                                autocomplete="off"
                                placeholder="请输入角色名称"
                            ></el-input>
                            <el-button
                                type="primary"
                                circle
                                icon="el-icon-search"
                                @click="getData"
                            ></el-button>
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
                    label="ID"
                ></el-table-column>
                <el-table-column
                    prop="role_name"
                    label="角色名称"
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
                        <el-button
                            type="primary"
                            size="mini"
                            icon="el-icon-edit"
                            circle
                            @click="showEditDialog(scope.row.id)"
                        ></el-button>
                        <el-button
                            type="danger"
                            size="mini"
                            icon="el-icon-delete"
                            circle
                            @click="del(scope.row.id, scope.$index)"
                        ></el-button>
                    </template>
                </el-table-column>
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
</template>

<script>
import TabelBase from "@view/base/TabelBase";
import Add from "./Add";
import Edit from "./Edit";
export default {
    components: {},
    props: {},
    data() {
        return {};
    },
    created() {},
    mounted() {},
    watch: {},
    computed: {},
    methods: {}
};
</script>
<style lang="less" scoped>
</style>