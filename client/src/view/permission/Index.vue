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
                                placeholder="请输入权限名称"
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

            <transition>
                <el-table
                    border
                    stripe
                    :data="treeArrayData"
                    style="width: 100%"
                    :height="tabelHeight"
                    :row-class-name="showRow"
                >
                    <el-table-column
                        prop="p_name"
                        label="权限名称"
                    >
                        <template slot-scope="scope">
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
                        prop="api1"
                        label="接口1"
                    ></el-table-column>
                    <el-table-column
                        prop="api2"
                        label="接口2"
                    ></el-table-column>
                    <el-table-column
                        prop="api3"
                        label="接口3"
                    ></el-table-column>
                    <el-table-column
                        prop="api4"
                        label="接口4"
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
            </transition>
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
import { treeToArray } from "../../common/util";
import Add from "./Add";
import Edit from "./Edit";
export default {
    mixins: [TabelBase],

    components: {
        Add,
        Edit
    },
    props: {},
    data() {
        return {
            pager: false,

            tdata: [
                {
                    id: 1,
                    p_name: "菜单页面",
                    api1: "/ttttttt/ttttt",
                    api2: "/ttttttt/ttttt",
                    api3: "/ttttttt/ttttt",
                    api4: "/ttttttt/ttttt",
                    ct_user: "6666666",
                    ct_time: "192.666245",
                    children: [
                        {
                            id: 2,
                            p_name: "功能页面",
                            api1: "/44444/444",
                            api2: "/44444/4444",
                            api3: "/44/444",
                            api4: "/4444444/44444",
                            ct_user: "44444444",
                            ct_time: "444444444444444"
                        },
                        {
                            id: 3,
                            p_name: "功能页面2222",
                            api1: "/nnnnnn/nnn",
                            api2: "/nnnn/nnn",
                            api3: "/nnnnnn/nnnn",
                            api4: "/nnnnn/nnnnnn",
                            ct_user: "nnnnnnn",
                            ct_time: "nnnnnnnnnnnn",
                            children: [
                                {
                                    id: 4,
                                    p_name: "333333333333",
                                    api1: "/33333",
                                    api2: "/3333n3333nnn/nnn",
                                    api3: "/333/nnnn",
                                    api4: "/3333/nnnnnn",
                                    ct_user: "3333",
                                    ct_time: "3333333"
                                }
                            ]
                        }
                    ]
                },
                {
                    id: 16,
                    p_name: "菜单33",
                    api1: "/vvvv/vvvvv",
                    api2: "/vvv/vv",
                    api3: "/vvvv/vv",
                    api4: "/vvv/vv",
                    ct_user: "6666666",
                    ct_time: "192.666245",
                    children: [
                        {
                            id: 2,
                            p_name: "功能页面",
                            api1: "/44444/444",
                            api2: "/44444/4444",
                            api3: "/44/444",
                            api4: "/4444444/44444",
                            ct_user: "44444444",
                            ct_time: "444444444444444"
                        }
                    ]
                }
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

            return show ? "show-tr" : "hide-tr";
        },

        toggle(item) {
            item._expanded = !item._expanded;
        }
    }
};
</script>
<style lang="less" scoped>
.expend-icon {
    padding: 4px 6px;
    transition: all 0.2s;
    cursor: pointer;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.show-tr {
    animation: fadeIn 0.5s;
}

.hide-tr {
    display: none;
}

.icon-down {
    transform: rotate(90deg);
}
</style>