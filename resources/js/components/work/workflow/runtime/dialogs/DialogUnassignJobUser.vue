<template>
    <div class="modal fade" id="modalUnassignJobUser" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <b-overlay :show="loading" rounded="sm">
                <div class="modal-content card">
                    <form @submit.prevent="unassign_job(wljob)">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">
                                <span v-if="wljob">{{ wljob.name }}</span>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" v-if="wljob">
                            <span><i class="fas fa-undo"></i>Bỏ chỉ định công việc cho</span>

                            <div class="btn-group" role="group" aria-label="User">
                                <treeselect :disabled="true" v-model="wljob.action_user" :disable-branch-nodes="true"
                                    :options="tree_users"></treeselect>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary mr-auto">Xác nhận</button>
                            
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
        tree_users: Array,
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
    },
    components: {
        Treeselect
    },
    data() {
        return {
            loading: false,
            token: "",
            page_url_wljob: "api/works/assign-job",
        }
    },
    methods: {
        unassign_job(job) {
            if (job.action_user) {
                var page_url = this.page_url_wljob;
                this.loading = true;
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify({ 'job_id': job.id, 'user_id': null }),
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
                            toastr.success("Bỏ chỉ định công việc thành công", "Thông báo");
                            this.$emit('fromAssignUser', { ...res.data });
                        }
                        else {
                            toastr.warning("Bỏ chỉ định công việc bị lỗi", "Lỗi");
                        }
                        $("#modalUnassignJobUser").modal('hide');

                        this.loading = false;
                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    })
            }
        },
    },
}
</script>