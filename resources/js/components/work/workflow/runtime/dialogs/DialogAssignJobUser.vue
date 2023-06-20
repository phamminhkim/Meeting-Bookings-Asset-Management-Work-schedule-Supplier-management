<template>
    <div class="modal fade" id="modalAssignJobUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <b-overlay :show="loading" rounded="sm">
                <div v-if="wljob" class="modal-content card">
                    <form @submit.prevent="assign_job(wljob)">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                <span>Chỉ định người {{ wljob.name }}</span>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" v-if="wljob">
                            <div class="form-group row">
                                <label class="col-form-label-sm col-sm-4 col-form-label">Người thực hiện<small
                                        class="text-red">( *
                                        )</small></label>
                                <div class="col-sm-8">
                                    <treeselect placeholder="Chọn người đảm nhận công việc này.."
                                        v-model="wljob.action_user" :disable-branch-nodes="true" :options="tree_users"
                                        :clearable="false" @input="removeDuplicateEmails()"></treeselect>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label-sm col-sm-4 col-form-label">Ghi chú</label>
                                <div class="col-sm-8">
                                    <b-form-textarea v-model="wljob.note" class="form-control form-control-sm" cols="30"
                                        rows="4" placeholder="Nhập ghi chú (nếu có)" maxlength="255" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label-sm col-sm-4 col-form-label">Thông báo đến</label>
                                <div class="col-sm-8">
                                    <treeselect v-model="wljob.action_user"
                                        :disable-branch-nodes="true" :options="tree_users" :clearable="false"
                                        :disabled="true" ></treeselect>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label-sm col-sm-4 col-form-label">Email CC đến</label>
                                <div class="col-sm-8">
                                    <treeselect placeholder="Gửi email thông báo đến.." v-model="wljob.emails"
                                        :disable-branch-nodes="true" :options="tree_users" :clearable="false"
                                        :multiple="true" ></treeselect>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" :disabled="!wljob.action_user" class="btn btn-primary mr-auto">
                                Chỉ định
                            </button>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $t('form.close')
                            }}</button>


                        </div>
                    </form>
                </div>
            </b-overlay>
        </div>
    </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
export default {
    props: {
        wljob: Object,
        document_user_id: Number,
        tree_users: Array,
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.current_user = window.Laravel.current_user;
    },
    components: {
        Treeselect
    },
    data() {
        return {
            loading: false,
            token: "",
            current_user: [],
            page_url_assign_job: "api/works/assign-job",
        }
    },
    watch: {
        wljob() {
            this.wljob.emails = [this.current_user.id, this.document_user_id];
            this.removeDuplicateEmails();
        }
    },
    methods: {
        removeDuplicateEmails() {
            this.wljob.emails = this.wljob.emails.filter((v, i, a) => a.indexOf(v) === i);

            // Không cc tới người thực hiện
            let index = this.wljob.emails.findIndex((item) => {
                return item == this.wljob.action_user;
            });
            if (index !== -1) {
                this.wljob.emails.splice(index, 1);
            }
        },
        assign_job(job) {
            if (job.action_user) {
                var page_url = this.page_url_assign_job;
                this.loading = true;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({
                        'job_id': job.id,
                        'user_id': job.action_user,
                        'note': job.note,
                        'emails_to': job.emails
                    }),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json",
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    }
                }).then(res => res.json())
                    .then(res => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            toastr.success("Giao công việc thành công", "Thông báo");
                            this.$emit('fromAssignUser', { ...res.data });
                            $("#modalAssignJobUser").modal('hide');
                        }
                        else {
                            toastr.warning(res.message, "Lỗi");
                        }


                        this.loading = false;
                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    })
            }
            else {
                toastr.warning("Chưa chọn người thực hiện nhiệm vụ", "Lỗi");
            }
        },
    },
}
</script>