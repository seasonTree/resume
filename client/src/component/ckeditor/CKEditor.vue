<template>
    <textarea :id="id"></textarea>
</template>

<script>
import { guid } from "@common/util";
import CKEDITOR from "CKEDITOR";

export default {
    name: "ck-editor",

    props: {
        language: {
            type: String,
            default: "zh-cn"
        },
        height: {
            type: String,
            default: "300px"
        },
        content: {
            type: String,
            default: ""
        },
        disabled: {
            type: Boolean,
            default: false
        },
        imageUploadUrl: {
            type: String,
            default: ""
        },
        imgUploadDataFile: {
            type: String,
            default: ""
        }
    },
    
    watch: {
        content(newValue, oldValue) {
            if (this.editor) {
                this.editor.setData(newValue);
            }
        }
    },

    data() {
        return {
            id: guid(false).substr(0, 6),
            editor: null
        };
    },

    mounted() {
        let that = this,
            language = this.language;

        switch (language) {
            case "zh_hk":
                language = "zh";
                break;
            case "en_us":
                language = "en";
                break;
            default:
                language = "zh_cn";
                break;
        }

        let toolbar = [
            {
                name: "clipboard",
                items: ["Undo", "Redo"]
            },
            {
                name: "basicstyles",
                items: [
                    "Bold",
                    "Italic",
                    "Underline",
                    "Strike",
                    "Subscript",
                    "Superscript",
                    "-",
                    "CopyFormatting",
                    "RemoveFormat"
                ]
            },
            {
                name: "paragraph",
                items: [
                    "NumberedList",
                    "BulletedList",
                    "-",
                    "Outdent",
                    "Indent",
                    "-",
                    "Blockquote",
                    "-",
                    "JustifyLeft",
                    "JustifyCenter",
                    "JustifyRight",
                    "JustifyBlock"
                ]
            },
            { name: "links", items: ["Link", "Unlink"] },
            {
                name: "insert",
                items: ["Image", "Table", "HorizontalRule", "SpecialChar"]
            },
            { name: "styles", items: ["Styles", "Format", "Font", "FontSize"] },
            { name: "colors", items: ["TextColor", "BGColor"] },
            { name: "tools", items: ["Maximize"] }
        ];

        that.editor = CKEDITOR.replace(that.id, {
            language: language,
            toolbar: toolbar,
            height: that.height,
            width: "100%",
            customConfig: "",
            removeDialogTabs: "image:advanced;link:advanced",
            readOnly: that.disabled,
            image_previewText: "",
            filebrowserImageUploadUrl: that.imageUploadUrl,
            imgUploadDataFile: that.imgUploadDataFile.split(".")

            // // Set the most common block elements.
            // format_tags: 'p;h1;h2;h3;pre',
            // // 将段落容器改为div
            // enterMode: CKEDITOR.ENTER_DIV,
            // forceEnterMode: true
        });

        that.editor.setData(that.content);
    },

    methods: {
        getInstance() {
            return this.editor;
        },

        getContent() {
            return this.editor.getData();
        },

        clearContent(){
            this.editor.setData('');
        }
    }
};
</script>
<style lang='less' scoped>
</style>