<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/ba9bd7b863.js" crossorigin="anonymous"></script>
    <meta name="view-transition" content="same-origin" />
    @yield("head")
</head>
<body>
    <header>
        @yield("header")
    </header>    
    <div class="main_container">
        @yield("content")
    </div>
    <footer>
        @yield("footer")
    </footer>
</body>
@yield("scripts")
</html>