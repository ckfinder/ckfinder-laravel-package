@extends('ckfinder::samples/layout')

@section('content')
	<h1>jQuery Mobile Skin</h1>

	<p>CKFinder UI is based on <a href="http://jquerymobile.com/">jQuery Mobile</a> so its look &amp; feel can be changed using the <a href="http://themeroller.jquerymobile.com/">jQuery Mobile Theme Roller</a>.
		For more information about custom skins and Theme Roller please refer to <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/guide/dev_themeroller">CKFinder documentation</a>.</p>

	<h3>jQuery Mobile Swatch "a" Skin </h3>
	<pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget', {
	width: '100%',
	height: 600,
	skin: 'jquery-mobile',
	swatch: 'a'
} );</code></pre>
	<div id="ckfinder-widget-a"></div>

	<h3>jQuery Mobile Swatch "b" Skin </h3>
	<pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget', {
	width: '100%',
	height: 600,
	skin: 'jquery-mobile',
	swatch: 'b'
} );</code></pre>
	<div id="ckfinder-widget-b"></div>
@stop

@section('scripts')
	<script>
		CKFinder.widget( 'ckfinder-widget-a', {
			width: '100%',
			height: 600,
			skin: 'jquery-mobile',
			swatch: 'a'
		} );

		CKFinder.widget( 'ckfinder-widget-b', {
			width: '100%',
			height: 600,
			skin: 'jquery-mobile',
			swatch: 'b'
		} );
	</script>
	<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
