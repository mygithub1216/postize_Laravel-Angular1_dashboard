<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    @if(!empty($post))
        <title>{{ $post->title }}</title>
        <meta property="og:title" content="{{ $post->title }}">
    @elseif(!empty($category))
        <title>{{ config('custom.app-name') }} - {{ $category->name }}</title>
        <meta property="og:title" content="{{ config('custom.app-name') }} - {{ $category->name }}">
    @else
        <title>{{ config('custom.app-name') }} - {{ config('custom.app-slogan') }}</title>
        <meta property="og:title" content="{{ config('custom.app-name') }}">
    @endif


    <meta property="fb:pages" content="{{ config('custom.facebook-page-id') }}" />
    <meta property="og:description" content="{{$post->description or config('custom.og-description-default') }}" />
    <meta property="og:image" content="{{ $post->image or ''}}" />
    <meta property="og:url" content="{{ !empty($post) ? url($post->slug) : Request::url() }}" />
    <meta property="og:site_name" content="{{ config('custom.app-name') }}" />
    <meta property="og:type" content="article" />
    <meta property="article:author" content="{{ config('custom.facebook-url') }}" />
    <meta property="article:publisher" content="{{ config('custom.facebook-url') }}" />
    <meta property="fb:app_id" content="{{ config('custom.facebook-app-id') }}" />

    <meta name="_token" content="{!! csrf_token() !!}"/>


    <link href="{{ asset('assets/front/css/style.css?v1.0.6') }}" rel="stylesheet" type="text/css">
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    {!! config('custom.adsense-enable-page-level-ads') !!}

    <script>window.twttr = (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0],
                    t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);

            t._e = [];
            t.ready = function (f) {
                t._e.push(f);
            };

            return t;
        }(document, "script", "twitter-wjs"));</script>
    @yield('css')


    <script type='text/javascript'>
      var googletag = googletag || {};
      googletag.cmd = googletag.cmd || [];
      (function() {
        var gads = document.createElement('script');
        gads.async = true;
        gads.type = 'text/javascript';
        var useSSL = 'https:' == document.location.protocol;
        gads.src = (useSSL ? 'https:' : 'http:') +
          '//www.googletagservices.com/tag/js/gpt.js';
        var node = document.getElementsByTagName('script')[0];
        node.parentNode.insertBefore(gads, node);
      })();
    </script>

    <script type='text/javascript'>
      googletag.cmd.push(function() {

        var home = (document.location.pathname == "/" || document.location.pathname == "") ? true : false,
        slots = [],
        map_atf = googletag.sizeMapping().
            addSize([1024, 200], [[970, 250], [970, 90], [728, 90]]).
            addSize([768, 200], [728, 90]).
            build(),
        map_lb = googletag.sizeMapping().
            addSize([768, 200], [728, 90]).
            build(),
        map_side = googletag.sizeMapping().
            addSize([768, 200], [300, 600]).
            build();
            
        slots[0] = googletag.defineSlot('/28112988/PT_Leaderboard_ATF', [[320, 50], [320, 100]], 'div-gpt-ad-1466672412806-1').        
            defineSizeMapping(map_atf).
            addService(googletag.pubads());
        slots[1] = googletag.defineSlot('/28112988/PT_Leaderboard_Middle', [[300, 250]], 'div-gpt-ad-1466672412806-3').
            defineSizeMapping(map_lb).
            addService(googletag.pubads()); 
        slots[2] = googletag.defineSlot('/28112988/PT_Leaderboard_BTF', [[336, 280], [300, 250]], 'div-gpt-ad-1466672412806-2').
            defineSizeMapping(map_lb).
            addService(googletag.pubads());
        slots[3] = googletag.defineSlot('/28112988/PT_Sidebar_ATF', [[300, 600], [300, 250]], 'div-gpt-ad-1466672412806-4').addService(googletag.pubads());
        googletag.defineSlot('/28112988/PT_Sidebar_BTF', [300, 250], 'div-gpt-ad-1466672412806-5').addService(googletag.pubads());
        googletag.defineSlot('/28112988/PT_InPost', [[300, 250], [336, 280]], 'div-gpt-ad-1466672412806-0').addService(googletag.pubads());

        if ( home == true ) {
        googletag.pubads().setTargeting("isHome", "true");
        } else {
        googletag.pubads().setTargeting("isHome", "false"); 
        }
        googletag.pubads().setTargeting("PostID", "code to fetch post ID");
        googletag.pubads().setTargeting("Cat", "code to fetch category");
        googletag.pubads().collapseEmptyDivs();
        googletag.enableServices();
      });
    </script>

    <script type="text/javascript">var m_w = window.innerWidth; </script>
    <style type="text/css">
        .fake, .mgtobottom { display: none; }
        @media (max-width:480px) {
            #M182928ScriptRootC61843 {display:none!important;}
            #M182928ScriptRootC61846 {min-height: 180px;}
            #M182928ScriptRootC61848 {display: none!important;}
            #M182928ScriptRootC61850 {min-height: 180px;}
        }
        @media (min-width:480px) {
            #M182928ScriptRootC61843 {min-height: 200px;}
            #M182928ScriptRootC61846 {display:none!important;}
            #M182928ScriptRootC61848 {min-height: 200px;}
            #M182928ScriptRootC61850 {display: none!important;}
        }
        @media (max-width:568px) {
            #M182928PreloadC61852 {min-height: 200px;}
            #M182928PreloadC61859 {display: none!important;}
        }
        @media (min-width:1024px) {
            #M182928PreloadC61852 {min-height: 200px;}
            #M182928PreloadC61859 {display: none!important;}
        }
        @media (min-width:667px) and (max-width:900px) {
            #M182928PreloadC61852 {display: none!important;}
            #M182928PreloadC61859 {min-height: 180px;}
        }
    </style>

    @yield('js-top')
</head>

<body>
<!-- Load Facebook SDK for JavaScript -->
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5&appId={{ config('custom.facebook-app-id') }}";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

<?php $f = 'assets/front/svg/shapes.svg'; if (file_exists($f)) {
    include($f);
} ?>

@include('partials.header')

@if ( $page == 'post' )

    @include('partials.header-post')

@endif

@include('partials.mobile-menu')

<main class="site">
    <div class="container wrapper" id="container">

        @yield('content')

    </div>

    <div class="wrapper">
        
        @if ( $page == 'page' )
            <h1 class="section-heading">You may also like:</h1>

            <div class="articles fill-content">
                @include('partials.you-may-like')
            </div>

            {{-- Load related posts of different categories
            <span href="" class="btn load" data-action="you-may-like" data-category="0">Load more</span>
            --}}
        @endif

        @if ( $page == 'post' )
            <h1 class="section-heading">Related stories:</h1>

            <div class="articles fill-content">
                @include('partials.related')
            </div>

            {{-- Load related posts of the same category
            <span href="" class="btn load" data-action="related" data-category="1">Load more</span>
            --}}
        @endif
        
    </div>

    @include('partials.footer')
</main>

<div class="go-top">
    <svg>
        <use xlink:href="#svg-arrow"></use>
    </svg>
</div>
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

    ga('create', '{{ config('custom.google-analytics-view-id') }}', 'auto');
    ga('send', 'pageview');

</script>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="{{ asset('assets/front/js/scripts.js?v1.0.1') }}"></script>
@yield('js-bottom')
</body>
</html>