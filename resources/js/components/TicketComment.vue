
<template>

    	<form class="form-horizontal" role="form">  
           
            <div class="form-group row">
                    <div class="col-md-5">
                        <div  class="col-md-5 col-form-label "><b>Ticket:</b> </div>
                    
                    </div>
                    <div  class="col-md-7"><b>{{fnaItem.commentable_id}}</b>
                    </div>
            </div>
           <div class="form-group row">
               <div class="col-md-7">
                    <div  class="col-md-7 col-form-label ">
                            <b>Nội dung:              </b>
                    </div>
             </div>
         </div>   
		 <div class="form-group row">
             <!-- <ckeditor ></ckeditor>        -->
             <!-- <ckeditor v-model="fnaItem.content" ></ckeditor>    -->
             <textarea class="form-control" v-model="fnaItem.content" placeholder="Add public comment" cols="30" rows="2"></textarea><br>             
         </div>    
         	 <div class="form-group row">
		
				<div class="form-actions  w3-container">
				<button type="button" class="btn btn-sm btn-success " v-on:click="savecomment(fnaItem)" >
					SEND
				</button>
			</div>
            </div>
		</form>
</template>
<script>

export default {
     props: {
            pItem:{}, //current ticket  .
            aItem:{}, // current comment
     },            
    data()     
        { 
            return {             
               Action:this.action,     
               editorUrl: "https://cdn.ckeditor.com/4.14.1/full-all/ckeditor.js",        
                editorClass : "col-lg-12",   
                editorConfig: {
                    toolbarGroups : [
                        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                        // { name: 'forms', groups: [ 'forms' ] },
                        // { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                        // { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                        // { name: 'links', groups: [ 'links' ] },
                        // { name: 'insert', groups: [ 'insert' ] },
                        { name: 'styles', groups: [ 'styles' ] },
                        { name: 'colors', groups: [ 'colors' ] },
                        // { name: 'tools', groups: [ 'tools' ] },
                        // { name: 'others', groups: [ 'others' ] },
                        // { name: 'about', groups: [ 'about' ] }              
                    ],
                    removeButtons: 'NewPage,Print,Save,Templates,Replace,Find,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CreateDiv,Anchor,Flash,Smiley,PageBreak,ShowBlocks,About,Language,Iframe,Image'
                },  
            }; 
        },    
    computed:{       
      fnaItem(){ 
            var _item = {};   
            _item.commentable_id=this.pItem.id;
            if(this.aItem)
            _item.parent_id=this.aItem.parent_id;
            console.log('fnaItem',_item);
           return _item;
                },     
    },
    methods:
        {           
            savecomment:function(_item)
            {                   
            //   alert(JSON.stringify(_item));
                  this.$emit('savecomment',_item);                          
            }, 
          
        },
     }


</script>
