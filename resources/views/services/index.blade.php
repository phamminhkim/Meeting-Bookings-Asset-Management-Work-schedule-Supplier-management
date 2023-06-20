@extends('layouts.appfrontnew')

@section('css')
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
 <style>
 .bootbox{
	 opacity:1;
 }
 </style>
@endsection
@section('content')

<div class="main-content">
				<div class="main-content-inner">
					<!-- <div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Ticket</a>
							</li>

							<li>
								<a href="#">My Ticket</a>
							</li>
							
						</ul> 

						<div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div> 
					</div> -->

					<div class="page-content">
						 

						<!-- <div class="page-header">
								<h1>
								My Ticket
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									
								</small>
							</h1>
						</div>  -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
							
								<div class="row">
									<div class="col-xs-12">
										<table id="example" class="table  table-bordered table-hover">
											<thead>
												<tr>	 
													<th>ID</th>
													<th >Status</th>
													 <th style="min-width:30px;" >Subject</th>
													<th>Type</th>
												
													<th>Assign to</th>
													<th>Create date</th>
													<th>Finish date</th>
													<th>Edit</th>
													<th>Delete</th>
													<th>Add</th>
													<th>Detail</th>
												</tr>
											</thead>

											<tbody id="ticket-content">
	
											</tbody>
										</table>
										
									</div><!-- /.span -->
								</div><!-- /.row -->

								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

@endsection
@section('script')
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js" ></script> -->
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" ></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js" ></script>
<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js" ></script>
<!-- <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script> -->
<!-- <script src="https://makitweb.com/demos/resources/bootstrap/js/bootstrap.min.js"></script> -->
<script src="assets/js/bootbox.js"></script>
<script>
		$(document).ready(function() {
			$.ajax({
				headers: {
					'Authorization': `Bearer `+ window.Laravel.access_token ,
				},
				type: 'GET',
				url: '/api/service/ticket',
				success: function(response) {
					$('#ticket-content').empty();
					var parseResponse = JSON.parse(response);
					if(parseResponse.success == '1'){
						$.each(parseResponse.data, function(index, item) {
							var request_date = new Date(item.request_date);
							if(item.finish_date != null){
							var finish_date1 = new Date(item.finish_date);
							var finish_date = finish_date1.toLocaleDateString();
							}else{
								var finish_date = "";
							}
						
							if(item.name_assign == undefined){
								var name_assign = "";
							}
							else{
								var name_assign = item.name_assign;
							}
							
							$('#ticket-content').append('<tr><td><a href="service/detail/'+item.id+'">'+item.id+'</a></td><td><span class="label label-sm label-success">Hoàn tất</span></td><td>'+item.title+'</td><td>'+item.name_categorie+'</td><td>'+name_assign+'</td><td>'+request_date.toLocaleDateString()+'</td><td>'+finish_date+'</td><td ><a  class="btn btn-xs text-green" href="service/edit/'+item.id+'"><i class="fa fa-edit bigger-120"></i></a> </td><td style="text-align: center;"><button class="btn btn-xs text-red delete" value="'+item.id+'" ><i class="ace-icon fa fa-trash bigger-120"></i></button></td><td>   <a  class="btn btn-xs text-green" href="service/create"><i class="ace-icon fa fa-plus"></i></a></td><td>   <a  class="btn btn-xs text-green" href="service/report/'+item.id+'"><i class="ace-icon fas fa-file"></i></a></td></tr>');
						})
						 $('#example').dataTable({response,
							"order": [[ 0, "desc" ]],
							"bInfo" : false
						} );
					}

				}
			})
		});
    $(document).on("click", ".delete", function() { 
		var $ele = $(this).parent().parent();
        var id= $(this).val();
		bootbox.confirm("Do you really want to delete record?", function(result) {
        if(result){
		$.ajax({
			headers: {
					'Authorization': `Bearer `+ window.Laravel.access_token ,
				},
			url: '/api/service/ticket/'+id,
			type: 'DELETE',
			cache: false,
			data:{
				_token:'{{ csrf_token() }}'
			},
			success: function(data){
				var dataResult = JSON.parse(data);
				if(dataResult.success ==1){
					$ele.fadeOut().remove();
				}
				else{
					alert('Ticket is Assigned');
				}
			}
		});
		}
		});
	});

	function report(item)
	{
		alert(item);
	}
</script>

@endsection