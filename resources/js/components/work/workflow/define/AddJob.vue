<template>

    <div class="modal fade" id="modalAddJob" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="modalAddJobLabel" aria-hidden="true">
        <div class="modal-dialog ">

            <div class="modal-content card">
                <form @submit.prevent="AddEditJob" @keydown="clearError">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddJobLabel">
                            <span v-if="!edit">Thêm</span>
                            <span v-else>Sửa</span>
                            công việc
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <b-overlay :show="loading" rounded="sm">
                        <div class="modal-body ">

                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 ">Tên công việc</label>
                                <div class="col-sm-8">
                                    <input type="text" required v-model="wljob.name"
                                        class="form-control form-control-sm">
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 ">Mô tả công việc</label>
                                <div class="col-sm-8">
                                    <input type="text" required v-model="wljob.description"
                                        class="form-control form-control-sm">
                                </div>

                            </div>
                            <!-- <div class="form-group row">
                        <label for="" class="col-form-label-sm col-sm-4 ">Điểm</label>
                        <div class="col-sm-8">
                        <input type="number"  v-model="wljob.scores"  class="form-control form-control-sm"  >
                        </div>

                    </div> -->
                            <!-- <div class="form-group row">
                        <label for="" class="col-form-label-sm col-sm-4 ">Số giờ</label>
                        <div class="col-sm-8">
                        <input type="number"  v-model="wljob.time_execution"  class="form-control form-control-sm"  >
                        </div>

                    </div> -->
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 ">Ngày dự kiến HT</label>
                                <div class="col-sm-8">
                                    <!-- <input type="date"  v-model="wljob.date_expected"  class="form-control form-control-sm"  > -->
                                    <!-- <input type="date"  v-model="wljob.date_expected"  class="form-control form-control-sm"  > -->
                                    <!-- <b-form-input v-model="wljob.date_expected"   type="date"></b-form-input> -->
                                    <b-form-datepicker v-model="wljob.date_expected"
                                        :date-format-options="{ year: 'numeric', month: 'numeric', day: 'numeric' }">

                                    </b-form-datepicker>
                                    <!-- <input type="date" class="form-control" v-model="wljob.date_expected" /> -->
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 ">Người thực hiện</label>
                                <div class="col-sm-8">
                                    <treeselect style="font-size: 15px;" required v-model="wljob.action_user"
                                        placeholder="" :disable-branch-nodes="true" :multiple="false"
                                        :options="tree_users"></treeselect>
                                </div>

                            </div>
                            <div class="form-group row">
                                <label for="" class="col-form-label-sm col-sm-4 ">Ghi chú</label>
                                <div class="col-sm-8">
                                    <textarea class="form-control form-control-sm"
                                        v-model="wljob.note"></textarea>
                                </div>

                            </div>




                        </div>
                    </b-overlay>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Đóng</button>
                        <button class="btn btn-primary" type="submit">Lưu</button>
                    </div>
                </form>

            </div>


        </div>


    </div>
</template>

<script>

import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
import DataObjectList from './DataObjectList.vue';
export default {
    components: { DataObjectList, Treeselect },

    props: {
        wlphase_id: Number,
        tree_users: Array,
        wljob_id: Number
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;

    },
    data() {
        return {
            wlphase: {},
            wljob: {
                name: "",
                time_execution: "",
                action_user: null,
                date_expected: "",
                date_received: "",
                wlphase_id: "",
                description: "",
                note: "",
                scores: "",
                id: null,

            },
            loading: false,
            token: "",
            edit: false,
            errors: {},
        }
    },
    methods: {
        reset() {
            this.clearAllError();
            this.edit = false;
            this.wljob.name = "";
            this.wljob.id = null;
            this.wljob.time_execution = "";
            this.wljob.action_user = null;
            this.wljob.date_expected = "";
            this.wljob.date_received = "";
            this.wljob.wlphase_id = "";
            this.wljob.note = "";
            this.wljob.description = "";
            this.wljob.scores = "";
        },
        AddEditJob() {

            this.wljob.wlphase_id = this.wlphase_id;
            var apiAddress = "/api/jobs";
            if (this.edit === false) {

                fetch(apiAddress, {
                    method: "POST",
                    body: JSON.stringify(this.wljob),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.data.success == '1') {
                            this.reset();
                            var obj = data.data;

                            toastr.success(this.$t('form.save_success'), "");
                            $("#modalAddJob").modal("hide");
                            this.$emit('updateJob', { ...obj.data });


                        } else {

                            var mess = "";
                            var parsedobj = JSON.parse(JSON.stringify(data.data.errors));
                            for (const key in parsedobj) {
                                if (Object.hasOwnProperty.call(parsedobj, key)) {
                                    const element = parsedobj[key];
                                    mess = element;
                                    break;

                                }
                            }
                            toastr.warning(JSON.stringify(mess), "Lưu bị lỗi");


                        }
                    })
                    .catch(err => console.log(err));
            } else {
                //update
                fetch(apiAddress + this.wljob_id, {
                    method: "PUT",
                    body: JSON.stringify(this.wljob),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.data.success == '1') {
                            var obj = data.data;
                            toastr.success('Thông báo', 'Lưu thành công');
                            $("#modalAddJob").modal("hide");
                            this.$emit('updateJob', { ...obj.data });
                            this.reset();

                        } else {

                            var mess = "";
                            var parsedobj = JSON.parse(JSON.stringify(data.data.errors));
                            for (const key in parsedobj) {
                                if (Object.hasOwnProperty.call(parsedobj, key)) {
                                    const element = parsedobj[key];
                                    mess = element;
                                    break;

                                }
                            }
                            toastr.warning(JSON.stringify(mess), "Lưu bị lỗi");

                        }
                    })
                    .catch(err => console.log(err));
            }
        },
        fetJob() {

            if (this.wljob_id) {
                this.loading = true;


                var apiAddress = "api/works/jobs/" + this.wljob_id + "/edit";
                fetch(apiAddress, {
                    method: "GET",
                    headers: { Authorization: this.token }
                }).then(res => res.json())
                    .then(res => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            this.wljob = res.data;
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Tải công việc bị lỗi");
                        }
                        this.loading = false;
                        this.edit = true;
                    })
                    .catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            } else {
                this.loading = false;
            }

        },
        hasError(fieldName) {

            return (fieldName in this.errors);
        },
        getError(fieldName) {
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            return this.errors[fieldName][0];

        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //console.log(event.target.name);
        },

        clearAllError() {
            this.errors = {};
        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },

    },
    watch: {
        wljob_id() {
            this.reset();
            this.fetJob();
        },
    },
}
</script>
 
<style lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>