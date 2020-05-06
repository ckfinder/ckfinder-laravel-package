<!DOCTYPE html>
<!--
Copyright (c) 2007-2018, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or https://ckeditor.com/sales/license/ckfinder
-->
<html>
<head>
    <meta charset="utf-8">
    <title>CKFinder 3 Samples</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 9]>
    <script src="{{ asset('js/ckfinder/samples/js/html5shiv.min.js') }}"></script>
    <![endif]-->
    <link href="{{ asset('js/ckfinder/samples/css/sample.css') }}" rel="stylesheet">
<body>
<header class="header-a">
    <div class="grid-container">
        <h1 class="header-a-logo grid-width-30">
            <a href="{{ route('ckfinder_examples') }}"><img src="{{ asset('js/ckfinder/samples/img/logo.png') }}" alt="CKFinder Logo"></a>
        </h1>
        <nav class="navigation-b grid-width-70">
            <ul>
                <li><a href="https://docs.ckeditor.com/ckfinder/ckfinder3/" class="button-a">Documentation</a></li>
            </ul>
        </nav>
    </div>
</header>
<main class="grid-container">
    <nav class="tree-a tree-a-layout grid-width-30">
        <h1>CKFinder Samples</h1>
        <h2 @if($section === 'integration')class="tree-a-active"@endif>Website Integration</h2>
        <ul>
            <li @if($sample === 'widget')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'widget']) }}">Widget</a></li>
            <li @if($sample === 'popups')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'popups']) }}">Popup</a></li>
            <li @if($sample === 'modals')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'modals']) }}">Modal</a></li>
            <li @if($sample === 'full-page')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'full-page']) }}">Full Page</a></li>
        </ul>
        <h2 class="tree-a-no-sub @if($section === 'ckeditor') tree-a-active @endif"><a href="{{ route('ckfinder_examples', ['example' => 'ckeditor']) }}">CKEditor Integration</a></h2>
        <h2 @if($section === 'skins')class="tree-a-active"@endif>Skins</h2>
        <ul>
            <li @if($sample === 'skins-moono')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'skins-moono']) }}">Moono</a></li>
            <li @if($sample === 'skins-jquery-mobile')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'skins-jquery-mobile']) }}">jQuery Mobile</a></li>
        </ul>
        <h2 @if($section === 'user-interface')class="tree-a-active"@endif>User Interface</h2>
        <ul>
            <li @if($sample === 'user-interface-default')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'user-interface-default']) }}">Default</a></li>
            <li @if($sample === 'user-interface-compact')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'user-interface-compact']) }}">Compact</a></li>
            <li @if($sample === 'user-interface-mobile')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'user-interface-mobile']) }}">Mobile</a></li>
            <li @if($sample === 'user-interface-listview')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'user-interface-listview']) }}">List View</a></li>
        </ul>
        <h2 class="tree-a-no-sub @if($section === 'localization') tree-a-active @endif"><a href="{{ route('ckfinder_examples', ['example' => 'localization']) }}">Localization</a></h2>
        <h2 @if($section === 'other')class="tree-a-active"@endif>Other</h2>
        <ul>
            <li @if($sample === 'other-read-only')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'other-read-only']) }}">Read-only Mode</a></li>
            <li @if($sample === 'other-custom-configuration')class="tree-a-active"@endif><a href="{{ route('ckfinder_examples', ['example' => 'other-custom-configuration']) }}">Custom Configuration</a></li>
        </ul>
        <h2 class="tree-a-no-sub @if($section === 'plugin-examples') tree-a-active @endif"><a href="{{ route('ckfinder_examples', ['example' => 'plugin-examples']) }}">Plugin Examples</a></h2>
    </nav>
    <section class="content grid-width-70">
        @yield('content')
    </section>
</main>
<footer class="footer-a grid-container">
    <div class="grid-container">
        <p class="grid-width-100">
            CKFinder 3 for PHP &ndash; <a href="https://ckeditor.com/ckeditor-4/ckfinder/">https://ckeditor.com/ckeditor-4/ckfinder/</a>
        </p>
        <p class="grid-width-100">
            Copyright &copy; 2003-2018, <a class="samples" href="http://cksource.com/">CKSource</a> &ndash; Frederico
            Knabben. <a href="https://ckeditor.com/sales/license/ckfinder">All rights reserved</a>.
        </p>
    </div>
</footer>
<nav class="navigation-a">
    <div class="grid-container">
        <ul class="navigation-a-left grid-width-70">
            <li><a href="https://ckeditor.com/ckfinder/">Project Homepage</a></li>
            <li class="global-is-mobile-hidden"><a href="https://github.com/ckfinder/ckfinder/issues">I found a bug in CKFinder</a></li>
            <li class="global-is-mobile-hidden"><a class="icon-pos-right icon-navigation-a-github" href="https://github.com/ckfinder/ckfinder-docs-samples">Sample Plugins on GitHub</a></li>
        </ul>
    </div>
</nav>

<script src="{{ asset('js/ckfinder/samples/js/sf.js') }}"></script>
<script src="{{ asset('js/ckfinder/samples/js/tree-a.js') }}"></script>
@include('ckfinder::setup')
@yield('scripts')

</body>
</html>
