<script>
import DialogForm from "./DialogForm";

export default {
    mixins: [DialogForm],

    data() {
        return {
            form: {},
            formRules: {},
            apiType: ""
        };
    },

    methods: {
        addCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    //提交之前
                    that.beforeAdd();

                    that.$api[that.apiType]
                        .add(that.form)
                        .then(res => {
                            if (res.code == 0) {
                                //新增成功后
                                that.afterAdd(res.data);

                                that.$emit(
                                    "add-item",
                                    JSON.parse(JSON.stringify(res.data))
                                );

                                that.$message({
                                    message: "新增成功.",
                                    type: "success",
                                    duration: 800
                                });

                                that.closeDialog();
                            } else if (res.error == 501) {
                                that.$message.error(res.msg);
                            }
                        })
                        .catch(res => {
                            that.$message.error("新增失败，请重试.");
                        });
                }
            });
        },

        beforeAdd() {},
        afterAdd(item) {}
    }
};
</script>