<template>
    <div class="container ">
        <div class="printable" style="position:fixed;top:10px;right:10px;z-index:1000;">
            <button class="btn btn-outline-secondary" id="printButton" @click="printPage()"> <i class="fas fa-print"> </i>
                Print </button>
        </div>
        <div class="printable" style="position:fixed;top:10px;left:10px;z-index:1000;">
            <button class="btn btn-outline-secondary" id="goBackButton" @click="goBack()"> <i
                    class="fas fa-arrow-circle-left"></i>
                Quay lại </button>
        </div>
        <div class="mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="text-center"> <img src="logo.png" loading="lazy" /> </td>
                        <td class="text-center">
                            <h4 class="mt-3">
                                <b>BIÊN BẢN
                                    kim
                                    <span v-if="asset_record.trans_type == 1"> BÀN GIAO </span>
                                    <span v-if="asset_record.trans_type == 4"> SỬA CHỮA </span>
                                    TÀI SẢN - CÔNG CỤ DỤNG CỤ</b>
                            </h4>
                        </td>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="text-center">
            <label>Hôm nay ngày {{ asset_record.date_transaction | formatDate }} </label>
        </div>
        <!-- <div class="form-group">
            <p> Hôm nay ngày {{ asset_record.created_at | formatDate }} tiến hành bàn giao tài sàn giữa <span
                    v-if="asset_record.create_by"> {{ $t(getuserName(asset_record.create_by)) }}</span> và {{
                        asset_record.user.name }} (bên nhận)
            </p>
        </div> -->
        <label>I. Bên giao</label>

        <div class="form-group row">
            <div class="col-md-12">
                <div class="form-group row" v-if="asset_record.create_by">
                    <div class="col-md-4 d-flex align-items-center">
                        Anh/Chị:
                        <div class="col-form ml-1   flex-grow-1" style="border-bottom:1px dotted #cccccc;;">
                            <span v-if="asset_record.create_by"> <b>{{ $t(getuserName(asset_record.create_by)) }}</b>
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center" v-if="asset_record.department_id == null">
                        Bộ phận:
                        <div class="col-form ml-1   flex-grow-1" style="border-bottom:1px dotted #cccccc;;">
                            <span v-if="asset_record.create_by"> <b>{{
                                $t(GetDepartmentName(asset_record.user.department_id)) }}</b> </span>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center" v-if="asset_record.department_id !== null">
                        Bộ phận:
                        <div v-for="user in filterDepartment" :key="user.id" class="col-form ml-1   flex-grow-1"
                            style="border-bottom:1px dotted #cccccc;;">
                            <span> <b> {{ $t(GetDepartmentName(user.department_id)) }} </b> </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <label>II. Bên nhận</label>
        <div class="form-group row">
            <div class="col-md-12">
                <div class="form-group row" v-if="asset_record.user_id">
                    <div class="col-md-4 d-flex align-items-center">
                        Anh/Chị:
                        <div class="col-form ml-1 flex-grow-1" style="border-bottom: 1px dotted #cccccc;;">
                            <b>{{ asset_record.user.name }}</b>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        MSNV:
                        <div class="col-form ml-1  flex-grow-1" style="border-bottom: 1px dotted #cccccc;;">
                            <b>{{ asset_record.user.username }}</b>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center">
                        Bộ phận:
                        <div class="col-form ml-1 flex-grow-1" style="border-bottom: 1px dotted #cccccc;">
                            <b> {{ $t(GetDepartmentName(asset_record.user.department_id)) }}</b>
                        </div>
                    </div>
                </div>
                <div class="form-group row" v-if="asset_record.department_id">
                    <div class="col-md-6 d-flex align-items-center">
                        Bộ phận:
                        <div class="col-form ml-1   flex-grow-1" style="border-bottom: 1px dotted #cccccc;;">
                            <b> {{ asset_record.department.name }}</b>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        Công ty:
                        <div class="col-form ml-1   flex-grow-1" style="border-bottom: 1px dotted #cccccc;;">
                            <b> {{ $t(getCompanyName(asset_record.department.company_id)) }}</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>III. Nội dung bàn giao</label>
        </div>
        <div class="form-group">
            <div class="form-group d-flex align-items-center">
                Lý do:
                <div :class="asset_record.note !== null ? 'flex-grow-1  ml-1  ' : 'mt-3 flex-grow-1  ml-1'"
                    style="border-bottom: 1px dotted #cccccc;;"> {{ asset_record.note }} </div>
            </div>

            <label>Bảng thống kê chi tiết bàn giao: </label>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr class="font-weight-bold">
                            <td>STT</td>
                            <td>Tên thiết bị</td>
                            <td>Mã tài sản</td>
                            <td>Số lượng</td>
                            <td>Tình trạng</td>
                            <td>Nội dung</td>
                            <td>Chữ ký nhận</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in assetItems" :key="index">
                            <td> {{ index + 1 }} </td>
                            <td v-if="item.objectable_id"> {{ item.objectable.name }} </td>
                            <td v-if="item.objectable_id"> {{ item.objectable.asset_code }} </td>
                            <td v-if="item.objectable_id"> {{ item.quantity }} </td>
                            <td v-if="item.objectable_id && item.objectable.asset_status_id == 1"> Tốt </td>
                            <td v-if="item.objectable_id"> {{ item.objectable.description }} </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="form-group row">
            <div class="col-md-12">
                <div class="form-group row">
                    <div class="col-md-12 d-flex align-items-center">
                        Ghi chú:
                        <div class="col-form ml-1   flex-grow-1 mt-3" style="border-bottom: 1px dotted #cccccc;;"></div>
                    </div>
                </div>
            </div>
            <!-- <label>Cam kết của người dùng</label>
            <ul>
                <li>Không cài đặt bất kỳ phần mềm nào vào máy tính công ty cấp sử dụng.</li>
                <li>Không thực hiện các hành vi gây hư hại ảnh hưởng đến hệ thống mạng, e-mail, máy tính và thiết bị tài sản
                    của công ty.</li>
                <li>Bảo quản, giữ gìn máy móc thiết bị IT do công ty cấp.</li>
            </ul> -->
        </div>
        <div class="form-group font-italic">
            <div class="float-left" v-if="asset_record.user_id">
                <label>Ngày xác nhận: </label> <span class="text-secondary">{{ asset_record.updated_at | formatDate
                }}</span>
            </div>
            <div class="float-right">
                <label>Ngày bàn giao: </label> <span class="text-secondary">{{ asset_record.date_transaction | formatDate
                }}</span>
            </div>

        </div>
        <div class="form-group table-responsive">
            <table class="table table-bordered text-center ">
                <thead>
                    <tr style="height:150px;">
                        <td>
                            <span class="font-weight-bold">Bên nhận</span> <i>(ký, ghi rõ họ tên)</i> <br>
                            <label class="mt-5" v-if="asset_record.user_id"> {{ asset_record.user.name }} </label>
                            <label class="mt-5" v-if="asset_record.department_id"> {{ asset_record.department.name }}
                            </label>
                        </td>
                        <td>
                            <span class="font-weight-bold">Bên giao</span><i>(ký, ghi rõ họ tên)</i><br>
                            <label class="mt-5"> {{ $t(getuserName(asset_record.create_by)) }} </label>
                        </td>
                    </tr>
                </thead>
            </table>
            <div class="w-100 container text-center table-responsive"
                style="position:fixed;bottom:0;border-top:2px solid black">
                <div class="float-left">
                    <span class="text-secondary">
                        <i>ITR01F02 – 04/04/16</i>
                    </span>
                </div>
                <div class="ml-4" style="display:inline-block">
                    <span class="text-secondary">
                        <i>Thời gian lưu: Sau 24 tháng kể từ khi nhân viên nghỉ việc hoặc thiết bị đã thanh lý</i>
                    </span>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
export default {

    props: {
        id: String,
        title: String,
        asset_record: Object,
    },
    data() {
        return {
            loading: false,
            token: '',
            asset_record: [],
            url_asset_my: "api/asset/my",
            // page_url_asset: "api/asset/assets",
            page_url_department: "/api/category/departments",
            page_url_company: "/api/category/companies",
            companies: [],
            // assets: [],
            users: [],
            departments: [],
            departmentMap: {},
            page_url_users: "api/user/all",
        }
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px"
        });

        this.fetchUser();
        this.fetchDepartment();
        this.fetchCompany();
    },
    methods: {
        fetchUser() {
            var page_url = this.page_url_users
            //  console.log(page_url);
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.users = data.data;
                }).catch(err => {
                    console.log(err);
                });
        },
        fetchDepartment() {
            // const this.token = "Bearer " + window.Laravel.access_this.token;
            var page_url = this.page_url_department;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    //console.log("Xin chao");
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
        },
        fetchCompany() {
            var page_url = this.page_url_company;
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.companies = data.data;
                }).catch(err => {
                    console.log(err);
                });


        },
        getuserName(id) {
            var obj = this.users.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        GetDepartmentName(id) {
            var obj = this.departments.find(x => x.id == id);
            return obj ? obj.name : '';
        },
        getCompanyName(company_id) {
            return this.companyMap[company_id] || '';
        },
        goBack() {
            window.history.back();
        },
        printPage() {
            var printButton = document.getElementById("printButton");
            var goBackButton = document.getElementById("goBackButton");
            printButton.style.display = "none"; // ẩn nút button khi ingoBackButton
            goBackButton.style.display = "none";
            window.print();
            printButton.style.display = "block"; // hiển thị lại nút button sau khi in
            goBackButton.style.display = "block";
        },
    },
    computed: {
        assetItems() {
            if (this.asset_record && this.asset_record.asset_transaction_items) {
                return this.asset_record.asset_transaction_items.filter(item => item.objectable_id);
            }
            return [];
        },
        filterDepartment() {
            return this.users.filter(user => user.id === this.asset_record.create_by);
        },
        companyMap() {
            return this.companies.reduce((map, company) => {
                map[company.id] = company.name;
                return map;
            }, {});
        }

    }
}
</script>