<template>				
<div class="main-content">
				<div class="main-content-inner">				
					<div class="page-content">				
								
						<div class="row">
							<div class="col-md-12">
								 <div class="row justify-content-center">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header">
											<h4>Edit Ticket</h4>
											</div>
											<div class="card-body">
												<form  id="ticketForm" action="javascript:void(0)" >
												
												<div class="form-group row">
													<label for="service_category_id" class="col-md-2 col-form-label text-md-right">Service Type </label>
															<div class="col-md-8" >
																<select class="form-control"  required>
                                                                     <option v-for="item in categorylist" v-bind:value="item.id" >{{ item.name }}</option>
																</select>
															</div>
													</div>
													<div class="form-group row">
														<label for="name" class="col-md-2 col-form-label text-md-right">Subject</label>
														<div class="col-md-8">
														<input id="title" type="text" class="form-control" v-model="fnItem.title" name="title" required />
														</div>
												</div>
													<div class="form-group row">
															<label for="name" class="col-md-2 col-form-label text-md-right">Content</label>
															<div class="col-md-10">
															<!-- <textarea class="summernote" name="content" id="content"></textarea> -->
															<ckeditor v-model="fnItem.content"></ckeditor>
																
												</div>
											</div>
											<div class="form-group row">
												<label for="name" class="col-md-2 col-form-label text-md-right">Attachments</label>
												<div class="col-md-8">
												<input type="file" name="file"  id="file" >
												<div id="frame">
												
												 </div>
												</div>
											</div>
											<div class="form-group row">
												<label for="name" class="col-md-2 col-form-label text-md-right">Finish date</label>
												<div class="col-md-4">
													<div class="input-group">
														<input id="finish_date" type="date" class="form-control" name="finish_date" required v-model="fnItem.finish_date"/>
														<span class="input-group-addon">
															<i class="fa fa-clock-o bigger-110"></i>
														</span>
													</div>
												</div>
											</div>
													<div class="form-group row mb-0">
														<div class="col-md-6 offset-md-4">														
															<button type="submit"  class="btn btn-primary">
																Save
															</button>
				
														</div>
													</div>
											</form>
											</div>
										</div>
									</div>
								</div>
				
								<!-- /.row -->
							</div><!-- /.page-content -->
						</div>
	
						</div><!-- /.main-content -->
				
						<div class="footer">
							<div class="footer-inner">
								<div class="footer-content">
									<a href="#"></a>
								</div>
							</div>
						</div>				
					
					</div>
</div>

</template>
<script>

export default {
     props: {        
          categorylist:{
			  	type:Array,
				require:false,
		  },
		  aItem:{},
     },            
    data()     
        { 
            return {           
                obj:{   
						id:0,
						content:'',
                        request_date:new Date(),
                        note:'',
                        service_category_id:0,
						request_date:new Date(),
						finish_date:new Date(),
						company_id:'1000',
						project_id:'',
						finished_at:new Date(),
						create_by:'',
						request_by:'',
						team_id:'',
                        }  ,
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
     fnItem: function()
	 {
		 if(this.aItem)
		 	return this.aItem;
		 else
			return this.obj;
	 }
    },
    methods:
        {          
           
          
        },
     }


</script>
