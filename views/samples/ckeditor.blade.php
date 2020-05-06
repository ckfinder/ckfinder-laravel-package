@extends('ckfinder::samples/layout')

@section('content')
    <h1>CKEditor Integration</h1>

    <h2>CKEditor 5</h2>
    <p>To integrate CKFinder with CKEditor 5
        all you have to do is pass some additional configuration options to CKEditor:</p>
    <pre class="prettyprint"><code>ClassicEditor
	.create( document.querySelector( '#editor2' ), {
		ckfinder: {
            // Use named route for CKFinder connector entry point
			uploadUrl: '@{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files'
		}
	} )
	.catch( error => {
		console.error( error );
	} );
</code></pre>
    <p>The sample below presents the result of the integration. Try <strong>pasting images from clipboard</strong> directly into the editing area as well as <strong>dropping images</strong> &mdash; the files will be saved on the fly by CKFinder.</p>
    <div id="editor2"></div>

    <h2>CKEditor 4</h2>
    <p>To <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/guide/dev_ckeditor">integrate CKFinder with CKEditor</a>
        all you have to do is pass some additional configuration options to CKEditor:</p>
    <pre class="prettyprint"><code>CKEDITOR.replace( 'editor1', {
	// Use named CKFinder browser route
	filebrowserBrowseUrl: '@{{ route('ckfinder_browser') }}',
	// Use named CKFinder connector route
	filebrowserUploadUrl: '@{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files'
} );</code></pre>
    <p>It is also possible to use <code>CKFinder.setupCKEditor()</code> as shown below, to automatically setup integration between CKEditor and CKFinder:</p>
    <pre class="prettyprint"><code>var editor = CKEDITOR.replace( 'ckfinder' );
CKFinder.setupCKEditor( editor );</code></pre>
    <p>The sample below presents the result of the integration. You can manage and select files from your server when creating links or embedding images in CKEditor 4 content. In modern browsers you may also try <strong>pasting images from clipboard</strong> directly into the editing area as well as <strong>dropping images</strong> &mdash; the files will be saved on the fly by CKFinder.</p>
    <div id="editor1"></div>
@stop

@section('scripts')
    <style>
        .ck-editor__editable {
            min-height: 200px;
        }
    </style>
    <script src="https://cdn.ckeditor.com/4.14.0/standard-all/ckeditor.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/19.0.0/classic/ckeditor.js"></script>
    <script>
		// Note: in this sample we use CKEditor with two extra plugins:
		// - uploadimage to support pasting and dragging images,
		// - image2 (instead of image) to provide images with captions.
		// Additionally, the CSS style for the editing area has been slightly modified to provide responsive images during editing.
		// All these modifications are not required by CKFinder, they just provide better user experience.
		if ( typeof CKEDITOR !== 'undefined' ) {
			CKEDITOR.disableAutoInline = true;
			CKEDITOR.addCss( 'img {max-width:100%; height: auto;}' );
			var editor = CKEDITOR.replace( 'editor1', {
				extraPlugins: 'uploadimage,image2',
				removePlugins: 'image',
				height:250
			} );
			CKFinder.setupCKEditor( editor );
		} else {
			document.getElementById( 'editor1' ).innerHTML =
				'<div class="tip-a tip-a-alert">This sample requires working Internet connection to load CKEditor 4 from CDN.</div>'
		}

		if ( typeof ClassicEditor !== 'undefined' ) {
			ClassicEditor
				.create( document.querySelector( '#editor2' ), {
					ckfinder: {
						// To avoid issues, set it to an absolute path that does not start with dots, e.g. '/ckfinder/core/php/(...)'
						uploadUrl: '{{ route('ckfinder_connector') }}?command=QuickUpload&type=Files&responseType=json'
					},
					toolbar: [ 'ckfinder', 'imageUpload', '|', 'heading', '|', 'bold', 'italic', '|', 'undo', 'redo' ]
				} )
				.then( function( editor ) {
					// console.log( editor );
				} )
				.catch( function( error ) {
					console.error( error );
				} );
		} else {
			document.getElementById( 'editor2' ).innerHTML =
				'<div class="tip-a tip-a-alert">This sample requires working Internet connection to load CKEditor 5 from CDN.</div>'
		}

    </script>
    <script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
