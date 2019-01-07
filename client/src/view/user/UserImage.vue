<template>
    <el-dialog
        title="修改用户头像"
        :visible.sync="show"
        :before-close="closeDialog"
        class="custom-dialog"
        :close-on-click-modal="false"
        v-dialog-drag
        width="550px"
    >
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
import { mapGetters } from "vuex";
export default {
    name: "UserImage",
    mixins: [DialogForm],

    props: {
        //用户的id
        id: {
            type: Number,
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

    computed: {
        ...mapGetters(["userInfo"])
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

            that.$api.user
                .getByID({
                    id: that.id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.userImage = res.data.avatar
                            ? res.data.avatar  + '?d=' + Date.now()
                            : "./image/user_image.jpg";
                    } else {
                        that.$message.error(
                            res.msg || "获取用户头像失败，请重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请重试.");
                });
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
                data = { id: that.id, avatar };

            that.$api.user
                .changeUserAvatar(data)
                .then(res => {
                    if (res.code == 0) {
                        that.$message({
                            message: "更新头像成功.",
                            type: "success",
                            duration: 800
                        });

                        if (that.id == that.userInfo.id) {
                            that.$store.commit("updateAvatar", res.data);
                        }

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