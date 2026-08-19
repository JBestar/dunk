(function () {
    function onReady(callback) {
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", callback);
            return;
        }

        callback();
    }

    onReady(function () {
        var toggle = document.getElementById("admin-sidebar-toggle");
        var sidebar = document.getElementById("main-sidebar-id");

        if (!toggle || !sidebar) {
            return;
        }

        function setOpen(isOpen) {
            document.body.classList.toggle("admin-sidebar-open", isOpen);
            toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
        }

        toggle.addEventListener("click", function () {
            setOpen(!document.body.classList.contains("admin-sidebar-open"));
        });

        document.addEventListener("click", function (event) {
            if (window.innerWidth > 900 || !document.body.classList.contains("admin-sidebar-open")) {
                return;
            }

            if (sidebar.contains(event.target) || toggle.contains(event.target)) {
                return;
            }

            setOpen(false);
        });

        window.addEventListener("resize", function () {
            if (window.innerWidth > 900) {
                setOpen(false);
            }
        });
    });
})();
