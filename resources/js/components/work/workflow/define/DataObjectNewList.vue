<template>
    <div class="col-md-12">
        <b-card header-tag="header" footer-tag="footer">
            <template #header>
                <b-input-group>
                    <template #prepend>
                        <b-input-group-text>Thêm trường dữ liệu</b-input-group-text>
                    </template>
                    <b-form-input v-model="createNewObjectUniqueName" placeholder="Mã định danh của control"
                        :state="createNewObjectUniqueName.length > 0"></b-form-input>
                    <b-form-input v-model="createNewObjectName" placeholder="Tên hiển thị của control"
                        :state="createNewObjectName.length > 0"></b-form-input>

                    <template #append>
                        <b-dropdown text="Chọn kiểu dữ liệu" variant="success"
                            :disabled="createNewObjectUniqueName.length == 0 || createNewObjectName.length == 0">
                            <div v-for="(dataType, index) in dataObjectTypes" :key="index">
                                <b-dropdown-item v-if='dataType.value > 0' :key="dataType.value" :id="dataType.value"
                                    @click="createNewDataObject(dataType.value, createNewObjectUniqueName, createNewObjectName)">
                                    <i :class="dataType.icon"></i> {{ dataType.text }}
                                </b-dropdown-item>
                                <b-dropdown-divider v-else></b-dropdown-divider>
                            </div>
                        </b-dropdown>
                    </template>
                </b-input-group>
            </template>

            <b-overlay :show="loading" rounded="sm">
                <div v-for="(dataObject, index) in wldataobjects" v-bind:key="index" id="collapseField"
                    style="padding-bottom:10px">
                    <b-input-group draggable @dragstart="dragStart(index, $event)" @dragenter="dragEnter"
                        @dragend="dragEnd" @dragover.prevent @dragleave="dragLeave"
                        @drop="dragFinish('dataObject', dataObject.id, index, $event)" style="cursor:move">
                        <template #prepend is-text>
                            <b-input-group-text>#{{ index + 1 }}</b-input-group-text>
                            <b-dropdown variant="info">
                                <template #button-content>
                                    <i :class="getDataTypeIcon(dataObject.wldatatype_id)"></i> {{
                                    getDataTypeText(dataObject.wldatatype_id)
                                    }}
                                </template>
                                <div v-for="(dataType, index) in dataObjectTypes" :key="index">
                                    <b-dropdown-item v-if='dataType.value > 0' :key="dataType.value"
                                        :id="dataType.value" @click="dataObject.wldatatype_id = dataType.value">
                                        <i :class="dataType.icon"></i> {{ dataType.text }}
                                    </b-dropdown-item>
                                    <b-dropdown-divider v-else></b-dropdown-divider>
                                </div>
                            </b-dropdown>
                            <b-form-input v-model="dataObject.unique_name" placeholder="Mã định danh.."></b-form-input>
                        </template>
                        <b-form-input v-model="dataObject.name"></b-form-input>

                        <template #append>

                            <b-button data-toggle="collapse" :data-target="'#collapseID' + dataObject.id"
                                aria-expanded="true" aria-controls="collapseOne">Chi tiết</b-button>
                        </template>
                    </b-input-group>
                    <div :id="'collapseID' + dataObject.id" class="collapse hide" aria-labelledby="headingOne"
                        data-parent="#collapseField">
                        <b-card>
                            <b-input-group class="mb-2" size="sm">
                                <b-input-group-prepend is-text>
                                    <b-form-checkbox v-model="dataObject.require" switch class="mr-n2 mb-n1" size="sm">
                                    </b-form-checkbox>
                                </b-input-group-prepend>
                                <b-form-input @focusin="toggleReadonly" @focusout="toggleReadonly"
                                    :value="getRequireText(dataObject.require)" />
                            </b-input-group>
                            <b-input-group class="mb-2" size="sm">
                                <b-input-group-prepend is-text>
                                    <b-form-checkbox v-model="dataObject.read_only" switch class="mr-n2 mb-n1" size="sm">
                                    </b-form-checkbox>
                                </b-input-group-prepend>
                                <b-form-input @focusin="toggleReadonly" @focusout="toggleReadonly"
                                    :value="getReadonlyText(dataObject.read_only)" />
                            </b-input-group>

                            <b-input-group class="mb-2" size="sm">
                                <b-input-group-prepend is-text>
                                    Mã định danh
                                </b-input-group-prepend>
                                <b-form-input v-model="dataObject.unique_name"></b-form-input>
                            </b-input-group>

                            <b-input-group class="mb-2" size="sm">
                                <b-input-group-prepend is-text>
                                    Mô tả trường dữ liệu
                                </b-input-group-prepend>
                                <b-form-input v-model="dataObject.description"></b-form-input>
                            </b-input-group>

                            <b-input-group class="mb-2" size="sm"
                                v-if="dataObject.wldatatype_id == 5 || dataObject.wldatatype_id == 6">
                                <b-input-group-prepend is-text>
                                    Dữ liệu
                                </b-input-group-prepend>
                                <table class="table table-bordered table-sm text-center">
                                    <thead>
                                        <tr>
                                            <th style="width: 30px">#</th>
                                            <th scope="col">Key</th>
                                            <th scope="col">Text hiển thị</th>
                                            <th style="width: 15%" scope="col">
                                                <a href="#" @click.prevent.stop="addNewDataRow(dataObject.id)"
                                                    class="btn btn-xs mr-2" title="Thêm dòng">
                                                    <i class="ace-icon text-blue fa fa-plus-circle bigger-120"></i>
                                                </a>
                                                <a href="#" @click.prevent.stop="copyDataRows(dataObject.id)"
                                                    class="btn btn-xs mr-2" title="Copy danh sách">
                                                    <i class="ace-icon text-green fa fa-copy bigger-120"></i>
                                                </a>
                                                <a href="#" @click.prevent.stop="pasteDataRows(dataObject.id)"
                                                    class="btn btn-xs mr-2" title="Paste danh sách">
                                                    <i class="ace-icon text-orange fa fa-paste bigger-120"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in dataObject.items" v-bind:key="index">
                                            <td scope="row" style="cursor:move" draggable
                                                @dragstart="dragStart(index, $event)" @dragenter="dragEnter"
                                                @dragend="dragEnd" @dragover.prevent @dragleave="dragLeave"
                                                @drop="dragFinish('dataField', dataObject.id, index, $event)">{{ index +
                                                1
                                                }}</td>
                                            <td @click="changeGridEdit($event)" style="width: 40%">
                                                <span @click="changeGridViewToEdit($event)">{{
                                                item.name
                                                }}</span>
                                                <input style="display: none" @blur.prevent.self="changeGridView($event)"
                                                    v-model="item.name" type="text"
                                                    class="form-control form-control-sm" />
                                            </td>
                                            <td @click="changeGridEdit($event)" style="width: 40%">
                                                <!-- <div class="d-flex justify-content-between"> -->
                                                <span @click="changeGridViewToEdit($event)">{{
                                                item.value
                                                }}</span>
                                                <input style="display: none" @blur.prevent.self="changeGridView($event)"
                                                    v-model="item.value" type="text"
                                                    class="form-control form-control-sm" />
                                                <!-- </div> -->
                                            </td>
                                            <td>
                                                <a href="#" class="text-red sm"
                                                    @click.prevent.stop="deleteDataRow(dataObject.id, index)">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr v-if="dataObject.items.length == 0">
                                            <td colspan="4">
                                                Không có lựa chọn nào
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </b-input-group>



                            <template #footer>
                                <b-input-group size="sm">
                                    <b-form-input disabled></b-form-input>

                                    <template #append>
                                        <b-button variant="dark"
                                            @click.prevent="duplicateDataObject(dataObject, index)"> <i
                                                class="fa fa-copy"></i></b-button>
                                        <b-button variant="danger" @click.prevent="deleteDataObject(dataObject, index)">
                                            <i class="fa fa-trash"></i>
                                        </b-button>
                                    </template>
                                </b-input-group>
                            </template>

                        </b-card>
                    </div>

                </div>

                <tr v-if="wldataobjects.length == 0">
                    Không có trường dữ liệu nào
                </tr>
            </b-overlay>


            <template #footer>
                <b-input-group>
                    <b-input-group-prepend>
                        <b-button variant="primary" @click.prevent="showDataObjectPreview()" class="fa fa-eye"> Xem
                            trước</b-button>
                    </b-input-group-prepend>

                    <b-form-input disabled></b-form-input>

                    <b-input-group-append>
                        <b-button variant="success" @click.prevent="saveAllDataObjects()" class="fa fa-save">
                            Lưu</b-button>
                    </b-input-group-append>
                </b-input-group>
            </template>
        </b-card>
        <dialog-data-control-preview :id="'dialogDataControlPreview'" :workflow_id="wlworkflow_id" :title="'Xem trước'"
            :controls="previewControls" />
    </div>
</template>

<script>
import PhaseCreate from './PhaseCreate.vue';
import DialogDataControlPreview from './dialogs/DialogDataObjectControlPreview.vue';
export default {
    components: { PhaseCreate, DialogDataControlPreview },
    props: {
        title: String,
        wlworkflow_id: Number,
        phase_id: Number,
        job_id: Number,
    },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
    },
    data() {
        return {
            wldataobjects: [],
            wldataobject: {
                id: "",
                name: "",
                wlphase_id: "",
                wljob_id: "",
                wlworkflow_id: "",
                wldatatype_id: "",
                require: "0",
                read_only: "0",
                description: " ",
                index_after_by: "0",
                index: "",
                items: [],
                itemsRemoved: [],
            },
            previewControls: [],
            createNewObjectUniqueName: "",
            createNewObjectName: "",
            draggingItemIndex: -1,
            errors: {},
            loading: false,
            token: "",
            edit: false,
            dataObjectTypes: [
                { value: 3, text: "Nhập số", icon: "fas fa-sort-numeric-down" },
                { value: 1, text: "Nhập chữ (Một dòng)", icon: "fas fa-font" },
                { value: 2, text: "Nhập chữ (Nhiều dòng)", icon: "fas fa-align-left" },
                { value: 13, text: "Nhập chữ (Chi tiết)", icon: "fas fa-align-justify" },
                { value: 11, text: "Nhập chữ (Liên kết)", icon: "fas fa-link" },
                { value: 0 },
                { value: 5, text: "Chọn một", icon: "fas fa-dot-circle" },
                { value: 6, text: "Chọn nhiều", icon: "fas fa-list" },
                { value: 0 },
                { value: 14, text: "Chọn giờ", icon: "fas fa-clock" },
                { value: 7, text: "Chọn ngày tháng", icon: "fas fa-calendar" },
                { value: 0 },
                { value: 9, text: "Chọn một người dùng", icon: "fas fa-user" },
                { value: 16, text: "Chọn nhiều người dùng", icon: "fas fa-user-group" },
                { value: 0 },
                { value: 10, text: "Đính kèm", icon: "fas fa-paperclip" },
                { value: 12, text: "Yêu cầu xác nhận", icon: "fas fa-check" },
            ],
            wldataobject_itm: {
                name: "",
                value: "",
            },
        }

    },
    methods: {
        toggleReadonly(event) {
            event.preventDefault()
            if (event.target.getAttribute('readonly') == 'readonly') {
                event.target.removeAttribute('readonly')
            }
            else {
                event.target.setAttribute('readonly', 'readonly')
            }
        },
        getRequireText(require) {
            return require ? "Bắt buộc" : "Không bắt buộc";
        },
        getReadonlyText(read_only) {
            return read_only ? "Chỉ đọc" : "Có thể chỉnh sửa";
        },
        getDataTypeText(dataType) {
            var dataObjectType = this.dataObjectTypes.find((x) => x.value == dataType);
            return dataObjectType == null ? "" : dataObjectType.text;
        },
        getDataTypeIcon(dataType) {
            var dataObjectType = this.dataObjectTypes.find((x) => x.value == dataType);
            return dataObjectType == null ? "" : dataObjectType.icon;
        },
        showDataObjectPreview() {
            var apiAddress = "/api/works/preview-data-objects";
            fetch(apiAddress, {
                method: "POST",
                body: JSON.stringify({
                    objects: this.wldataobjects,
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
                        this.previewControls = res.data;
                        $("#dialogDataControlPreview").modal("show");
                    }
                    else {
                        this.errors = res.errors;
                    }
                })
                .catch((err) => console.log(err));

        },
        saveAllDataObjects() {
            this.loading = true;
            var apiAddress = "/api/works/dataobjects";

            var TotalObjects = 0;
            var TotalSuccess = 0;
            var TotalFailed = 0;

            this.wldataobjects.forEach((dataObject, index) => {
                dataObject.order = index;
                TotalObjects++;
                fetch(apiAddress + '/' + dataObject.id, {
                    method: "PUT",
                    body: JSON.stringify(dataObject),
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
                            this.wldataobjects[index] = res.data;
                            TotalSuccess++;
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Cập nhật đối tượng bị lỗi");
                            TotalFailed++;
                        }
                        if (TotalSuccess == TotalObjects) {
                            toastr.success("Cập nhật đối tượng thành công", "Thông báo");
                        }
                        if (TotalSuccess + TotalFailed == TotalObjects) {
                            this.loading = false;
                        }
                    })
                    .catch((err) => console.log(err));
            });

        },
        createNewDataObject(dataObjectType, dataObjectUniqueName, dataObjectName) {
            this.loading = true;

            this.wldataobject.wljob_id = this.job_id;
            this.wldataobject.wlphase_id = this.phase_id;
            this.wldataobject.wlworkflow_id = this.wlworkflow_id;
            this.wldataobject.wldatatype_id = dataObjectType;
            this.wldataobject.unique_name = dataObjectUniqueName;
            this.wldataobject.name = dataObjectName;
            this.wldataobject.description = dataObjectName;

            var apiAddress = "/api/works/dataobjects";
            fetch(apiAddress, {
                method: "POST",
                body: JSON.stringify(this.wldataobject),
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
                        this.wldataobjects.push(res.data);
                        this.reOrderDataObject();
                        toastr.success("Thêm đối tượng thành công", "Thông báo");
                        this.createNewObjectUniqueName = "";
                        this.createNewObjectName = "";
                    }
                    else {
                        this.errors = res.errors;
                        toastr.warning(res.errors, "Thêm đối tượng bị lỗi");
                    }
                    this.loading = false;
                })
                .catch((err) => console.log(err));
        },
        duplicateDataObject(dataObject, index) {
            if (confirm("Bạn có muốn duplicate trường \"" + dataObject.name + "\" không?")) {
                this.loading = true;

                this.wldataobject = dataObject;
                this.wldataobject.id = "";
                this.wldataobject.name = dataObject.name + " (Copy)";

                var apiAddress = "/api/works/dataobjects";
                fetch(apiAddress, {
                    method: "POST",
                    body: JSON.stringify(this.wldataobject),
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
                            this.wldataobjects.splice(index + 1, 0, res.data);
                            this.reOrderDataObject();
                            toastr.success("Copy đối tượng thành công", "Thông báo");
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Thêm đối tượng bị lỗi");
                        }
                        this.loading = false;
                    })
                    .catch((err) => console.log(err));
            }
        },
        deleteDataObject(dataObject, index) {
            if (confirm("Bạn có chắc muốn xoá trường \"" + dataObject.name + "\" không?")) {
                var apiAddress = "/api/works/dataobjects/" + dataObject.id;
                fetch(apiAddress, {
                    method: "delete",
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
                            toastr.success("Xóa đối tượng thành công", "Thông báo");
                            this.wldataobjects.splice(index, 1);
                            this.reOrderDataObject();
                        }
                        else {
                            this.errors = res.errors;
                            toastr.warning(res.errors, "Xóa đối tượng bị lỗi");
                        }
                        this.loading = false;
                    });
            }
        },
        fetchDataObjects() {
            this.loading = true;

            this.wldataobjects = [];
            let params = "";
            if (this.job_id != null && this.job_id != 0) {
                params = new URLSearchParams({
                    wlworkflow_id: this.wlworkflow_id,
                    wlphase_id: this.phase_id,
                    wljob_id: this.job_id,
                });
            } else if (this.phase_id != null && this.phase_id != 0) {
                params = new URLSearchParams({
                    wlworkflow_id: this.wlworkflow_id,
                    wlphase_id: this.phase_id,
                });
            } else if (this.wlworkflow_id != null && this.wlworkflow_id != 0) {
                params = new URLSearchParams({
                    wlworkflow_id: this.wlworkflow_id,
                });
            }

            if (params != "") {
                var apiAddress = "/api/works/dataobjects?" + params.toString();
                fetch(apiAddress, { headers: { Authorization: this.token } })
                    .then((res) => res.json())
                    .then((res) => {
                        if (res.statuscode > 0) {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        else if (res.success == 1) {
                            this.wldataobjects = res.data;
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
        dragFinish(field, objectID, toIndex, event) {
            this.moveItem(field, objectID, this.draggingItemIndex, toIndex);
        },
        moveItem(field, objectID, fromIndex, toIndex) {
            if (field == "dataObject") {
                if (toIndex === -1) {
                    this.wldataobjects.splice(fromIndex);
                } else {
                    this.wldataobjects.splice(toIndex, 0, this.wldataobjects.splice(fromIndex, 1)[0]);
                }
                //Gọi api cập nhật lại Index
                this.reOrderDataObject();
            }
            else if (field == "dataField") {
                this.wldataobjects.forEach(dataObject => {
                    if (dataObject.id == objectID) {
                        if (toIndex === -1) {
                            dataObject.items.splice(fromIndex);
                        } else {
                            dataObject.items.splice(toIndex, 0, dataObject.items.splice(fromIndex, 1)[0]);
                            dataObject.items[toIndex].order = fromIndex;
                        }
                    }
                });
                this.reOrderDataField(objectID);
            }
        },
        reOrderDataObject() {
            this.wldataobjects.forEach((dataObject, index) => {
                this.wldataobjects[index].order = index;
            });
        },
        reOrderDataField(objectID) {
            this.wldataobjects.forEach(dataObject => {
                if (dataObject.id == objectID) {
                    dataObject.items.forEach((objectSubItem, index) => {
                        dataObject.items[index].order = index;
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
        addNewDataRow(objectID) {
            this.wldataobjects.forEach(dataObject => {
                if (dataObject.id == objectID) {
                    dataObject.items.push({ ...this.wldataobject_itm });
                }
            });

            this.reOrderDataField(objectID);
        },
        copyDataRows(objectID) {
            this.wldataobjects.forEach(dataObject => {
                if (dataObject.id == objectID) {
                    navigator.clipboard.writeText(JSON.stringify(dataObject.items))
                        .then((value) => {
                            this.showMessage(this.$t("form.message"), "Copy danh sách dữ liệu thành công");
                        })
                        .catch((err) => {
                            this.showFailMessage(this.$t("form.message"), "Copy danh sách dữ liệu thất bại");
                        });
                }
            });


        },
        pasteDataRows(objectID) {
            this.wldataobjects.forEach(dataObject => {
                if (dataObject.id == objectID) {
                    if (dataObject.itemsRemoved == null) {
                        dataObject.itemsRemoved = [];
                    }

                    navigator.clipboard.readText()
                        .then((text) => {
                            dataObject.itemsRemoved = [...dataObject.items];
                            dataObject.items = [];

                            var clipboardItems = JSON.parse(text);
                            clipboardItems.forEach(item => {
                                delete item['id'];

                                dataObject.items.push(item);
                            });
                            this.reOrderDataField(objectID);
                            this.showMessage(this.$t("form.message"), "Paste danh sách dữ liệu thành công");
                        })
                        .catch((err) => {
                            this.showFailMessage(this.$t("form.message"), "Paste danh sách dữ liệu thất bại");
                        });
                }
            });

        },
        deleteDataRow(objectID, index) {
            this.wldataobjects.forEach(dataObject => {
                if (dataObject.id == objectID) {
                    if (dataObject.itemsRemoved == null) {
                        dataObject.itemsRemoved = [];
                    }
                    dataObject.itemsRemoved.push({ ...dataObject.items[index] });
                    dataObject.items.splice(index, 1);
                }
            });

            this.reOrderDataField(objectID);
        },
        showMessage(title, message) {
            if (!title) title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right",
            };

            toastr.success(message, title);
        },
    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },

    },
    watch: {
        wlworkflow_id() {
            if (this.wlworkflow_id != null) {
                this.fetchDataObjects();

                return this.wlworkflow_id + "phase" + this.phase_id + "job" + this.job_id;
            }
            return null;
        },
    },
}
</script>
