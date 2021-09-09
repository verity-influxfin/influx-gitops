<!DOCTYPE html>
<html lang="Zh-Hant-TW" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
    @if ($page['GTM'] ?? false)
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','{{$page['GTM']}}');</script>
    <!-- End Google Tag Manager -->
    @endif

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />


    {{-- ========== Libraries ========== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    @if ($using_vuejs ?? FALSE)
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @endif

    {{-- ========== Page Info ========== --}}
    @if (! empty($page))
    <title>{{$page['title']}}</title>
    <meta name="description" content="{{$page['description']}}">
    @endif

    @if (! empty($keywords = $page['meta_keywords'] ?? null))
    <meta name="keywords" content="{{implode(',', $keywords)}}">
    @endif

    <link rel="icon" type="image/png" href="/images/site_icon.png">
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon" href="/images/site_icon.png">

    {{-- ========== Open Graph ========== --}}
    @if (! empty($page))
    <meta name="title" content="{{$page['title']}}">

    <meta property="og:title" content="{{$page['title']}}">
    <meta property="og:type" content="event">
    <meta property="og:description" content="{{$page['description']}}">
    <meta property="og:site_name" content="inFlux普匯金融科技">
    <meta property="og:locale" content="zh_TW">
    <meta property="og:url" content="{{$page['site_url']}}">
    <meta property="og:image" content="{{$page['cover_image']}}">

    <meta name="twitter:title" content="{{$page['title']}}">
    <meta name="twitter:description" content="{{$page['description']}}">
    <meta name="twitter:url" content="{{$page['site_url']}}">
    <meta name="twitter:image" content="{{$page['cover_image']}}">
    @endif

    @if (! empty($event))
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Event",
      "name": "{{$event['name']}}",
      "startDate": "{{$event['start_date']}}}",
      "endDate": "{{$event['end_date']}}}",
      "eventStatus": "https://schema.org/EventScheduled",
      "eventAttendanceMode": "https://schema.org/OnlineEventAttendanceMode",
      "location": {
        "@type": "VirtualLocation",
        "url": "{{$event['signup_url']}}"
        },
      @if (! empty($event['images']))
      "image": {{json_encode($event['images'])}},
      @endif
      "description": "{{$event['description']}}",
      "organizer": {
        "@type": "Organization",
        "name": "inFlux普匯金融科技股份有限公司",
        "url": "https://www.influxfin.com/"
      }
    }
    </script>
    @endif

    {{-- ========== Analytics ========== --}}
    <meta name="google-site-verification" content="2arsm3rXMMsobi4wX5akzPpQO6-Q6zgUjqwIT0P9UKo" />
    <meta property="fb:app_id" content="2194926914163491">

    <!-- Facebook Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window,document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '2521369841313676');
    fbq('track', 'PageView');
    </script>
    <noscript>
     <img height="1" width="1" src="https://www.facebook.com/tr?id=2521369841313676&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    @yield('styles')
</head>
<body>
@if ($page['GTM'] ?? false)
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{$page['GTM']}}"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif

@yield('content')
</body>

{{-- ========== Libraries Scripts ========== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
@yield('scripts')
</html>
