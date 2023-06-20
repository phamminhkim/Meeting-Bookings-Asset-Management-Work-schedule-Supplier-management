<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h5 class="m-0 text-dark"> {{ $t(title) }}</h5>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row" style="background-color:#F4F6F9">
                            <div class="col-md-9">
                                <download-excel class="btn btn-info btn-sm ml-1 mt-1" :fields="field_excel"
                                    :fetch="fetchDataExcel" :before-generate="startDownload"
                                    :before-finish="finishDownload" type="xls" :name="fetchExcelFileName()">
                                    <i class="fas fa-file-excel"></i>
                                    Xuất Excel
                                </download-excel>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-group-sm mt-1 mb-1">
                                    <input type="search" class="form-control form-control-navbar" v-model="filter"
                                        :placeholder="$t('form.search')" aria-label="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <b-table striped hover bordered responsive head-variant="light" small :fields="fields"
                                :items="list_audits" :filter="filter" :current-page="current_page" :per-page="per_page">
                                <template #cell(active)="data">
                                    <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                    <span class="badge bg-warning" v-if="data.value == 0">Ngưng hoạt động</span>
                                </template>
                                <template #cell(created_at)="data">
                                    <span>{{ data.value | formatDateTime }}</span>
                                </template>
                                <template #cell(current_permissions)="data">
                                    <span>{{ data.value.join(',') }}</span>
                                </template>
                            </b-table>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="margin">
                                        <div class="btn-group">
                                            <label class="col-form-label-sm text-nowrap mr-1">Per page: </label>
                                            <b-form-select size="sm" v-model="per_page" :options="pageOptions">
                                            </b-form-select>
                                            <b-pagination v-model="current_page" :total-rows="rows" :per-page="per_page"
                                                size="sm" class="ml-1"></b-pagination>
                                        </div>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Pagination from '../shared/Pagination.vue';
export default {
    components: { Pagination },
    props: {
        title: ""
    },
    data() {
        return {
            list_audits: [],

            export_data: [],
            filter: "",
            per_page: 10,
            current_page: 1,
            pageOptions: [2, 10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_audit: "/api/admin/audit-roles",
            fields: [
                {
                    key: 'name',
                    label: 'Tên role',
                    sortable: true,
                },
                {
                    key: 'created_at',
                    label: 'Ngày tạo',
                    sortable: true,
                },
                {
                    key: 'active',
                    label: 'Trạng thái',
                    sortable: true,
                },
                {
                    key: 'current_permissions',
                    label: 'Quyền hiện có',
                    sortable: true,
                },
            ],
            field_excel: {
                "STT": "STT",
                "TÊN VAI TRÒ": "TEN_VAI_TRO",
                "NGÀY TẠO": "NGAY_TAO_ROLE",
                "TRẠNG THÁI": "TRANG_THAI",
                "QUYỀN": "QUYEN",
            },
            selected: [],
            selectAll: false,
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        this.fetchAuditData();
    },
    methods: {
        fetchAuditData() {
            var page_url = this.page_url_audit;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.list_audits = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchExcelFileName() {
            var current_date = new Date();
            return "Audit vai trò_" + current_date.getUTCDate() + "-" + (current_date.getUTCMonth() + 1) + "-" + current_date.getUTCFullYear();
        },
        fetchDataExcel() {
            if (this.list_audits && this.list_audits.length > 0) {
                let data = [];
                let index = 0;
                this.list_audits.forEach(row => {
                    index++;
                    data.push({
                        'STT': index,
                        'TEN_VAI_TRO': row.name,
                        'NGAY_TAO_ROLE': row.created_at,
                        'TRANG_THAI': row.active ? 'Đang hoạt động' : 'Ngưng hoạt động',
                        'QUYEN': row.current_permissions,
                    })
                });

                return data;
            } else {
                toastr.warning("Không tìm thấy dữ liệu", "");
            }
        },
        startDownload() {
            if (this.list_audits && this.list_audits.length > 0) {
                toastr.info("Đang download....", "");
            }
        },
        finishDownload() {
            toastr.success("Download thành công", "");
        },
    },

    computed: {
        rows() {
            return this.list_audits.length;
        },
    }

}
</script>

<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
