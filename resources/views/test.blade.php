@extends('layouts.appfront')

@section('content')
    <h1>Balloon editor</h1>
	<textarea type="text"  class="form-control" cols="80" id="editor1" name="editor1" rows="6"></textarea>   
    <script>
		// var ckValue =jQuery('<textarea />').html(CKEDITOR.instances['editor1'].getData()).val();
		CKEDITOR.disableAutoInline = true; 
		CKEDITOR.replace( 'editor1' );
    </script>
@endsection