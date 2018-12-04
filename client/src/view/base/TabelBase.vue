<script>
export default {
    name: "Tabel",

    props: {
        bodyHeight: {
            type: Number,
            default: 300
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
        };
    },

    created() {
        let that = this;

        if (that.apiType) {
            that.getData();
        }
    },

    computed: {
        tabelHeight() {
            return this.bodyHeight - 130;
        }
    },

    methods: {
        getData() {
            let that = this;

            that.$api[that.apiType]
                .get({
                    //添加搜索条件
                    ...that.search,
                    pageIndex: that.pager.current,
                    pageSize: that.pager.size
                })
                .then(res => {
                    if (res.error == 0) {
                        that.tdata = res.data.rows;
                        that.pager.total = res.data.count || 1;
                    } else if (res.error == 505) {
                        that.$message.error("获取数据失败，请刷新后重试.");
                    }
                })
                .catch(res => {
                    that.$message.error("获取数据失败，请刷新后重试.");
                });
        },

        changePage(index) {
            let that = this;

            that.pager.current = index;

            that.getData();
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
                    if (res.error == 0) {
                        that.currentEditItem = res.data;
                    } else {
                        that.$message.error(res.msg);
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
                }
            }
        },

        del(id, index) {
            let that = this;

            that.$api[that.apiType]
                .del({
                    id
                })
                .then(res => {
                    if (res.error == 0) {
                        that.$message({
                            message: "删除成功.",
                            type: "success",
                            duration: 800
                        });

                        for (var i = 0; i < that.tdata.length; i++) {
                            var item = that.tdata[i];

                            if (item.id == id) {
                                that.tdata.splice(i, 1);
                                break;
                            }
                        }
                    } else {
                        that.$message.error(res.msg);
                    }
                })
                .catch(res => {
                    that.$message.error("删除失败，请刷新后重试.");
                });
        }
    }
};
</script>
