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
        <meta property="fb:app_id" content="config('services.facebook.client_id')" />
        <meta property="og:title" content="{{$og_data['title']}}  | {{Config::get('app.app_name')}}" />
        <meta property="og:description" content="{{$og_data['description']}}" />
        <meta property="og:site_name" content="{{$og_data['site_name']}}" />
        <meta property="og:type" content="{{$og_data['type']}}" />
        <meta property="og:locale" content="{{$og_data['locale']}}"/>
        <meta property="og:url" content="{{$og_data['url']}}" />
        @if(isset($og_data['image']) && $og_data['image'])
            <meta property="og:image" content="{{$og_data['image']}}" />
        @endif
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

        @if(Util::isProduction())
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-85608089-2', 'auto');
          ga('send', 'pageview');
        </script>
        @else
        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
          (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
          m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
          })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

          ga('create', 'UA-85608089-1', 'auto');
          ga('send', 'pageview');
        </script>
        @endif
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
        @if(isset($showAddThis) && $showAddThis)
            <script type="text/javascript">
                var addthis_config = addthis_config||{};
                    addthis_config.data_track_clickback = false;
                    addthis_config.data_track_addressbar = false;
            </script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-57fc511eedc18a77"></script>
        @endif
        <div class="modal fade bs-example-modal-sm" id="modalLogin" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Login</h4>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{route('login.fb')}}" class="btn btn-social-login btn-facebook">Entrar com Facebook</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{route('login.gp')}}" class="btn btn-social-login btn-google">Entrar com Google+</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="{{route('login.linkedin')}}" class="btn btn-social-login btn-linkedin">Entrar com Linked In</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                    OU
                            </div>
                        </div>
                        <div class="row">
                            <form action="{{url('/login')}}" method="POST">
                                {{ csrf_field() }}
                                <div class="col-xs-12">
                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="control-label">E-Mail</label>
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="control-label">Senha</label>
                                        <input id="password" type="password" class="form-control" name="password">
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>                                    
                                </div>
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <div class="col-xs-12">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="remember"> Lembrar
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-12 text-left">
                                        <button type="submit" class="btn btn-primary btn-raised submit">
                                            <i class="material-icons">account_box</i> Entrar
                                        </button>
                                        <a href="{{ url('/password/reset') }}">Esqueceu a senha?</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
