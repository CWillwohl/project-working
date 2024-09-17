<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Working</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body>
    <div class="flex flex-col justify-start w-full min-h-screen p-4 bg-base-100">
        <p class="text-zinc-600">Olá {{ $userName }}, recebemos um pedido de recuperação de senha!</p>
        <hr class="w-full my-4 border">
        <p class="text-zinc-600">Copie o código abaixo e utilize ele para redefinir a sua senha.</p>
        <p class="text-zinc-600">Código para recuperação de senha: <span class="font-medium text-zinc-600">{{ $token }}</span></p>
    </div>
</body>
</html>

