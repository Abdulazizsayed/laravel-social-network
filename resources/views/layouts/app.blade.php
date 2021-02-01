<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token  -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.jscroll.min.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/custom.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        @auth
                        <form class="form-inline ml-5 my-2 my-lg-0">
                            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
                        </form>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @if (Auth::check() && auth()->user()->hasVerifiedEmail())
                        <li class="nav-item">
                            <a class="nav-link text-light" href="/home">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link text-light notification" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span class="notification-count text-light bg-danger">{{ $notSeenMessages ?? '' }}</span>
                                <i class="fa fa-comments"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right notifications-dropdown" aria-labelledby="navbarDropdown">
                                @foreach ($lastReceivedMessages as $message)
                                    <a class="dropdown-item{{$message->seen ? '' : ' not-seen'}}" href="{{asset('users/messages/' . $message->sender->id)}}">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <img class="rounded-circle" src="{{ $message->sender->image ? asset('storage/' . $message->sender->image->file_name) : asset('storage/images/users/profile/default_user_photo.jpg') }}" width="50px" height="50px" alt="User photo">
                                            </div>
                                            <div class="col-md-11">
                                                {{$message->content}}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                <div class="p-2 text-center">
                                    <a href="{{asset('users/messages/')}}">See all messages</a> <!-- Link to be edited -->
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link text-light notification" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <span class="notification-count text-light bg-danger">{{ $notSeenNotifications ?? '' }}</span>
                                <i class="fa fa-bell"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right notifications-dropdown" aria-labelledby="navbarDropdown">
                                @foreach ($notifications as $notification)
                                    <a class="dropdown-item{{$notification->seen ? '' : ' not-seen'}}" href="{{$notification->link}}"> <!-- Notification link to be added to db -->
                                        <div class="row">
                                            <div class="col-md-1">
                                                <img class="rounded-circle" src="{{ $notification->from->image ? asset('storage/' . $notification->from->image->file_name) : asset('storage/images/users/profile/default_user_photo.jpg') }}" width="50px" height="50px" alt="User photo">
                                            </div>
                                            <div class="col-md-11">
                                                {{$notification->content}}
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                                <div class="p-2 text-center">
                                    <a href="/notifications">See all notifications</a> <!-- Notifications page to be added -->
                                </div>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle text-light navbar-brand " href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img class="rounded-circle" src="{{ Auth::user()->image ? asset('storage/' . Auth::user()->image->file_name) : 'default_user_photo.jpg' }}" width="40px" height="40px" alt="User photo">
                                <small>{{ Auth::user()->name }}</small>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{asset('users/profile/' . Auth::user()->id)}}">
                                    {{ __('Profile') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link text-light" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link text-light" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @endif

                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>
    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            // paginate on posts
            $('.scrolling-pagination').jscroll({
                autoTrigger: true,
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.scrolling-pagination',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });
        });
    </script>
</body>
</html>
