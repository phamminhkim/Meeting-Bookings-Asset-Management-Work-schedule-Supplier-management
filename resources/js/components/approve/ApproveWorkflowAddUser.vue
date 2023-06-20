<template>
    <div>

        <div class="row">

            <div class="col-md-12">
                <div class="form-group row">
                    <!-- <label for="" class="col-form-label-sm col-sm-5 col-form-label  text-md-right ">{{ $t('form.document_num')}}</label> -->
                    <label class="col-form-label-sm col-sm-3 text-md-right">{{ $t('form.approve_confirm') }}</label>
                    <div class="col-sm-7">
                        <div class="btn-group" role="group" aria-label="User">

                            <treeselect style="font-size: 15px;" placeholder="" v-model="user_confirm"
                                @input="addUser('1')" :disable-branch-nodes="true" :options="tree_user"></treeselect>

                        </div>
                        <div v-if="team_step1_users.length > 0" class="d-flex justify-content-between">

                            <ul class="list-unstyled" id="step1">
                                <li v-for="(step, index) in team_step1_users" v-bind:key="index" class="todo-item"
                                    draggable="true" @dragstart="dragStart(index, $event, 1)" @dragenter="dragEnter"
                                    @dragend="dragEnd" @drop="dragFinish(index, $event, 1)" @dragover.prevent
                                    @dragleave="dragLeave">
                                    <span>{{ step.username }}-{{ step.name }}</span>
                                    <span style=" float: right; color: #a45;opacity: 0.5;" class="ml-3"
                                        @click.prevent="deleteUser(step, index, '1')">
                                        <i class="fas fa-times"></i>
                                    </span>

                                    <!-- <div class="btn-group">
                                            <button type="button" class="btn btn-default btn-xs"> {{step.name}}</button>
                                            <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteUser(step,index)" ><i class="far fa-times-circle "></i></button>

                                          </div> -->
                                </li>
                            </ul>


                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group row">
                    <!-- <label for="" class="col-form-label-sm col-sm-5 col-form-label  text-md-right ">{{ $t('form.document_num')}}</label> -->
                    <label class="col-form-label-sm col-sm-3 text-md-right ">{{ $t('form.approve_consider') }}</label>
                    <div class="col-sm-7">
                        <div class="btn-group" role="group" aria-label="User">

                            <treeselect style="font-size: 15px;" placeholder="" v-model="user_view"
                                @input="addUser('2')" :disable-branch-nodes="true" :options="tree_user"></treeselect>

                        </div>
                        <div v-if="team_step2_users.length > 0" class="d-flex justify-content-between">

                            <ul class="list-unstyled" id="step2">
                                <li v-for="(step, index) in team_step2_users" v-bind:key="index" class="todo-item"
                                    draggable="true" @dragstart="dragStart(index, $event, '2')" @dragenter="dragEnter"
                                    @dragend="dragEnd" @drop="dragFinish(index, $event, '2')" @dragover.prevent
                                    @dragleave="dragLeave">
                                    <span>{{ step.username }}-{{ step.name }}</span>
                                    <span style=" float: right; color: #a45;opacity: 0.5;" class="ml-3"
                                        @click.prevent="deleteUser(step, index, '2')">
                                        <i class="fas fa-times"></i>
                                    </span>
                                    <!-- <div class="btn-group">

                                            <button type="button" class="btn btn-default btn-xs" > {{step.name}}</button>
                                            <button type="button" class="btn btn-default btn-xs text-red" @click.prevent="deleteUser(step,index)" ><i class="far fa-times-circle "></i></button>

                                          </div> -->
                                </li>
                            </ul>


                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="group_id" class="col-form-label-sm col-sm-3 text-md-right">{{ $t('form.approve_approve') }}<small
                    class="text-red">( * )</small></label>
            <div class="col-sm-7">
                <div class="btn-group" role="group" aria-label="User">

                    <treeselect style="font-size: 15px;" placeholder="" v-model="user_approve"
                        :disable-branch-nodes="true" :options="tree_user"></treeselect>
                    <!-- <button type="button" class="btn btn-secondary btn-sm">Add</button> -->
                </div>

            </div>
        </div>
    </div>

</template>

<script>
import Treeselect from "@riophae/vue-treeselect";
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
export default {
    props: {
        list_user: Array,
        tree_user: Array,
        layout: Object,
        eventname: String,
        team: Object,
        user_id: Number,
    },
    components: {
        Treeselect
    },
    data() {
        return {
            options: [],
            team_users: [],
            team_step1_users: [],
            team_step2_users: [],
            user_approve: null,
            user_confirm: null,
            user_view: null,
            team_user: {
                name: "",
                user_id: "",
                team_id: "",
                level: "",
                step: "",
                review: "",
                sign: "",
            },
            dragging: -1,
            current_step: "",



        };
    },

    methods: {
        dragStart(which, ev, step) {
            // ev.dataTransfer.setData('Text', this.id);
            ev.dataTransfer.setData('Text', ev.target.getAttribute('id'));
            //console.log("ID="+ev.target.getAttribute('id'));
            ev.dataTransfer.dropEffect = 'move'
            this.dragging = which;
            this.current_step = step;
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
        dragFinish(to, ev, step) {
            this.moveItem(this.dragging, to, step);
            ev.target.style.marginTop = '2px'
            ev.target.style.marginBottom = '2px'
        },
        moveItem(from, to, step) {
            if (to === -1) {
                this.removeItemAt(from, step);

            } else {
                if (step == "1") {
                    this.team_step1_users.splice(to, 0, this.team_step1_users.splice(from, 1)[0]);
                } else if (step == "2") {
                    this.team_step2_users.splice(to, 0, this.team_step2_users.splice(from, 1)[0]);
                }

            }
            this.transerDataParent();
        },
        removeItemAt(index, step) {
            if (step == "1") {
                this.team_step1_users.splice(index, 1);
            } else if (step == "2") {
                this.team_step2_users.splice(index, 1);
            }

        },
        transerDataParent() {
            this.team_users = [];

            this.team_step1_users.forEach(team_user => {
                this.team_users.push({ ...team_user });
            });
            this.team_step2_users.forEach(team_user => {
                this.team_users.push({ ...team_user });
            });

            let user = this.list_user.find(x => x.id == this.user_approve);
            //console.log(this.user_approve+"-" + user);
            if (this.user_approve != null && user) {
                user = this.getTeamUserAprrove();
                if (user) {
                    this.team_users.push(user);
                    //console.log( user);
                }

            }

            this.$emit(this.eventname, this.team_users);

        },
        getTeamUserAprrove() {
            let user = this.list_user.find(x => x.id == this.user_approve);
            if (user) {
                this.team_user.name = user.name;
                this.team_user.user_id = user.id;
                this.team_user.step = '3';
                this.team_user.sign = '1';
            }

            return { ...this.team_user };
        },

        addUser(step) {

            let user_id = "";
            let user = {};
            let exist = false;
            let sign = "";
            if (step == '1') {
                user_id = this.user_confirm;

                this.user_confirm = null;
            } else if (step == '2') {
                user_id = this.user_view;

                this.user_view = null;
            }
            else if (step == '3') {
                user_id = this.user_approve;
                sign = "1";

            }
            if (user_id == this.user_id) {
                alert("user tạo không nằm trong cây duyệt");
                return;
            }
            user = this.list_user.find(x => x.id == user_id);
            if (user) {
                this.team_user.name = user.name;
                this.team_user.username = user.username;
                this.team_user.user_id = user.id;
                this.team_user.step = step;
                this.team_user.sign = "";

                this.team_users.forEach(team_user => {
                    if (team_user.user_id == user.id) {
                        exist = true;
                    }
                });
                if (exist) {
                    alert("user đã được thêm");
                } else {
                    if (step == "1") {
                        this.team_step1_users.push({ ...this.team_user });
                    } else if (step == "2") {
                        this.team_step2_users.push({ ...this.team_user });
                    }

                    this.transerDataParent();

                    // this.$emit(this.eventname, this.team_users);
                }

            }




        },
        steps(step) {
            let list = [];
            this.team_users.forEach(team => {
                if (team.step == step) {
                    list.push({ ...team });
                }

            });
            return list;
        },
        deleteUser(item, index, step) {
            if (confirm(this.$t('form.confirm_delete') + '?')) {
                if (step == "1") {
                    let index = this.team_step1_users.findIndex(x => x.user_id == item.user_id);
                    this.team_step1_users.splice(index, 1);
                } else if (step == "2") {
                    let index = this.team_step2_users.findIndex(x => x.user_id == item.user_id);
                    this.team_step2_users.splice(index, 1);
                }

                this.transerDataParent();
                //this.$emit(this.eventname, this.team_users);
            }
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
        showControl(fieldName) {
            if (fieldName in this.layout) {

                return this.layout[fieldName]['isVisible'];
            }
            return false;
        },
        clearAllError() {
            this.errors = {};
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
    },
    computed: {


        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
    },
    watch: {
        team() {
            if (this.team != null && this.team.users) {
                this.team.users.forEach(user => {

                    this.team_user.name = user.name;
                    this.team_user.username = user.username;
                    this.team_user.user_id = user.id;
                    this.team_user.step = user.pivot.step;
                    //  this.team_users.push({...this.team_user });
                    if (user.pivot.step == '1') {
                        this.team_step1_users.push({ ...this.team_user });
                    } else if (user.pivot.step == '2') {
                        this.team_step2_users.push({ ...this.team_user });
                    } else if (user.pivot.step == '3') {
                        this.user_approve = user.id;
                        //alert("test"+this.user_approve);
                    }
                    //console.log( this.user_approve);
                });
                this.transerDataParent();
                // this.$emit(this.eventname, this.team_users);
            }
        },
        user_approve() {

            if (this.user_approve == this.user_id) {
                alert("user tạo không nằm trong cây duyệt");
                this.user_approve = null;
                return;
            }
            //   let user = this.team_users.find(x=>x.user_id == this.user_approve);
            //     if(user){
            //         alert("user đã được thêm");
            //         this.user_approve = null;
            //         return ;
            //     }
            if (this.user_approve == null) {

                return;
            }

            this.transerDataParent();
            //    let index = this.team_users.findIndex(x=>x.step == "3");

            //     let user = this.list_user.find(x=>x.id == this.user_approve);
            //    //console.log(index);
            //    this.team_users.splice(index,1);
            //    this.team_user .name = user.name;
            //    this.team_user .user_id = user.id;
            //    this.team_user .step = '3';
            //    this.team_users.push({...this.team_user });
            //    this.$emit(this.eventname, this.team_users);



        },

        list_user() {
            var list = [];
            var item = {};
            this.list_user.forEach(user => {

                item = {};
                item.id = user.id;
                item.label = user.name;
                list.push(item);
                this.options.push(item);

            });
            this.transerDataParent();

            return this.options;
        },
    },
}

</script>
<style lang="scss" scoped>
.form-group {
    margin-bottom: 5px !important;
}

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
