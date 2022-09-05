@extends('layouts.app')
@section('content')
    <div class="container fs-normal">
        <div class="form-wrap">
            <form id="form" method="post" action="/new-family-farm">
                @if(session()->has('error'))
                    <div class="message">{{ session()->get('error') }}</div>
                @endif
                @if(session()->has('success'))
                    <div class="message success">{{ session()->get('success') }}</div>
                @endif
                @csrf
                <p>Ukoliko ste zainteresirani za korištenje aplikacije OPGmatrix, ispunite ovaj obrazac te ga pošaljite.</p>
                <p>Potrebno je navesti ili adresu e-pošte ili broj telefona za kontakt.</p>
                <p>Očekujte odgovor u najkraćem mogućem roku.</p>
                <div class="form-section">
                    <div class="form-control">
                        <label for="name" class="required">Ime i prezime:</label>
                    </div>
                    <div class="form-control">
                        <div class="field">
                            <input type="text" class="green @if($errors->has('name')) invalid @endif" value="{{old('name')}}" name="name" id="name" autofocus="autofocus">
                        </div>
                        @if($errors->has('name'))
                            <div class="error">{{ $errors->first('name') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-control">
                        <label for="email">Adresa e-pošte:</label>
                    </div>
                    <div class="form-control">
                        <div class="field">
                            <input type="text" class="green @if($errors->has('email')) invalid @endif" value="{{old('email')}}" name="email" id="email">
                        </div>
                        @if($errors->has('email'))
                            <div class="error">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-section">
                    <div class="form-control">
                        <label for="phone">Broj telefona:</label>
                    </div>
                    <div class="form-control">
                        <div class="field">
                            <input type="text" class="green @if($errors->has('phone')) invalid @endif" value="{{old('phone')}}" name="phone" id="phone">
                        </div>
                        @if($errors->has('phone'))
                            <div class="error">{{ $errors->first('phone') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-section">
                    <button type="submit" class="green w-10">POŠALJI</button>
                </div>
                <div class="form-section">
                    <a href="/" class="button green ghost w-10">Povratak na početnu stranicu</a>
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
            padding: 5vH;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 14px!important;
        }

        .form-wrap {
            width: 380px;
        }

        .success {
            color: var(--green)!important;
            background: var(--light-green)!important;
            border-color: var(--green)!important;
        }
    </style>
@endsection
