<!DOCTYPE html>
<html data-theme='dark' lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>WoW LP</title>

    <script src="https://kit.fontawesome.com/09192c278e.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="app"></div>

    <script>
        window.addEventListener("change", function(e) {
            if (e.target.id === "themeToggle") {
                const html = document.querySelector("html");
                html.dataset.theme = html.dataset.theme === "dark" ? "light" : "dark";
            }
        })
    </script>
</body>

</html>
