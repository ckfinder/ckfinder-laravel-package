@extends('ckfinder::samples/layout')

@section('content')
	<h1>Read-only Mode</h1>
	<p>Read-only mode can be enabled in CKFinder with the <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/api/CKFinder.Config-cfg-readOnly"><code>readOnly</code></a>
		configuration option. The user will be able to browse the files but will not be able to introduce any changes. Thanks to this setting you will be able to use
		CKFinder as an online gallery.</p>
	<p>Note: This will only disable certain UI elements. In order to successfully block file uploads and modifications, or to set read-only permissions for particular
		folders, you will need to adjust <a href="https://docs.ckeditor.com/ckfinder/ckfinder3-php/configuration.html#configuration_options_accessControl">ACL settings</a>
		accordingly in the server-side configuration file.</p>

	<pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget', {
	readOnly: true,
	width: '100%',
	height: 500
} );</code></pre>

	<div id="ckfinder-widget"></div>
	<h2>Simple Gallery</h2>
	<p>With a little bit of imagination it is possible to turn CKFinder into a very simple gallery. Here CKFinder is configured to
		open a file on double click, run without a toolbar and without the folders panel.</p>
	<div id="ckfinder-widget2"></div>
	<p>The code behind this setup is quite simple:</p>
	<pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget2', {
	displayFoldersPanel: false,
	height: 500,
	id: 'gallery',
	readOnly: true,
	readOnlyExclude: 'Toolbars',
	thumbnailDefaultSize: 143,
	width: '100%'
} );
		</code></pre>
@stop

@section('scripts')
	<script>
		CKFinder.widget( 'ckfinder-widget2', {
			displayFoldersPanel: false,
			height: 500,
			// The main reason why ID is set here is "thumbnailDefaultSize" specified below.
			// Without setting the ID, CKFinder would use remembered user setting from previously used instance.
			id: 'gallery',
			readOnly: true,
			readOnlyExclude: 'Toolbars',
			thumbnailDefaultSize: 143,
			width: '100%'
		} );
	</script>
	<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
