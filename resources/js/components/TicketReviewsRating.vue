<template>
    	<form class="form-horizontal" role="form">              
			<h3 class="smaller lighter black" style="font-size: 15px;">Report Ticket</h3>
            <div>
				ticket ID: {{fnpItem.id}}
			</div>
			<div class="form-group">
				<input type="text" class="wysiwyg-editor" id="editor1" name="content"	lines="5" v-model="obj.content"/>
			</div>
			<!-- <div class="form-group"  >
				<input type="file" multiple v-model="fnItem.comment">
			</div>  -->
				<div class="form-actions center">
				<button type="button" class="btn btn-xs btn-success" v-on:click="reportResult(obj)" v-show='iscomment'>
					
				</button>
			</div>
		</form>
</template>
<script>

export default {
     props: {
            pItem:{},  
            action:{},    
            },            
    data()     
        { 
            return {  
               aItem:{},
               Action:this.action,
               obj:{ticket_id:0,content:''},
            }; 
        }, 
    created()
        {
            
            console.log('created message',this.message);   
        },
    computed:{
         fnpItem(){ 
            var _item =  this.pItem; 
           return _item;
                }, 
        fnaItem(){ 
            var _item =  this.aItem; 
           return _item;
                }, 
    },
    methods:
        {
            promiseApi: function (pars) {
            var results = [];
            // pars.headers = this.headers();
            let _promise2 = new Promise((resolve, reject)=>{
                $.ajax({
                    type: pars.type || 'get',
                    dataType: pars.dataType || "json",
                    url: pars.url,
                    data: pars.data,
                    // headers: pars.headers,
                }).done((data)=>{
                    resolve(data);
                })
                .fail((errmsg)=>{reject(errmsg);})
            });
            return _promise2;
            },  
            alert: function(_item)
            {
                    console.log('alert _item',_item);
            //     alert("Xin chaof Report Ticket", this.a_Item);
            //     console.log('this.Raction',this.Action);
            //     this.Action.isReportDisplay=!this.Action.isReportDisplay;
            //    console.log('this.Action.isReportDisplay',this.Action.isReportDisplay);
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
                    }
                );
            }
        },

}
</script>
