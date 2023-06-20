<template>
    <div class="card">
          <loading :loading="loading"></loading>
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-3">

            <div class="">
              <div class="card-body box-profile">

                <h3 class="profile-username text-center"><STRONg> {{$t('form.payment_profile')}}</STRONg></h3>

                <p class="text-muted text-center">{{$t('Tổng')}}: {{this.dashboard.payrequest.total}} ( {{$t('form.dossier').toLowerCase()}} )</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>{{$t('Tạo mới')}}</b> <a class="float-right">{{this.dashboard.payrequest.taomoi}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>{{$t('Đang xử lý')}}</b> <a class="float-right">{{this.dashboard.payrequest.chuaduyet}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>{{$t('Duyệt hoàn tất')}}</b> <a class="float-right">{{this.dashboard.payrequest.daduyet}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>{{$t('Đã thanh toán')}}</b> <a class="float-right">{{this.dashboard.payrequest.dathanhtoan}}</a>
                  </li>
                    <li class="list-group-item">
                    <b>{{$t('Đã hủy')}}</b> <a class="float-right">{{this.dashboard.payrequest.dahuy}}</a>
                  </li>
                </ul>

                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.widget-user -->
          </div>
           <div class="col-md-2"></div>
          <div class="col-md-3">

            <div class="">
              <div class="card-body box-profile">

                <h3 class="profile-username text-center"><STRONG>{{$t('form.browser_profile')}}</STRONG> </h3>

                <p class="text-muted text-center">{{$t('Tổng')}}: {{this.dashboard.document.total}} ( {{$t('form.dossier').toLowerCase()}} )</p>

                <ul class="list-group list-group-unbordered mb-3">
                   <li class="list-group-item">
                    <b>{{$t('Tạo mới')}}</b> <a class="float-right">{{this.dashboard.document.taomoi}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>{{$t('Đang xử lý')}}</b> <a class="float-right">{{this.dashboard.document.chuaduyet}}</a>
                  </li>
                  <li class="list-group-item">
                    <b>{{$t('Duyệt hoàn tất')}}</b> <a class="float-right">{{this.dashboard.document.daduyet}}</a>
                  </li>
                    <li class="list-group-item">
                    <b>{{$t('Đã hủy')}}</b> <a class="float-right">{{this.dashboard.document.dahuy}}</a>
                  </li>
                </ul>

                <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->

              </div>
              <!-- /.card-body -->

            </div>
            <!-- /.widget-user -->

          </div>
            <div class="col-md-2">

          </div>
          <!-- /.col -->

        </div>

    </div>
</template>

<script>
import Loading from '../shared/Loading.vue';
export default {
	components: { Loading },
    data() {
        return {
            dashboard:{
                payrequest:{
                    chuaduyet:"",
                    daduyet:"",
                    dathanhtoan:"",

                },
                document:{
                     chuaduyet:"",
                     daduyet:"",

                },

            },
            token:"",
            tabIndex: 0,
            loading: false,
            page_url_dashboard : "api/dashboard",
        };
    },
    methods:{
        getInfo(){
            this.loading = true;
            var page_url = this.page_url_dashboard;
            fetch(page_url,{
                headers:{
                    Authorization:this.token,
                    'content-type':'application/json'
                }}).then(res=>res.json())
                .then(data=>{

                        if (data.success == 1) {
                             this.dashboard = data;


                        }
                        this.loading=false;
                }).catch(err => {
                    console.log(err);
                    this.loading= false;
                });

        },
    },
    created(){
     this.token = "Bearer " + window.Laravel.access_token;
      this.getInfo();
    },
}
</script>

<style>

</style>
