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
                <div class="form-section">
                    <a href="/new-family-farm" class="button green ghost w-10">POSTANI KORISNIK</a>
                </div>
                <div class="form-section txt-center txt-gray txt-small">
                    KS © {{date('Y.')}}
                </div>
                <div class="form-section txt-center txt-gray txt-small">
                    <span>Podrška korisnicima</span>
                    <br>
                    <a href="mailto:support@opg-matrix.com" class="link">support@opg-matrix.com</a>
                </div>
            </form>
            @if(0)
                <div class="info-wrap">
                    Poštovani korisnici,<br>aplikacija <strong>OPGmatrix</strong> neće biti dostupna u ponedjeljak, <strong>22. kolovoza 2022.</strong> godine, u razdoblju od <strong>15:00h</strong> do <strong>16:00h</strong> zbog radova na održavanju sustava.<br>Zahvaljujemo se na razumijevanju!
                </div>
            @endif
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
            padding: calc(5vH + 2%);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 14px!important;
        }

        .title {
            margin-bottom: 10px;
            text-decoration: none;
            font-size: 180%;
            color: var(--dark-green);
            transition: unset!important;
        }

        .form-wrap {
            width: 320px;
        }

        .info-wrap {
            width: 100%;
            padding: 20px;
            background: var(--light-green);
            border-radius: 4px;
            margin-top: 50px;
            color: var(--dark-green);
        }

        .link {
            color: var(--blue);
        }
    </style>
@endsection
