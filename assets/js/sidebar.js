document.addEventListener("DOMContentLoaded", function () {
    const toggle = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebarMenu");

    if (!toggle || !sidebar) {
        return;
    }

    let backdrop = document.querySelector(".sidebar-backdrop");

    if (!backdrop) {
        backdrop = document.createElement("div");
        backdrop.classList.add("sidebar-backdrop");
        document.body.appendChild(backdrop);
    }

    toggle.addEventListener("click", function (e) {
        e.preventDefault();
        sidebar.classList.toggle("show");
        backdrop.classList.toggle("show");
    });

    backdrop.addEventListener("click", function () {
        sidebar.classList.remove("show");
        backdrop.classList.remove("show");
    });
});