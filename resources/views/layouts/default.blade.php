<html>
    <head>
        <title>
            @section('title')
                {{ trans('messages.app_title') }}
            @show
        </title>
        
        @section('head')
            <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
            <script src="{{ elixir('js/app.js') }}"></script>
        @show
    </head>
    <body class="with-navbar">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed"
                            data-toggle="collapse" data-target="#layout-navbar-top">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">{{ trans('messages.app_title') }}</a>
                </div>

                <div class="collapse navbar-collapse" id="layout-navbar-top">
                    <ul class="nav navbar-nav">
                    </ul>

                    @if (LocaleHub::countAvailableLocales() > 1)
                        <script>
                            function setLocaleCookie(locale) {
                                if (locale)
                                    $.cookie('locale', locale, { path: '/', expires: 365 });
                                else
                                    $.removeCookie('locale', { path: '/' });
                                window.location.reload()
                            }
                        </script>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="javascript:void(0)" class="dropdown-toggle"
                                   data-toggle="dropdown" role="button" aria-expanded="false">
                                    <img src="{{ asset('img/flags/' . LocaleHub::getLocale() . '.gif') }}">
                                    {{ LocaleHub::getLocale() }}
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    @foreach (LocaleHub::getAvailableLocales() as $locale)
                                        <li>
                                            <a href="javascript:void(0)" onclick="setLocaleCookie('{{ $locale }}')">
                                                <span class="glyphicon glyphicon-ok {{ $locale == @$_COOKIE['locale'] ? '' : 'invisible' }}"></span>
                                                {{ Lang::has("messages.$locale")
                                                    ? trans("messages.$locale") . " ($locale)"
                                                    : $locale }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li class="divider"></li>
                                    <li>
                                        <a href="javascript:void(0)" onclick="setLocaleCookie(null)">
                                            <span class="glyphicon glyphicon-ok {{ @$_COOKIE['locale'] ? 'invisible' : '' }}"></span>
                                            {{ trans("messages.autodetection") }}
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </nav>

        @yield('content')
    </body>
</html>
