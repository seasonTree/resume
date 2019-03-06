<script>
import DialogForm from "./DialogForm";
import { deepClone } from "@common/util";
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
            form: {},
            formRules: {},
            apiType: "",

            editMethod: "edit"
        };
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this,
                    newItem = deepClone(that.editItem);

                that.beforeSetData(newItem);

                //复制数值
                for (var key in newItem) {
                    that.form[key] = newItem[key];
                }
            }
        }
    },

    methods: {
        //在设置数据之前操作
        beforeSetData() {},

        editCommit() {
            let that = this;

            that.$refs["form"].validate(valid => {
                if (valid) {
                    that.commitLoading = true;

                    //提交之前
                    that.beforeEdit(that.form);

                    that.$api[that.apiType]
                        [that.editMethod](that.form)
                        .then(res => {
                            if (res.code == 0) {
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
                            } else {
                                that.$message.error(
                                    res.message ||
                                        "修改失败，请重试."
                                );
                            }

                            that.commitLoading = false;
                        })
                        .catch(res => {
                            that.$message.error(
                                "修改失败，请重试."
                            );

                            that.commitLoading = false;
                        });
                }
            });
        },

        beforeEdit(item) {},
        afterEdit(item) {}
    }
};
</script>