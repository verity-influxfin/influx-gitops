<!DOCTYPE html>
<html lang="Zh-Hant-TW" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="pragma" content="no-cache" />


    {{-- ========== Libraries ========== --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
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

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117279688-9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'AW-692812197');
    </script>
    <script>
        window.addEventListener("load", function(event) {
          setTimeout(function(){
                document.querySelectorAll("a[href*='investLink']").forEach(function(e){
                    e.addEventListener('click',function(){
                        gtag('event', 'conversion', {'send_to': 'AW-692812197/vcdCCJyj_ZkCEKXzrcoC'});
                });
            });

            document.querySelectorAll("a[href*='borrowLink']").forEach(function(e){
                    e.addEventListener('click',function(){
                        gtag('event', 'conversion', {'send_to': 'AW-692812197/WE5GCNWzgpoCEKXzrcoC'});
                });
            });
          },2000)
      });
    </script>
    <!-- End Google Analytics -->
    @yield('styles')
</head>
<body>
@yield('content')
</body>

{{-- ========== Libraries Scripts ========== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
@yield('scripts')
</html>
