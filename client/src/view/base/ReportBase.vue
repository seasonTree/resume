<script>
export default {
    name: "ReportBase",

    props: {
        bodyHeight: {
            type: Number,
            default: 400
        }
    },

    data() {
        return {
            tdata: [],
            reportData: [],
            pager: {
                total: 0,
                current: 1,
                size: 10
            },
            search: {},
            tabelHeight: 300,

            tableLoading: false
        };
    },

    computed: {},

    watch: {
        bodyHeight(newValue, oldValue) {
            this.resizeTable();
        }
    },

    mounted() {
        let that = this;

        setTimeout(() => {
            that.resizeTable();
        }, 0);
    },

    methods: {
        resizeTable() {
            let that = this,
                height = that.bodyHeight - 148;

            if (that.$refs.search) {
                height -= that.$refs.search.offsetHeight;
            }

            that.tabelHeight = height;
        },

        getData() {
            let that = this,
                params = {};

            if (that.search) {
                //添加搜索条件
                params = { ...that.search };
            }

            that.tableLoading = true;

            that.pager.current = 1;
            // that.pager.total = 1;

            that.$api[that.apiType]
                .get(params)
                .then(res => {
                    if (res.code == 0) {
                        that.reportData = res.data;

                        if (that.pager) {
                            that.pager.total = that.reportData.length;

                            that.tdata = that.reportData.slice(
                                0,
                                that.pager.size
                            );
                        }
                    } else {
                        that.$message.error(
                            res.message || "获取数据失败，请刷新后重试."
                        );
                    }

                    that.tableLoading = false;
                })
                .catch(res => {
                    that.tableLoading = false;
                    that.$message.error("获取数据失败，请刷新后重试.");
                });
        },

        changePage(index) {
            let that = this;

            if (that.pager) {
                that.pager.current = index;

                //更新表格的内容
                that.tdata = that.reportData.slice(
                    that.pager.size * (index - 1),
                    that.pager.size * (index - 1) + that.pager.size
                );
            }
        },

        pageSizeChange(val) {
            let that = this;

            if (that.pager) {
                that.pager.size = val;

                if(that.tdata.length){
                    that.changePage(1);
                }
            }
        }
    }
};
</script>
