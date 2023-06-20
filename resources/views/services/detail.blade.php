@extends('layouts.appfrontnew')

@section('css')

<link rel="stylesheet" href="plugins/bootstrap-multiselect/css/bootstrap-multiselect.min.css" />
<!-- <link rel="stylesheet" href="assets/css/bootstrap-duallistbox.min.css" />
<link rel="stylesheet" href="assets/css/bootstrap-multiselect.min.css" /> -->
<!-- <link rel="stylesheet" href="assets/css/select2.min.css" /> -->
@endsection
@section('content')
		<div class="main-content">
			<div class="main-content-inner">
	
				<div class="page-content">


					<!-- <div class="page-header">
							<h1>
								Ticket 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Tiêu để ticket ,mua thêm ram
								</small>
							</h1>
						</div>  -->

					<div class="row">
						<div class="col-md-12">
							<div class="row justify-content-center">
								<div class="col-xs-12 col-md-8">
									<div class="card">
										<div class="card-header">
											<h4 class=""><span  id="title_ticket"></span>
												<br> <small id="name_categorie"><i class="menu-icon fa fa-folder red"></i> </small>

											</h4>
										</div>
									 
										<div class="card-body">
											<div class="col-xs-12  col-sm-offset-1">
												<div class="timeline timeline-inverse">

													<div >
													<!-- <i class="fas fa-envelope bg-primary"></i> -->
														<div class="timeline-item ">
																<span class="time"  id="request_date_ticket"><i class="far fa-clock"></i> 20/12/2021 12:05</span>

															  <h3 class="timeline-header"><a href="#">Support Team</a> report</h3>	 
															

															<div>
																<div class="timeline-body">
																	 
																		
																		<input id="id" type="hidden" value="{{$id}}"/>
																		<!-- <td colspan="2"> -->
																			<span class="responsebold"> </span>
																			<div id="content_ticket" class="m-2">
																			</div>
																		<!-- </td> -->
																		<br>
																		<span id="file_ticket"></span>
																	 
																</div>
															</div>
														</div>

													</div><!-- /.timeline-items -->
												</div><!-- /.timeline-container -->
											</div>
										</div>
	
										<div class="card-body form-assign" style="display:none;">
												<div class="row">
													<div class="col-md-12">
													<div id="Message" style="padding-bottom:10px; display:none;">
													<div class="alert alert-success">
														<button type="button" class="close" data-dismiss="alert">
															<i class="ace-icon fa fa-times"></i>
																</button>
																<strong>
																<i class="ace-icon fa fa-check"></i>
																Thông Báo!
																</strong>
																<b id="successMessage"> </b>
																<br>
															</div>
														</div>
													<div id="Danger" style="padding-bottom:10px; display:none;">
														<div class="alert alert-danger">
														<button type="button" class="close" data-dismiss="alert">
																<i class="ace-icon fa fa-times"></i>
																</button>
																<strong>
																<i class="ace-icon fa fa-times"></i>
																Thông Báo!
																</strong>
																<b id="dangerMessage"> </b>
																<br>
														</div>
													</div>
												
														<form class="form-horizontal" role="form" id="assignForm" method="POST" action="javascript:void(0)">
															 
						
															<div class="space-6"></div>
						
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="food">Assignt to</label>
						
																<div class="col-xs-12 col-sm-9">
																	<select id="username" class="multiselect" >
																	</select>
																	
																</div>
															</div>
															<div class="form-group">
																<div class="col-xs-12 col-sm-9">
																	<button class="btn btn-default" onclick="goBack()">Back</button>
																	 <button class="btn btn-primary" type="submit">Save</button>
																</div>
															</div>
														</form>	
													</div>
												</div>
											</div>
									</div>
								</div>
								<div class="col-xs-12 col-md-4">


									<div class="card">
										<div class="card-header">

										</div>

										<div class="card-body">
											<div class="widget-box">
												<div class="widget-header widget-header-flat">
													<div class="timeline-info">
														<img alt="Susan't Avatar"
															src="assets/images/avatars/avatar1.png">

													</div>

													Request by <br>					
													<span  id="create_ticket"> </span><br>
													Phòng ERP
												</div>

												<div class="widget-body">
													<div class="widget-main ">

														<div class="row">
															<div class="col-xs-12">
																<ul class="list-unstyled spaced">
																	<li>
																		<i
																			class="ace-icon fa fa-envelope bigger-110 purple"></i>
																		Email: a.nn@thienlongnv.com
																	</li>

																	<li>
																		<i
																			class="ace-icon fa  fa-bell-o bigger-110 green"></i>
																		Phone: 09X.XXX.XXX
																	</li>


																</ul>
																<hr>
																<ul class="list-unstyled spaced">
																	<li>Ratings:
																		<small>


																			<div class="rating inline"></div>

																			<div class="hr hr-16 hr-dotted"></div>
																		</small>
																	</li>
																	<li>
																		Revies:
																		<small>
																			Cảm ơn đã hỗ trợ nhieu
																		</small>
																		<div class="form-acttion no-padding">
																			<div class="form-group">
																				<textarea name="review" id="" cols="30"
																					rows="2"
																					placeholder="review"></textarea>
																			</div>
																			<div class="form-group">
																				<button
																					class="btn btn-gray <button>Send</button>">Send</button>
																			</div>

																		</div>

																	</li>
																	<li></li>
																</ul>
															</div>
															<div>
																<!--<div class="row">-->
															</div>
															<!--<div class="widget-main">-->

														</div>
														<!--<div class="widget-body">-->
													</div> <!-- <div class="widget-box">-->
												</div>
												<!--<div class="card-body">-->
											</div>



										</div>



									</div>


								</div>

								<!-- /.row -->


							</div><!-- /.page-content -->
						</div>


					 
					</div>
				</div><!-- /.main-content -->




			</div><!-- /.main-container -->

			<div class="row replyRow" style="display: none;">
				<div class="col-md-12" >
						<textarea class="form-control" id="replyComment" placeholder="Add public comment" cols="30" rows="2"></textarea><br>
						
						<button style="float:right;" class="btn btn-sm btn-primary" onclick="isReply=true;" id="addReply">Add Reply</button>
						<button style="float:right;" class="btn btn-link"  onclick="$('.replyRow').hide();">Close</button> 
				</div>
			</div>
@endsection
@section('script')
			<script src="assets/js/markdown.min.js"></script>
			<script src="assets/js/bootstrap-markdown.min.js"></script>
			<script src="assets/js/jquery.hotkeys.index.min.js"></script>
			<script src="assets/js/bootstrap-wysiwyg.min.js"></script>
			<script src="assets/js/bootbox.js"></script>

			<!-- trang riêng -->
			<script src="assets/js/jquery.raty.min.js"></script>
			<script src="assets/js/bootstrap-datepicker.min.js"></script>
			<script src="assets/js/bootstrap-timepicker.min.js"></script>
			<script src="assets/js/moment.min.js"></script>
			<script src="assets/js/daterangepicker.min.js"></script>
			<script src="assets/js/bootstrap-datetimepicker.min.js"></script>
			<!-- ace scripts -->
			<script src="assets/js/ace-elements.min.js"></script>
			<script src="assets/js/ace.min.js"></script>
			<!-- <script src="assets/js/bootstrap-multiselect.min.js"></script>
			<script src="assets/js/select2.min.js"></script> -->
			<script src="plugins/bootstrap-multiselect/js/bootstrap-multiselect.min.js"></script>
			<!-- inline scripts related to this page -->
<script>
		$(document).ready(function() {
			$.ajax({
				headers: {
					'Authorization': `Bearer `+ window.Laravel.access_token ,
				},
				type: 'GET',
				url: '/api/service/ticket/'+$("#id").val(),
				success: function(response) {
					console.log(response);
					var parseResponse = JSON.parse(response);
					var request_date = new Date(parseResponse.data[0].request_date);
					if(parseResponse.success == 1){
						$('#title_ticket').append(parseResponse.data[0].title);
						$('#name_categorie').append(parseResponse.data[0].name_categorie);
						$('#content_ticket').append(parseResponse.data[0].content);
						$('#create_ticket').append(parseResponse.data[0].name_create);
						$('#file_ticket').append('<i class="fa fa-download grey bigger-125"></i> <a target="blank" href="http://localhost/tlgportal/storage/app/public/images/'+parseResponse.file[0].url+'">'+parseResponse.file[0].url+'</a>');
						$('#request_date_ticket').append(request_date.toLocaleDateString());
					}
				}
			});
			$.ajax({
				headers: {
					'Authorization': `Bearer `+ window.Laravel.access_token ,
				},
				type: 'GET',
				url: '/api/service/userTeam/'+$("#id").val(),
				success: function(response) {
					var parseResponse = JSON.parse(response);
					if(parseResponse.success == 1){
						$('.form-assign').removeAttr('style');
						for(i=0; i< parseResponse.data.length;i++){
							if(parseResponse.data[i].id == parseResponse.assign){
								$("#username").append('<option selected value="'+parseResponse.data[i].id+'">'+parseResponse.data[i].name+'</option>');
								}
							else{
								$("#username").append('<option value="'+parseResponse.data[i].id+'">'+parseResponse.data[i].name+'</option>');
							}
						}
				$('.multiselect').multiselect({
				 enableFiltering: true,
				 enableHTML: true,
				 buttonClass: 'btn btn-white btn-primary',
				 templates: {
					button: '<button type="button" class="multiselect dropdown-toggle" data-toggle="dropdown"><span class="multiselect-selected-text"></span> &nbsp;<b class="fa fa-caret-down"></b></button>',
					ul: '<ul class="multiselect-container dropdown-menu"></ul>',
					filter: '<li class="multiselect-item filter"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
					filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default btn-white btn-grey multiselect-clear-filter" type="button"><i class="fa fa-times-circle red2"></i></button></span>',
					li: '<li><a tabindex="0"><label></label></a></li>',
			        divider: '<li class="multiselect-item divider"></li>',
			        liGroup: '<li class="multiselect-item multiselect-group"><label></label></li>'
				 }
				});
					}
				}
			});
			
		$.ajaxSetup({
			headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
			});
			$('#assignForm').submit(function(e) {
			e.preventDefault();
			var formData = new FormData(this);
			formData.append("ticket_id", $("#id").val());
			formData.append("assign_to", $(".multiselect").val());
		$.ajax({
			headers: {
			'Authorization': `Bearer `+ window.Laravel.access_token ,
			},
			type:'POST',
			url: "/api/service/assignTicket",
			data: formData,
			cache:false,
			contentType: false,
			processData: false,
			success: (data) => {
			this.reset();
			
			var parseResponse = JSON.parse(data);
			if(parseResponse.success == '1'){
				$('#successMessage').html(parseResponse.message); 
				$('#Message').removeAttr('style');
			}
			else{
				$('#dangerMessage').html(parseResponse.message); 
				$('#Danger').removeAttr('style');
			}
		console.log(data);
		},
		error: function(data){
		console.log(data);
		}
		});
		});			
		})
</script>
 
<script>
function goBack() {
  window.history.back();
}
</script>
			<script type="text/javascript">
				jQuery(function ($) {
					$('textarea[data-provide="markdown"]').each(function () {
						var $this = $(this);
						if ($this.data('markdown')) {
							$this.data('markdown').showEditor();
						}
						else $this.markdown()
						$this.parent().find('.btn').addClass('btn-white');
					})
					$('.rating').raty({
						'cancel': true,
						'half': true,
						'starType': 'i',
						'click': function () {
							//alert('');
							setRatingColors.call(this);
							this.find('i:not(.star-raty)').addClass('grey');
						},
						/**,
						
					
						'mouseover': function() {
							setRatingColors.call(this);
						},
						'mouseout': function() {
							setRatingColors.call(this);
						}*/
					})//.find('i:not(.star-raty)').addClass('grey');
					function showErrorAlert(reason, detail) {
						var msg = '';
						if (reason === 'unsupported-file-type') { msg = "Unsupported format " + detail; }
						else {
							//console.log("error uploading file", reason, detail);
						}
						$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
							'<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
					}
					//$('#editor1').ace_wysiwyg();//this will create the default editor will all buttons
					//but we want to change a few buttons colors for the third style
					$('#editor1').ace_wysiwyg({
						toolbar:
							[
								'font',
								null,
								'fontSize',
								null,
								{ name: 'bold', className: 'btn-info' },
								{ name: 'italic', className: 'btn-info' },
								{ name: 'strikethrough', className: 'btn-info' },
								{ name: 'underline', className: 'btn-info' },
								null,
								{ name: 'insertunorderedlist', className: 'btn-success' },
								{ name: 'insertorderedlist', className: 'btn-success' },
								{ name: 'outdent', className: 'btn-purple' },
								{ name: 'indent', className: 'btn-purple' },
								null,
								{ name: 'justifyleft', className: 'btn-primary' },
								{ name: 'justifycenter', className: 'btn-primary' },
								{ name: 'justifyright', className: 'btn-primary' },
								{ name: 'justifyfull', className: 'btn-inverse' },
								null,
								{ name: 'createLink', className: 'btn-pink' },
								{ name: 'unlink', className: 'btn-pink' },
								null,
								// { name: 'insertImage', className: 'btn-success' },
								null,
								'foreColor',
								null,
								{ name: 'undo', className: 'btn-grey' },
								{ name: 'redo', className: 'btn-grey' }
							],
						'wysiwyg': {
							fileUploadError: showErrorAlert
						}
					}).prev().addClass('wysiwyg-style2');
					$('.dialogs,.comments').ace_scroll({
						size: 500
					});
					$('#id-input-file-3').ace_file_input({
						style: 'well',
						btn_choose: 'Drop files here or click to choose',
						btn_change: null,
						no_icon: 'ace-icon fa fa-cloud-upload',
						droppable: true,
						thumbnail: 'small'//large | fit
						//,icon_remove:null//set null, to hide remove/reset button
						/**,before_change:function(files, dropped) {
							//Check an example below
							//or examples/file-upload.html
							return true;
						}*/
						/**,before_remove : function() {
							return true;
						}*/
						,
						preview_error: function (filename, error_code) {
							//name of the file that failed
							//error_code values
							//1 = 'FILE_LOAD_FAILED',
							//2 = 'IMAGE_LOAD_FAILED',
							//3 = 'THUMBNAIL_FAILED'
							//alert(error_code);
						}
					}).on('change', function () {
						//console.log($(this).data('ace_input_files'));
						//console.log($(this).data('ace_input_method'));
					});
					/**
					//make the editor have all the available height
					$(window).on('resize.editor', function() {
					var offset = $('#editor1').parent().offset();
					var winHeight =  $(this).height();
					
					$('#editor1').css({'height':winHeight - offset.top - 10, 'max-height': 'none'});
					}).triggerHandler('resize.editor');
					*/
					$('#editor2').css({ 'height': '200px' }).ace_wysiwyg({
						toolbar_place: function (toolbar) {
							return $(this).closest('.widget-box')
								.find('.widget-header').prepend(toolbar)
								.find('.wysiwyg-toolbar').addClass('inline');
						},
						toolbar:
							[
								'bold',
								{ name: 'italic', title: 'Change Title!', icon: 'ace-icon fa fa-leaf' },
								'strikethrough',
								null,
								'insertunorderedlist',
								'insertorderedlist',
								null,
								'justifyleft',
								'justifycenter',
								'justifyright'
							],
						speech_button: false
					});
					$('[data-toggle="buttons"] .btn').on('click', function (e) {
						var target = $(this).find('input[type=radio]');
						var which = parseInt(target.val());
						var toolbar = $('#editor1').prev().get(0);
						if (which >= 1 && which <= 4) {
							toolbar.className = toolbar.className.replace(/wysiwyg\-style(1|2)/g, '');
							if (which == 1) $(toolbar).addClass('wysiwyg-style1');
							else if (which == 2) $(toolbar).addClass('wysiwyg-style2');
							if (which == 4) {
								$(toolbar).find('.btn-group > .btn').addClass('btn-white btn-round');
							} else $(toolbar).find('.btn-group > .btn-white').removeClass('btn-white btn-round');
						}
					});
					//RESIZE IMAGE
					//Add Image Resize Functionality to Chrome and Safari
					//webkit browsers don't have image resize functionality when content is editable
					//so let's add something using jQuery UI resizable
					//another option would be opening a dialog for user to enter dimensions.
					if (typeof jQuery.ui !== 'undefined' && ace.vars['webkit']) {
						var lastResizableImg = null;
						function destroyResizable() {
							if (lastResizableImg == null) return;
							lastResizableImg.resizable("destroy");
							lastResizableImg.removeData('resizable');
							lastResizableImg = null;
						}
						var enableImageResize = function () {
							$('.wysiwyg-editor')
								.on('mousedown', function (e) {
									var target = $(e.target);
									if (e.target instanceof HTMLImageElement) {
										if (!target.data('resizable')) {
											target.resizable({
												aspectRatio: e.target.width / e.target.height,
											});
											target.data('resizable', true);
											if (lastResizableImg != null) {
												//disable previous resizable image
												lastResizableImg.resizable("destroy");
												lastResizableImg.removeData('resizable');
											}
											lastResizableImg = target;
										}
									}
								})
								.on('click', function (e) {
									if (lastResizableImg != null && !(e.target instanceof HTMLImageElement)) {
										destroyResizable();
									}
								})
								.on('keydown', function () {
									destroyResizable();
								});
						}
						enableImageResize();
						/**
						//or we can load the jQuery UI dynamically only if needed
						if (typeof jQuery.ui !== 'undefined') enableImageResize();
						else {//load jQuery UI if not loaded
							//in Ace demo ./components will be replaced by correct components path
							$.getScript("assets/js/jquery-ui.custom.min.js", function(data, textStatus, jqxhr) {
								enableImageResize()
							});
						}
						*/
					}
				});
			</script>

			<script>
				//datepicker plugin
				//link
				$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
				})
					//show datepicker when clicking on the icon
					.next().on(ace.click_event, function () {
						$(this).prev().focus();
					});
				if (!ace.vars['old_ie']) $('#date-timepicker1').datetimepicker({
					//format: 'MM/DD/YYYY h:mm:ss A',//use this option to display seconds
					icons: {
						time: 'fa fa-clock-o',
						date: 'fa fa-calendar',
						up: 'fa fa-chevron-up',
						down: 'fa fa-chevron-down',
						previous: 'fa fa-chevron-left',
						next: 'fa fa-chevron-right',
						today: 'fa fa-arrows ',
						clear: 'fa fa-trash',
						close: 'fa fa-times'
					}
				}).next().on(ace.click_event, function () {
					$(this).prev().focus();
				});
				function reply(caller){
          
					$(".replyRow").insertAfter($(caller));
					$(".replyRow").show();
				}
					
			</script>
@endsection
