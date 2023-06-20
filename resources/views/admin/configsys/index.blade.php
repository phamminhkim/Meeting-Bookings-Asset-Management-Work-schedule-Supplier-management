@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="admin/home">Admin</a>
            </li>
            <li class="active">{{__('form.id')}}</li>
        </ul><!-- /.breadcrumb -->
		  @include('admin.configsys.search')
    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
			{{__('form.list')}}: {{__('form.configsys')}}

            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')

                <a class="btn btn-sm" href="{{route('adminconfigsyss.create')}}">{{__('form.create')}}</a>
                <div class="row">

                    <div class="col-xs-10">
                        <table class=" table table-bordered table-hover">
                            <thead>

                                <th>{{__('form.code')}}</th>
                                <th>{{__('form.value')}}</th>

                                <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($configsyss as $configsys)
                            <tr>

                            <td>
                            {{$configsys->id}}
                            </td>

                            <td>
                                {{$configsys->value}}
                            </td>

                            <td>

                                <a href="{{route('adminconfigsyss.edit',$configsys->id)}}"><button class="btn btn-link" style="float:left;"> <i class="ace-icon fa fa-pencil blue bigger-130"></i></button></a>

                                <form action="{{route('adminconfigsyss.destroy',$configsys->id)}}" method="POST">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    <button type="submit" onclick="return confirm('{{__('form.confirm')}}');" class="btn btn-link " style="float:left;"><i class=" fa fa-trash bigger-120"></i></button>
                                </form>


                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$configsyss->links()}}

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
