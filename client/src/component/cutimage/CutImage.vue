<template>
    <div class="cut-image">
        <div
            class="cut-image-screen"
            ref="cutImageContainer"
            :style="{
                width: `${imageWidth}`,
                height: `${imageHeight}`,                
            }"
        >
            <div class="cut-image-container">
                <div
                    class="cut-image-upload"
                    :style="{     
                        backgroundImage: imageData
                    }"
                >
                </div>

                <!-- 一定要去除默认的事件，不然卡顿 -->
                <div
                    class="mask-container"
                    :class="{ 'cir-mask': mask == 'cir' }"
                    :style="{
                        top:`${top}px`,
                        left:`${left}px`,
                        width:`${maskWidth}`,
                        height:`${maskHeight}`,
                        zIndex: `${maskZIndex}`
                    }"
                    @mousedown.stop.prevent="startMove($event)"
                    ref="maskContainer"
                ></div>
            </div>
        </div>

        <div class="cut-image-viewer">
            <div class="cut-image-viewer-text">
                预览
            </div>

            <canvas ref="canvas"></canvas>

        </div>
    </div>
</template>

<script>
import { base64ToBlob } from "@common/util";
export default {
    name: "CutImage",
    components: {},
    props: {
        //图片的长宽
        imageWidth: {
            default: "300px",
            type: String
        },

        imageHeight: {
            default: "300px",
            type: String
        },

        //截取框的类型和长宽高
        mask: {
            //cir圆形，rec矩形
            default: "cir",
            type: String
        },
        maskHeight: {
            default: "50%",
            type: String
        },
        maskWidth: {
            default: "50%",
            type: String
        },

        imageUrl: {
            type: String,
            default: ""
        },

        //注入小图canvas的class或id
        // canvasClass: {
        //     type: String,
        //     default: ""
        // },

        //设置左边默认的图片
        defaultBackImage: {
            type: String,
            default: ""
        },

        //设置默认的剪切的图像
        defaultCutImage: {
            type: String,
            default: ""
        },

        //导出图片的格式
        exportCutType: {
            type: String,
            default: "image/jpg"
        }
    },

    watch: {
        defaultBackImage(newValue, oldValue) {
            this.setDeaultBackImage();
        },

        defaultCutImage(newValue, oldValue) {
            this.setDefalutCutImage();
        },

        imageUrl(newValue, oldValue) {
            let that = this;

            if(newValue){
                that.maskZIndex = 0;
                that.setBackImage();
            }else{
                //如果清空了传入的图片，则还原成默认的
                that.setDeaultBackImage();
                that.maskZIndex = -9999;
                that.top = 0;
                that.left = 0;
            }
        },

        maskWidth(newValue, oldValue) {
            //重新计算元素
            this.calcEl();
        },

        maskHeight(newValue, oldValue) {
            //重新计算元素
            this.calcEl();
        }
    },

    data() {
        return {
            //外部的长宽
            outerWidth: 0,
            outerHeight: 0,

            //移动裁剪的
            moveWidth: 0,
            moveHeight: 0,

            //移动坐标
            top: 0,
            left: 0,

            //点击时的top和lef距离
            orginTop: 0,
            orginLeft: 0,

            //当前鼠标开始点击x,y点
            disX: 0,
            disY: 0,

            //可接受最高的移动区域
            maxMoveWidth: 0,
            maxMoveHeight: 0,

            //左侧展示的图片
            imageData: "url()",

            //当前上传的图片
            image: null,

            //没有上传图片的时候，没有截取遮罩
            maskZIndex: -9999
        };
    },

    mounted() {
        let that = this;

        that.calcEl();

        //设置默认的图片
        that.setDeaultBackImage();
        that.setDefalutCutImage();
    },

    //销毁的时候清除事件
    destroyed() {
        document.removeEventListener("mouseup", that.onMouseUp);
        document.removeEventListener("mousemove", that.onMouseMove);
    },

    methods: {

        setDeaultBackImage() {
            let that = this;

            if (that.defaultBackImage) {
                let $img = document.createElement("img");

                $img.addEventListener("error", () => {
                    //抛出事件图片加载失败
                    that.$emit("image-load-error");
                });
                $img.addEventListener("load", () => {
                    that.image = $img;

                    that.imageData = "url(" + that.defaultBackImage + ")";
                });

                $img.src = that.defaultBackImage;
            }
        },

        //设置默认的裁剪的图片
        setDefalutCutImage() {
            let that = this,
                $img = document.createElement("img");

            if (that.defaultCutImage) {
                $img.addEventListener("error", () => {
                    //抛出事件图片加载失败
                    that.$emit("default-image-load-error");
                });
                $img.addEventListener("load", () => {
                    let context = that.$refs.canvas.getContext("2d");

                    context.clearRect(0, 0, that.moveWidth, that.moveHeight);

                    //圆形
                    if (that.mask == "cir") {
                        context.arc(
                            that.moveWidth / 2,
                            that.moveHeight / 2,
                            that.moveHeight / 2,
                            0,
                            2 * Math.PI
                        );
                        context.clip();
                    }

                    //设置剪切的图片
                    context.drawImage(
                        $img,
                        0,
                        0,
                        that.moveWidth,
                        that.moveHeight
                    );
                });

                $img.src = that.defaultCutImage;
            }
        },

        //设置左侧的图片，传多个参数，是否要预览，初始化的时候使用
        setBackImage() {
            let that = this;

            if (that.imageUrl) {
                let $img = document.createElement("img");

                $img.addEventListener("error", () => {
                    //抛出事件图片加载失败
                    that.$emit("image-load-error");
                });
                $img.addEventListener("load", () => {
                    that.image = $img;

                    that.imageData = "url(" + that.imageUrl + ")";

                    that.preview();
                });

                $img.src = that.imageUrl;
            }
        },

        //计算元素高度
        calcEl() {
            let that = this,
                $refContainer = that.$refs.cutImageContainer,
                $maskContainer = that.$refs.maskContainer,
                canvas = that.$refs.canvas;

            //外层框的宽高
            that.outerWidth = $refContainer.clientWidth;
            that.outerHeight = $refContainer.clientHeight;

            //选中框的宽高
            that.moveWidth = $maskContainer.clientWidth;
            that.moveHeight = $maskContainer.clientHeight;

            //更新预览的宽高
            canvas.width = that.moveWidth;
            canvas.height = that.moveHeight;
        },

        startMove(evt) {
            let that = this;

            //调整移动的区间范围
            that.maxMoveWidth = that.outerWidth - that.moveWidth;
            that.maxMoveHeight = that.outerHeight - that.moveHeight;

            //点击鼠标点击的位置
            that.disX = evt.clientX;
            that.disY = evt.clientY;

            //记录原来的mask的位置，从这个位置开始计算左右移动距离，- 0 是为了复制值
            that.orginTop = that.top - 0;
            that.orginLeft = that.left - 0;

            // 全局监听松开事件，放在在内容选择框外松开
            document.addEventListener("mouseup", that.onMouseUp);
            document.addEventListener("mousemove", that.onMouseMove);
        },

        onMouseMove(event) {
            let that = this,
                //计算左右或上下偏移，然后加上点击的时候的位置，得出准确的偏移位置
                left = event.clientX - that.disX + that.orginLeft,
                top = event.clientY - that.disY + that.orginTop;

            that.left = left < 0 ? 0 : Math.min(left, that.maxMoveWidth);
            that.top = top < 0 ? 0 : Math.min(top, that.maxMoveHeight);

            //预览效果
            that.preview();
        },

        onMouseUp() {
            let that = this;

            document.removeEventListener("mousemove", that.onMouseMove);
            document.removeEventListener("mouseup", that.onMouseUp);
        },

        //生成canvas
        preview() {
            let that = this,
                // canvas = document.createElement("canvas"),
                // canvas = document.querySelector(that.canvasClass),
                canvas = that.$refs.canvas,
                context = canvas.getContext("2d"),
                //计算比例，然后蒙版放大或缩小
                widthScaleRate = that.outerWidth / that.image.width,
                heightScaleRate = that.outerHeight / that.image.height;

            // 初始化以处理，这里不处理
            // canvas.width = that.moveWidth;
            // canvas.height = that.moveHeight;

            //清空模板
            context.clearRect(0, 0, that.moveWidth, that.moveHeight);

            // 因为初始化的时候已经处理了圆形问题，不需要重绘制
            // //圆形
            // if (that.mask == "cir") {
            //     context.arc(
            //         that.moveWidth / 2,
            //         that.moveHeight / 2,
            //         that.moveHeight / 2,
            //         0,
            //         2 * Math.PI
            //     );
            //     context.clip();
            // }

            context.drawImage(
                that.image,
                //计算比例，然后蒙版参数放大或缩小
                Math.floor(that.left / widthScaleRate),
                Math.floor(that.top / heightScaleRate),
                Math.floor(that.moveWidth / widthScaleRate),
                Math.floor(that.moveHeight / heightScaleRate),
                0,
                0,
                that.moveWidth,
                that.moveHeight
            );
        },

        //获取base64编码裁剪的图片
        getCutImageByBase64() {
            let that = this;

            //选取最高质量
            return that.$refs.canvas.toDataURL(that.exportCutType, 1);
        },

        //获取二进制编码的裁剪的图片
        getCutImageByBlob() {
            let that = this,
                base64Image = that.getCutImageByBase64();

            return base64ToBlob(base64Image);
        },

        // //清除原来上传的图片，还原成默认
        // clearImage(){
        //     let that = this;
        //     that.setDeaultBackImage();
        //     that.top = 0;
        //     that.left = 0;
        // }
    }   
};
</script>
<style lang="less" scoped>
.cut-image {
    height: 100%;
    width: 100%;
    position: relative;

    > div {
        display: inline-block;
    }

    .cut-image-screen {
        background-color: white;
        position: relative;
        box-sizing: border-box;
        border: 1px solid #e3e3e3;

        .cut-image-container {
            position: relative;
            height: 100%;
            width: 100%;
            box-sizing: border-box;

            .cut-image-upload {
                position: absolute;
                height: 100%;
                width: 100%;
                box-sizing: border-box;
                background-size: 100% 100%;
            }

            .mask-container {
                position: absolute;
                cursor: pointer;
                box-sizing: border-box;
                background-color: #ffffff;
                opacity: 0.4;
            }

            .cir-mask {
                //圆形
                border-radius: 50%;
            }
        }
    }

    .cut-image-viewer {
        border: 1px solid #e3e3e3;
        border-radius: 6px;
        padding: 10px;
        margin-left: 30px;
        position: absolute;
        top: 50%;
        transform: translate(0, -50%);

        .cut-image-viewer-text {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
    }
}
</style>