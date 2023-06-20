
<style scoped>
.user {
  font-weight: bold;
  color: black;
}
.time,
.reply a {
  color: gray;
}
.userComment {
  font-weight: normal;
  color: #000;
}
.replies .comment {
  margin-top: 2px;
}
.replies {
  margin-left: 50px;
}
#registerModal input,
#loginModal input {
  margin-top: 10px;
}
</style>
    
<template>
<div class="col-sx-12 col-md-12">
     	<div class="row replyRow" style="display: none;">
				<div class="col-md-12" >
						<textarea class="form-control" id="replyComment" placeholder="Add public comment" cols="30" rows="2" v-model="obj.content"></textarea><br>						
						<button style="float:right;" class="btn btn-sm btn-primary" v-on:click="saveComment(obj)" id="addReply">Add Reply</button>
						<button style="float:right;" class="btn btn-link"  v-on:click="close(this)">Close</button> 
				</div>
			</div>
<div class="row">
   <div class="col-md-12">
      <!-- Add Comment -->      
      <div class="row">
         <div class="col-md-12" >
           
            <h4 id="numComment">{{ this.commentlist.length }} Comments</h4>
               <button style="float:right;" class="btn btn-sm btn-info " v-on:click="reply('addComment')" id="addComment"><i class="fa fa-send">Add Comment</i></button>
         </div>
      </div>
      <!-- End Add Comment -->      
   </div>
</div>
<div v-for="cm in fncommentlist" class="userComments p-3">
   <div class="comment callout callout-primary">
      <div class="user">
         {{ cm.user_id }} - {{getUserbyId(cm.user_id).name}}
         <small> <i><span class="time" style="float:right"> Ngày tạo: {{ cm.created_at | formatDate }}</span></i></small>
         <div class="userComment" v-html="cm.content" id ="cm.id"></div>       
            <div class="reply" style="margin-left:5px"><button class="btn btn-xs btn-info "
               v-on:click="subreply(this,cm)"> <i class="fa fa-send">Reply</i></button></div>         
              
            <div class="replies">
               <div class="comment" v-for="subcm in getsubbycomment(cm)">
                  <div class="user" >
                 {{ subcm.user_id }} -- {{getUserbyId(subcm.user_id).name}}
         <small> <i><span class="time"  style="float:right"> Ngày tạo: {{ subcm.created_at | formatDate }}</span></i></small>
                     <div class="userComment" v-html="subcm.content"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>


</div> 
</template>
<script>
export default {
  props: {
    options: {
      type: Array,
      required: false,
    },
    ticket_id: 0,
  },
  data() {
    return {
      commentlist: [],
      aItem: {},
      obj: {},
      userlist:[],
    };
  },
  created() {
     this.getticketbyid(this.ticket_id);
     this.getAllUser();
  },

  computed: {
    fncommentlist: function () {
      var _filterlist = [];
      // console.log('fncommentlist',this.options);
      if (this.commentlist) {
        _filterlist = [...this.commentlist];
        _filterlist = _filterlist.filter(function (item) {
          return (
            item.parent_id == 0 ||
            item.parent_id == "" ||
            item.parent_id == null
          );
        });
      }
      console.log('fncommentlist',_filterlist);
      return _filterlist;
    },

    fnsubcommentlist: function () {
      var _filterlist = [];
      // console.log('fnsubcommentlist',this.options);
      if (this.commentlist) {
        _filterlist = [...this.commentlist];
        _filterlist = _filterlist.filter(function (item) {
          return item.parent_id != "" && item.parent_id != null;
        });        
      }
      console.log('fnsubcommentlist',_filterlist);
      return _filterlist;
    },
  },
  methods: {
    isparent: function (_item) {
      var _parent = _item.parentid + "";
      if (_parent == "") return false;
      else return true;
    },
    addcomment: function () {
      this.$emit("addcomment");
    },
   
    addsubcomment: function (_item) {
      _item.parent_id = _item.id;
      this.$emit("addsubcomment", _item);
    },
    getsubbycomment: function (_item) {
      var _list = [];
      if (this.fnsubcommentlist.length > 0) _list = [...this.fnsubcommentlist];
      _list = _list.filter(function (item) {
        return item.parent_id == _item.id;
      });
      console.log("getsubbycomment",_list);
      return _list;
    },
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

    getcommentbyid: function (id) {
      if (id && id > 0) {
        var pars = {};
        pars.data = { ticket_id: id };
        pars.url = "/api/service/listComment";
        this.promiseApi(pars).then((response) => {
          this.commentlist = response.comments;
        });
      }
    },
     getticketbyid: function (id) {
      if (id && id > 0) {
        var pars = {};
        pars.data = {id:id};
        pars.url = "/api/service/ticketbyId";
        this.promiseApi(pars).then((response) => {
          this.pItem = {...response.data[0]};
          this.commentlist=[...response.comments]; 
        });
      }
    },
    saveComment: function(_item) {          
            var item=_item;  
            item.ticket_id=this.pItem.id;
            item.commentable_id=this.pItem.id;
            this.isReply=true;         
            var pars={};
            pars.data={content:item.content,parent_id:item.parent_id,commentable_id:item.commentable_id,ticket_id:item.commentable_id}
            pars.url='/api/service/comment'
            pars.type='post' 
            //this.commentlist=[];
            this.promiseApi(pars).then( respone=>
               {
                     alert('insert report thành công!');               
                       this.getcommentbyid(this.pItem.id);
                         $('.replyRow').hide();
               }
            );            
        },
    btnreply: function (e) {
      console.log("reply", e);
      
    },
    subreply:function(caller,cm){
          this.obj.parent_id=cm.id;  
          this.obj.content='';
					$(".replyRow").insertAfter($(caller));
					$(".replyRow").show();
				},
    reply:function(caller){
          console.log("reply",caller);       
					$(".replyRow").insertAfter($(caller));
					$(".replyRow").show();
				},
     close:function(caller){
       
          $('.replyRow').hide();
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
