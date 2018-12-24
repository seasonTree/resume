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
                    that.beforeAdd(that.form);
                    //  console.log(that.form);
               
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
                            } else {
                                that.$message.error(res.msg || '新增失败，请重试.');
                            }
                        })
                        .catch(res => {
                            that.$message.error("新增失败，请重试.");
                        });
                }
            });
        },

        beforeAdd(item) {},
        afterAdd(item) {}
    }
};
</script>