<template>
    <div class="modal fade" id="approveDialog" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <b-overlay :show="loading" rounded="sm">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title"> {{ $t('form.approval_information') }} </h4>
                            <span v-if="info.finished" class="badge badge-primary badge-success mr-1 "
                                :title="$t('form.complete_approval')">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div v-for="(step, index) in info.steps" v-bind:key="index" id="thongtinduyet">
                                <span v-if="showstep == 'X'" class="text-success">{{ $t(step.name) }}</span>

                                <ul class="list-group">
                                    <li class="list-group-item align-items-center border-0 "
                                        v-for="(user, idx) in step.users" v-bind:key="idx">
                                        <div class="d-flex justify-content-between ">
                                            <span :title="user.username">{{ user.name }} <br />

                                                <small v-if="user.release == 2">
                                                    <span class="time"><i class="fas fa-clock  text-gray"></i>{{
                                                            user.checkout | formatDateTime
                                                    }}</span>
                                                </small>
                                            </span>
                                            <div v-if="user.release == 2">
                                                <span class="badge badge-primary badge-success mt-2 "
                                                    style="float:right;" :title="$t('form.approved')">
                                                    <i class="fas fa-check"></i>
                                                </span>

                                            </div>
                                            <span v-if="user.waiting && user.release == 0"
                                                class="badge badge-primary badge-light">{{
                                                        $t('form.waiting_for_approval')
                                                }}</span>
                                            <!-- <span  v-if="user.user_current_approve &&  !user.status && !user.refuse && user.review" class="badge badge-primary badge-light">Chờ review</span> -->
                                            <span v-if="user.release == 1" class="badge badge-primary badge-warning">{{
                                                    $t('form.feedback')
                                            }}</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="modal-footer justify-content-between" v-if="info.is_document_cancel">
                            <span class="btn btn-block text-red">{{ $t('form.document_cancel') }}</span>
                        </div>
                        <div class="modal-footer justify-content-between" v-if="!info.is_document_cancel">
                            <button type="button" class="btn btn-info  btn-block" :disabled="loading"
                                @click="approvePaymentSend()" v-if="info.is_send">
                                <i class="fas fa-paper-plane"></i> {{ $t('form.send_approve') }}
                            </button>
                            <button type="button" class="btn btn-success  btn-block" :disabled="loading"
                                v-if="info.is_approve" @click="approvePaymentAgree()">
                                <i class="fas fa-check"> </i> {{ $t('form.approve') }}
                            </button>
                            <button type="button" class="btn btn-danger  btn-block" @click="showDialogAddFeedback()"
                                v-if="info.is_approve">
                                {{ $t('form.reject_feedback') }}
                            </button>
                        </div>
                    </form>
                </b-overlay>

            </div>
        </div>
    </div>
</template>

<script>
import Loading from "../shared/Loading.vue";
export default {
    props: {
        object: Object,
        type: String,
        user_id: String,
        showstep: String,
    },
    data() {
        return {
            info: {
                is_send: false,
                finished: false,
                user_wait: {},
                next_user: {},
                list_user: [],
                steps: []
            },
            after_send: false,
            loading: false,
            token: "",
            page_url_approve_payment: "/api/approve"
        };
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
    },
    watch: {
        object: function (value) {
            //   console.log('Prop changed: ', newVal, ' | was: ', oldVal)
            this.loadApproveInfo();
        }
    },
    methods: {
        loadApproveInfo() {
            this.loading = true;
            var page_url = this.page_url_approve_payment + "/info?type=" + this.type + "&id=" + this.object.id;
            fetch(page_url, {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.info = data.data.info;

                    this.loading = false;
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        //Gửi yêu cầu duyệt
        approvePaymentSend() {
            this.loading = true;
            fetch(this.page_url_approve_payment + "/send", {
                method: "post",
                body: JSON.stringify({ type: this.type, id: this.object.id }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    // console.log(data);
                    this.loading = false;
                    if (data.data.success == "1") {
                        this.loadApproveInfo();
                        this.$emit('onApproveSend');
                        toastr.success(this.$t('form.send_approved_success'), "");
                    } else {

                        if (data.data.errors != null && !Array.isArray(data.data.errors)) {
                            toastr.success(data.data.errors, "");
                        }

                        toastr.success(this.$t('form.send_approved_error'), "");

                    }
                })
                .catch(err => {
                    this.loading = false;
                    console.log(err);
                });
        },
        approvePaymentAgree() {
            this.loading = true;
            fetch(this.page_url_approve_payment + "/agree", {
                method: "post",
                body: JSON.stringify({ type: this.type, id: this.object.id }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    // console.log(data);
                    if (data.data.success == "1") {
                        this.loadApproveInfo();
                        this.$emit('onApproveAgree');
                        toastr.success(this.$t('form.approved_success'), "");
                    } else {
                        toastr.success(this.$t('form.approved_error'), "");
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.loading = false;
                });
        },
        //Ghi ý kiến phản hồi
        showDialogAddFeedback() {
            $("#ykienphanhoi").modal("show");
        },

        approvePaymentRefuse() {
            fetch(this.page_url_approve_payment + "/refuse", {
                method: "post",
                body: JSON.stringify({
                    type: this.type,
                    id: this.object.id,
                    feedback: $("#feedback").val()
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    //  console.log(data);
                    if (data.data.success == "1") {
                        this.loadApproveInfo();
                        this.$emit('onApproveRefuse');
                        toastr.success(this.$t('form.feedback_success'), "");
                    } else {
                        toastr.success(this.$t('form.feedback_error'), "");
                    }
                })
                .catch(err => console.log(err));
        }
    }
};
</script>

<style scoped>
.list-group-item {
    position: relative;
    display: block;
    padding: 0.35rem 0.15rem;
    background-color: #fff;
    /* border: 1px solid rgba(184, 26, 26, 0.125); */
    border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
