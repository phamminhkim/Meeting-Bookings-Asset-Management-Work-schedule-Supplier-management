@extends('layouts.appfrontnew')

@section('css')

 <style scoped>
        .content{
            background-color: white;
        }
        .dot_underline {
            border-bottom: 1px dotted #cccccc;
            display: inline-table;
            height: 1px;
            width: 70%;
            }
            .dot_underline_custom {
            border-bottom: 1px dotted #cccccc;
            display: inline-table;
            height: 1px;

            }
          
 
 </style>
 <style>
  @page {
    margin: 10mm;
  }

  body {
    
    line-height: 1.3;

    /* Avoid fixed header and footer to overlap page content */
    margin-top: 100px;
    margin-bottom: 50px;
  }

  #header {
    position: fixed;
    top: 0;
    width: 100%;
    height: 100px;
    /* For testing */
    background: yellow; 
    opacity: 0.5;
  }

  #footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    height: 50px;
    font-size: 10pt;
    color: #777;
    /* For testing */
    background: red; 
    opacity: 0.5;
  }

  /* Print progressive page numbers */
  .page-number:before {
    /* counter-increment: page; */
    content: "Page: " counter(page);
  }

</style>
@endsection
@section('content')
<div class="content-header " >
        <div class="container-fluid ml-0">
          <div class="row">
            <div class="col-md-6">
            <!-- <h5 class="m-0 text-dark"><i class="fa fa-home"></i>Hợp đồng / Tạo</h5> -->
             <ol class="breadcrumb ">
              <li class="breadcrumb-item"> <h5 class="m-0 text-dark"><i class="nav-icon fas fa-file-contract"></i> <a href="#" @click.prevent="backToList()">{{__('form.print')}}</a> </h5></li>
              
                <li class="breadcrumb-item active">
                   @if($payrequest->document_type)
                  <span > {{__($payrequest->document_type->name)}} </span> 
                   @endif
                  </li>
             </ol>
            </div> 
            <div class="col-md-6" >
              <div class="float-sm-right "  style="margin-top:-5px; ">
                    <button class="btn btn-default" title="Print" onclick="window.history.back()"> <i class="fas fa-back"></i> {{__('form.back')}}</button>
                    <button class="btn btn-default" title="Print" onclick="window.print()"> <i class="fas fa-print"></i> </button>
              </div>
            </div> 
          </div>
        </div> 
    </div>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->

<!-- <input type="button" value="print 1" onclick="window.print();" /> -->

<div class="container card mt-2" id="divToPrint" style=" min-height:29.7cm;display:block;background-color:white;font-family: 'Times New Roman', Times, serif;">

     
        <?php
            $doctype = "DNTT";
           // dd($payrequest->document_type->code);
            $doctype = $payrequest->document_type->code;
            $file_name = "payment.report.content_" . $doctype;       
        ?>
            @include($file_name)
        
             
   
    <!-- <p style="page-break-before: always"> -->
       
  </div>
@endsection

@section('script')
<!-- <script>
        window.addEventListener('load',window.print());
    </script> -->
@endsection

