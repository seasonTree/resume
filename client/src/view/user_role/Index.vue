<template>
    <div>
        <tree-grid></tree-grid>
    </div>
</template>

<script>
import TabelBase from "@view/base/TabelBase";
import TreeGrid from '@component/treegrid/TreeGrid';
import Add from "./Add";
import Edit from "./Edit";
export default {
    mixins: [TabelBase],

    components: {
        Add,
        Edit,
        TreeGrid
    },
    data() {
        return {
            //填写API获取的类型，由父类自动调用，不填不调用
            apiType: "user",
            search: {
                name: ""
            },

            tdata: [
                {
                    id: 1,
                    role_name: "123",
                    ct_user: "创建人",
                    ct_time: "创建时间",
                    mfy_user: "修改人",
                    mfy_time: "修改时间"
                }
            ]
        };
    },
    methods: {
        setRolePremission(id){

        }

    }
};
</script>
<style lang="less" scoped>
</style>