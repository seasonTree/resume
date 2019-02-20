<template>
  <div>
    <div>
      <el-row class="table-container">
        <div class="action-bar">
          <el-row type="flex" justify="space-around" :gutter="20">
            <el-col :span="6">
              <el-button
                type="primary"
                @click="addDialog = true"
                :disabled="!$check_pm('role_add')"
              >新增</el-button>
            </el-col>
            <el-col :span="18">
              <el-row type="flex" justify="end">
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
          v-loading="tableLoading"
        >
          <el-table-column prop="id" label="ID"></el-table-column>
          <el-table-column prop="job_name" label="职位名称"></el-table-column>
          <el-table-column prop="ct_user" label="创建人"></el-table-column>
          <el-table-column prop="ct_time" label="创建时间"></el-table-column>

          <el-table-column fixed="right" label="操作" align="center">
            <template slot-scope="scope">
              <el-tooltip effect="dark" content="修改" placement="top">
                <el-button
                  type="primary"
                  size="mini"
                  icon="el-icon-edit"
                  circle
                  @click="showEditDialog(scope.row.id)"
                  :disabled="!$check_pm('role_edit')"
                ></el-button>
              </el-tooltip>
              <el-tooltip effect="dark" content="删除" placement="bottom">
                <el-button
                  type="danger"
                  size="mini"
                  icon="el-icon-delete"
                  circle
                  @click="del(scope.row.id, scope.$index)"
                  :disabled="!$check_pm('role_del')"
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
        ></el-pagination>
      </el-row>

      <add :show.sync="addDialog" @add-item="addItem"></add>

      <edit :show.sync="editDialog" :edit-item="currentEditItem" @edit-item="editItem"></edit>
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
    Edit,
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
      ],
    };
  },
  methods: {
  }
};
</script>
<style lang="less" scoped>
</style>