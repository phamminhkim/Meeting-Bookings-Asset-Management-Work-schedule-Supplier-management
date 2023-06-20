<template>
  <div class="modal fade" id="DialogAddJob" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div v-if="job_type" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">
              <span> Tạo yêu cầu {{ job_type.keyword }} </span>
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
                  placeholder="Nhập tên hiển thị của công việc" maxlength="50" />
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Nội dung</label>
              <div class="col-sm-7">
                <b-form-textarea v-model="current_job.note" class="form-control form-control-sm" cols="30" rows="4"
                  placeholder="Nhập mô tả công việc" maxlength="200" />
              </div>
            </div>


            <!-- <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Điểm số</label>
              <div class="col-sm-7">
                <input v-model="current_job.scores" class="form-control form-control-sm" type="text" />
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Thời hạn (giờ)</label>
              <div class="col-sm-7">
                <input v-model="current_job.time_execution" class="form-control form-control-sm" type="text" required />
              </div>
            </div> -->
            <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label">
                Người {{job_type.keyword }} <small class="text-red">( *
                  )</small>
              </label>
              <div class="col-sm-7">
                <treeselect style="font-size: 15px;" required
                  :placeholder="'Chọn người ' + job_type.keyword" v-model="current_job.action_user"
                  :disable-branch-nodes="true" :options="tree_users"></treeselect>
              </div>
            </div>

            <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label"> 
                {{ job_type.require_depends_text }} <small v-if="job_type.is_require_depends" class="text-red">( * )</small>
              </label>
              <div class="col-sm-7">
                <Treeselect style="font-size: 15px" 
                  :placeholder="job_type.is_require_depends ? 'Chọn phụ thuộc..' : 'Chọn phụ thuộc hoặc để trống'" :multiple="true"
                  :disable-branch-nodes="true" v-model="current_job.dependencies" :options="tree_jobs" />
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" @click.prevent="createNewJob()" class="btn btn-primary mr-auto">
              <i class="fas fa-save"></i> Lưu
            </button>

            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              {{ $t('form.close') }}
            </button>

          </div>
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

        required_job: true,
        private_job: false,
        allow_admin_withdraw_job: true,
        allow_user_withdraw_job: false,
        allow_abandon_job: false,
        allow_self_assign_job: false,
        allow_admin_assign_user: true,
        allow_user_assign_another_user: true,
        action_user: null,
        dependencies: [],
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

        required_job: true,
        private_job: false,
        allow_admin_withdraw_job: true,
        allow_user_withdraw_job: false,
        allow_abandon_job: false,
        allow_self_assign_job: false,
        allow_admin_assign_user: true,
        allow_user_assign_another_user: true,
        action_user: null,
        dependencies: [],
      };
    },
    fetchJobTypes() {
      var page_url = this.page_url_job_type;//"/api/category/companies";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.job_types = res.data.action_job_types;
        })
        .catch(err => console.log(err));
    },
    createNewJob() {
      this.current_job.wlworkflow_id = this.workflow_id;
      this.current_job.wlphase_id = this.phase_id;
      this.current_job.job_type_id = this.job_type_id;

      this.loading = true;
      var apiAddress = "/api/works/create-new-job";
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
            toastr.success("Tạo yêu cầu thành công", "Thông báo");
            $('#DialogAddJob').modal('hide');
            this.$emit('updateJob', { ...res.data });
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, "Tạo yêu cầu thất bại");
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