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
                                placeholder="请输入用户名或姓名"
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
                    prop="uname"
                    label="用户名"
                ></el-table-column>

                <el-table-column
                    prop="pesonal_name"
                    label="姓名"
                ></el-table-column>

                <el-table-column
                    prop="phone"
                    label="电话"
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
                    label="状态"
                    align="center"
                >
                    <template slot-scope="scope">
                        <i
                            v-if="scope.row.status == 0"
                            class="fa fa-check right status-icon"
                            @click="changeStatus(scope.row.id, 1)"
                        ></i>
                        <i
                            v-if="scope.row.status == 1"
                            class="fa fa-ban ban status-icon"
                            @click="changeStatus(scope.row.id, 0)"
                        ></i>
                    </template>
                </el-table-column>

                <el-table-column
                    fixed="right"
                    label="操作"
                    align="center"
                >
                    <template slot-scope="scope">

                        <el-tooltip
                            effect="dark"
                            content="修改密码"
                            placement="bottom"
                        >
                            <el-button
                                type="success"
                                size="mini"
                                icon="fa fa-key"
                                circle
                                @click="changeUserPwd(scope.row.id)"
                            ></el-button>
                        </el-tooltip>
                        <el-tooltip
                            effect="dark"
                            content="修改密码"
                            placement="top"
                        >
                            <el-button
                                type="primary"
                                size="mini"
                                icon="el-icon-edit"
                                circle
                                @click="showEditDialog(scope.row.id)"
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
                            ></el-button>
                        </el-tooltip>
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

        <change-pwd
            :show.sync="changePwdDialog"
            :id=changePwdID
        ></change-pwd>
    </div>
</template>

<script>
import TabelBase from "@view/base/TabelBase";
import Add from "./Add";
import Edit from "./Edit";
import ChangePwd from "./ChangePwd";
export default {
    mixins: [TabelBase],

    components: {
        Add,
        Edit,
        ChangePwd
    },

    data() {
        return {
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: "user",

            //修改用户的密码 -------
            changePwdDialog: false,
            changePwdID: 0,
            // -------------------

            tdata: [
                // {
                //     id: 1,
                //     uname: "123",
                //     pesonal_name: "aaaaa",
                //     phone: 128154444,
                //     status: 1,
                //     ct_user: "创建人",
                //     ct_time: "创建时间",
                //     mfy_user: "修改人",
                //     mfy_time: "修改时间"
                // },
                //  {
                //     id: 1,
                //     uname: "123",
                //     pesonal_name: "aaaaa",
                //     phone: 128154444,
                //     status: 1,
                //     ct_user: "创建人",
                //     ct_time: "创建时间",
                //     mfy_user: "修改人",
                //     mfy_time: "修改时间"
                // },
            ],

            //搜索条件
            search: {
                name: ""
            }
        };
    },

    methods: {
        changeStatus(id, status) {
            let that = this;

            that.$api.user
                .changeStatus({
                    id,
                    status
                })
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "更新状态成功.",
                            type: "success",
                            duration: 800
                        });
                    } else {
                        that.$message.error(res.msg || "更新状态失败，请重试.");
                    }
                })
                .catch(res => {
                    that.$message.error("更新状态失败，请重试.");
                });
        },

        changeUserPwd(id) {
            let that = this;
            that.changePwdID = id;
            that.changePwdDialog = true;
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