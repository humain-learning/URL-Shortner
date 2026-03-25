<!DOCTYPE html>
<html>
<head>
    <title>Opening NRSM App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script>
        function openApp() {
            const start = Date.now();
            let hidden = false;

            document.addEventListener("visibilitychange", function () {
                if (document.hidden) {
                    hidden = true; // app opened
                }
            });

            // TRY opening the app (best guess scheme)
            window.location.href = "nrsm://";

            // FALLBACK to Play Store
            setTimeout(function () {
                if (!hidden && Date.now() - start < 3000) {
                    window.location.href =
                        "https://play.google.com/store/apps/details?id=com.nrsm.snipe";
                }
            }, 2000);
        }

        window.onload = openApp;
    </script>
</head>
<body>
    <p>Opening NRSM App…</p>
    <p>If nothing happens, <a href="https://play.google.com/store/apps/details?id=com.nrsm.snipe">tap here</a>.</p>
</body>
</html>
