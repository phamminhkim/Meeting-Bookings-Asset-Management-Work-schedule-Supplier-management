<template>
    <div class="">
        <div class="col-xs-12 col-md-12">
            <!-- /.page-header -->
            <div class="">
                <div class="col-xs-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"><b>List Ticket</b></div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Subject</th>
                                        <th>Nội dung</th>
                                        <th>Type</th>
                                        <!-- <th>Contact</th> -->
                                     
                                        <th>Assign to</th>
                                        <th>Create date</th>
                                        <!-- <th>Exprire data</th> -->
                                        <th>
                                              <a href="./service/create" title="Thêm mới" class="fa fa-plus" target="_blank">   </a>
                                            Action    
                                            
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>                                  
                                  <tr>
                                      <td colspan="7">                                         
                                          </td>
                                      </tr>
                                <tr
                                        v-for="tk in tks"
                                        v-on:click="ticketSelecting(tk)"      >
                                        <td>{{ tk.id }}</td>
                                        <td>{{ tk.title }}</td>
                                         <td><span v-html="tk.content"></span></td>
                                        <td>
                                            {{ tk.service_category_id }}
                                        </td>
                                       
                                        <!-- <td>{{tk.create_by}} </td>
                           <td>{{tk.request_by}} </td> -->
                                        <td> {{tk.name_assign}}</td>
                                        <td>{{ tk.request_date}}</td>
                                        <!-- <td>{{tk.finished_at}} </td> -->
                                        <td> 
                                            <a href="./service/assign" title="Phân công" class="btn btn-info btn-xs fa fa-flag" target="_blank">   </a>                                                                                            
                                      
                                            <button
                                                type="button"
                                                class="btn btn-info btn-xs fa fa-file"
                                                value="Input Button"
                                                v-on:click="reportTicket(tk)"
                                                title="Report"
                                            >
                                                <i class=""></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-info btn-xs fa fa-star"
                                                value="Input Button"
                                                v-on:click="ratingTicket(tk)"
                                                title="Rating"
                                            >
                                                <i class=""></i>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-info btn-xs fa-user-graduate"
                                                value="Input Button"                                              
                                                title="Assign"
                                                v-on:click="assignTicket(tk)"
                                            >
                                                <i class=""></i>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <br/>
                           <br/>
                            <p v-show="isShowComment">
                                <!-- <span><i>Comment List</i></span>
                                  <button  v-on:click="isShowComment=false"     class="btn btn-info btn-xs fa fa-comment" title='hide comment' ></button>       -->
                                                                                      
                                   <ticketcommentlist
                                            v-bind:options="fnConmmentList"  
                                            v-bind:aItem="aItem"                                           
                                            v-on:addcomment="addcomment"
                                            v-on:addsubcomment="addsubcomment"                                      
                                        ></ticketcommentlist>   
                                        </p>
                                        <!-- <p>                                              
                                              <myticket-item
                                            v-bind:aItem="aItem"
                                            v-bind:categorylist="categorylist"
                                            v-bind:isAction="0"
                                            v-on:onadd="addTicket"
                                            v-on:onupdate="editTicket"
                                     
                                        ></myticket-item>    
                                            </p> -->
                        </div>
                    </div>
        
        
                </div>
            </div>
        </div>
      <!-- The Modal idComment -->
            <div class="modal" id="idComment">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <!-- Modal Header -->
                        <div class="modal-header">
                            <div class="modal-title"> 														
								<h2>Comment Ticket</h2>
														
						</div>
                            <button type="button" class="close w3-right" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">         
                           
                              <ticket-comment v-bind:pItem="aItem" v-bind:aItem="aComment" v-on:savecomment="saveComment"></ticket-comment>
                        </div>
                                  
                    </div>
                </div>
            </div>
         <!-- End The Modal idComment -->

       <!-- The Modal idReport -->
            <div class="modal" id="idReport">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Report Ticket</h4>
                            <button type="button" class="close w3-right" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                        
                        <myticket-report
                                            v-bind:pItem="aItem"
                                            v-bind:action="reportAction"
                                            v-on:reportresult="addreport"
                                        ></myticket-report>                                        
                        </div>               
                    </div>
                </div>
            </div>
         <!-- End The Modal idReport -->
          <!-- The Modal idViewReport -->
            <div class="modal" id="idViewReport">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Report Ticket</h4>
                            <button type="button" class="close w3-right" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                        <myticket-reportlist
                                            v-bind:pItem="aItem"
                                            v-bind:action="reportAction"                                    
                                        ></myticket-reportlist>
                                        
                        </div>               
                    </div>
                </div>
            </div>
         <!-- End The Modal idViewReport -->
           <!-- The Modal idRating -->
            <div class="modal" id="idRating">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Report Ticket</h4>
                            <button type="button" class="close w3-right" data-dismiss="modal">&times;</button>
                        </div>
                        <!-- Modal body -->
                        <div class="modal-body">
                        <myticket-rating   v-bind:pItem="aItem" v-on:reviewrating="reviewrating"></myticket-rating>
                        </div>               
                    </div>
                </div>
            </div>
         <!-- End The Modal idRating -->
           <!-- The Modal idAssign -->
            <div class="modal" id="idAssign">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <!-- Modal Header -->                       
                        <!-- Modal body -->
                        <div class="modal-body">    
                              <myticket-assign 
                                v-bind:aItem="aItem"
                                v-bind:categorylist="categorylist"
                              ></myticket-assign>
                        </div>
                                  
                    </div>
                </div>
            </div>
         <!-- End The Modal idAssign -->
    </div>
</template>
<script>
import TicketReport from './TicketReport.vue';
export default {
  components: { TicketReport },    
    data() {
        return {           
            tks: this.data,
            commentlist:[],
            reportlist:[],
            categorylist:[],
            title: "Danh sách Tícket",

            aItem: {ticket_id:0,content:' '}, // current ticket
            aComment: {ticket_id:0,content:' '}, //current comment
            msg: "test",
            reportAction: { isAction: false, isShow: false },
            itemAction:{isAction:0},

            iscomment:false,
            isShowComment:false,
        };
    },
    created() {
       
         this.getallticket();      
         this.getallCategory();
    }, 
    computed:
    {
        fnConmmentList: function()
        {
            var _filterRows=this.commentlist;
            var sortDir =-1;
            var sortKey="created_at";
              _filterRows = _filterRows.slice().sort(function(a, b) {
                            return (a[sortKey] > b[sortKey]?1:-1)*sortDir;
                        });
         console.log("fnConmmentList",_filterRows);
               return _filterRows;  
        },
       
    }  ,
    methods: {  
        headers: function() {
            var _headers = {};      
             var _token = window.Laravel.access_token;
           // console.log('_token',_token);
            _headers.Authorization = 'Bearer '+ _token;
            _headers.Accept = 'application/json';
            return _headers;
        },

        promiseApi: function(pars) {
            var results = [];
            pars.headers = this.headers();
             //  console.log('header',document.cookie);
            let _promise2 = new Promise((resolve, reject) => {
                $.ajax({
                    type: pars.type || "get",
                    dataType: pars.dataType || "json",
                    url: pars.url,
                    data: pars.data,
                    headers: pars.headers
                })
                    .done(data => {
                        resolve(data);
                        console.log("pars", pars);
                    })
                    .fail(errmsg => {
                        reject(errmsg);
                    });
            });
            return _promise2;
        },
      
        ticketSelecting: function(item) {
            this.aItem = item;    
            this.isShowComment=true;     
        //    console.log("ticketSelecting",item);
        this.getcommentbyid(item);
        },
        //ACTION
        CommentTicket: function(item) {
                this.aItem = item;               
                this.getcommentbyid(item);  
                    $('#idComment').modal('toggle');
                    $('#idComment').modal('show');
                },
       
        reportTicket: function(item) {
                    this.aItem = item;
                    
                    $('#idReport').modal('toggle');
                    $('#idReport').modal('show');
                },
        viewreportTicket: function(item) {
                    this.aItem = item;
                    this.getreportbyTicketId(item); 
                    $('#idViewReport').modal('toggle');
                    $('#idViewReport').modal('show');
                },
         addcomment: function() {
             
             console.log('addcomment',this.aItem);
              $('#idComment').modal('toggle');
              $('#idComment').modal('show');
                //  this.iscomment=true;                
                },
         addsubcomment: function(item) {
              console.log('addsubcomment',item);
              this.aComment = item;
               $('#idComment').modal('toggle');
               $('#idComment').modal('show');
                  //  this.iscomment=true;                
                },
        ratingTicket: function(item) {
                    this.aItem = item;  
                    alert('ratingTicket');                  
                    $('#idRating').modal('toggle');
                    $('#idRating').modal('show');
                },
        assignTicket: function(item) {
                    this.aItem = item;  
                    alert('assignTicket');                  
                     $('#idAssign').modal('toggle');
                     $('#idAssign').modal('show');
                },
                       
                
        ///END ACTION
        //get all category
        getallCategory: function()
        {            
           var pars = {};
            pars.data = {};       
            pars.url = "/api/service/serviceCategory";           

            this.promiseApi(pars).then(response => {            
             
                this.categorylist = response.data;
                   console.log("getallCategory",this.categorylist);
            });      
        },

        getallticket: function() {
            var pars = {};
            pars.data = {};
          //  pars.url = "/api/service/showall";
              pars.url = "/api/service/ticket";
           

            this.promiseApi(pars).then(response => {
                console.log("getallticket response", response);
                this.tks = response.data;
            });
        },

        //Get All By Assign
        loadAssign: function() {
            this.getticketAsign(4);
        },

        getticketAsign: function(userid) {
            var pars = {};
            pars.data = { user_id: userid };
            pars.url = "/api/service/listForUserAssign";
            this.promiseApi(pars).then(response => {
                this.tks = response.data;
                console.log("pars", pars);
                console.log("getticketAsign this.tks", this.tks);
            });
        },
        //Get All By Assign
        getticketbyCreated: function(userid) {
            var pars = {};
            pars.data = { userid: userid };
            pars.url = "/api/service/listForUserCreate";
            this.promiseApi(pars).then(response => {
                this.tks = response.data;
                console.log("getticketbyCreated this.tks", this.tks);
            });
        },
       
        //END ACTION

        //TICKET COMMENTS
        getcommentbyid: function(item) {           
            if (item) {
                var pars = {};
                pars.data = { ticket_id: item.id };
                pars.url = "/api/service/listComment";
                this.promiseApi(pars).then(response => {                      
                    this.commentlist = response.comments ;                                                             
                });
            }
        },
        //end comment
        //TICKET Reports
        getreportbyTicketId: function(item) {           
            if (item) {
                var pars = {};
                pars.data = { ticket_id: item.id };
                pars.url = "/api/service/listReport";
                this.promiseApi(pars).then(response => {                      
                    this.reportlist = response ;                                                             
                });
            }
        },
        //end comment
        //rating
        reviewrating: function(item) {
            if (this.pitem) 
                item.objectable_id = this.pitem.id; 
            var pars = {};
            pars.data = {
                reviews: item.reviews,                
                rating: item.rating,
                objectable_id: item.objectable_id,              
            };
            pars.url = "/api/service/reviewsRating";
            pars.type = "post";
            this.promiseApi(pars).then(respone => {
                alert("insert rating thành công!");
                $('#idRating').modal('hide');
            });
        },
        //end rating
        //report
       
        addreport: function(_item) {          
            var item=_item;       

            var pars={};
            pars.data={ticket_id:item.ticket_id,content:item.content}
            pars.url='/api/service/reportResult'
            pars.type='post'

            this.promiseApi(pars).then( respone=>
               {
                     console.log('alert _item',_item);
                     alert('insert report thành công!');
                     $('#idReport').modal('hide');
               }
            );            
        },          
        saveComment: function(_item) {          
            var item=_item;  
          alert('saveComment _item');
          alert(JSON.stringify( item));
            var pars={};
            pars.data={content:item.content,parent_id:item.parent_id,commentable_id:item.commentable_id,ticket_id:item.commentable_id}
            pars.url='/api/service/comment'
            pars.type='post'           


            this.promiseApi(pars).then( respone=>
               {
                     alert('insert report thành công!');
                       $('#idComment').modal('hide');
                       this.getcommentbyid(this.aItem);
               }
            );            
        },
        addTicket: function(item){
        var pars={};
            pars.data={content:item.content,
                        note:item.note,
						service_category_id:item.service_category_id,
						request_date:item.request_date,
						finish_date:item.finish_date,
						company_id:item.company_id,
						project_id:item.project_id,
						finished_at:item.finished_at,
						create_by:item.create_by,
						request_by:item.request_by,
						team_id:item.team_id}
            pars.url='/api/service/store'
            pars.type='post'           


            this.promiseApi(pars).then( respone=>
               {
                     alert('insert ticket thành công!');
                      // $('#idComment').modal('hide');
                      // this.getcommentbyid(this.aItem);
               }
            );            
        },
        editTicket: function(){

        },
        //end report
        test: function() {
            alert("Nhận emit");
        }
    }
};
         
        // viewCommentTicket: function(item) {
        //         this.aItem = item;
        //             $('#idComment').modal('toggle');
        //             $('#idComment').modal('show');
        //         },

</script>
 