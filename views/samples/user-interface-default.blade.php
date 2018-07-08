@extends('ckfinder::samples/layout')

@section('content')
	<h1>Default User Interface</h1>
	<p>By default folders are displayed in CKFinder in a folder tree panel, like in the example below.</p>

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
