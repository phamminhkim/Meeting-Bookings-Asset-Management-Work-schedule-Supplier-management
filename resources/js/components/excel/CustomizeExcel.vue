<template>
    <div>
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-9">
                <div class="row">
                    <b-button class="col-md-4" variant="success" @click.prevent="importData">Nhập dữ liệu</b-button>
                    <b-dropdown class="col-md-4" variant="warning" text="Xuất dữ liệu">
                        <b-dropdown-item @click.prevent="exportDataAsFile('xslx', false)">Xuất toàn bộ (.xlsx)
                        </b-dropdown-item>
                        <b-dropdown-item @click.prevent="exportDataAsFile('csv', false)">Xuất toàn bộ (.csv)
                        </b-dropdown-item>
                        <b-dropdown-divider></b-dropdown-divider>
                        <b-dropdown-item :disabled="selectedData.length == 0"
                            @click.prevent="exportDataAsFile('xslx', true)">Xuất dữ liệu đã chọn (.xlsx)
                        </b-dropdown-item>
                        <b-dropdown-item :disabled="selectedData.length == 0"
                            @click.prevent="exportDataAsFile('csv', true)">Xuất dữ liệu đã chọn (.csv)
                        </b-dropdown-item>
                    </b-dropdown>
                    <b-button class="col-md-4" variant="danger" @click.prevent="deleteAllData">Xóa dữ liệu</b-button>
                </div>
                <div class="row">
                    <vue-excel-editor ref="dataTable" v-model="tableData" :disable-panel-setting="true"
                        :disable-panel-filter="true" :no-footer="true" :cellStyle="updateCellStyle" @select="onSelect"
                        @update="onUpdate" @cell-focus="onCellFocus" @validate-error="onValidateError">
                        <vue-excel-column v-for="(column, findex) in columnData" v-bind:key="findex"
                            :field="column.field" :label="column.label" :type="column.type" :width="column.width"
                            :options="column.options" :validate="validationFilter"
                            :mandatory="column.required ? 'Trường này không thể bỏ trống' : null" />

                    </vue-excel-editor>
                </div>
            </div>
        </div>



    </div>

</template>

<script>
import VueExcelEditor from 'vue-excel-editor';

export default {
    props: {
        datas: Array,
    },
    methods: {
        exportDataAsFile(extension, selectedOnly) {
            const filename = 'Exported data';
            this.$refs.dataTable.exportTable(extension, selectedOnly, filename);
        },
        importData() {
            this.$refs.dataTable.importTable((callback) => {
                console.log(callback);
            },
                (error) => {

                });
        },
        deleteAllData() {
            this.tableData = [];
        },
        onSelect(selectedRows, isSelect) {
            if (isSelect) {
                console.log('Select: ');
            }
            else {
                console.log('Deselect: ');

            }
            console.log(selectedRows);
            //this.selectedData = this.$refs.dataTable.getSelectedRecords();

        },
        onUpdate(records) {
            for (let i = 0; i < records.length; i++) {
                console.log(records[i]);
            }
        },
        onCellFocus(object) {
            this.focusingData = object;
            //console.log('Focus: ');
            //console.log(object);
        },
        onValidateError(error, row, field) {
            //console.log('Error: ' + error);
            //console.log(row);
            //console.log(field);
        },
        updateCellStyle(obj, obj2) {
            //console.log(obj);
            //console.log(obj2);
            //return { textAlign: obj.name == 'Admin User' ? 'center' : 'right' };
            return {};
        },
        validationFilter(content, oldContent, record, field) {
            var columnSetting = this.columnData.find(column => {
                return column.field === field.name
            })
            console.log(columnSetting);
            if (columnSetting) {
                //var re = new RegExp(columnSetting.regexPattern, 'g');
                //if (!re.test(content)) return columnSetting.regexMessage;
            }
            return ''; // return empty string if there is no error
        },
    },
    data() {
        return {
            tableData: this.datas,
            selectedData: [],
            focusingData: null,
            columnData: [
                { field: 'username', label: 'User ID', type: 'string', width: "80px" },
                { field: 'name', label: 'Tên nhân viên', type: 'string', width: "160px" },
                {
                    field: 'email',
                    label: 'Email',
                    type: 'string',
                    width: "200px",
                    required: true,
                    regexPattern: "^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$",
                    regexMessage: 'Email không hợp lệ',
                },
                {
                    field: 'gender', label: 'Giới tính', type: 'map',
                    options: { '1': 'Nam', '0': 'Nữ' },
                },
                {
                    field: 'active', label: 'Hoạt động', type: 'map',
                    options: { '1': 'Đang hoạt động', '0': 'Ngưng hoạt động' },

                }
            ]
        };
    },
    watch: {
        datas() {
            this.tableData = this.datas;
        }
    },
}
</script>
