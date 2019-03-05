<template>
    <div>
        <div>
            <el-row class="table-container">
                <div class="action-bar">
                    <div class="search-item">
                        <el-button
                            type="primary"
                            @click="addDialog = true"
                            :disabled="!$check_pm('role_add')"
                        >新增</el-button>
                    </div>
                    <div class="action-bar-right">
                        <div class="search-item">
                            <el-input
                                type="text"
                                class="search-input"
                                v-model.trim="search.name"
                                autocomplete="off"
                                placeholder="请输入角色名称"
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
                    style="width: 100%"
                    :height="tabelHeight"
                    v-loading="tableLoading"
                >
                    <el-table-column
                        prop="id"
                        label="ID"
                    ></el-table-column>
                    <el-table-column
                        prop="role_name"
                        label="角色名"
                    ></el-table-column>
                    <el-table-column
                        prop="ct_user"
                        label="创建人"
                    ></el-table-column>
                    <el-table-column
                        prop="ct_time"
                        label="创建时间"
                    ></el-table-column>

                    <!-- <el-table-column
                        fixed="right"
                        label="状态"
                        align="center"
                        width="60"
                    >
                        <template slot-scope="scope">
                            <i
                                v-if="scope.row.status == 0"
                                class="fa fa-check right status-icon"
                                @click="changeStatus(scope.row.id, 1, scope.row)"
                            ></i>
                            <i
                                v-if="scope.row.status == 1"
                                class="fa fa-ban ban status-icon"
                                @click="changeStatus(scope.row.id, 0, scope.row)"
                            ></i>
                        </template>
                    </el-table-column> -->

                    <el-table-column
                        fixed="right"
                        label="操作"
                        align="center"
                        width="180"
                    >
                        <template slot-scope="scope">

                            <el-tooltip
                                effect="dark"
                                content="设置角色用户"
                                placement="top"
                            >
                                <el-button
                                    type="info"
                                    size="mini"
                                    icon="fa fa-user"
                                    circle
                                    @click="setRoleUser(scope.row.id)"
                                    :disabled="!$check_pm('role_user_set')"
                                ></el-button>
                            </el-tooltip>

                            <el-tooltip
                                effect="dark"
                                content="设置权限"
                                placement="bottom"
                            >
                                <el-button
                                    type="success"
                                    size="mini"
                                    icon="fa fa-cog"
                                    circle
                                    @click="setRolePremission(scope.row.id)"
                                    :disabled="!$check_pm('role_permiss_set')"
                                ></el-button>
                            </el-tooltip>
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
                                    :disabled="!$check_pm('role_edit')"
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
                                    @click="del(scope.row, scope.$index)"
                                    :disabled="!$check_pm('role_del')"
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

            <permission
                :show.sync="permissionDialog"
                :id="permissionID"
            ></permission>

            <user
                :show.sync="userDialog"
                :id="userRoleID"
            ></user>
        </div>
    </div>
</template>

<script>
import TableBase from "@view/base/TableBase";
import Add from "./Add";
import Edit from "./Edit";
import Permission from "./Permission";
import User from "./User";
export default {
    mixins: [TableBase],

    components: {
        Add,
        Edit,
        Permission,
        User
    },
    data() {
        return {
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: "role",
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
            ],

            //权限
            permissionDialog: false,
            permissionID: 0,

            //设置角色的用户
            userDialog: false,
            userRoleID: 0
        };
    },
    methods: {
        // changeStatus(id, status, item) {
        //     let that = this;

        //     that.$api.role
        //         .changeStatus({
        //             id,
        //             status
        //         })
        //         .then(res => {
        //             if (res.code == 0) {
        //                 that.$message({
        //                     message: "更新状态成功.",
        //                     type: "success",
        //                     duration: 800
        //                 });

        //                 item.status = status;
        //             } else {
        //                 that.$message.error(res.msg || "更新状态失败，请重试.");
        //             }
        //         })
        //         .catch(res => {
        //             that.$message.error("更新状态失败，请重试.");
        //         });
        // },

        //设置当前的角色权限
        setRolePremission(id) {
            let that = this;

            that.permissionID = id;
            that.permissionDialog = true;
        },

        //设置当前角色的用户
        setRoleUser(id) {
            let that = this;
            that.userRoleID = id;
            that.userDialog = true;
        }
    }
};
</script>
<style lang="less" scoped>
.right {
    color: #00c100;
}

.ban {
    color: #f56c6c;
}

.status-icon {
    cursor: pointer;
}
</style>