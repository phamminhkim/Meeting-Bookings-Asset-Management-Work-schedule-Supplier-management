@extends('layouts.appfront')

@section('css')

@endsection
@section('content')

<div class="main-content">
				<div class="main-content-inner">
					<div class="breadcrumbs ace-save-state" id="breadcrumbs">
						<ul class="breadcrumb">
							<li>
								<i class="ace-icon fa fa-home home-icon"></i>
								<a href="#">Ticket</a>
							</li>
							<li class="active">Assign User</li>
						</ul><!-- /.breadcrumb -->
				
						<!-- <div class="nav-search" id="nav-search">
							<form class="form-search">
								<span class="input-icon">
									<input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" />
									<i class="ace-icon fa fa-search nav-search-icon"></i>
								</span>
							</form>
						</div> -->
						<!-- /.nav-search -->
					</div>
				
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
							<div class="col-xs-12">
								<div class="row justify-content-center">
									<div class="col-xs-12 col-md-8">
										<div class="card">
											<div class="card-header">
												
													<h4 class=""></i> Mua thêm ram
														<br> <small><i class="fa fa-folder-o "></i> IT Support  </small> 
														
														 
													</h4>
													
											</div>
											<hr>
											<div class="card-body">
												<div class="col-xs-12 col-sm-10 col-sm-offset-1">
													<div class="timeline-container timeline-style2">
														<span class="timeline-label">
															<b>Today</b>
														</span>
		
														<div class="timeline-items">
															<div class="timeline-item clearfix">
																<div class="timeline-info">
																	<span class="timeline-date">11:15 pm</span>
		
																	<i class="timeline-indicator btn btn-info no-hover"></i>
																</div>
		
																<div class="widget-box transparent">
																	<div class="widget-body">
																		<div class="widget-main no-padding">
																			 
																			<td colspan="2">
																				<span class="responsebold"> </span><div>
																					Dear A.Kiên,</div>
																				<div>
																					&nbsp;</div>
																				<div>
																					Nhờ anh chuyển TR lên QAS(300,400,500):</div>
																				<div>
																					&nbsp;DEVK914891&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; DEVK914889 PXK: ZL08 thêm barcode(1)</div>
																				<div>
																					&nbsp;</div>
																				<div>
																					Thanks,</div>
																				<div>
																					Ngọc Hùng</div>
																				<div>
																					&nbsp;</div>
																				
																				</td>
																			<br>
																			<i class="fa fa-download grey bigger-125"></i>
																			<a href="#">File ABC.pdf </a>|	<a href="#">File XY.pdf </a>
																		</div>
																	</div>
																</div>
															</div>
		 
														</div><!-- /.timeline-items -->
													</div><!-- /.timeline-container -->
		 
										 
												</div>

												
											</div>
											<div class="card-body">
												<div class="row">
													<div class="col-md-12">
														<form class="form-horizontal" role="form">
															 
						
															<div class="space-6"></div>
						
															<div class="form-group">
																<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="food">Assignt to</label>
						
																<div class="col-xs-12 col-sm-9">
																	<select id="food" class="multiselect" multiple="">
																		<option value="cheese">Hung.nn</option>
																		<option value="tomatoes">hoa.lv</option>
																		<option value="mozarella">thi.nt</option>
																		<option value="mushrooms">lam.tv1</option>
																	 
																	</select>
																</div>
															</div>
															<div class="form-group">
																 
						
																<div class="col-xs-12 col-sm-9">
																	<button class="btn btn-default" type="submit">Back</button>
																	 <button class="btn btn-primary" type="submit">Save</button>
																</div>
															</div>
														</form>
													</div>
												</div>
											</div>
											
										</div>
									</div>

									<div class="col-xs-12 col-md-4" >
									 
										 
												<div class="card" >
													<div class="card-header" >
														
													</div>
												 
													<div class="card-body">
														<div class="widget-box">
															<div class="widget-header widget-header-flat">
																<div class="timeline-info">
																	<img alt="Susan't Avatar" src="assets/images/avatars/avatar1.png">
																	
																</div>
																
																Request by <br>
																<span class="label label-info label-sm">Nguyễn Ngọc Hùng  </span><br>
																Phòng ERP
															</div>
				
															<div class="widget-body">
																<div class="widget-main">
																	 															
																	<div class="row">
																		<div class="col-xs-12">
																			<ul class="list-unstyled spaced">
																				<li>
																					<i class="ace-icon fa fa-envelope bigger-110 purple"></i>
																					Email: hung.nn@thienlongnv.com
																				</li>
				
																				<li>
																					<i class="ace-icon fa  fa-bell-o bigger-110 green"></i>
																					Phone: 09X.XXX.XXX
																				</li>
				
																				 
																			</ul>
																			<hr>
																			<ul class="list-unstyled spaced">
																				<li>Ratings: 
																					<small>
																						<div class="rating inline" style="cursor: pointer;"></i>&nbsp;<i data-alt="1" class="star-on-png" title="bad"></i>&nbsp;<i data-alt="2" class="star-on-png" title="poor"></i>&nbsp;<i data-alt="3" class="star-on-png" title="regular"></i>&nbsp;<i data-alt="4" class="star-off-png" title="good"></i>&nbsp;<i data-alt="5" class="star-off-png" title="gorgeous"></i><input name="score" type="hidden" value="3"></div>
																					</small>
																				</li>
																				<li>
																				  Revies:
																				  <small>
																					  Cảm ơn đã hỗ trợ nhie
																				  </small>

																				</li>
																				<li></li>
																			</ul>
																		</div>
																	<div>
																</div>
															</div>
														</div>
													</div>	 
												</div>			
												 
											 
											
										</div>
										

									 
									</div> 
								</div>
				
								<!-- /.row -->
							</div><!-- /.page-content -->
						</div>
						
				
						<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
							<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
						</a>
					</div>
			</div><!-- /.main-content -->

@endsection
@section('script')

@endsection