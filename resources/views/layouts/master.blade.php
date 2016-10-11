@inject('assetLoader','App\Asset\AssetLoader')
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        @if(Util::isProduction())        
            @if(isset($noIndex) && $noIndex)
            <meta name="robots" content="noindex,follow">
            @else
            <meta name="robots" content="index, follow">
            @endif
        @else 
        <meta name="robots" content="noindex">
        @endif
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        @if(isset($canonical) && !empty($canonical))
        <link rel="canonical" href="{{$canonical}}">
        @endif
        <link rel="shortcut icon" href="{{url('/')}}/assets/images/favicon.png">
        @if(isset($page_keywords))
        <meta name="keywords" content="{{$page_keywords}}"/>
        @endif
        @if(isset($page_description))
        <meta name="description" content="{{$page_description}}">
        @endif
        @if(isset($og_data))
        <meta property="og:title" content="{{$og_data['title']}}  | {{Config::get('app.app_name')}}" />
        <meta property="og:description" content="{{$og_data['description']}}" />
        <meta property="og:site_name" content="{{$og_data['site_name']}}" />
        <meta property="og:type" content="{{$og_data['type']}}" />
        <meta property="og:locale" content="{{$og_data['locale']}}"/>
        <meta property="og:url" content="{{$og_data['url']}}" />
        <meta property="og:image" content="{{$og_data['image']}}" />
        <meta property="fb:app_id" content="1035171313211088" />
        @endif
        <title>
        @if(isset($page_title))
        {{$page_title}} | {{Config::get('app.app_name')}}
        @else
        {{Config::get('app.app_name')}}
        @endif
        </title>
        
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/icon?family=Material+Icons">
        
        @foreach ( $assetLoader::css() as $style )
        <link type="text/css" rel="stylesheet" href="{{$style}}"/>
        @endforeach
    </head>
    <body {{isset($bodyDocumentClass)?"class=".$bodyDocumentClass:""}} itemscope itemtype="http://schema.org/WebPage">
        <meta itemprop="isFamilyFriendly" content="true">
        @include('partials.menu-bar')        
        @yield('body')
        @include('partials.modal-perfil')
        <script type="text/javascript">
        var BASE_URL = '{{url("/")}}/';
        var GENERIC_ERROR_MSG = '{{trans('custom_messages.unexpected_error')}}';
        var TOKEN = '{{csrf_token()}}';
        @if(isset($js_variables) && count($js_variables))
        @foreach($js_variables as $key => $val)
        var {{$key}} = '{!!$val!!}'
        @endforeach                
        @endif
        </script>
        @foreach ( $assetLoader::js() as $script )
        <script type="text/javascript" src="{{$script}}"></script>    
        @endforeach
    </body>
</html>
