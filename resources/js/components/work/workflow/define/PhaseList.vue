<template>
    <div class="row mt-2">
        <div class="col-md-4 ">
            <div class="card small" style="width: 18rem;">

                <div class="card-header">
                    <strong>Danh sách giai đoạn</strong>
                </div>

                <b-overlay :show="loading" rounded="sm">
                    <ul class="list-group m-2" style="height:300px;overflow:auto">
                        <li v-for="(wlphase, index) in wlphases" v-bind:key="index" @click.prevent="choosePhase(index)"
                            :style="wlphase.selected == 'X' ? 'background:#ececec' : ''" draggable="true"
                            @dragstart="dragStart(index, $event)" @dragenter="dragEnter" @dragend="dragEnd"
                            @dragover.prevent @dragleave="dragLeave" @drop="dragFinish(index, $event)"
                            class="list-group-item d-flex justify-content-between align-items-center todo-item">

                            <span>[{{ index - 1 + 1 }}] {{ wlphase.name }}</span>
                            <!-- <a class="btn btn-link text-red" @click.prevent="deletePhase(item,findex)"><i class="fas fa-trash"></i> </a> -->
                            <div>

                                <span v-if="wlphase.selected == 'X'" type="button">
                                    <i class="fas fa-angle-right"></i>
                                </span>
                                <!-- <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deletePhase(item,findex)" ><i class="far fa-times-circle "></i></button> -->
                            </div>

                        </li>

                    </ul>

                    <div class="input-group m-2">
                        <input type="text" class="form-control" v-model="wlphase.name" placeholder="Thêm giai đoạn"
                            aria-label="Thêm giai đoạn" aria-describedby="button-addon2">
                        <div class="input-group-append mr-3">
                            <a class="btn btn-secondary" @click.prevent="AddPhase()" type="button"
                                id="button-addon2">+</a>
                        </div>
                    </div>
                </b-overlay>

            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-8 ">
            <phase-create v-on:actionRefresh="actionRefresh" 
            :wlworkflow_id="workflow_id" 
            :phase_id="currentPhase.id"
            :workflow_structure="workflow_structure">
            </phase-create>
        </div>
    </div>
</template>

<script>
import PhaseCreate from './PhaseCreate.vue';
export default {
    components: { PhaseCreate },
    props: {
        workflow_id: Number,
        workflow_structure: Object,
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.getWorkflowType();
        this.fetchPhases()
    },
    data() {
        return {
            wlphases: [],
            wlphase: {
                description: "",
                id: "",
                name: "",
                selected: "",
                time_execution: "",
                wlworkflow_id: "",
            },
            currentPhase: {

            },
            dragging: -1,
            current_step: "",
            wlworkflow_types: [],
            errors: {},
            loading: false,
            edit_workflow: false,
            token: "",
            edit: false,
            tabIndex: 0,
            page_url_workflow_types: "/api/category/workflowtypes",

        }

    },
    methods: {
        dragStart(which, ev) {
            // ev.dataTransfer.setData('Text', this.id);
            ev.dataTransfer.setData('Text', ev.target.getAttribute('id'));
            //console.log("ID="+ev.target.getAttribute('id'));
            ev.dataTransfer.dropEffect = 'move'
            this.dragging = which;

        },
        dragEnter(ev) {
            return true;
        },
        dragLeave(ev) {
        },
        dragEnd(ev) {
            this.dragging = -1
        },

        dragOver(ev) {
            return false;
        },
        dragFinish(to, ev) {
            this.moveItem(this.dragging, to);
            ev.target.style.marginTop = '2px'
            ev.target.style.marginBottom = '2px'
        },
        moveItem(from, to) {
            if (to === -1) {
                this.removeItemAt(from);

            } else {
                this.wlphases.splice(to, 0, this.wlphases.splice(from, 1)[0]);

            }
            //Gọi api cập nhật lại Index
            this.updatePhaseIndex();

        },
        removeItemAt(index) {
            this.wlphases.splice(index);

        },
        updatePhaseIndex() {
            this.loading = true;
            var apiAddress = "api/works/update-phase-order";
            fetch(apiAddress, {
                method: "POST",
                body: JSON.stringify({ 'wlphases': this.wlphases }),
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

                    }
                    else {
                        this.errors = res.errors;
                        toastr.warning(res.errors, "Đổi vị trí bị lỗi");
                    }
                    this.loading = false;
                }).catch(err => {
                    toastr.warning(err, "Đổi vị trí bị lỗi");
                });
        },
        actionRefresh(obj, type) {

            var index = this.wlphases.findIndex(data => data.id == obj.id);
            switch (type) {
                case 'DELETE':
                    //gọi ham delete
                    this.wlphases.splice(index, 1);
                    //this.fetchPhases();
                    if (index > 0) {
                        index = index - 1;
                        this.choosePhase(index);
                    } else {
                        if (this.wlphases.length > 0) {
                            this.choosePhase(index);
                        }
                    }

                    break;

                default:

                    this.wlphases[index] = { ...obj };
                    // this.$forceUpdate();
                    this.choosePhase(index);
                    break;
            }

        },
        AddWorkflow() {

        },
        resetPhase() {
            this.wlphase.description = "";
            this.wlphase.id = "";
            this.wlphase.name = "";
            this.wlphase.selected = "";
            this.wlphase.time_execution = "";
            this.wlphase.wlworkflow_id = "";
        },
        AddPhase() {

            if (this.wlphase.name != '') {
                if (this.wlphases == null)
                    this.wlphases = [];
                this.wlphase.wlworkflow_id = this.workflow_id;

                var apiAddress = "/api/works/phases";
                fetch(apiAddress,
                    {
                        method: "POST",
                        body: JSON.stringify(this.wlphase),
                        headers: {
                            Authorization: this.token,
                            "content-type": "application/json"
                        }
                    })
                    .then(res => res.json())
                    .then(data => {


                        if (!data.errors) {

                            this.wlphases.push({ ...data.data });
                            this.choosePhase(this.wlphases.length - 1);
                            toastr.success(this.$t('form.save_success'), "");
                            this.resetPhase();

                        } else {

                            this.errors = data.data.errors;
                            toastr.warning(data.data.errors, "Lưu bị lỗi");

                        }

                    }).catch(err => {
                        alert("Loi catch");
                        console.log(err)
                    });

            }

        },
        choosePhase(index) {

            this.wlphases.forEach(phase => {
                phase.selected = "";
            });
            this.wlphases[index].selected = "X";
            this.currentPhase = this.wlphases[index];

        },
        deletePhase(item, index) {


            if (confirm(this.$t('form.confirm_delete') + '?')) {

                if (this.wlphases == null)
                    this.wlphases = [];

                //this.wlphases_del.push({...item});
                this.wlphases.splice(index, 1);
            }
        },
        getWorkflowType() {
            var page_url = this.page_url_workflow_types;
            fetch(page_url, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {
                    this.wlworkflow_types = [];
                    this.wlworkflow_types = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        fetchPhases() {
            this.loading = true;

            this.wlphases = [];
            const params = new URLSearchParams({
                wlworkflow_id: this.workflow_id,

            });
            var apiAddress = "/api/works/phases?" + params.toString();
            fetch(apiAddress, { headers: { Authorization: this.token } })
                .then(res => res.json())
                .then(res => {

                    var wlphases = res.data.data;
                    for (let index = 0; index < wlphases.length; index++) {
                        if (index == 1) {
                            wlphases[index].selected = "X";
                            this.currentPhase = wlphases[index];
                        }
                        else {
                            wlphases[index].selected = "";
                        }
                        this.wlphases.push(wlphases[index]);
                    }
                    this.loading = false;
                })
                .catch(err => {

                    console.log(err);
                    this.loading = false;
                });


        },
        showLabel(fieldName, value) {
            if (fieldName in this.layout) {
                if (this.layout[fieldName]['has_custom_label']) {
                    return this.layout[fieldName]['custom_label_text'];
                }
            }
            return value;
        },
        showValidate(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]['require_validation'];
            }
            return false;
        },
        readOnly(fieldName) {
            if (fieldName in this.layout) {
                return this.layout[fieldName]['is_read_only'];
            }
            return false;
        },
        showControl(fieldName) {
            if (fieldName in this.layout) {

                return this.layout[fieldName]['isVisible'];
            }
            return false;
        },
        hasError(fieldName) {
            return (fieldName in this.errors);
        },
        getError(fieldName) {
            return this.errors[fieldName][0];

        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
        },

        clearAllError() {
            this.errors = {};
        },
        // Nhập trên lưới
        changeGridEdit(event) {
            let span = $(event.target).children('span');
            $(span).hide();
            console.log($(event.target).children('input').show());

        },
        changeGridView(event) {
            console.log($(event.target).hide());
            console.log($(event.target.parentElement).children('span').show());
        },
        changeGridViewToEdit(event) {
            console.log($(event.target).hide());
            console.log($(event.target.parentElement).children('input').show());
        },
    },
    computed: {
        wlphases_filter() {
            return this.wlphases;
        },
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },

    },
    watch: {
        workflow_id() {
            this.fetchPhases();
        }

    },
}
</script>

<style  lang="scss" scoped>
.todo-item {
    border: 1px solid #ccc;
    border-radius: 2px;
    padding: 7px 4px;
    margin-bottom: 3px;
    background-color: #fff;
    box-shadow: 1px 2px 2px #ccc;
    font-size: 14px;
    cursor: pointer;
}
</style>
