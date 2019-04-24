<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700" rel="stylesheet">
  <link rel="icon" href="{{ url('/images') }}/logo.png">
  <title>Hall Map</title>
  <style type="text/css">
    .preloader{opacity:1;height:100%;display:block;position:fixed;left:0;top:0;z-index:2000;width:100%;background:url(http://files.mimoymima.com/images/loading.gif) center center no-repeat #333}.preloader-disable{-webkit-animation:hidePreloader 350ms ease-in-out both;animation:hidePreloader 350ms ease-in-out both}.preloader__text{position:absolute;font-family:'GothamProMedium',Arial,Tahoma,Verdana,sans-serif;color:#fff;font-size:40px;width:100%;text-align:center;margin:0;top:calc(50% + 66px)}@-webkit-keyframes hidePreloader{from{opacity:1}to{opacity:0;z-index:-1}}@keyframes hidePreloader{from{opacity:1}to{opacity:0;z-index:-1}}
  </style>
  <link href="{{ url('/widget/css/app.css') }}" rel="stylesheet">
  <script>
    var id = '{{ $id }}';
    var mode = '{{ $mode }}';
  </script>
</head>
<body>
  <noscript>
    <strong>
      We're sorry but site doesn't work properly without JavaScript enabled. Please enable it to continue.
    </strong>
  </noscript>
  <div id="app"></div>

  <script src="{{ url('/widget/js/app.js') }}"></script>
</body>
</html>