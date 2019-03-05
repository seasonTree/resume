<script>
import { formatDate } from "@common/util";
export default {
    name: "Utils",

    methods: {
        //格式化yyyy-MM-dd
        formatterDate(row, column, cellValue, index) {
            return formatDate(cellValue, "yyyy-MM-dd");
        },

        //格式化yyyy-MM-dd hh:mm:ss
        formatterDateTime(row, column, cellValue, index) {
            return formatDate(cellValue, "yyyy-MM-dd hh:mm:ss");
        }
    }
};
</script>