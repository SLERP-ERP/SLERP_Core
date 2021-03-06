<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{env('APP_TITLE', 'ORM')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">

    <div class="top-right links">
        @if (Auth::check())
            <a id="home" href="{{ url('/home') }}">Home</a>
            <a id="login" href="{{ url('/logout') }}">Logout</a>
        @else
            @if(settings('enable-registration', $default = false))
                <a id="register" href="{{ url('/register') }}">Register</a>
            @endif
            <a id="login" href="{{ url('/login') }}">Login</a>
        @endif
    </div>

    <div class="content">
        <div class="title m-b-md">
            {{config('app.name', 'ORM Core')}}
        </div>

        <div class="links">
            <a href="{{URL::to('/')}}/api/documentation">Documentation</a>
            <a href="https://github.com/OpenResourceManager/Core">Source</a>
        </div>
    </div>
</div>
</body>
</html>
