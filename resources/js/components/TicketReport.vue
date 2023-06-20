<style scoped>
.container {
  height: 50px;
  position: relative;
}
.vertical-center {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}
</style>
<template>
  <div class="w3-container"> 
     <!-- <div class="row container">
      <div class="col-sm-4">
        <b>Người báo cáo: </b>
      </div>
      <div class="col-sm-8">
        <b>{{user_id}}</b>
      </div>
    </div>
    <div class="row container">
      <div class="col-sm-4">
        <b>Ticket ID: </b>
      </div>
      <div class="col-sm-8">
        <b>{{ fnaItem.id }}</b>
      </div>
    </div> -->
    <!-- <div class="row container">
      <div class="col-sm-4">
        <b> Nội dung: </b>
      </div>
    </div> -->
    <div class="row ">
      <div class="col-sm-12">
        <div>      
          <ckeditor
            v-model="fnaItem.content"
            v-bind:config="editorConfig"
            v-bind:class="editorClass"
          ></ckeditor>
        </div>
      </div>
    </div>
    <div class="col-sm-12 w3-center p-3"> 

      <div class="w3-center" style="float:right">
     
      <button
          type="button"
          class="btn btn-sm btn-info"
          v-on:click="reportResult(fnaItem)"
          v-show="1" >
         <i class="fa fa-send">Send</i>
        </button>
      </div>
    </div>
  
  </div>
</template>
<
<script>
export default {
     props: {
            id:0,
            user_id:{},              
            },            
    data()     
        { 
    return { 
            //   Action:this.action,
               aItem:{},
                pItem:{},
               obj:{ticket_id:0,content:''}, 
               editorClass : "col-lg-12",   
               editorConfig: {
                    toolbarGroups : [
                        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                        { name: 'forms', groups: [ 'forms' ] },
                        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                        { name: 'links', groups: [ 'links' ] },
                        { name: 'insert', groups: [ 'insert' ] },
                        { name: 'styles', groups: [ 'styles' ] },
                        { name: 'colors', groups: [ 'colors' ] },
                        { name: 'tools', groups: [ 'tools' ] },
                        { name: 'others', groups: [ 'others' ] },
                        { name: 'about', groups: [ 'about' ] }              
                    ],
                    removeButtons: 'NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image'
                }                                  
            }; 
        }, 
    created()   
    {
        this.getticketbyid(this.id);
    },
    computed:{  
          fnpItem(){ 
            var _item ={...this.pItem};           
        
           return _item;
                },          
         fnaItem(){ 
            var _item ={...this.pItem};           
        _item.content='';
           return _item;
                },     
              
    }, 
    methods:
        {           
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
        // pars.data = { id: id };
        pars.url = "/api/service/ticket/" + id;
        this.promiseApi(pars).then((response) => {
         this.pItem = response.data[0];
          console.log("getticketbyid", response.data);
        });
      }
    },  
    reportResult: function(_item) {          
            var item=_item;       

            var pars={};
            pars.data={ticket_id:item.ticket_id,content:item.content}
            pars.url='/api/service/reportResult'
            pars.type='post'

            this.promiseApi(pars).then( respone=>
               {                   
                     alert('insert report thành công!');
               }
            );            
        },  
   reportResult:function(_item)
            {
                var item=_item;
                item.ticket_id = this.pItem.id;
                  console.log('reportResult _item',_item);
                var pars={};
                pars.data={ticket_id:item.ticket_id, user_id:1,content:item.content}
                pars.url='/api/service/reportResult'
               // pars.dataType='post'
               pars.type='post'
                this.promiseApi(pars).then( respone=>
                    {
                            console.log('alert _item',_item);
                          alert('insert comment thành công!');
                    });
            },

} ,
}

</script>
