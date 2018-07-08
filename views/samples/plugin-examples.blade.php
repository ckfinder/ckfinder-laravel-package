@extends('ckfinder::samples/layout')

@section('content')
	<h1>Plugin Examples</h1>
	<p>The example below shows the <code>StatusBarInfo</code> plugin that displays basic information about the selected file in the application status bar.
		You can find more plugin examples in the  <a href="https://github.com/ckfinder/ckfinder-docs-samples">CKFinder sample plugins repository</a>.
		Please have a look at <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/guide/dev_plugins">plugin documentation</a>, too.</p>
	<pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget', {
	width: '100%',
	height: 500,
	plugins: [
		// The path must be relative to the location of the ckfinder.js file.
		'../samples/plugins/StatusBarInfo/StatusBarInfo'
	]
} );</code></pre>
	<div id="ckfinder-widget"></div>
@stop

@section('scripts')
	<script>
		CKFinder.widget( 'ckfinder-widget', {
			width: '100%',
			height: 500,
			plugins: [
				// Path must be relative to the location of ckfinder.js file
				'samples/plugins/StatusBarInfo/StatusBarInfo'
			]
		} );
	</script>
	<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
