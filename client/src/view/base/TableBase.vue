<script>
import { mapGetters } from "vuex";
import Utils from "./Utils";

export default {
    name: "Table",

    mixins: [Utils],

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
                total: 0,
                current: 1,
                size: 10
            },
            addDialog: false,
            editDialog: false,
            currentEditItem: {},
            search: {},
            tabelHeight: 300,

            //是不是要重新绘制表格的高度，弹出窗使用
            useResizeTable: true,

            //apiType
            apiType: "",

            //获取数据的方法
            getDataMethod: "get",

            //根据id获取行数据的方法
            getByIDMethod: "getByID",

            //删除当前行数据的默认方法
            delMethod: "del",

            //初始化的时候是否自动获取数据，弹出窗使用
            initGetData: true,

            tableLoading: false,

            //删除后自动重新获取数据
            afterDelAutoRefresh: false,
            //删除自动更新并跳到第一页
            afterDelAutoRefreshToFirstPage: false,

            //根据id获取行记录的field
            getByIDField: "id",
            //根据那个字段的删除记录
            delIDField: "id",

            //单列头排序
            singleSortData: "",

            //---------------------------
            //是否启动多列排序
            multiSort: false,
            //排序的对象, 指向下面数组的下标 { 'aaa': 1}
            sortObject: {},
            //排序的数组 { field: '', order: '' }
            sortArr: [],
            //是否有class
            headerSortClassName: {}
            //---------------------------
        };
    },

    watch: {
        bodyHeight(newValue, oldValue) {
            this.resizeTable();
        }
    },

    created() {
        let that = this;

        if (that.initGetData && that.apiType) {
            that.getData();
        }

        // console.log(that.$check_pm('role_permiss_set'))
    },

    mounted() {
        let that = this;

        if (that.useResizeTable) {
            setTimeout(() => {
                that.resizeTable();
            }, 0);
        }
    },

    computed: {
    },

    methods: {

        resizeTable() {
            let that = this,
                height = that.bodyHeight;

            that.pager ? (height -= 148) : (height -= 108);

            if (that.$refs.search) {
                height -= that.$refs.search.offsetHeight;
            }

            that.tabelHeight = height;
        },

        //获取数据之前
        beforeGetData(isSearch) {},

        getData(isSearch) {
            let that = this,
                params = {},
                searchData = JSON.parse(JSON.stringify(that.search));

            if (searchData) {
                for (var key in searchData) {
                    if (
                        searchData[key] !== null &&
                        searchData[key] !== undefined &&
                        searchData[key] !== ""
                    ) {
                        params[key] = searchData[key];
                    }
                }

            }

            if (that.pager) {
                if (isSearch) {
                    that.pager.current = 1;
                    // that.pager.total = 1;
                }

                params["pageIndex"] = that.pager.current;
                params["pageSize"] = that.pager.size;
            }

            //是否多列排序
            if (that.multiSort) {
                let st = [];
                for (var i = 0; i < that.sortArr.length; i++) {
                    var item = that.sortArr[i];
                    st.push(item.field + " " + item.order);
                }

                params["_sort"] = st.join(",");
            } else {
                //单列排序
                if (that.singleSortData) {
                    params["_sort"] = that.singleSortData;
                }
            }

            that.beforeGetData(isSearch, params);

            if (that.apiType) {
                that.tableLoading = true;
                that.$api[that.apiType]
                    [that.getDataMethod](params)
                    .then(res => {
                        if (res.code == 0) {
                            if (res.data.row) {
                                that.tdata = res.data.row;
                            } else {
                                that.tdata = res.data;
                            }

                            if (that.pager) {

                                that.$set(
                                    that.pager,
                                    "total",
                                    res.data.total || 0
                                );
                            }
                        } else {
                            that.$message.error(
                                res.msg ||
                                    "获取数据失败，请重试."
                            );
                        }

                        that.tableLoading = false;
                    })
                    .catch(res => {
                        that.tableLoading = false;
                        that.$message.error(
                            "获取数据失败，请重试."
                        );
                    });
            }
        },

        changePage(index) {
            let that = this;

            if (that.pager) {
                that.pager.current = index;
                that.getData();
            }
        },

        pageSizeChange(val) {
            let that = this;

            if (that.pager) {
                that.pager.size = val;
                that.getData();
            }
        },

        addItem(item) {
            let that = this;
            that.tdata.unshift(item);
        },

        showEditDialog(row) {
            let that = this,
                params = {
                    //根据定义getbyid的field来获取数据
                    [that.getByIDField]: row[that.getByIDField]
                };

            if (that.apiType) {
                that.$api[that.apiType]
                    [that.getByIDMethod](params)
                    .then(res => {
                        if (res.code == 0) {
                            that.currentEditItem = res.data;
                            that.editDialog = true;
                        } else {
                            that.$message.error(
                                res.msg ||
                                    "获取数据失败，请重试."
                            );
                        }
                    })
                    .catch(res => {
                        that.$message.error(
                            "获取数据失败，请重试."
                        );
                    });
            } else {
                that.editDialog = true;
            }
        },

        editItem(item) {
            let that = this;

            //debugger

            for (var i = 0; i < that.tdata.length; i++) {
                var citem = that.tdata[i];

                if (citem[that.getByIDField] == item[that.getByIDField]) {
                    for (var key in item) {
                        if (key != that.getByIDField) {
                            citem[key] = item[key];
                        }
                    }
                    break;
                }
            }
        },

        del(row, index) {
            let that = this,
                params = {
                    //根据定义delIDField的field来获取数据
                    [that.delIDField]: row[that.delIDField]
                };

            that.$confirm("是否删除当前记录?", "提示", {
                confirmButtonText: "确定",
                cancelButtonText: "取消",
                type: "warning"
            })
                .then(() => {
                    that.$api[that.apiType]
                        [that.delMethod](params)
                        .then(res => {
                            if (res.code == 0) {
                                that.$message({
                                    message: "删除成功.",
                                    type: "success",
                                    duration: 800
                                });

                                //删除后刷新界面
                                //或者当页的全部记录都删除后重新刷新界面
                                if (
                                    that.afterDelAutoRefresh ||
                                    that.tdata.length == 0
                                ) {
                                    that.getData(
                                        that.afterDelAutoRefreshToFirstPage
                                    );
                                } else {
                                    let delItem = null;
                                    for (
                                        var i = 0;
                                        i < that.tdata.length;
                                        i++
                                    ) {
                                        var item = that.tdata[i];

                                        if (item.id == row.id) {
                                            delItem = that.tdata.splice(i, 1);
                                            break;
                                        }
                                    }
                                    that.afterDel(delItem);
                                }
                            } else {
                                that.$message.error(res.msg);
                            }
                        })
                        .catch(res => {
                            that.$message.error(
                                "删除失败，请刷新后重试."
                            );
                        });
                })
                .catch(() => {});
        },

        //删除之后
        afterDel(item) {},

        //单列排序------------------------
        singleSort({ column, prop, order }) {
            let that = this;

            if (prop) {
                that.singleSortData = prop + " " + order.replace("ending", "");
            } else {
                that.singleSortData = "";
            }

            that.getData(true);
        },

        //-------------------------------

        //多列排序------------------------------------

        // 多列排序
        // table 标签加入下面
        // @header-click="tableSort"
        // :header-cell-class-name="sortHeaderCell"

        //columns的要添加 sortable="custom"
        //排序的样式
        sortHeaderCell({ row, column, rowIndex, columnIndex }) {
            return this.headerSortClassName[column.property] || "";
        },

        tableSort(column, event) {
            let that = this;

            //阻止默认事件
            event.preventDefault();
            //停止冒泡
            event.stopPropagation();

            if (column.sortable == "custom") {
                let field = column.property,
                    //找到定义排序数组的下表
                    arrIndex = that.sortObject[field],
                    //找到定义排序数组的记录
                    sortItem = that.sortArr[arrIndex],
                    order = (sortItem || {})["order"];

                //asc, desc, null方式轮转
                order =
                    order == "asc" ? "desc" : order == "desc" ? null : "asc";

                //如果记录不存在表示是排序记录不存在的
                if (sortItem === undefined) {
                    //如果要排序的就记录排序的顺序
                    that.sortArr.push({ field: field, order });
                    that.sortObject[field] = that.sortArr.length - 1;

                    //如果记录已经存在，并且存在排序，则修改
                } else if (sortItem && order) {
                    //如果已经排序则按回原来的顺序
                    sortItem["order"] = order;

                    //order记录已经存在了，但是不需要排序了，就删除
                } else if (order == null) {
                    //如果不需要排序了，则移除那条记录
                    delete that.sortObject[field];
                    that.sortArr.splice(arrIndex, 1);
                }

                that.getData();

                //注入每个头部附加的class
                that.headerSortClassName[field] = "active-" + (order || "none");
            }
        }

        //--------------------------------------------------
    }
};
</script>
