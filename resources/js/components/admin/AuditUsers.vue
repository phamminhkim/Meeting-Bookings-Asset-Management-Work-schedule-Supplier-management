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
                                :items="user_audits" :filter="filter" :current-page="current_page" :per-page="per_page">
                                <template #cell(active)="data">
                                    <span class="badge bg-success" v-if="data.value == 1">Hoạt động</span>
                                    <span class="badge bg-warning" v-if="data.value == 0">Ngưng hoạt động</span>
                                </template>

                                <template #cell(company_id)="data">
                                    <span>{{ getCompanyName(data.value) }}</span>
                                </template>
                                <template #cell(department_id)="data">
                                    <span>{{ getDepartmentName(data.value) }}</span>
                                </template>

                                <template #cell(created_at)="data">
                                    <span>{{ data.value | formatDateTime }}</span>
                                </template>
                                <template #cell(last_login_at)="data">
                                    <span>{{ data.value | formatDateTime }}</span>
                                </template>
                                <template #cell(current_roles)="data">
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
            companies: [],
            departments: [],
            user_audits: [],

            export_data: [],
            filter: "",
            per_page: 10,
            current_page: 1,
            pageOptions: [2, 10, 50, 100, 500, { value: this.rows, text: "All" }],
            errors: {},

            loading: false,
            edit: false,
            token: "",
            page_url_department: "/api/category/departments",
            page_url_company: "/api/category/companies",
            page_url_audit: "/api/admin/audit-users",
            fields: [

                {
                    key: 'username',
                    label: 'Tên đăng nhập',
                    sortable: true,
                },
                {
                    key: 'user_id',
                    label: 'Mã nhân viên',
                    sortable: true,
                },
                {
                    key: 'name',
                    label: 'Tên nhân viên',
                    sortable: true,
                },
                {
                    key: 'email',
                    label: 'Email',
                    sortable: true,
                },
                {
                    key: 'company_id',
                    label: 'Công ty',
                    sortable: true,
                },
                {
                    key: 'department_id',
                    label: 'Bộ phận',
                    sortable: true,
                },
                {
                    key: 'created_at',
                    label: 'Ngày tạo',
                    sortable: true,
                },
                {
                    key: 'last_login_at',
                    label: 'Lần login cuối',
                    sortable: true,
                },
                {
                    key: 'active',
                    label: 'Trạng thái',
                    sortable: true,
                },
                {
                    key: 'current_roles',
                    label: 'Role hiện có',
                    sortable: true,
                },
            ],
            field_excel: {
                "STT": "STT",
                "TÊN ĐĂNG NHẬP": "TEN_DANG_NHAP",
                "MÃ NHÂN VIÊN": "MA_NHAN_VIEN",
                "TÊN NHÂN VIÊN": "TEN_NHAN_VIEN",
                "EMAIL": "EMAIL",
                "CÔNG TY": "CONG_TY",
                "BỘ PHẬN": "BO_PHAN",
                "NGÀY TẠO USER": "NGAY_TAO_TAI_KHOAN",
                "LOGIN LẦN CUỐI": "LOGIN_LAN_CUOI",
                "TRẠNG THÁI": "TRANG_THAI",
                "QUYỀN HIỆN TẠI": "QUYEN_HIEN_TAI",
            },
            selected: [],
            selectAll: false,
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;

        this.fetchCompanyData();
        this.fetchDepartmentData();
        this.fetchAuditData();
    },
    methods: {
        fetchAuditData() {
            var page_url = this.page_url_audit;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.user_audits = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchCompanyData() {
            var page_url = this.page_url_company;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.companies = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchDepartmentData() {
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },
        getCompanyName(company_id) {
            var obj = this.companies.find(x => x.id == company_id);
            return obj != null ? obj.name : '';
        },
        getDepartmentName(department_id) {
            var obj = this.departments.find(x => x.id == department_id);
            return obj != null ? obj.name : '';
        },
        fetchExcelFileName() {
            var current_date = new Date();
            return "Audit tài khoản_" + current_date.getUTCDate() + "-" + (current_date.getUTCMonth() + 1) + "-" + current_date.getUTCFullYear();
        },
        fetchDataExcel() {
            if (this.user_audits && this.user_audits.length > 0) {
                let data = [];
                let index = 0;
                this.user_audits.forEach(row => {
                    index++;
                    data.push({
                        'STT': index,
                        'TEN_DANG_NHAP': row.username,
                        'MA_NHAN_VIEN': row.user_id,
                        'TEN_NHAN_VIEN': row.name,
                        'EMAIL': row.email,
                        'CONG_TY': this.getCompanyName(row.company_id),
                        'BO_PHAN': this.getDepartmentName(row.department_id),
                        'NGAY_TAO_TAI_KHOAN': row.created_at,
                        'LOGIN_LAN_CUOI': row.last_login_at,
                        'TRANG_THAI': row.active ? 'Đang hoạt động' : 'Ngưng hoạt động',
                        'QUYEN_HIEN_TAI': row.current_roles,
                    })
                });

                return data;
            } else {
                toastr.warning("Không tìm thấy dữ liệu", "");
            }
        },
        startDownload() {
            if (this.user_audits && this.user_audits.length > 0) {
                toastr.info("Đang download....", "");
            }
        },
        finishDownload() {
            toastr.success("Download thành công", "");
        },
    },

    computed: {
        rows() {
            return this.user_audits.length;
        },
    }

}
</script>

<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
