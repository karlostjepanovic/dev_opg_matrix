@extends('layouts.app')
@section('content')
    <div class="container fs-normal">
        <div class="form-wrap">
            <a href="/" class="title"><strong>OPG</strong>matrix</a>
            <form id="form" method="post" action="/login">
                @if(session()->has('error'))
                    <div class="message">{{ session()->get('error') }}</div>
                @endif
                @csrf
                <div class="form-section">
                    <div class="form-control">
                    <label for="username">Korisničko ime:</label>
                </div>
                    <div class="form-control">
                        <div class="field">
                            <input type="text" class="green @if($errors->has('username')) invalid @endif" value="{{old('username')}}" name="username" id="username" autofocus="autofocus">
                        </div>
                        @if($errors->has('username'))
                            <div class="error">{{ $errors->first('username') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-control">
                    <label for="password">Lozinka:</label>
                </div>
                    <div class="form-control">
                        <div class="field">
                            <input type="password" class="green @if($errors->has('password')) invalid @endif" value="{{old('password')}}" name="password" id="password">
                        </div>
                        @if($errors->has('password'))
                            <div class="error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-section">
                    <button type="submit" class="green w-10">PRIJAVI SE</button>
                </div>
                <div class="form-control txt-center txt-gray txt-small">
                    Karlo Stjepanović © {{date('Y.')}}
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript">
        const form = document.getElementById("form");
        form.onsubmit = () => {
            const btn = form.querySelector("button");
            btn.setAttribute("disabled", "disabled");
            btn.textContent = "Molimo pričekajte..."
        }
    </script>
    <style>
        .container {
            width: 100%;
            margin-top: calc(20vH - 5%);
            padding-bottom: 20vH;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .title {
            margin-bottom: 10px;
            text-decoration: none;
        }

        .title {
            font-size: 180%;
            color: var(--dark-green);
        }

        .form-wrap {
            width: 320px;
        }
    </style>
@endsection
