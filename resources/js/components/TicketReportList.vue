<template>
<div>  
  <div class="card">    

    <div class="card-header" v-html="pItem.content"></div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                      <!-- timeline time label -->
                      <!-- <div class="time-label">
                        <span class="bg-danger">
                          10 Feb. 2014
                        </span>
                      </div> -->
                      <!-- /.timeline-label -->
                      <!-- timeline item -->
                      <div  v-for="tk in options">
                        <i class="fas fa-envelope bg-primary"></i>

                        <div class="timeline-item">                       
                       <div class="timeline-header row">
                         <span  class="col-sm-6 col-lg-6">
                          <h5><a href="#">{{tk.user_id}}</a></h5></span>
                          <span  class="col-sm-6 col-lg-6">
                           <span class="time" style="float: right;"><i class="fas fa-clock"></i>  {{tk.created_at}}   </span>
                          </span>
                          </div>
                          <div class="timeline-body"  v-html="tk.content">                         
                          </div>
                          <div class="timeline-footer ">    
                           
                                    
                          </div>
                        </div>
                      </div>        
                      <div>
                             
                        </div>
                      </div>
                      <!-- END timeline item -->
                      <!-- timeline time label -->                    
                      <!-- /.timeline-label -->                 
                  </div>
                  <!-- /.tab-pane -->
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
  
  
  </div>
</template>
<script>
export default {
  props: {
   ticket_id: 0,
   user_id:{}
    
  },
  data() {
    return {
      aItem: {},
      Action: this.action,
      obj: { ticket_id: 0, content: "" },
      options: [],
      userlist:[],
      pItem:{},
    };
  },
  created() {
  this.getticketbyid(this.ticket_id);
  this.getAllUser();
  },
  computed: {
    fnpItem() {
      var _item = this.pItem;
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
      if (id && id > 0) {
        var pars = {};
        pars.data = { id: id };
        pars.url = "/api/service/ticketbyId";
        this.promiseApi(pars).then((response) => {
          this.pItem = { ...response.data[0] };
          this.options = [...response.reports];
          console.log('getticketbyid report',response);
        });
      }
    },
    reportResult: function (_item) {
      var item = _item;
      item.ticket_id = this.pItem.id;
      console.log("reportResult _item", _item);
      var pars = {};
      pars.data = {
        ticket_id: item.ticket_id,
        user_id: 1,
        content: item.content,
      };
      pars.url = "/api/service/reportResult";
      pars.dataType = "post";
      pars.type = "post";
      this.promiseApi(pars).then((respone) => {
        console.log("alert _item", _item);
        alert("insert comment thành công!");
      });
    },
      getAllUser: function()
        {
        var pars = {};
        pars.data = { };
        pars.url = "/api/user/all";
        this.promiseApi(pars).then((response) => {
          this.userlist = response.data;
        });
        },
        getUserbyId:function(id)
        {
          var _filterlist= this.userlist;
          _filterlist=_filterlist.filter(item=>{
            return item&&item.id==id;
          });
          if(_filterlist.length>0)
          return _filterlist[0];
          else return{};
        }
  },
};
</script>
