<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="h-screen bg-[#2E3532] flex items-center justify-center">
        <div>
        <h1 class="text-center text-6xl text-white font-extrabold">StockHive</h1>
        <p class="text-center my-8 hover:scale-105 hover:opacity-55 transition-all"><a class="rounded-lg py-2 px-4 bg-[#EE4266] font-bold text-white bg-accent text-3xl" href="/login">Login</a></p>
        </div>
      </div>
</body>
</html>