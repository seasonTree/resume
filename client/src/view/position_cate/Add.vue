<template>
  <el-dialog
    title="新增职位"
    :visible.sync="show"
    :before-close="closeDialog"
    class="custom-dialog"
    :close-on-click-modal="false"
    width="500px"
    v-dialog-drag
  >
    <el-form :model="form" :rules="formRules" label-width="80px" ref="form">
      <el-form-item label="职位名称" prop="job_name">
        <el-input v-model.trim="form.job_name" autocomplete="off"></el-input>
      </el-form-item>
    </el-form>

    <div slot="footer" class="dialog-footer">
      <el-button @click="closeDialog" :disabled="commitLoading">取 消</el-button>
      <el-button
        type="primary"
        @click="addCommit"
        :loading="commitLoading"
        :disabled="!$check_pm('position_cate_add')"
      >确 定</el-button>
    </div>
  </el-dialog>
</template>

<script>
import AddDialogForm from "@view/base/AddDialogForm";
export default {
  name: "Add",
  mixins: [AddDialogForm],
  data() {
    return {
      apiType: "position_cate",

      form: {
        job_name: ""
      },

      formRules: {
        job_name: [
          {
            required: true,
            message: "请输入职位名称",
            trigger: "blur"
          }
        ]
      }
    };
  }
};
</script>
<style lang="less" scoped>
</style>