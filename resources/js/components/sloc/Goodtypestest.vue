<template>
  <div>
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6">
            <ol class="breadcrumb ">
              <li class="breadcrumb-item">
                <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="/sloc/goodtypes">Các
                    loại hàng hóa</a> </h5>
              </li>
              <li class="breadcrumb-item active">
                <span>Chi tiết</span>

              </li>
            </ol>
          </div>
          <div class="col-sm-6">

            <div class="float-sm-right " style="margin-top:-5px; ">
              <a href="/sloc/goodtypes" class="btn btn-default ">Quay lại</a>

            </div>

          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
                <h1 class="card-title">STT: <strong>{{ goodtypess.id }}</strong></h1>

        <br>
        <h1 class="card-title">Mã loại hàng: <strong>{{ goodtypess.code }}</strong></h1>
        <br>
        <h2 class="card-title">Tên loại hàng: <strong>{{ goodtypess.name }}</strong></h2>
        <br>
        <h3 class="card-title">Mô tả: <strong>{{ goodtypess.description }}</strong></h3>
        <br>
        Ngày tạo: <strong> {{ goodtypess.created_at | formatDate }} </strong>
        <br>
        Ngày cập nhật: <strong> {{ goodtypess.updated_at | formatDate }} </strong>

      </div>
      <!-- <thead>
                  <tr>
                    <th >Ma code</th>
                   
                   
                  
                  </tr>
                  </thead> -->
      <!-- <tbody>
<tr v-for="(term,index) in goodtypes_childs" v-bind:key="index">
                     <td>{{term.code_child}}</td>
                    
                    <td style="text-align:center">
                    
                      </td>
                  
                   
                  </tr>
                  </tbody> -->
      <loading :loading="loading"></loading>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    id: String
  },
  data() {
    return {
      //  goodtypes: [],
      goodtypess: {
        id: "",
        code: "",
        name: "",
        description: "",


      },
      //   goodtypess_terms:[],
      //   fields: [
      //                {
      //   key: 'code',
      //    label:'Mã',
      //   sortable: true,
      //   stickyColumn: true
      // }, {
      //   key: 'name',
      //    label:'Tên',
      //   sortable: true,
      //   stickyColumn: true
      // },

      //   ],
      token: "",
      loading: false,
      url_goodtypes: "/api/sloc/goodtypes",
    }
  },
  created() {
    this.token = "Bearer " + window.Laravel.access_token;

    this.getGoodtypes();
  },
  methods: {
    getGoodtypes() {
      if (this.id != '') {
        this.loading = true;

        var page_url = this.url_goodtypes + "/" + this.id;
        fetch(page_url, { headers: { Authorization: this.token } })
          .then(res => res.json())
          .then(res => {

            if (res.data.success == '1') {
              this.goodtypess = res.data;

              this.loading = false;
            } else {
              this.loading = false;
            }

          }).catch(err => {
            this.loading = false;
            console.log(err);
          });
      }


    },
    getIcon(ext) {
      return getIconFile(ext);
    },
  },
  computed: {
    // goodtypes_childs(){
    //       let child_terms  = [];
    //       if(this.goodtypess && this.goodtypess.childs){
    //       this.goodtypess.childs.forEach(child => {
    //          child.goodtypess_terms.forEach(term => {
    //            term.code_child = child.code;
    //            child_terms.push(term);
    //          });
    //       });
    //       }

    //       return child_terms;
    //     }



  }

}
</script>

<style>
</style>