<script>
import { mapGetters } from "vuex";

export default {
    name: "Tabel",

    props: {
        bodyHeight: {
            type: Number,
            default: 400
        }
    },

    data() {
        return {
            tdata: [],
            pager: {
                total: 1,
                current: 1,
                size: 10
            },
            addDialog: false,
            editDialog: false,
            currentEditItem: {},
            search: {},
            tabelHeight: 300
        };
    },

    watch: {
        bodyHeight(newValue, oldValue) {
            this.resizeTable();
        }
    },

    created() {
        let that = this;

        if (that.apiType) {
            that.getData();
        }

        // console.log(that.$check_pm('role_permiss_set'))
    },

    mounted() {
        let that = this;

        setTimeout(() => {
            that.resizeTable();
        }, 0);
    },

    computed: {
        // tabelHeight() {
        //     let height = 300;
        //     if (this.pager) {
        //         height = this.bodyHeight - 145;
        //     } else {
        //         height = this.bodyHeight - 105;
        //     }
        //     if(this.$refs.search){
        //         height -= this.$refs.search.offsetHeight;
        //     }
        //     return height < 300? 300: height;
        // },
        //获取功能
        // ...mapGetters(["btn_act"])
    },

    methods: {
        // //检查权限
        // checkUserPermission(key){
        //     //如果btn_act不等于未定义的话就表示有权限
        //     return !!btn_act[key];
        // },

        // //获取数据以后执行
        // afterGetDate(){},

        resizeTable() {
            let that = this,
                height = that.bodyHeight;

            that.pager ? (height -= 145) : (height -= 105);

            if (that.$refs.search) {
                height -= that.$refs.search.offsetHeight;
            }

            that.tabelHeight = height;
        },

        getData(isSearch) {
            let that = this,
                params = {},
                searchData = JSON.parse(JSON.stringify(that.search));

            if (that.searchData) {
                for (var key in that.searchData) {
                    if (that.searchData[i]) {
                        params[key] = that.searchData[key];
                    }
                }

                //添加搜索天剑
                // params = { ...that.search };
            }

            if (that.pager) {
                if (isSearch) {
                    that.pager.current = 1;
                    // that.pager.total = 1;
                }

                params["pageIndex"] = that.pager.current;
                params["pageSize"] = that.pager.size;
            }

            that.$api[that.apiType]
                .get(params)
                .then(res => {
                    if (res.code == 0) {
                        if (res.data.row) {
                            that.tdata = res.data.row;
                        } else {
                            that.tdata = res.data;
                        }

                        // //获取数据以后
                        // that.afterGetDate(res.data.rows);

                        if (that.pager) {
                            that.pager.total = res.data.total || 1;
                        }
                    } else if (res.code) {
                        that.$message.error(
                            res.msg || "获取数据失败，请刷新后重试."
                        );
                    }
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请刷新后重试.");
                });
        },

        changePage(index) {
            let that = this;

            if (that.pager) {
                that.pager.current = index;
                that.getData();
            }
        },

        addItem(item) {
            let that = this;
            that.tdata.unshift(item);
        },

        showEditDialog(id) {
            let that = this;

            that.$api[that.apiType]
                .getByID({
                    id
                })
                .then(res => {
                    if (res.code == 0) {
                        that.currentEditItem = res.data;
                    } else {
                        that.$message.error(res.msg || "获取数据失败，请重试.");
                    }

                    that.editDialog = true;
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请重试.");
                });
        },

        editItem(item) {
            let that = this;

            for (var i = 0; i < that.tdata.length; i++) {
                var citem = that.tdata[i];

                if (citem.id == item.id) {
                    for (var key in item) {
                        if (key != "id") {
                            citem[key] = item[key];
                        }
                    }
                    break;
                }
            }
        },

        del(id, index) {
            let that = this;

            that.$confirm("是否删除当前记录?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    that.$api[that.apiType]
                        .del({
                            id
                        })
                        .then(res => {
                            if (res.code == 0) {
                                that.$message({
                                    message: "删除成功.",
                                    type: "success",
                                    duration: 800
                                });

                                let delItem = null;
                                for (var i = 0; i < that.tdata.length; i++) {
                                    var item = that.tdata[i];

                                    if (item.id == id) {
                                        delItem = that.tdata.splice(i, 1);
                                        break;
                                    }
                                }

                                that.afterDel(delItem);
                            } else {
                                that.$message.error(res.msg);
                            }
                        })
                        .catch(res => {
                            that.$message.error("删除失败，请刷新后重试.");
                        });
                })
                .catch(() => {});
        },

        //删除之后
        afterDel(item) {}
    }
};
</script>
