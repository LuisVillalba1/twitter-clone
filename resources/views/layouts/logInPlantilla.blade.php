<!DOCTYPE html>
<html lang="en">
<head>
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