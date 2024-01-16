<!DOCTYPE html>
<html lang="en">
<head>
    @yield('head')
</head>
<body>
    <div class="main_container">
        @yield("header")
        @yield("content")
    </div>
    @yield("footer")
</body>
@yield("scripts")
</html>