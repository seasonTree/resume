<template>
    <div :class="{ 'hide-arrow' :collapse}">
        <template v-for="(item, index) in menu">
            <template v-if="item.children && item.children.length">
                <el-submenu
                    :key="item.name + index + item.url"
                    :index="item.name + index + item.url"
                >
                    <template slot="title">
                        <span>
                            <span
                                class="menu-icon"
                                v-if="item.icon"
                            >
                                <i :class="item.icon"></i>
                            </span>
                            <span v-show="!collapse">
                                {{item.name}}
                            </span>
                        </span>
                    </template>

                    <menu-tree :menu="item.children"></menu-tree>
                </el-submenu>
            </template>

            <template v-if="!item.children">
                <el-menu-item
                    :key="item.name + index + item.url"
                    :index="item.url"
                >
                    <span
                        class="menu-icon"
                        v-if="item.icon"
                    >
                        <i :class="item.icon"></i>
                    </span>

                    <span v-show="!collapse">
                        {{item.name}}
                    </span>
                </el-menu-item>
            </template>
        </template>
    </div>
</template>

<script>
export default {
    name: "MenuTree",
    props: {
        menu: {
            type: Array,
            default: []
        },
        collapse: {
            type: Boolean,
            default: false
        }
    }
};
</script>
<style lang='less' scoped>
.menu-icon {
    display: inline-block;
    width: 28px;
    margin-top: -2px;
    margin-right: 14px;
    // margin-left: 6px;
}
</style>