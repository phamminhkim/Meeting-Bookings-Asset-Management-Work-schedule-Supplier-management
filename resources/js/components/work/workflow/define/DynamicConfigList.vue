<template>
    <div>
        <b-overlay :show="loading" rounded="sm">
            <b-card no-body>
                <b-tabs pills card>
                    <!-- Render Tabs, supply a unique `key` to each tab -->
                    <b-tab v-for="(config, index) in configs" :key="'dyn-tab-' + index" :active="config.active == 1"
                        :title="config.keyword + (config.active == 1 ? ' (đang chọn)' : '')">

                        <b-card header-tag="header" footer-tag="footer">
                            <template #header>
                                <b-input-group class="mb-2">
                                    <template #prepend>
                                        <b-input-group-text>Tên bộ cấu hình</b-input-group-text>
                                    </template>
                                    <b-form-input v-model="config.keyword"></b-form-input>
                                    <template #append>
                                        <b-button variant="primary" v-if="config.active == 0"
                                            @click.prevent="chooseConfig(config)" class="fa fa-bookmark">
                                            Chọn làm cấu hình chính</b-button>
                                    </template>

                                </b-input-group>
                            </template>

                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 20%" scope="col">Key</th>
                                        <th style="width: 20%" scope="col">Name</th>
                                        <th scope="col">Value</th>
                                        <th style="width: 15%" scope="col"><a href="#"
                                                @click.prevent.stop="addNewDataRow(config.keyword)">
                                                <i class="fa fa-plus-circle"></i>
                                            </a></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(option, index) in config.options" v-bind:key="index">
                                        <td scope="row" style="cursor:move" draggable
                                            @dragstart="dragStart(index, $event)" @dragenter="dragEnter"
                                            @dragend="dragEnd" @dragover.prevent @dragleave="dragLeave"
                                            @drop="dragFinish(config.keyword, index, $event)">{{
                                                    index
                                                    + 1
                                            }}</td>
                                        <td @click="changeGridEdit($event)">
                                            <span @click="changeGridViewToEdit($event)">{{
                                                    option.key
                                            }}</span>
                                            <b-form-input style="display: none"
                                                @blur.prevent.self="changeGridView($event)" v-model="option.key"
                                                type="text" :state="optionKeyState(config, option)"
                                                class="form-control form-control-sm"
                                                aria-describedby="input-live-help input-live-feedback" />
                                            <b-form-invalid-feedback id="input-live-feedback">
                                                Trùng key
                                            </b-form-invalid-feedback>
                                        </td>
                                        <td @click="changeGridEdit($event)">
                                            <span @click="changeGridViewToEdit($event)">{{
                                                    option.name
                                            }}</span>
                                            <b-form-input style="display: none"
                                                @blur.prevent.self="changeGridView($event)" v-model="option.name"
                                                type="text" class="form-control form-control-sm" />
                                        </td>
                                        <td @click="changeGridEdit($event)">
                                            <span @click="changeGridViewToEdit($event)">{{
                                                    option.value
                                            }}</span>
                                            <b-form-input style="display: none"
                                                @blur.prevent.self="changeGridView($event)" v-model="option.value"
                                                type="text" class="form-control form-control-sm" />
                                        </td>
                                        <td>
                                            <a href="#" class="text-red sm"
                                                @click.prevent.stop="deleteDataRow(config.keyword, index)">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr v-if="config.options.length == 0">
                                        <td colspan="4">
                                            Không có cấu hình nào
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <template #footer>
                                <b-input-group>
                                    <b-input-group-prepend>
                                        <b-button variant="danger" @click.prevent="deleteConfig(config)"> <i
                                                class="fa fa-trash"></i>
                                        </b-button>
                                    </b-input-group-prepend>

                                    <b-form-input disabled></b-form-input>

                                    <b-input-group-append>
                                        <b-button variant="success" @click.prevent="saveAllConfigs()"
                                            class="fa fa-save">
                                            Lưu</b-button>
                                    </b-input-group-append>
                                </b-input-group>
                            </template>
                        </b-card>
                    </b-tab>

                    <!-- New Tab Button (Using tabs-end slot) -->
                    <template #tabs-end>
                        <b-nav-item role="presentation" @click.prevent="addNewConfig" href="#"><strong>+</strong>
                        </b-nav-item>
                    </template>

                    <!-- Render this if no tabs -->
                    <template #empty>
                        <div class="text-center text-muted">
                            Chưa có bộ cấu hình nào được tạo<br>
                            Bấm vào dấu <b>+</b> trở phía trên để tạo mới bộ cấu hình.
                        </div>
                    </template>
                </b-tabs>
            </b-card>
        </b-overlay>


    </div>
</template>

<script>

import Treeselect from '@riophae/vue-treeselect'
// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'

export default {
    components: { Treeselect },
    props: {
        wlworkflow_id: Number,
        phase_id: Number,
        job_id: Number,
        dataobject_id: Number,
        inputConfigs: Array
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
    },
    data() {
        return {
            configs: [],
            removeConfigs: [],
            objectType: '',
            objectID: - 1,
            draggingItemIndex: -1,
            errors: {},
            loading: false,
            token: "",
            edit: false,
        }

    },
    methods: {
        optionKeyState(config, currentOption) {
            config.options.forEach(option => {
                if (option.index != currentOption.index && option.key == currentOption.key) {
                    return false;
                }
            });
            return true;
        },
        chooseConfig(chooseConfig) {
            this.configs.forEach(config => {
                if (config.keyword === chooseConfig.keyword) {
                    config.active = 1;
                }
                else {
                    config.active = 0;
                }
            });
        },
        deleteConfig(deleteConfig) {
            this.configs.forEach((config, index) => {
                if (config.keyword === deleteConfig.keyword) {
                    if (config.id) {
                        this.removeConfigs.push(config);
                    }
                    this.configs.splice(index, 1);
                }
            });
            if (this.configs.length > 0) {
                this.configs[0].active = 1;
            }
        },
        fetchConfigs() {
            this.loading = true;

            this.configs = [];
            let params = params = new URLSearchParams({
                objectType: this.objectType,
                objectID: this.objectID,
            });

            if (params != "") {
                var apiAddress = "/api/works/configs?" + params.toString();
                fetch(apiAddress, { headers: { Authorization: this.token } })
                    .then((res) => res.json())
                    .then((res) => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            this.configs = res.data;
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Tải danh sách đối tượng bị lỗi");
                        }
                        this.loading = false;
                    })
                    .catch((err) => {
                        console.log(err);
                        this.loading = false;
                    });
            }
        },
        saveAllConfigs() {
            this.loading = true;
            var apiAddress = "/api/works/configs";

            fetch(apiAddress + '/' + this.objectID, {
                method: "PUT",
                body: JSON.stringify({
                    'configs': this.configs,
                    'removeConfigs': this.removeConfigs
                }),
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json",
                },
            })
                .then((res) => res.json())
                .then((res) => {
                    if (res.statuscode > 0) {
                        window.location.href = "/errorpage?statuscode=" + res.statuscode;
                    }
                    else if (res.success == 1) {
                        this.configs = res.data;
                        this.removeConfigs = [];
                        toastr.success("Cập nhật cấu hình thành công", "Thông báo");
                    }
                    else {
                        this.errors = res.errors;
                        toastr.warning(res.errors, "Cập nhật cấu hình bị lỗi");
                    }
                    this.loading = false;
                })
                .catch((err) => console.log(err));

        },
        dragStart(dragItemIndex, event) {
            event.dataTransfer.dropEffect = 'move'
            this.draggingItemIndex = dragItemIndex;
        },
        dragEnter(event) {
            return true;
        },
        dragLeave(event) {
        },
        dragEnd(event) {
            this.draggingItemIndex = -1;
        },
        dragOver(event) {
            return false;
        },
        dragFinish(configKeyword, toIndex, event) {
            this.moveItem(configKeyword, this.draggingItemIndex, toIndex);
        },
        moveItem(configKeyword, fromIndex, toIndex) {
            this.configs.forEach((config, index) => {
                if (config.keyword === configKeyword) {
                    if (toIndex === -1) {
                        config.options.splice(fromIndex);
                    } else {
                        config.options.splice(toIndex, 0, config.options.splice(fromIndex, 1)[0]);
                    }
                }
            });

            this.reOrderConfigOptions(configKeyword);
        },
        reOrderConfigOptions(configKeyword) {
            this.configs.forEach(config => {
                if (config.keyword === configKeyword) {
                    config.options.forEach((option, index) => {
                        config.options[index].index = index;
                    });
                }
            });
        },
        changeGridEdit(event) {
            let span = $(event.target).children("span");
            let input = $(event.target).children("input");
            $(span).hide();
            $(input).show();
        },
        changeGridView(event) {
            $(event.target).hide();
            $(event.target.parentElement).children("span").show();
        },
        changeGridViewToEdit(event) {
            $(event.target).hide();
            $(event.target.parentElement).children("input").show();
        },
        addNewConfig() {
            var config = {
                objectable_id: this.objectID,
                objectable_type: this.objectType,
                keyword: "Bộ cấu hình " + (this.configs.length + 1),
                options: [],
                active: this.configs.length == 0 ? 1 : 0,
            };
            this.configs.push(config);
        },
        addNewDataRow(configKeyword) {
            this.configs.forEach(config => {
                if (config.keyword === configKeyword) {
                    var option = {
                        key: 'UniqueKey ' + (config.options.length + 1),
                        name: 'Cấu hình ' + (config.options.length + 1),
                        value: ''
                    };
                    config.options.push(option);
                }
            });

            this.reOrderConfigOptions(configKeyword);
        },
        deleteDataRow(configKeyword, index) {
            this.configs.forEach(config => {
                if (config.keyword === configKeyword) {
                    if (config.optionsRemoved == null) {
                        config.optionsRemoved = [];
                    }
                    config.optionsRemoved.push({ ...config.options[index] });
                    config.options.splice(index, 1);
                }
            });

            this.reOrderConfigOptions(configKeyword);
        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
    },
    watch: {
        wlworkflow_id() {
            if (this.wlworkflow_id) {
                this.objectType = 'workflow';
                this.objectID = this.wlworkflow_id;
                this.fetchConfigs();
            }
        },
        phase_id() {
            if (this.phase_id) {
                this.objectType = 'phase';
                this.objectID = this.phase_id;
                this.fetchConfigs();
            }
        },
        job_id() {
            if (this.job_id) {
                this.objectType = 'job';
                this.objectID = this.job_id;
                this.fetchConfigs();
            }
        },
        dataobject_id() {
            if (this.dataobject_id) {
                this.objectType = 'dataobject';
                this.objectID = this.dataobject_id;
                this.fetchConfigs();
            }

        },
    }

}
</script>
