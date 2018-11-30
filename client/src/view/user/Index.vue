<template>
      <div>
        <el-row class="table-container">
            <div class="action-bar">
                <el-button type="primary" @click="addDialog = true">新增</el-button>
            </div>

            <el-table border stripe :data="tdata" style="width: 100%" :height="tabelHeight">
                <el-table-column prop="id" label="ID"></el-table-column>
                <el-table-column prop="uname" label="用户名"></el-table-column>
                <el-table-column prop="pesonal_name" label="姓名"></el-table-column>
                <el-table-column prop="ct_user" label="创建人"></el-table-column>
                <el-table-column prop="ct_time" label="创建时间"></el-table-column>
                <el-table-column prop="mfy_user" label="修改人"></el-table-column>
                <el-table-column prop="mfy_time" label="修改时间"></el-table-column>

				<el-table-column fixed="right" label="状态" align="center">
                    <template slot-scope="scope">
                        <i v-if="scope.row.status == 0" class="fa fa-check right"></i>
                        <i v-if="scope.row.status == 1" class="fa fa-ban ban"></i>
                    </template>
                </el-table-column>

                <el-table-column fixed="right" label="操作" align="center">
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
  components:{},
    mixins: [TabelBase],

    components: {
        Add,
        Edit
    },

    data() {
        return {         
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: 'user',

            tdata: [
                {
                    id: 1,
                    uname: '123',
                    pesonal_name: 'aaaaa',
                    status: 1,
                    ct_user: '创建人',
                    ct_time: '创建时间',
                    mfy_user: '修改人',
                    mfy_time: '修改时间'
                }
            ],
        };
    }
}
</script>
<style lang="less" scoped>
    .right{
        color: #00c100;
    }

    .ban{
        color: red;
    }

</style>