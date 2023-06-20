@extends('layouts.appadmin')

@section('content')
<div class="main-content-inner">
    <div class="breadcrumbs ace-save-state" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-home home-icon"></i>
                <a href="admin/home">Admin</a>
            </li>
            <li class="active">{{__('form.maillog')}}</li>
        </ul><!-- /.breadcrumb -->
		  @include('admin.maillog.search')
    </div>

    <div class="page-content">

        <div class="page-header">
            <h1>
			{{__('form.list')}}: {{__('form.maillog')}}

            </h1>
        </div><!-- /.page-header -->

        <div class="row">
            <div class="col-xs-12">

                @include('partials.alert')

                <!-- <a class="btn btn-sm" href="{{route('adminmaillogs.create')}}">{{__('form.create')}}</a> -->
                <div class="row">

                    <div class="col-xs-10">
                        <table class=" table table-bordered table-hover"">
                            <thead>

                                <th>ID</th>
                                <th>Date</th>
                                <th>From</th>
								 <th>To</th>
								 <th>Cc</th>
								 <th>Subject</th>
                                 <th>Success</th>
                                 <th>Resend</th>
                                 <th>Action</th>
                            </thead>
                            <tbody>
                                @foreach($maillogs as $maillog)
                            <tr>

                            <td>
                            {{$maillog->id}}
                            </td>

                            <td>
                                {{$maillog->date}}
                            </td>
                            <td>
                                {{$maillog->from}}
                            </td>
                            <td>
                                {{$maillog->to}}
                            </td>
                            <td>
                                {{$maillog->cc}}
                            </td>

                            <td>
                                {{$maillog->subject}}
                            </td>
                            <td>
                                @if ($maillog->sent_success == 1)
                                    YES
                                @else
                                    NO
                                @endif
                            </td>
                            <td>
                                {{$maillog->resend}}
                            </td>
                            <td>
                            <a href="{{route('adminmaillogs.edit',$maillog->id)}}"><button class="btn btn-link" style="float:left;"> <i class="ace-icon fa fa-pencil blue bigger-130"></i></button></a>
                                <!-- <a href="{{route('adminmaillogs.show',$maillog->id)}}"><button class="btn btn-link" style="float:left;"> <i class="ace-icon fa fa-envelope blue bigger-130"></i></button></a> -->

                                <form action="{{route('adminmaillogs.destroy',$maillog->id)}}" method="POST">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    <button type="submit" onclick="return confirm('{{__('form.confirm')}}');" class="btn btn-link " style="float:left;"><i class=" fa fa-trash bigger-120"></i></button>
                                </form>

                                <form action="admin/resend_maillog/{{$maillog->id}}" target="_blank" method="GET">
                                    @csrf

                                    <!-- <a href="admin/resend_maillog/{{$maillog->id}}"><button class="btn btn-link" style="float:left;"> resend</i></button></a> -->
                                    <button type="submit" onclick="return confirm('Bạn muốn gửi lại email này cho user');" class="btn btn-link " style="float:left;">Re-send</button>
                                </form>



                            </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{$maillogs->links()}}

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
