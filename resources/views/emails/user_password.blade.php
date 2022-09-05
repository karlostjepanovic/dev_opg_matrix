<!DOCTYPE html>
<html>
    <head>
        <title>OPGmatrix - podatci za prijavu</title>
    </head>
<body>
<div>
    <p>Poštovani {{ $details['firstname'].' '.$details['lastname'] }},<br>ovo je automatski generirana poruka koja sadrži Vaše podatke za prijavu u sustav na adresi <a href="{{ request()->server('SERVER_NAME') }}" target="_blank">{{ request()->server('SERVER_NAME')  }}</a>.</p>
    <div><span style="font-weight: bold;">Korisničko ime:</span> {{ $details['username'] }}</div>
    <div><span style="font-weight: bold;">Lozinka:</span> <span style="font-family: Consoles, serif;">{{ $details['password'] }}</span></div>
</div>
</body>
</html>
