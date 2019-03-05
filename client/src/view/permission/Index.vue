<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">
                <div class="search-item">
                    <el-button
                        type="primary"
                        @click="addDialog = true"
                        :disabled="!$check_pm('permiss_add')"
                    >新增</el-button>

                    <el-button
                        type="primary"
                        @click="sortDialog = true"
                        :disabled="!$check_pm('permiss_sort')"
                    >排序</el-button>

                </div>
            </div>
            <el-table
                border
                stripe
                :data="treeArrayData"
                :height="tabelHeight"
                :row-class-name="showRow"
                class="premission-table width100"
                v-loading="tableLoading"
            >
                <el-table-column
                    prop="p_name"
                    label="权限名称"
                >
                    <template slot-scope="scope">
                        <!-- 左边距离 -->
                        <span :style="{'margin-left': scope.row._level * 24 + 'px'}">
                            <i
                                v-if="scope.row._hasChild"
                                class="fa fa-caret-right expend-icon"
                                :class="{ 'icon-down' : scope.row._expanded }"
                                @click="toggle(scope.row)"
                            ></i>

                            <span>{{scope.row.p_name}}</span>
                        </span>
                    </template>
                </el-table-column>
                <el-table-column
                    prop="url"
                    label="菜单地址"
                ></el-table-column>
                <el-table-column
                    prop="api"
                    label="接口"
                ></el-table-column>
                <el-table-column
                    prop="p_act_name"
                    label="功能英文名称"
                ></el-table-column>
                <!-- <el-table-column
                        prop="ct_user"
                        label="创建人"
                    ></el-table-column> -->
                <el-table-column
                    prop="ct_time"
                    label="创建时间"
                    width="150"
                ></el-table-column>
                <el-table-column
                    fixed="right"
                    label="操作"
                    align="center"
                    width="100"
                >
                    <template slot-scope="scope">
                        <el-button
                            type="primary"
                            size="mini"
                            icon="el-icon-edit"
                            circle
                            @click="showEditDialog(scope.row)"
                            :disabled="!$check_pm('permiss_edit')"
                        ></el-button>
                        <el-button
                            type="danger"
                            size="mini"
                            icon="el-icon-delete"
                            circle
                            @click="del(scope.row, scope.$index)"
                            :disabled="!$check_pm('permiss_del')"
                        ></el-button>
                    </template>
                </el-table-column>
            </el-table>
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

        <sort
            :show.sync="sortDialog"
            @refresh-data="getData"
        ></sort>
    </div>
</template>

<script>
import TableBase from "@view/base/TableBase";
import { treeToArray } from "@common/util";
import Add from "./Add";
import Edit from "./Edit";
import Sort from "./Sort";
export default {
    mixins: [TableBase],

    components: {
        Add,
        Edit,
        Sort
    },
    props: {},
    data() {
        return {
            //不分页
            pager: false,

            //不查找
            search: false,

            apiType: "permission",
            search: {
                name: ""
            },

            //菜单排序
            sortDialog: false,

            tdata: [
                // {
                //     id: 1,
                //     p_name: "菜单页面",
                //     url: '/user/aaa',
                //     api: "/ttttttt/ttttt",
                //     ct_user: "6666666",
                //     ct_time: "192.666245",
                //     children: [
                //         {
                //             id: 2,
                //             p_name: "功能页面",
                //             url: '/user/aaa',
                //             api: "/44444/444",
                //             ct_user: "44444444",
                //             ct_time:"444444444444444"
                //         },
                //         {
                //             id: 3,
                //             p_name: "功能页面2222",
                //             url: '/user/aaa',
                //             api: "/nnnnnn/nnn",
                //             ct_user: "nnnnnnn",
                //             ct_time: "nnnnnnnnnnnn",
                //             children: [
                //                 {
                //                     id: 4,
                //                     p_name:"333333333333",
                //                     url: '/user/aaa',
                //                     api: "/33333",
                //                     ct_user: "3333",
                //                     ct_time:"3333333"
                //                 }
                //             ]
                //         }
                //     ]
                // },
                // {
                //     id: 16,
                //     p_name: "菜单33",
                //     api: "/vvvv/vvvvv",
                //     url: '/user/aaa',
                //     ct_user: "6666666",
                //     ct_time: "192.666245",
                //     children: [
                //         {
                //             id: 2,
                //             p_name: "功能页面",
                //             api: "/44444/444",
                //             url: '/user/aaa',
                //             ct_user: "44444444",
                //             ct_time:"444444444444444"
                //         }
                //     ]
                // }
            ]
        };
    },

    computed: {
        treeArrayData() {
            return treeToArray(this.tdata, null, null, true, this);
        }
    },

    methods: {
        showRow({ row, index }) {
            //如果父展开了，还有父是展示的情况下显示
            let show = row._parent
                ? row._parent._expanded && row._parent._show
                : true;

            row._show = show;

            return show ? "" : "hide-tr";
        },

        toggle(item) {
            item._expanded = !item._expanded;
        },

        //重写新增方法, 重新获取数据
        addItem(item) {
            let that = this;
            that.getData();
        },

        //重新修改方法，重新获取数据
        editItem(item) {
            let that = this;
            that.getData();
        },

        //删除之后
        afterDel(item) {
            let that = this;
            that.getData();
        },
    }
};
</script>
<style lang="less" scoped>
.expend-icon {
    padding: 4px 6px;
    transition: all 0.2s;
    cursor: pointer;
}

.icon-down {
    transform: rotate(90deg);
}
</style>