@extends('layouts.appfrontnew')

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
 <style>
 .bootbox{
	 opacity:1;
 }
 </style>
@section('content')
<!-- <script src="https://eoffice.thienlong.vn/Link/Scripts/Eoffice10/ckeditor/ckeditor.js"></script> -->
<!-- <div > -->
      <myticket ></myticket>     
      <!-- <textarea type="text"  class="form-control" cols="80" id="editor1" name="editor1" rows="6"></textarea> -->
<!-- </div> -->
<!-- <script>
		// var ckValue =jQuery('<textarea />').html(CKEDITOR.instances['editor1'].getData()).val();
		CKEDITOR.disableAutoInline = true; 
		CKEDITOR.replace( 'editor1' );
    </script> -->
<!-- 
<script type="application/json">
CKEDITOR.disableAutoInline = true; 
CKEDITOR.replace( 'editor1' );
var myvue = new Vue({
        el:'#myapp',
        data: {
            ticketlist:[]
        },
        created(){
              this.getallticket();
        },
        method()
        {
           getallticket: function()
           {      
               var pars={};
               pars.data={}
               pars.url='/api/service/showall';
               this.promiseApi(pars).then(response=>{    
                    console.log('getallticket response',response);                
                    this.ticketlist = response; 
                    
                }); 
            },
        }
});
</script> -->
@endsection
@section('script')
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.4/summernote.js"></script>
<!--<script>
  $(function () {
    // Summernote
    $('#editor_Noidung').summernote(
        {
       
        height: 200
      }
    );
  });
</script> -->
@endsection