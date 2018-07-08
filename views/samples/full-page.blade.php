@extends('ckfinder::samples/layout')

@section('content')
	<h1>Full Page Mode</h1>

	<p>The <strong>full page</strong> mode opens CKFinder using the entire page as the working area.</p>
	<pre class="prettyprint"><code>CKFinder.start();</code></pre>
	<p>Click the button below to open CKFinder in full page mode.</p>

	<a href="{{ route('ckfinder_examples', ['example' => 'full-page-open']) }}" class="button-a button-a-background" target="_blank">Open CKFinder</a>
@stop

@section('scripts')
    <script src="//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js" type="text/javascript"></script>
@stop
