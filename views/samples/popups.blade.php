@extends('ckfinder::samples/layout')

@section('content')
    <h1>Popup Mode</h1>

    <p>The <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/guide/dev_installation-section-using-ckfinder-in-a-popup-window">popup mode</a> is most suitable for selecting files that are stored on a server.<br />
        Click the button below to open the popup and choose any file. After that you will see basic information
        about the file that was selected, including the URL.</p>

    <div class="popup-example">
        <button id="ckfinder-popup" class="button-a button-a-background" style="float: left">Open Popup</button>
        <div id="output" style="float: left;font-size: 0.8em;line-height: 1.4em;margin: 3px 7px;">
            <span id="file-name"></span>
            <br>
            <span id="file-url"></span>
        </div>
    </div>
    <div style="clear: both"></div>

    <p>Additionally, CKFinder supports a special file selection mode for images called <strong>Choose Resized</strong>. This feature
        allows you to resize the selected image to any size that is suitable for you. The CKFinder connector will automatically create
        a resized version of the image and return its URL.</p>

    <h2>Multiple Popups</h2>
    <p>In some cases you may need more than one popup to handle multiple places that require selecting a file.
        Below you can find an example that fills each of the inputs with the URL of the selected file.</p>

    <input id="ckfinder-input-1" type="text" style="width:60%">
    <button id="ckfinder-popup-1" class="button-a button-a-background">Browse Server</button>

    <div style="height: 5px"></div>

    <input id="ckfinder-input-2" type="text" style="width:60%">
    <button id="ckfinder-popup-2" class="button-a button-a-background">Browse Server</button>

    <pre class="prettyprint"><code>var button1 = document.getElementById( 'ckfinder-popup-1' );
var button2 = document.getElementById( 'ckfinder-popup-2' );

button1.onclick = function() {
	selectFileWithCKFinder( 'ckfinder-input-1' );
};
button2.onclick = function() {
	selectFileWithCKFinder( 'ckfinder-input-2' );
};

function selectFileWithCKFinder( elementId ) {
	CKFinder.popup( {
		chooseFiles: true,
		width: 800,
		height: 600,
		onInit: function( finder ) {
			finder.on( 'files:choose', function( evt ) {
				var file = evt.data.files.first();
				var output = document.getElementById( elementId );
				output.value = file.getUrl();
			} );

			finder.on( 'file:choose:resizedImage', function( evt ) {
				var output = document.getElementById( elementId );
				output.value = evt.data.resizedUrl;
			} );
		}
	} );
}
</code></pre>
@stop

@section('scripts')
<script>
    var button = document.getElementById( 'ckfinder-popup' );

    button.onclick = function() {
        CKFinder.popup( {
            chooseFiles: true,
            width: 800,
            height: 600,
            onInit: function( finder ) {
                finder.on( 'files:choose', function( evt ) {
                    var file = evt.data.files.first();
					var outputFileName = document.getElementById( 'file-name' );
					var outputFileUrl = document.getElementById( 'file-url' );
                    outputFileName.innerText = 'Selected: ' + file.get( 'name' );
                    outputFileUrl.innerText = 'URL: ' + file.getUrl();
                } );

                finder.on( 'file:choose:resizedImage', function( evt ) {
					var outputFileName = document.getElementById( 'file-name' );
					var outputFileUrl = document.getElementById( 'file-url' );
					outputFileName.innerText = 'Selected resized image: ' + evt.data.file.get( 'name' );
					outputFileUrl.innerText = 'URL: ' + evt.data.resizedUrl;
                } );
            }
        } );
    };

    var button1 = document.getElementById( 'ckfinder-popup-1' );
    var button2 = document.getElementById( 'ckfinder-popup-2' );

    button1.onclick = function() {
        selectFileWithCKFinder( 'ckfinder-input-1' );
    };
    button2.onclick = function() {
        selectFileWithCKFinder( 'ckfinder-input-2' );
    };

    function selectFileWithCKFinder( elementId ) {
        CKFinder.popup( {
            chooseFiles: true,
            width: 800,
            height: 600,
            onInit: function( finder ) {
                finder.on( 'files:choose', function( evt ) {
                    var file = evt.data.files.first();
                    var output = document.getElementById( elementId );
                    output.value = file.getUrl();
                } );

                finder.on( 'file:choose:resizedImage', function( evt ) {
                    var output = document.getElementById( elementId );
                    output.value = evt.data.resizedUrl;
                } );
            }
        } );
    }
</script>
<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
