<template>
  <div class="modal fade" id="DialogAddResponse" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div v-if="job_type" class="modal-content">
          <form @submit.prevent="createNewJob">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">
                <span> {{ job_type.name }} </span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" v-if="current_job">

              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Tiêu đề <small class="text-red">( *
                    )</small></label>
                <div class="col-sm-7">
                  <input v-model="current_job.name" class="form-control form-control-sm" type="text" required
                    placeholder="Nhập tiêu đề.." maxlength="50" />
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">
                  {{ job_type.keyword }} <small class="text-red">( *
                    )</small>
                </label>
                <div class="col-sm-7">
                  <treeselect style="font-size: 15px;" required :placeholder="'Chọn users..'"
                    v-model="current_job.email_to" :disable-branch-nodes="true" :options="tree_users"></treeselect>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">
                  Email CC đến
                </label>
                <div class="col-sm-7">
                  <treeselect style="font-size: 15px;" :multiple="true" :placeholder="'Chọn users..'"
                    v-model="current_job.email_ccs" :disable-branch-nodes="true" :options="tree_users"></treeselect>
                </div>
              </div>
              <div class="form-group row">
                <label for="" class="col-form-label-sm col-sm-5 col-form-label">Nội dung<small class="text-red">( *
                    )</small></label>
                <div class="col-sm-7">
                  <b-form-textarea v-model="current_job.note" class="form-control form-control-sm" cols="30" rows="4"
                    placeholder="Nhập nội dung.." maxlength="200" required />
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary mr-auto">
                <i :class="job_type.icon"></i> {{ job_type.name }}
              </button>

              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                {{ $t('form.close') }}
              </button>
            </div>
          </form>


        </div>
      </b-overlay>
    </div>
  </div>
</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
import "@riophae/vue-treeselect/dist/vue-treeselect.css";

export default {
  components: {
    Treeselect
  },
  props: {
    workflow_id: Number,
    phase_id: Number,
    tree_users: Array,
    jobs: Array,
    job_type_id: Number,
  },
  watch: {
    job_type_id() {
      this.reset();
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetchJobTypes();
  },
  data() {
    return {
      current_job: {
        wlworkflow_id: "",
        wlphase_id: "",
        job_type_id: "",
        name: "",
        description: "",

        time_execution: "",
        date_expected: "",
        date_received: "",
        note: "",
        scores: "",

        required_job: false,
        private_job: false,
        allow_admin_withdraw_job: false,
        allow_user_withdraw_job: false,
        allow_abandon_job: false,
        allow_self_assign_job: false,
        allow_admin_assign_user: false,
        allow_user_assign_another_user: false,

        email_to: null,
        email_ccs: [],
      },
      job_types: [],
      page_url_job_type: "/api/category/workflowjobtypes",
      errors: {},
      loading: false,
      token: "",
    };
  },
  methods: {
    reset() {
      this.current_job = {
        wlworkflow_id: "",
        wlphase_id: "",
        job_type_id: "",
        name: "",
        description: "",

        time_execution: "",
        date_expected: "",
        date_received: "",
        note: "",
        scores: "",

        required_job: false,
        private_job: false,
        allow_admin_withdraw_job: false,
        allow_user_withdraw_job: false,
        allow_abandon_job: false,
        allow_self_assign_job: false,
        allow_admin_assign_user: false,
        allow_user_assign_another_user: false,
        action_user: null,
        dependencies: [],
      };
    },
    fetchJobTypes() {
      var page_url = this.page_url_job_type;//"/api/category/companies";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.job_types = res.data.response_job_types;
        })
        .catch(err => console.log(err));
    },
    createNewJob() {
      this.current_job.wlworkflow_id = this.workflow_id;
      this.current_job.wlphase_id = this.phase_id;
      this.current_job.job_type_id = this.job_type_id;

      this.loading = true;
      var apiAddress = "/api/works/send-new-response";
      fetch(apiAddress, {
        method: "POST",
        body: JSON.stringify(this.current_job),
        headers: {
          Authorization: this.token,
          "content-type": "application/json"
        }
      }).then(res => res.json())
        .then(res => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            toastr.success(this.job_type.name + " thành công", "Thông báo");
            $('#DialogAddResponse').modal('hide');
            this.$emit('updateJob', { ...res.data });
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, this.job_type.name + " thất bại");
          }
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
          console.log(err);
        });
    },
  },
  computed: {
    job_type() {
      return this.job_types.find((x) => x.id == this.job_type_id);
    },
    tree_jobs() {
      let list = [];
      if (this.current_job && this.jobs) {
        list = this.jobs.map(job =>
        (
          {
            id: job.id,
            label: job.name,
          }))
        return list.filter(e => e.id !== this.current_job.id);
      }
      return list;
    },
    tree_jobtypes() {
      let list = [];
      if (this.job_types) {
        list = this.job_types.map(type =>
        (
          {
            id: type.id,
            label: type.name,
          }))
        return list;
      }
      return list;
    },
  },
}
</script>

<style>

</style>