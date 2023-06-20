    
<template >
  <div class="w3-container p-3 border">    
    <form>        
                <div class="form-group row">
                  <label
                    for="service_category_id"
                    class="col-sm-2 col-form-label text-md-right"
                    >Service Type
                  </label>
                  <div class="col-sm-8 col-md-6 col-form-label text-md-left">
                  {{fnItem.name_categorie}}
                  </div>
                </div>
                <div class="form-group row">
                  <label
                    for="name"
                    class="col-sm-2  col-form-label text-md-right"
                    >Subject</label
                  >
                  <div class="col-sm-8 col-md-6 col-form-label text-md-left">
                    {{fnItem.title }}
                  </div>
                </div>
                <div class="form-group row">
                  <label
                    for="name"
                    class="col-sm-2 col-form-label text-md-right"
                    >Content</label
                  >
                  <div class="col-sm-8 col-md-6 col-form-label text-md-left">
                    <div v-html="fnItem.content"></div>
                  </div>
                </div>
                <div class="form-group row">
                  <label
                    for="name"
                    class="col-sm-2  col-form-label text-md-right"
                    >Attachments</label>
                  <div class="col-sm-8 col-md-6 col-form-label text-md-left">
                    <!-- <input type="file" name="file" id="file" v-model="" /> -->
                    <a  v-bind:href="fnItem.files"/>{{fnItem.files}}
                    <div id="frame"></div>
                  </div>
                </div>
                <div class="form-group row">
                  <label
                    for="name"
                    class="col-sm-2 col-form-label text-md-right"
                    >Finish date</label
                  >
                  <div class="col-sm-8 col-md-6 col-form-label text-md-left">
                    <div class="input-group">
                      {{fnItem.finish_date}}
                      <span class="input-group-addon">
                        <!-- <i class="fa fa-clock-o bigger-110"></i> -->
                      </span>
                    </div>
                  </div>
                </div>
                </form>
              </div>   
</template>

<script>
export default {
  // props: { tickets: Array, message: String },
  data() {
    return {
      aItem: {},
    };
  },
  props: {
    ticket_id: 0,
  },
  created() {
    this.getticketbyid(this.ticket_id);
  },
  computed: {
    fnItem: function () {
      var _item = {};
      if (this.aItem) _item = { ...this.aItem };
      return _item;
    },
  },
  methods: {
    headers: function () {
      var _headers = {};
      var _token = window.Laravel.access_token;
      // console.log('_token',_token);
      _headers.Authorization = "Bearer " + _token;
      _headers.Accept = "application/json";
      return _headers;
    },
    promiseApi: function (pars) {
      var results = [];
      pars.headers = this.headers();
      //  console.log('header',document.cookie);
      let _promise2 = new Promise((resolve, reject) => {
        $.ajax({
          type: pars.type || "get",
          dataType: pars.dataType || "json",
          url: pars.url,
          data: pars.data,
          headers: pars.headers,
        })
          .done((data) => {
            resolve(data);
            console.log("pars", pars);
          })
          .fail((errmsg) => {
            reject(errmsg);
          });
      });
      return _promise2;
    },
    getticketbyid: function (id) {
      if (id) {
        var pars = {};
       // pars.data = { id: id };
        pars.url = "/api/service/ticket/"+id;
        this.promiseApi(pars).then((response) => {
          this.aItem = response.data[0];
          console.log('getticketbyid',response.data);
        });
      }
    },
  },
};
</script>