window.onload = () => {
    let sidebar = document.getElementById("sidebar"),
        toggler = document.getElementById('sidebarToggler');

    toggler.addEventListener("click", () => {
        if (sidebar.classList.contains("closed")) {
            sidebar.classList.remove("closed");
            sidebar.classList.add("opened");
        } else {
            sidebar.classList.remove("opened");
            sidebar.classList.add("closed");
        }
    });
    window.addEventListener("click", E => {
        if (!E.path.includes(sidebar) && !E.path.includes(toggler)) {
            sidebar.classList.remove("opened");
            sidebar.classList.add("closed");
        }
    })
}
