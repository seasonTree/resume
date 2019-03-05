<script>
import { formatDate } from "@common/util";
import Utils from "./Utils";
export default {

    mixins: [Utils],

    props: {
        show: {
            type: Boolean,
            default: false
        }
    },    

    data() {
        return {
            commitLoading: false
        };
    },

    methods: {
        closeDialog() {
            let that = this;
            that.$emit("update:show", false);
            setTimeout(() => {
                that.$refs["form"] && that.$refs["form"].resetFields();

                //允许用户自定义关闭窗口后的动作
                that.afterClose();
            }, 200);
        },

        //关闭窗口后调用
        afterClose() {},
    }
};
</script>
