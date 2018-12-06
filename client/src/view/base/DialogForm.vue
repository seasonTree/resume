<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false
        }
    },

    methods: {
        closeDialog() {
            let that = this;
            that.$emit("update:show", false);
            setTimeout(() => {
                that.$refs['form'] && that.$refs["form"].resetFields();

                //允许用户自定义关闭窗口后的动作
                that.afterClose();
            }, 200);
        },

        //关闭窗口后调用
        afterClose() {}
    }
};
</script>
