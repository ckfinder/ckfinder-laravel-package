@extends('ckfinder::samples/layout')

@section('content')
	<h1>Moono Skin</h1>

	<p>Moono is a default skin used in CKFinder that provides visual integration with CKEditor.</p>

	<div id="ckfinder-widget"></div>
@stop

@section('scripts')
	<script>
		CKFinder.widget( 'ckfinder-widget', {
			width: '100%',
			height: 700
		} );
	</script>
	<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
