@extends('ckfinder::samples/layout')

@section('content')
	<h1>Mobile User Interface</h1>
	<p>Mobile user interface is enabled automatically when the width of the working area of the application gets below the value
		defined in the <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/api/CKFinder.Config-cfg-uiModeThreshold"><code>uiModeThreshold</code></a>
		configuration option.</p>
	<p>Note: This mode works best on mobile devices, as touch and swipe events are not enabled for desktop browsers.</p>

	<div id="ckfinder-widget" style="width: 500px; margin: 30px auto 0;"></div>
@stop

@section('scripts')
	<script>
		CKFinder.widget( 'ckfinder-widget', {
			displayFoldersPanel: false,
			width: 500,
			height: 700
		} );
	</script>
	<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
