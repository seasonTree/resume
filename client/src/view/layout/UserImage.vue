<template>
    <el-dialog
        title="修改头像"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        v-dialog-drag
        width="550px"
    >
        <!-- 
            如果选择了rec正方形，则填写 previewHeight， previewWidth
            如果选择了cir圆形，则填写 previewDiameter 直径
        -->
        <div class="mb-20">
            <cut-image
                mask="cir"
                :imageWidth="300"
                :imageHeight="300"
                :diameter='150'
                :imageUrl="image"
                defaultBackImage="./image/empty.png"
                :defaultCutImage="userImage"
                ref="cutImage"
            ></cut-image>
        </div>

        <div
            slot="footer"
            class="dialog-footer"
        >
            <el-upload
                action="/"
                :show-file-list="false"
                :on-change="fileChange"
                accept="image/*"
                :auto-upload="false"
            >
                <el-button type="success">上传新图片</el-button>
            </el-upload>

            <el-button @click="closeDialog">取 消</el-button>
            <el-button
                :loading="commitLoading"
                type="primary"
                @click="uploadUserImage"
            >确 定</el-button>
        </div>

    </el-dialog>
</template>

<script>
import DialogForm from "../base/DialogForm";
import CutImage from "@component/cutimage/CutImage";
export default {
    name: "UserImage",
    mixins: [DialogForm],

    props: {
        avatar: {
            type: String,
            required: true
        }
    },

    components: {
        CutImage
    },

    watch: {
        show(newValue, oldValue) {
            if (newValue) {
                let that = this;
                that.setImage();
            }
        }
    },

    data() {
        return {
            image: "",
            userImage: ""
        };
    },

    methods: {
        //设置头像
        setImage() {
            let that = this;
            that.userImage = that.avatar;
        },

        fileChange(file, fileList) {
            let that = this,
                reader = new FileReader();

            //base64读取
            reader.readAsDataURL(file.raw);
            reader.onload = function(e) {
                that.image = this.result;
            };
        },

        //更新用户的头像
        uploadUserImage() {
            let that = this,
                avatar = that.$refs.cutImage.getCutImageByBase64(),
                data = { avatar };

            that.$api.user
                .updateUserAvatar(data)
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "更新头像成功.",
                            type: "success",
                            duration: 800
                        });

                        that.$store.commit("updateAvatar", res.data);

                        that.closeDialog();
                    } else {
                        that.$message.error(res.message);
                    }

                    that.commitLoading = false;
                })
                .catch(res => {
                    that.$message.error("修改失败，请重试.");
                    that.commitLoading = false;
                });
        },

        //关闭后清除工作
        afterClose() {
            let that = this;
            that.image = "";
            that.userImage = "";
        }
    }
};
</script>
<style lang="less" scoped>
</style>