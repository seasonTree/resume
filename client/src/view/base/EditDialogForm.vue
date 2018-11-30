<script>
import DialogForm from "./DialogForm";
export default {
    mixins: [DialogForm],

    props: {
        editItem: {
            type: Object,
            default: {}
        }
    },

    data() {
        return {
            apiType: ""
        };
    },

    methods: {
        editCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    //提交之前
                    that.beforeEdit();

                    that.$api[that.apiType]
                        .edit(that.form)
                        .then(res => {
                            if (res.error == 0) {
                                //修改成功后
                                that.afterEdit(res.data);

                                that.$emit(
                                    "edit-item",
                                    JSON.parse(JSON.stringify(res.data))
                                );

                                that.$message({
                                    message: "修改成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.closeDialog();
                            } else if (res.error == 500 || res.error == 507) {
                                that.$message.error(res.message);
                            }
                        })
                        .catch(res => {
                            that.$message.error("修改失败，请重试.");
                        });
                }
            });
        },

        beforeEdit() {},
        afterEdit(item) {}
    }
};
</script>