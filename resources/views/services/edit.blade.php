@extends('layouts.appfrontnew')
@section('css')
<link rel="stylesheet" href="assets/css/bootstrap-datepicker3.min.css" />	
<link rel="stylesheet" href="assets/css/bootstrap-datetimepicker.min.css" />
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="main-content">
				<div class="main-content-inner">
					<!--<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Ticket</a>
							</li>
							<li class="active">Create new</li>
						</ul>
					</div> -->
					<div class="page-content">
						<!--<div class="page-header">
							<h1>
								Ticket 
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Create new
								</small>
							</h1>
						</div><!-- /.page-header -->
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
								
						<div class="row">
							<div class="col-md-12">
								 <div class="row justify-content-center">
									<div class="col-md-12">
										<div class="card">
											<div class="card-header">
											<h4>Edit Ticket</h4>
											</div>
											<div class="card-body">
												<form method="POST" enctype="multipart/form-data" id="ticketForm" action="javascript:void(0)" >
												@method('PUT')
												@csrf
												<input type="hidden" class="form-control" id="id" name="id" value="{{$id}}">
												<div class="form-group row">
													<label for="service_category_id" class="col-md-2 col-form-label text-md-right">Service Type </label>
															<div class="col-md-8">
																<select class="form-control" name="service_category_id" id="service_category_id" required>
																</select>
															</div>
													</div>
													<div class="form-group row">
														<label for="name" class="col-md-2 col-form-label text-md-right">Subject</label>
														<div class="col-md-8">
															<input id="title" type="text" class="form-control " name="title" required />
														</div>
												</div>
													<div class="form-group row">
															<label for="name" class="col-md-2 col-form-label text-md-right">Content</label>
															<div class="col-md-8">
															<textarea class="summernote" name="content" id="content"></textarea>
															
																
												</div>
											</div>
											<div class="form-group row">
												<label for="name" class="col-md-2 col-form-label text-md-right">Attachments</label>
												<div class="col-md-8">
												<input type="file" name="file"  id="file">
												<div id="frame">
												
												 </div>
												</div>
											</div>
											<div class="form-group row">
												<label for="name" class="col-md-2 col-form-label text-md-right">Finish date</label>
												<div class="col-md-4">
													<div class="input-group">
														<input id="finish_date" type="date" class="form-control" name="finish_date" required />
														<span class="input-group-addon">
															<i class="fa fa-clock-o bigger-110"></i>
														</span>
													</div>
												</div>
											</div>
													<div class="form-group row mb-0">
														<div class="col-md-6 offset-md-4">
												
															 <a href="" class="btn btn-default">Back</a>
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
				
						<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
							<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
						</a>

					
					</div>
@endsection
@section('script')
		<!-- basic scripts -->

<!-- page specific plugin scripts -->

<script src="assets/js/markdown.min.js"></script>
<script src="assets/js/bootstrap-markdown.min.js"></script>
<script src="assets/js/jquery.hotkeys.index.min.js"></script>
<script src="assets/js/bootstrap-wysiwyg.min.js"></script>
<script src="assets/js/bootbox.js"></script>

 <!-- trang riêng -->
 <script src="assets/js/bootstrap-datepicker.min.js"></script>
 <script src="assets/js/bootstrap-timepicker.min.js"></script>
 <script src="assets/js/moment.min.js"></script>
 <script src="assets/js/daterangepicker.min.js"></script>
 <script src="assets/js/bootstrap-datetimepicker.min.js"></script>
<!-- ace scripts -->
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
					//var html = document.getElementById('editor1').innerHTML;
					//console.log(parseResponse.file[0].url);
					if(parseResponse.success == '1'){
						$('#title').val(parseResponse.data[0].title);
						$('#content').summernote('code',parseResponse.data[0].content);
						if(parseResponse.file[0] != null){
						$('#frame').append(' <span id="frame"><a target="blank" href="http://localhost/tlgportal/storage/app/public/images/'+parseResponse.file[0].url+'"<i class="ace-icon fa fa-paperclip"></i></span>');
						}
						$('#finish_date').val(parseResponse.data[0].finish_date);
					}

				}
			})
		});
		$(document).ready(function() {
			$.ajax({
				headers: {
					'Authorization': `Bearer `+ window.Laravel.access_token ,
				},
				type: 'GET',
				url: '/api/service/selectserviceCategory/'+$("#id").val(),
				success: function(response) {
					console.log(response);
					var parseResponse = JSON.parse(response);
					if(parseResponse.success == '1'){
						for(i=0;i<parseResponse.data.length;i++){
							if(parseResponse.data[i].id == parseResponse.cate){
								$("#service_category_id").append('<option selected value="'+parseResponse.data[i].id+'">'+parseResponse.data[i].name+'</option>');
							}else{
								$("#service_category_id").append('<option value="'+parseResponse.data[i].id+'">'+parseResponse.data[i].name+'</option>');
							}
						}
					}

				}
			})
		})
</script>
<script type="text/javascript">
$(document).ready(function (e) {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
$('#ticketForm').submit(function(e) {
e.preventDefault();

var formData = new FormData(this);
formData.append("content", $('#content').summernote('code') );
$.ajax({
headers: {
'Authorization': `Bearer `+ window.Laravel.access_token ,
},
type:'POST',
url: "/api/service/ticket/"+$("#id").val(),
data: formData,
cache:false,
contentType: false,
processData: false,
success: (data) => {
var parseResponse = JSON.parse(data);
if(parseResponse.success == '1'){
    $('#successMessage').html(parseResponse.message); 
	$("#Message").show();
}
else{
	$('#dangerMessage').html(parseResponse.message); 
	$("#Danger").show();
}
console.log(data);
},
error: function(data){
console.log(data);
}
});
});
});
</script>


<!-- inline scripts related to this page -->


<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
 <script>
      $('.summernote').summernote({
        placeholder: 'Nhập nội dung',
        tabsize: 2,
		height: 120,
        toolbar: [
          //['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
         // ['table', ['table']],
         // ['insert', ['link', 'picture', 'video']],
         // ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    </script>
@endsection