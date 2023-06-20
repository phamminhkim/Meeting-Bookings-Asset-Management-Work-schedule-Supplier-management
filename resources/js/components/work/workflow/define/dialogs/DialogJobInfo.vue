<template>
  <div class="modal fade" id="dialogJobInfo" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <b-overlay :show="loading" rounded="sm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Thông tin công việc</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" v-if="currentjob">
            <b-tabs align="center">

              <b-tab title="Thông tin chung">
                <b-card>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">ID công việc</label>
                    <div class="col-sm-7">
                      <input v-model="currentjob.id" class="form-control form-control-sm" type="text" readonly />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Unique name</label>
                    <div class="col-sm-7">
                      <input v-if="currentjob" :value="getUniqueName" readonly class="form-control form-control-sm"
                        type="text" required placeholder="Nhập tên tham chiếu của công việc" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Tên công việc</label>
                    <div class="col-sm-7">
                      <input v-model="currentjob.name" class="form-control form-control-sm" type="text" required
                        placeholder="Nhập tên hiển thị của công việc" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Mô tả công việc</label>
                    <div class="col-sm-7">
                      <input v-model="currentjob.description" class="form-control form-control-sm" type="text"
                        placeholder="Nhập mô tả công việc" />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Bắt buộc hoàn thành</label>
                    <div class="col-sm-7">
                      <input type="checkbox" v-model="currentjob.required_job" class="form-control form-control-sm"
                        required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Công việc riêng tư</label>
                    <div class="col-sm-7">
                      <input type="checkbox" v-model="currentjob.private_job" class="form-control form-control-sm"
                        required />
                    </div>
                  </div>
                </b-card>
              </b-tab>

              <b-tab title="Loại công việc">
                <b-card>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Loại công việc</label>
                    <div class="col-sm-7">
                      <Treeselect style="font-size: 15px" placeholder="Chọn loại công việc.." :multiple="false"
                        :disable-branch-nodes="true" v-model="currentjob.job_type_id" :options="tree_jobtypes" />
                    </div>
                  </div>

                  <div class="form-group row" v-if="currentjob.job_type_id == 3">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Công việc điều phối</label>
                    <div class="col-sm-7">
                      <Treeselect style="font-size: 15px" placeholder="Chọn job điều phối.." :multiple="true"
                        :disable-branch-nodes="true" v-model="navigate_jobs" :options="tree_jobs" />
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Công việc phụ thuộc</label>
                    <div class="col-sm-7">
                      <Treeselect style="font-size: 15px" placeholder="Chọn job phụ thuộc.." :multiple="true"
                        :disable-branch-nodes="true" v-model="dependencies" :options="tree_jobs" />
                    </div>
                  </div>
                </b-card>
              </b-tab>

              <b-tab title="Quyền tương tác">
                <b-card>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Người thực hiện mặc định</label>
                    <div class="col-sm-7">
                      <input type="checkbox" v-model="currentjob.is_action_user_by_ref" @change="actionUserRefChange()"
                        class="form-input " required />
                      <span v-if="currentjob.is_action_user_by_ref">Người thực hiện theo tham chiếu</span>
                      <span v-else>Người thực hiện cố định</span>
                      <reference-tree v-if="currentjob.is_action_user_by_ref"
                        placeholder="Chọn người thực hiện theo tham chiếu.." :workflow_structure="workflow_structure"
                        v-model="currentjob.action_user_path_ref"></reference-tree>
                      <treeselect v-else style="font-size: 15px;" placeholder="Chọn người thực hiện cố định"
                        v-model="currentjob.action_user" :disable-branch-nodes="true" :options="treeusers"></treeselect>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-5 col-form-label">Người phản hồi mặc định</label>
                    <div class="col-sm-7">
                      <input type="checkbox" v-model="currentjob.is_assign_user_by_ref" @change="assignUserRefChange()"
                        class="form-input " required />
                      <span v-if="currentjob.is_assign_user_by_ref">Người phản hồi theo tham chiếu</span>
                      <span v-else>Người phản hồi cố định</span>
                      <reference-tree v-if="currentjob.is_assign_user_by_ref"
                        placeholder="Chọn người phản hồi theo tham chiếu.." :workflow_structure="workflow_structure"
                        v-model="currentjob.assign_user_path_ref"></reference-tree>
                      <treeselect v-else style="font-size: 15px;" placeholder="Chọn người phản hồi cố định"
                        v-model="currentjob.assign_user" :disable-branch-nodes="true" :options="treeusers"></treeselect>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-8 col-form-label">Cho phép ADMIN thu hồi công
                      việc</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model="currentjob.allow_admin_withdraw_job"
                        class="form-control form-control-sm" required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-8 col-form-label">Cho phép USER thu hồi công
                      việc</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model="currentjob.allow_user_withdraw_job"
                        class="form-control form-control-sm" required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-8 col-form-label">Cho phép thành viên nhận công
                      việc</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model="currentjob.allow_self_assign_job"
                        class="form-control form-control-sm" required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-8 col-form-label">Cho phép USER từ bỏ công
                      việc</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model="currentjob.allow_abandon_job" class="form-control form-control-sm"
                        required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-8 col-form-label">Cho phép ADMIN chuyển công
                      việc</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model="currentjob.allow_admin_assign_user"
                        class="form-control form-control-sm" required />
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="" class="col-form-label-sm col-sm-8 col-form-label">Cho phép USER tự chuyển công
                      việc</label>
                    <div class="col-sm-4">
                      <input type="checkbox" v-model="currentjob.allow_user_assign_another_user"
                        class="form-control form-control-sm" required />
                    </div>
                  </div>
                </b-card>
              </b-tab>
            </b-tabs>



            <!-- <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Điểm số</label>
              <div class="col-sm-7">
                <input v-model="currentjob.scores" class="form-control form-control-sm" type="text" />
              </div>
            </div>
            <div class="form-group row">
              <label for="" class="col-form-label-sm col-sm-5 col-form-label">Thời hạn (giờ)</label>
              <div class="col-sm-7">
                <input v-model="currentjob.time_execution" class="form-control form-control-sm" type="text" required />
              </div>
            </div> -->
          </div>
          <div class="modal-footer">
            <button type="button" @click.prevent="saveJob()" title="Lưu" class="btn btn-primary mr-auto">
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
import ReferenceTree from '../ReferenceTree.vue';

export default {
  components: {
    Treeselect,
    ReferenceTree
  },
  props: {
    currentjob: Object,
    treeusers: Array,
    jobs: Array,
    workflow_structure: Object,
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;
    this.fetchJobTypes();
  },
  data() {
    return {
      dependencies: [],
      navigate_jobs: [],

      job_types: [],
      page_url_job_type: "/api/category/workflowjobtypes",
      errors: {},
      loading: false,
      token: "",
    };
  },
  methods: {
    fetchJobTypes() {
      var page_url = this.page_url_job_type + "?all=true";//"/api/category/companies";
      fetch(page_url, { headers: { Authorization: this.token } })
        .then(res => res.json())
        .then(res => {
          this.job_types = res.data;
        })
        .catch(err => console.log(err));
    },
    actionUserRefChange() {
      if (this.currentjob.is_action_user_by_ref) {
        this.currentjob.action_user = null;
      }
    },
    assignUserRefChange() {
      if (this.currentjob.is_assign_user_by_ref) {
        this.currentjob.assign_user = null;
      }
    },
    saveJob() {
      this.loading = true;
      var apiAddress = "/api/works/jobs";
      fetch(apiAddress + '/' + this.currentjob.id, {
        method: "PUT",
        body: JSON.stringify(this.currentjob),
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
            this.saveDependencies();
            if (this.currentjob.job_type_id == 3) {
              this.saveNavigateJobs();
            }

            toastr.success("Lưu công việc thành công", "Thông báo");
            $('#dialogJobInfo').modal('hide');
            this.$emit('updateJob', { ...res.data });
          }
          else {
            this.errors = res.errors;
            toastr.warning(res.errors, "Lưu công việc bị lỗi");
          }
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
          console.log(err);
        });
    },
    saveNavigateJobs() {
      var apiAddress = "api/works/navigate-jobs/" + this.currentjob.id;
      fetch(apiAddress, {
        method: "PUT",
        body: JSON.stringify(
          {
            "job_id": this.currentjob.id,
            "navigate_jobs_id": this.navigate_jobs
          }),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      }).then((res) => res.json())
        .then((res) => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.$emit('updateJob', { ...res.data });
          }
          else {
            this.errors = res.errors;
          }
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
        });
    },
    saveDependencies() {
      var apiAddress = "api/works/jobdependencies/" + this.currentjob.id;
      fetch(apiAddress, {
        method: "PUT",
        body: JSON.stringify(
          {
            "jobid": this.currentjob.id,
            "dependjobids": this.dependencies
          }),
        headers: {
          Authorization: this.token,
          "content-type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
      }).then((res) => res.json())
        .then((res) => {
          if (res.statuscode > 0) {
            window.location.href = "/errorpage?statuscode=" + res.statuscode;
          }
          else if (res.success == 1) {
            this.$emit('updateJob', { ...res.data });
          }
          else {
            this.errors = res.errors;
          }
          this.loading = false;
        })
        .catch((err) => {
          this.loading = false;
        });
    },
  },
  computed: {
    dependency_jobs() {
      let list = [];
      if (this.currentjob && this.currentjob.dependencies && this.jobs) {
        this.jobs.forEach(job => {
          this.currentjob.dependencies.forEach(dependency => {
            if (dependency.dependjobid == job.id) {
              list.push(job.id);
            }
          });
        });
      }
      return list;
    },
    navigating_jobs() {
      let list = [];
      if (this.currentjob && this.currentjob.navigates && this.jobs) {

        this.jobs.forEach(job => {
          this.currentjob.navigates.forEach(navigate_job => {
            if (navigate_job.navigate_job_id == job.id) {
              list.push(job.id);
            }
          });
        });
      }
      return list;
    },
    tree_jobs() {
      let list = [];
      if (this.currentjob && this.jobs) {
        list = this.jobs.map(job =>
        (
          {
            id: job.id,
            label: job.name,
          }))
        return list.filter(e => e.id !== this.currentjob.id);
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
    getUniqueName() {
      if (this.currentjob) {
        this.currentjob.unique_name = this.currentjob.name.normalize('NFD')
          .replace(/[\u0300-\u036f]/g, '')
          .replace(/đ/g, 'd').replace(/Đ/g, 'D')
          .toLowerCase()
          .replace(/[^a-zA-Z0-9 ]/g, "")
          .replaceAll(' ', '_');

        return this.currentjob.unique_name;
      }
      return '';
    }
  },
  watch: {
    dependency_jobs() {
      if (this.currentjob && this.currentjob.dependencies) {
        this.dependencies = this.currentjob.dependencies.map(function (x) {
          return x.dependjobid;
        });
      }
    },
    navigating_jobs() {
      if (this.currentjob && this.currentjob.navigates) {
        this.navigate_jobs = this.currentjob.navigates.map(function (x) {
          return x.navigate_job_id;
        });
      }
    }
  }
}
</script>

<style>

</style>