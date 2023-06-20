@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="admin/home">Admin</a>
            </li>
            <li class="active">{{__('form.company')}}</li>
        </ul><!-- /.breadcrumb -->
		  @include('admin.company.search')
    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
			{{__('form.list')}}: {{__('form.company')}}

            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')

                <a class="btn btn-sm" href="{{route('admincompanies.create')}}">{{__('form.create')}}</a>
                <div class="row">

                    <div class="col-xs-10">
                        <table class=" table table-bordered table-hover"">
                            <thead>
								 
                                <th>{{__('form.code')}}</th>
                                <th>{{__('form.name')}}</th>                                
								 <th>{{__('form.status')}}</th>     
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($companies as $company)
                            <tr>
							
                            <td>
                            {{$company->id}}
                            </td>   
							
                            <td>
                                {{$company->name}}
                            </td>
							 <td>
							@if($company->active == '1') on
							@else
							off
							@endif
							</td>                     
                            <td>
                              
                                <a href="{{route('admincompanies.edit',$company->id)}}"><button class="btn btn-link" style="float:left;"> <i class="ace-icon fa fa-pencil blue bigger-130"></i></button></a>

                                <form action="{{route('admincompanies.destroy',$company->id)}}" method="POST">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    <button type="submit" onclick="return confirm('{{__('form.confirm')}}');" class="btn btn-link " style="float:left;"><i class=" fa fa-trash bigger-120"></i></button>
                                </form>
                             

                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$companies->links()}}

                    </div><!-- /.col -->
                </div>
                <!-- /.row -->
            </div><!-- /.page-content -->
        </div>
    </div>
</div>
<style>
    /* fix khoảng cách bên dưới table so với phân trang */

    .table {
        margin-bottom: 0px;
    }
</style>

@endsection

@section('script')
<script>

</script>
@endsection