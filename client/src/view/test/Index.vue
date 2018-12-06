<template>
    <div>
        <el-row class="table-container">
            <div class="action-bar">
                <el-button type="primary" @click="addDialog = true">新增</el-button>
            </div>

            <el-table border stripe :data="tdata" style="width: 100%" :height="tabelHeight">
                <el-table-column prop="ty_name" label="类型">
                </el-table-column>
                <el-table-column fixed="right" label="操作">
                    <template slot-scope="scope">
                        <el-button type="primary" size="mini" icon="el-icon-edit" circle @click="showEditDialog(scope.row.id)"></el-button>
                        <el-button type="danger" size="mini" icon="el-icon-delete" circle @click="del(scope.row.id, scope.$index)"></el-button>
                    </template>
                </el-table-column>
            </el-table>
        </el-row>

        <el-row class="pager">
            <el-pagination @current-change="changePage" background layout="prev, pager, next" :page-size="pager.size" :total="pager.total" :current-page="pager.current">
            </el-pagination>
        </el-row>

        <add :show.sync="addDialog" @add-item="addItem"></add>

        <edit :show.sync="editDialog" :edit-item="currentEditItem" @edit-item="editItem" ></edit>
    </div>
</template>

<script>
import TabelBase from "@view/base/TabelBase";
import Add from "./Add";
import Edit from "./Edit";

export default {
    name: "type",
    mixins: [TabelBase],

    components: {
        Add,
        Edit
    },

    data() {
        return {         
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: 'type',
        };
    }
};
</script>
<style lang='less' scoped>
</style>