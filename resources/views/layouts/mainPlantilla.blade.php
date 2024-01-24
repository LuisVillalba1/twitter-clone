<!DOCTYPE html>
<html lang="en">
<head>
    @yield("head")
</head>
<body>
    <header>
        @yield("header")
    </header>
    @yield("content")
    <footer>
        @yield("footer")
    </footer>
</body>
@yield("scripts")
</html>