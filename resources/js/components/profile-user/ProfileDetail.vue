<template>
    <div >
             <div class="content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h5 class="m-0 text-dark"><i class="fas fa-users"></i>  {{$t("form.profile")}}</h5>
            </div>      
            <div class="col-sm-6">
                <div class="float-sm-right">
                  <div class="btn-group-vertical">
                   
                  </div>
                  <!-- <a href="payment/requests?type=add" class="btn btn-primary" ><i class="fa fa-plus"></i>Tạo phiếu</a> -->
                </div>
               
            </div>   
         </div>
        </div>
      </div>
        <br />
        <div class="col-md-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img
                                    class="profile-user-img img-fluid img-circle"
                                    style="height:94px"
                                    v-bind:src="avatar_url"
                                    
                                    alt="User profile picture"
                                />
                            </div>

                            <h3 class="profile-username text-center">
                                {{ user.username }}
                            </h3>

                            <p class="text-muted text-center">
                                {{ user.name }}
                            </p>

                            <!-- <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Followers</b> <a class="float-right">1,322</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Following</b> <a class="float-right">543</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Friends</b> <a class="float-right">13,287</a>
                                </li>
                                </ul> -->
                            <input
                                type="file"
                                accept="image/*"
                                @input="emitEvent($event)"
                                id="fileAvata"
                                style="display:none"
                                ref="file"
                            />
                            <button
                                href="#"
                                class="btn btn-primary btn-block avatar"
                                v-on:click="handleClickInputFile()"
                            >
                                <b>{{ $t("form.upload_avata") }}</b>
                            </button>
                        </div>
                        <!-- /.card-body -->
                    </div>

                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a
                                        class="nav-link active"
                                        href="#info-user"
                                        data-toggle="tab"
                                        >{{ $t("form.basic_infomation") }}</a
                                    >
                                </li>
                                <li class="nav-item">
                                    <a
                                        class="nav-link "
                                        href="#change-password"
                                        data-toggle="tab"
                                        >{{ $t("form.change_password") }}</a
                                    >
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- /.tab-pane -->
                                <div class="tab-pane " id="change-password">
                                    <div
                                        id="edit-password"
                                        class="tab-pane active"
                                    >
                                        <form
                                            class="form-horizontal"
                                            @submit.prevent="changePass"
                                            @keydown="clearError"
                                        >
                                            <div class="card-body">
                                                <div class="form-group row">
                                                    <label
                                                        for="current-password"
                                                         class="col-sm-4 col-form-label"
                                                        >{{ $t("form.old_password") }}</label
                                                    >
                                                    <div class="col-sm-8">
                                                        <input
                                                            type="password"
                                                            class="form-control"
                                                            id="current-password"
                                                            required
                                                            name="current-password"
                                                            v-bind:class="hasError('current-password')?'is-invalid':''"
                                                        />
                                                         <span v-if="hasError('current-password')" class="invalid-feedback" role="alert">
                                                          <strong>{{getError('current-password')}}</strong>
                                                          
                                                      </span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        for="password"
                                                        class="col-sm-4 col-form-label"
                                                        >{{ $t("form.new_password") }}</label
                                                    >
                                                    <div class="col-sm-8">
                                                        <input
                                                            type="password"
                                                            class="form-control"
                                                            name="password"
                                                            required
                                                            id="password"
                                                             v-bind:class="hasError('password')?'is-invalid':''"
                                                        />
                                                         <span v-if="hasError('password')" class="invalid-feedback" role="alert">
                                                          <strong>{{getError('password')}}</strong>
                                                          
                                                      </span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label
                                                        for="password_confirmation"
                                                        class="col-sm-4 col-form-label"
                                                        >{{$t('form.repeat_new_password')}}</label
                                                    >
                                                    <div class="col-sm-8">
                                                        <input
                                                            type="password"
                                                            class="form-control"
                                                            name="password_confirmation"
                                                            required
                                                            id="password_confirmation"
                                                             v-bind:class="hasError('password_confirmation')?'is-invalid':''"
                                                        />
                                                          <span v-if="hasError('password_confirmation')" class="invalid-feedback" role="alert">
                                                          <strong>{{getError('password_confirmation')}}</strong>
                                                          
                                                      </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <div
                                                class="card-footer"
                                                style="text-align: center;"
                                            >
                                                <button
                                                    type="submit"
                                                    class="btn btn-info center"
                                                >
                                                    <i
                                                        class="ace-icon fa fa-check bigger-110"
                                                    ></i
                                                    >{{$t('form.save')}}
                                                </button>
                                                <button
                                                    type="reset"
                                                    class="btn btn-default center "
                                                >
                                                    <i
                                                        class="ace-icon fa fa-undo bigger-110"
                                                    ></i
                                                    >{{$t('form.clear')}}
                                                </button>
                                            </div>
                                            <!-- /.card-footer -->
                                        </form>
                                    </div>
                                </div>
                                <!-- /.tab-pane -->

                                <div class="tab-pane active" id="info-user">
                                    <form class="form-horizontal">
                                        <div class="form-group row">
                                            <label
                                                for="inputName"
                                                class="col-sm-4 col-form-label"
                                                >{{$t('form.username')}}</label
                                            >
                                            <div class="col-sm-8">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="inputName"
                                                    :value="user.username"
                                                    readonly
                                                    placeholder=""
                                                />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label
                                                for="inputEmail"
                                                class="col-sm-4 col-form-label"
                                                >{{$t('form.name')}}</label
                                            >
                                            <div class="col-sm-8">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    :value="user.name"
                                                />
                                            </div>
                                        </div>

                                        <!-- <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div> -->
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.nav-tabs-custom -->
                </div>
                <!-- /.col -->
            </div>
        </div>
    </div>
</template>

<script>
export default {
  props: {
    
  },
    created() {
        this.token = "Bearer " + window.Laravel.access_token;
        this.getUserInfo();
    },
    data() {
        return {
            user: {},
            current_pass: "",
            password: "",
            confirm_pass: "",
            token: "",
            errors:{},
            avatar: null,
            avatar_url: "img/avata-default.png",
            page_url_user: "/api/user/myinfo",
            page_url_upload_avatar: "api/upload_avatar",
            page_url_changepass: "api/user/changepass"
        };
    },
    methods: {
        getUserInfo() {
            fetch(this.page_url_user , {
                method: "get",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                }
            })
                .then(res => res.json())
                .then(data => {
                    // console.log(data);
                    this.user = data.data;
                    this.avatar_url = this.user.avatar;
                })
                .catch(err => console.log(err));
        },
        handleClickInputFile() {
            // console.log(this);
            this.$refs.file.click();
            this.avatar = this.$refs.file.files[0];
            // this.$refs.file.addEventListener("change", function(e){
            // uploadAvatar();
            // this.changeAvata();
            // }, false);
        },
        emitEvent(event) {
            var reader = new FileReader();
            reader.readAsDataURL(event.target.files[0]);

            reader.onload = () => {
                // console.log(event.target.files[0]);
                const docs = {
                    name: event.target.files[0].name,
                    size: event.target.files[0].size,
                    ext: event.target.files[0].type.split("/").pop(),
                    lastModifiedDate: event.target.files[0].lastModifiedDate,
                    base64: reader.result
                };
               // console.log(docs);
                this.changeAvata(this.user.id, docs);
                // this.$emit('input', docs)
            };
        },
        changeAvata(userId, docs) {
            let formData = new FormData();
            formData.append("file", docs);
            formData.append("user_id", userId);
            // this.avatar_url = 'img/avata-default.png';
            fetch(this.page_url_upload_avatar, {
                method: "post",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                body: JSON.stringify({ user_id: userId, file: docs })
            })
                .then(res => res.json())
                .then(data => {
                    //console.log(data.data.error);
                    if (!data.data.errors) {
                        this.avatar_url = data.data;
                    } else {
                        toastr.options = {
                            positionClass: "toast-bottom-right"
                        };
                        toastr.warning(data.data.errors, "");
                    }
                })
                .catch(err => console.log(err));
        },
        changePass() {

           
            fetch(this.page_url_changepass, {
                method: "post",
                headers: {
                    Authorization: this.token,
                    "content-type": "application/json"
                },
                body: JSON.stringify({
                    "current-password": $("#current-password").val(),
                    password: $("#password").val(),
                    password_confirmation: $("#password_confirmation").val()
                })
            })
                .then(res => res.json())
                .then(data => {
                  //  console.log(data);
                    if (data.data.success == '1') {
                        toastr.success(this.$t('form.change_password_success'), "");
                    } else {
                         toastr.warning(this.$t('form.change_password_error'), "");
                        this.errors = data.data.errors;
                    }
                })
                .catch(err => console.log(err));
        },
        hasError(fieldName) {
            if(this.errors)
              return (fieldName in this.errors);
            else{
              return false;
            }
        },
        getError(fieldName) {
            //console.log(fieldName+"="+ this.errors[fieldName][0]);
            if(this.errors)
            return this.errors[fieldName];
        },
        clearError(event) {
            Vue.delete(this.errors, event.target.name);
            //  console.log(event.target.name);
        }
    }
};
function uploadAvatar() {
    //console.log("upload vatava");
    // var formData = new FormData();
    // formData.append("file", $("#fileAvata").files);
    // formData.append("user_id", 184);

    fetch("api/upload_avatar", {
        method: "post",
        headers: {
            Authorization: "Bearer " + window.Laravel.access_token,
            "content-type": "application/json"
        },
        body: JSON.stringify({ user_id: this.user.id, file: $("#fileAvata") })
    })
        .then(res => res.json())
        .then(data => {
            //console.log(data);
        })
        .catch(err => console.log(err));
}
</script>

<style></style>
