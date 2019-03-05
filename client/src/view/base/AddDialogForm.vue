<script>
import DialogForm from "./DialogForm";

export default {
    mixins: [DialogForm],

    data() {
        return {
            form: {},
            formRules: {},
            apiType: "",

            addMethod: "add"
        };
    },

    methods: {
        addCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.commitLoading = true;

                    //提交之前
                    that.beforeAdd(that.form);
                    //  console.log(that.form);

                    that.$api[that.apiType]
                        [that.addMethod](that.form)
                        .then(res => {
                            if (res.code == 200) {
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
                            } else {
                                that.$message.error(
                                    res.message ||
                                        "新增失败，请重试."
                                );
                            }

                            that.commitLoading = false;
                        })
                        .catch(res => {
                            that.commitLoading = false;
                            that.$message.error(
                                "新增失败，请重试."
                            );
                        });
                }
            });
        },

        beforeAdd(item) {},
        afterAdd(item) {}
    }
};
</script>