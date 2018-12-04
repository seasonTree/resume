<template>

    <div>
        <el-row class="table-container">
            <div class="action-bar">
                <el-button
                    type="primary"
                    @click="addDialog = true"
                >新增简历</el-button>
                
            </div>

            <el-table
                :data="tdata"
                border
                style="width: 100%"
            >
                <el-table-column
                    fixed
                    prop="name"
                    label="姓名"
                    width="100"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="sex"
                    label="性别"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="age"
                    label="年龄"
                    width="50"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="phone"
                    label="手机"
                    width="150"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="edu"
                    label="学历"
                    width="100"
                >
                </el-table-column>
                <el-table-column
                    fixed
                    prop="workAge"
                    label="工作年限"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="objective"
                    label="求职意向"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="email"
                    label="电子邮箱"
                    width="200"
                >
                </el-table-column>
                <el-table-column
                    prop="expectPay"
                    label="期望薪水"
                    width="150"
                >
                </el-table-column>
                <el-table-column
                    prop="inPosition"
                    label="所处职位"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="recentlyUnit"
                    label="最近单位"
                    width="200"
                >
                </el-table-column>
                <el-table-column
                    prop="recentPosition"
                    label="最近职位"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="graduateSchool"
                    label="毕业院校"
                    width="300"
                >
                </el-table-column>
                <el-table-column
                    prop="professional"
                    label="专业"
                    width="150"
                >
                </el-table-column>

                <el-table-column
                    prop="englishLevel"
                    label="英语等级"
                    width="120"
                >
                </el-table-column>
                <el-table-column
                    prop="workingPlace"
                    label="工作地点"
                    width="200"
                >
                </el-table-column>
                <el-table-column
                    fixed="right"
                    label="操作"
                    width="100"
                >
                    <template slot-scope="scope">
                        <el-button
                            @click="handleClick(scope.row)"
                            type="text"
                            size="small"
                        >查看</el-button>
                        <el-button
                            type="text"
                            size="small"
                        >编辑</el-button>
                    </template>
                </el-table-column>
            </el-table>

        </el-row>

		<add
            :show.sync="addDialog"
            @add-item="addItem"
        ></add>
    </div>
</template>


<script>
import Add from "./Add";
import TabelBase from "@view/base/TabelBase";
export default {
	mixins: [TabelBase],
    components: {
        Add
    },
    props: {},

    created() {},
    mounted() {},
    watch: {},
    computed: {},
    methods: {
        handleClick(row) {
            console.log(row);
        }
    },

    data() {
        return {
            tdata: [
                {
                    name: "王小虎",
                    inPosition: "程序员",
                    sex: "男",
                    age: "11",
                    edu: "本科",
                    workAge: "三年",
                    objective: "Java工程师",
                    expectPay: "18k",
                    recentlyUnit: "中软",
                    recentPosition: "java工程师",
                    graduateSchool: "北京大学",
                    professional: "计算机",
                    phone: "13923819974",
                    email: "775803639@qq.com",
                    englishLevel: "四级",
                    workingPlace: "深圳"
                }
            ]
        };
    }
};
</script>
<style lang="less" scoped>
</style>