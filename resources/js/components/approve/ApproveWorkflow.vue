<template>
    <div style="margin-bottom: 20px">
        <b-overlay :show="loading" rounded="sm">
            <div v-if="info"  class="card">
                <div class="p-2" @click.prevent="showhide()" href="#"
                    :style="'cursor:pointer;text-align:center;background-color:#28659C;color:white'">
                    <i v-if="info.finished" class="fas fa-check-circle"></i>
                    {{ $t('form.approval_information') }} {{ info.finished ? '(Đã duyệt)' : '(' + approved_count + '/' +
                    approved_total_count + ')' }}

                    <i id="approve_collapse_caret"
                        :class="info.finished ? 'fas fa-caret-right' : 'fas fa-caret-down'"></i>
                </div>

                <b-collapse id="approve_collapse" :visible="info && !info.finished" role="tabpanel">
                    <div class="card-body">

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
                                            <!-- Chỉ vị trí đầu tiên  -->
                                            <!-- <span v-if="user.review == 1 && idx == 0"
                                    class="badge badge-primary badge-success mt-2 "
                                    style="float:right;"
                                    :title="$t('form.review')"
                                >
                                   <i style="width: 12px;" class="fas fa-user-check"></i>
                                </span>
                                <span  v-else
                                    class="badge badge-primary badge-success mt-2 "
                                    style="float:right;"
                                    :title="$t('form.approved')"
                                >
                                    <i class="fas fa-check"></i>
                                </span> -->

                                            <span class="badge badge-primary badge-success mt-2 " style="float:right;"
                                                :title="$t('form.approved')">
                                                <i class="fas fa-check"></i>
                                            </span>

                                        </div>
                                        <span v-if="user.waiting && user.release == 0"
                                            class="badge badge-primary badge-light">{{ $t('form.waiting_for_approval')
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

                    <div class="card-footer" v-if="info.is_document_cancel">
                        <span class="btn btn-block text-red">{{ $t('form.document_cancel') }}</span>
                        <div style="margin-top: 20px">
                            <h6 class="text-gray"> <i class="far fa-comments mr-1"></i>
                                <strong> <i> <u>{{
                                $t('form.info_feedback')
                                }}:</u> 
                                    </i>  {{info.feedbacks && info.feedbacks.length > 0 ? '' : 'Không có'}} </strong>
                            </h6>
                            <ul v-if="info.feedbacks && info.feedbacks.length > 0" class="list-group">
                                <li class="list-group-item disabled " style="background-color:rgb(235, 232, 232);"
                                    v-for="(feedback, index) in info.feedbacks" v-bind:key="index">
                                    <strong>{{ feedback.name }}:</strong>
                                    <small><span class="time"><i class="fas fa-clock  text-gray"> </i> {{ feedback.date
                                    |
                                    formatDateTime
                                    }}</span></small><br>
                                    {{ feedback.content }}
                                </li>


                            </ul>
                        </div>
                    </div>
                    <div class="card-footer" v-if="!info.is_document_cancel">
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
                        <div style="margin-top: 20px">
                            <h6 class="text-gray"> <i class="far fa-comments mr-1"></i>
                                <strong> <i> <u>{{
                                $t('form.info_feedback')
                                }}:</u> 
                                    </i>  {{info.feedbacks && info.feedbacks.length > 0 ? '' : 'Không có'}} </strong>
                            </h6>
                            <ul v-if="info.feedbacks && info.feedbacks.length > 0" class="list-group">
                                <li class="list-group-item disabled " style="background-color:rgb(235, 232, 232);"
                                    v-for="(feedback, index) in info.feedbacks" v-bind:key="index">
                                    <strong>{{ feedback.name }}:</strong>
                                    <small><span class="time"><i class="fas fa-clock  text-gray"> </i> {{ feedback.date
                                    |
                                    formatDateTime
                                    }}</span></small><br>
                                    {{ feedback.content }}
                                </li>


                            </ul>
                        </div>
                    </div>
                </b-collapse>



            </div>
        </b-overlay>


        <div class="modal fade" id="ykienphanhoi" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title">{{ $t('form.opinion_feedback') }}</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <textarea class="form-control" name="" id="feedback" required maxlength="254"
                            rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                            {{ $t('form.close') }}
                        </button>
                        <button type="button" @click="approvePaymentRefuse()" data-dismiss="modal"
                            class="btn btn-primary">
                            {{ $t('form.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
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
    computed: {
        approved_count() {
            var approved_count = 0;
            this.info.steps.forEach((step) => {
                var approveds = step.users.filter(function (item) {
                    if (item.release == 2) {
                        return true;
                    }
                    
                });
                approved_count += approveds.length;
            });
            return approved_count;
        },
        approved_total_count() {
            var approved_count = 0;
            this.info.steps.forEach((step) => {
                var approveds = step.users.filter(function (item) {
                    return true;
                });
                approved_count += approveds.length;
            });
            return approved_count;
        },
    },
    watch: {
        object: function (value) {
            //   console.log('Prop changed: ', newVal, ' | was: ', oldVal)
            this.get_info_approved();
        }
    },
    methods: {
        showhide() {
            $('#approve_collapse').toggle();
            var compid = "#approve_collapse_caret";
            if ($(compid).hasClass("fas fa-caret-right")) {
                $(compid).removeClass("fas fa-caret-right");
                $(compid).addClass("fas fa-caret-down");
            } else {
                $(compid).removeClass("fas fa-caret-down");
                $(compid).addClass("fas fa-caret-right");
            }
        },
        get_info_approved() {

            this.loading = true;
            var page_url =
                this.page_url_approve_payment +
                "/info?type=" +
                this.type +
                "&id=" +
                this.object.id;
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
                    this.$emit('onLoadedInfo', this.info);
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
                        this.get_info_approved();
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
                        this.get_info_approved();
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
                        this.get_info_approved();
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
