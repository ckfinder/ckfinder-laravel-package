@extends('ckfinder::samples/layout')

@section('content')
    <h1>Widget Mode</h1>

    <p>Using the <a href="https://docs.ckeditor.com/ckfinder/ckfinder3/#!/guide/dev_installation-section-embedding-as-widget">widget mode</a> you can embed CKFinder directly on a page, as shown below.</p>
    <pre class="prettyprint"><code>CKFinder.widget( 'ckfinder-widget', {
	width: '100%',
	height: 700
} );</code></pre>

    <div id="ckfinder-widget"></div>
@stop

@section('scripts')
<script>
    CKFinder.widget( 'ckfinder-widget', {
        width: '100%',
        height: 700
    } );
</script>
<script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
@stop
