<template>
    <div>
        <form id="AddDetails" @submit.prevent="addGood" @keydown="clearAllError" enctype="multipart/form-data" >
            <div class="content-header ">
                <div class="container-fluid ml-0">
                    <div class="row">
                        <div class="col-md-6">
                            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
                            <ol class="breadcrumb ">
                                <li class="breadcrumb-item">
                                    <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a
                                            href="/sloc/goods">Hàng hoá</a> </h5>
                                </li>
                                <li class="breadcrumb-item active">
                                    <span v-if="id != null">{{ $t('form.edit') }}</span>
                                    <span v-else>{{ $t('form.create') }}</span>
                                </li>
                            </ol>
                        </div>
                        <div class="col-md-6">
                            <div class="float-sm-right " style="margin-top:-5px; ">
                                <a href="/sloc/goods" class="btn btn-default ">Huỷ</a>
                                <button type="submit" :disabled="loading" value="Lưu" class="btn btn-primary">Lưu
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div v-if="hasAnyError > 0">
                        <div class="alert alert-warning">
                            <button type="button" class="close" @click.prevent="clearAllError()">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <ul>
                                <li v-for="(err, index) in errors" v-bind:key="index">{{ err }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="thongtinchung" role="tabpanel"
                                    aria-labelledby="thongtinchung-tab">
                                    <div class="row  ">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Mã mặt hàng</span><small class="text-red">( * )</small>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="code"
                                                        v-model="goodss.code"
                                                        v-bind:class="hasError('code') ? 'is-invalid' : ''" name="code"
                                                        placeholder="" required />
                                                    <span v-if="hasError('code')" class="invalid-feedback" role="alert">
                                                        <strong>{{ getError('code') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Tên mặt hàng</span>  <small class="text-red">( * )</small>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="name"
                                                        v-model="goodss.name"
                                                        v-bind:class="hasError('name') ? 'is-invalid' : ''" name="name"
                                                        placeholder="" required />
                                                    <span v-if="hasError('name')" class="invalid-feedback" role="alert">
                                                        <strong>{{ getError('name') }}</strong>
                                                    </span>
                                                </div>
                                            </div>


                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left"
                                                    for="goodunit_id">
                                                    <span>Đơn vị tính</span>
                                                    <small class="text-red">( * )</small>
                                                </label>
                                                <div class="col-md-8">
                                                    <select class="form-control form-control-sm" id="goodunit_id"
                                                        v-model="goodss.goodunit_id"
                                                        v-bind:class="hasError('goodunit_id') ? 'is-invalid' : ''"
                                                        name="goodunit_id" @change="clearError($event)" required>
                                                        <option v-for="goodunitss in goodunits" :key="goodunitss.id"
                                                            v-bind:value="goodunitss.id">
                                                            {{ goodunitss.name }}
                                                        </option>
                                                    </select>
                                                    <span v-if="hasError('goodunit_id')" class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ getError('goodunit_id') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Kích thước</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="size"
                                                        v-model="goodss.size"
                                                        v-bind:class="hasError('size') ? 'is-invalid' : ''" name="size"
                                                        placeholder=""  />
                                                    <span v-if="hasError('size')" class="invalid-feedback" role="alert">
                                                        <strong>{{ getError('size') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <!-- <img src='/goods/gau.jpg' >  -->
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Màu sắc</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="color"
                                                        v-model="goodss.color"
                                                        v-bind:class="hasError('color') ? 'is-invalid' : ''"
                                                        name="color" placeholder=""  />
                                                    <span v-if="hasError('color')" class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ getError('color') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Trọng lượng</span>
                                                  
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="weight"
                                                        v-model="goodss.weight "
                                                        v-bind:class="hasError('weight') ? 'is-invalid' : ''"
                                                        name="weight" placeholder="" />
                                                    <span v-if="hasError('weight')" class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ getError('weight') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Tồn kho</span>
                                                    
                                                </label>
                                                <div class="col-md-8">
                                                    <input class="form-control form-control-sm" id="open_stock"
                                                        v-model="goodss.open_stock"
                                                        v-bind:class="hasError('open_stock') ? 'is-invalid' : ''"
                                                        name="open_stock" placeholder=""  />
                                                    <span v-if="hasError('open_stock')" class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ getError('open_stock') }}</strong>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label-sm col-4 " style="text-align:left" for="">
                                                    <span>Mô tả</span>
                                                </label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control  textarea" id="description"
                                                        v-model="goodss.description"
                                                        v-bind:class="hasError('description') ? 'is-invalid' : ''"
                                                        name="description" placeholder=" "></textarea>
                                                    <span v-if="hasError('description')" class="invalid-feedback"
                                                        role="alert">
                                                        <strong>{{ getError('description') }}</strong>
                                                    </span>
                                                </div>
                                            </div>


                            <div class="form-group row">
                           
                          <div class="col-form-label-sm col-12">
                              <input type="file" v-on:change="onImageChange"  
                              class="form-control"
                               accept=".xlsx,.xls,image/x-png,image/gif,image/jpeg,.doc, .docx,.ppt, .pptx,.txt,.pdf"
                               name="images[]"   
                            
                          @change="emitEvent($event)"
                          id="ThemFile"
                          style="display:none"
                          ref="file"
                          multiple><button type="button" class="btn btn-info right btn-sm" style="width:30%;height: auto;margin-bottom: 10px;border-radius: 20px;font-style: italic;font-weight: 600;"
                      v-on:click="handleClickInputFile()"
                      >  <i class="fas fa-image fa-lg"></i> Thêm ảnh </button>
                       
                              <tr  v-for="(file,index) in goodss.attachment_file" v-bind:key="index">
                             
                      
                       <td  v-if="image" style="border:none; height:100px;width:50%" >
                              <img :src="image" class="img-responsive" style="height:auto;width:100%;border-radius:35px;">
                        </td>
                      <td  v-if="file.url" style="border:none;height:100px;width:50%">
                              <img :src="file.url" class="img-responsive" style="height:auto;width:100%;border-radius:35px;">
                              <span>{{file.url}}</span>
                           </td>


                      <td class="text-left py-0 align-middle" style="border:none;width: 30%;">
                        <div class="btn-group btn-group-sm" style="width:100%;">
                          <button style="border-radius:50px;" class="btn text-red"  @click.prevent="deleteFile(file,index)" ><i class="fas fa-trash fa-lg"></i></button>
                        </div>
                      </td>
                    </tr>
                             
                          </div>
                         
                        </div>
                                            <br>


                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-header">

                        </div>

                    </div>
                </div>
            </div>
        </form>


        <!-- Modal dialog -->
    </div>
</template>
<script>
export default {
    props: {
        id: String,
        parent: String,

    },

    data() {
        return {
            url:"storage/vit.jpg",

            goodss: {
                id: "",
                code: "",
                name: "",
                description: "",
                goodunit_id: "",
                size: "",
                color: "",
                weight: "",
                open_stock: "",
                index: "",
                goods: [],
                goods_del: [],
                gooddocus_details: [],
                attachment_file:[],
                attachment_file_del:[],
            },
            filter: '',
            goodunits: [],
            fields: [
            ],
image:'',
            page_url_details: "/api/sloc/goods",
            page_url_goodunits: "/api/sloc/goodunits",
            edit_payrequest: false,
            errors: {},
            loading: false,
            edit_term: false,
            edit_contract: false,

            edit: false,
            token: "",

            edit_voucher: false,
        }
    },

    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        $("#spinner-opts small").css({
            display: "inline-block",
            width: "60px",
        });
        this.getGoods();
        this.getGoodunits();
    },
    methods: {
        handleClickInputFile() {
            
            this.$refs.file.click();
    
        },
        deleteFile(item,index){
           if(confirm('Bạn muốn xoá file?')){
                
                this.goodss.attachment_file_del.push({...item});
                this.goodss.attachment_file.splice(index,1);
            }
        },
        emitEvent(event) {
           
            for (let index = 0; index < event.target.files.length; index++) {
              const file = event.target.files[index];
              //sử dụng let , nếu sử sụng biến var reader sẽ sử dụng tham chiếu, dẫn đến kết quả đọc file sai
              let reader = new FileReader();
              reader.readAsDataURL(file);

              reader.onload = () => {
                  // console.log(event.target.files[0]);
                  const docs = {
                     name: file.name,
                      size:  file.size ,
                      ext: file.type.split("/").pop(),
                      lastModifiedDate: file.lastModifiedDate,
                      base64: reader.result
                  };
                 // console.log(docs);
                  this.goodss.attachment_file.push({...docs});

              };
            }

              $("#filedinhkem").collapse("show");
           
        },
    onImageChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
        getGoods() {
            if (this.id != null) {
                this.loading = true;
                var page_url = this.page_url_details + "/" + this.id + "/edit";
                fetch(page_url, { headers: { Authorization: this.token } })
                    .then(res => res.json())
                    .then(res => {
                        if (res.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + res.statuscode;
                        }
                        if (res.data.success == '1') {
                            this.goodss = res.data;
                            console.log(this.goodss);
                            this.goodss.goods_del = [];
                            this.goodss.attachment_file_del = [];

                        }

                        this.edit_payrequest = true;
                        this.loading = false;

                    }).catch(err => {
                        this.loading = false;
                        console.log(err);
                    });
            }
        },
        addGood() {
            var page_url = this.page_url_details;
            if (this.edit_payrequest === false) {
                fetch(page_url, {
                    method: "POST",
                    body: JSON.stringify(this.goodss),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {

                        if (data.statuscode == "403") {
                            window.location.href = "/errorpage?statuscode=" + data.statuscode;
                        }
                        this.loading = false;                 
                        if (!data.data.errors) {
                            toastr.success(this.$t('form.save_success'), "");
                            window.location.href = "/sloc/goods";

                        } else {

                            this.errors = data.data.errors;

                        }
                    })
                    .catch(err => {
                        this.loading = false;

                    });
            } else {
                //update
                fetch(page_url + "/" + this.goodss.id, {
                    method: "PUT",
                    body: JSON.stringify(this.goodss),
                    headers: {
                        Authorization: this.token,
                        "content-type": "application/json"
                    }
                })
                    .then(res => res.json())
                    .then(data => {
                        console.log(data);
                        if (!data.data.errors) {
                            toastr.success(this.$t('form.update_success'), "");
                            window.location.href = "/sloc/goods";
                        } else {
                            this.errors = data.data.errors;

                        }
                        this.loading = false;

                    })
                    .catch(err => {
                        this.loading = false;

                    });
            }
        },
        getGoodunits() {

            var page_url = this.page_url_goodunits
            fetch(page_url, {
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            }).then(res => res.json())
                .then(data => {
                    this.goodunits = data.data;
                }).catch(err => {

                    console.log(err);
                });

        },
        showMessage(title, message) {
            if (!title)
                title = "Information";
            toastr.options = {
                positionClass: "toast-bottom-right"
            };

            toastr.success(message, title);
        },
        showGood() {
            this.edit_voucher = false;
            $("#AddGood").modal("show");
        },

        reset() {
            this.clearAllError();
            this.edit = false;
            for (let field in this.good) {
                this.good[field] = null;
            }
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
        reset() {
            // this.clearAllError();
            // this.edit = false;
            // for(let field in this.team){
            //     this.team[field] = null;
            // }
        },
        clearAllError() {
            this.errors = {};
        },


    },
    computed: {
        hasAnyError() {
            return Object.keys(this.errors).length > 0;
        },
    },

}


</script>
<style  lang="scss" scoped>
.form-group {
    margin-bottom: 1px !important;
}
</style>
 