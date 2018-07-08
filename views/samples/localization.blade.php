@extends('ckfinder::samples/layout')

@section('content')
	<h1>Localization</h1>

	<p>CKFinder interface is automatically displayed in the user's language (as set in the browser or operating system settings).</p>
	<p>The language of the CKFinder UI can also be set manually by using the <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/api/CKFinder.Config-cfg-language"><code>language</code></a> configuration option.</p>

	<div class="tip-a" style="padding-top:0.5em;padding-bottom:0.5em">
		<h4>Translations Needed!</h4>
		<p>At the moment many localizations are incomplete. Please feel free to <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/guide/dev_translations">provide translations for your native language</a>. Your help will be much appreciated!</p>
	</div>

	<p>In the example below CKFinder is started in Brazilian Portuguese.</p>

	<pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget', {
	language: 'pt-br',
	width: '100%',
	height: 500
} );</code></pre>

	<div id="ckfinder-widget"></div>
@stop

@section('scripts')
	<script>
		CKFinder.widget( 'ckfinder-widget', {
			language: 'pt-br',
			width: '100%',
			height: 500
		} );
	</script>
	<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
