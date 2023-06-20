<div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
          
           <h5 class="m-0 text-dark"><i class="fa fa-home"></i> {{$menucurr->title}}</h5>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"> <a href="#">{{ __('form.home') }}</a></li>
            
                @include('layouts.breadcrumbfront_child',['menuparent'=>$menucurr->parents()->get()->first()])
              <!-- <li class="breadcrumb-item">

                @if($menucurr->parents()!=null && $menucurr->parents()->get()->first()!=null)
                <a href="{{$menucurr->parents()->get()->first()->link}}">{{$menucurr->parents()->get()->first()->title}}</a>
                @endif
                </li> -->
              <li class="breadcrumb-item active">{{$menucurr->title}}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
