/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    // 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {
    console.log("Scripts.js loaded successfully!");

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector("#sidebarToggle");
    console.log("Sidebar toggle button found:", sidebarToggle);

    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener("click", (event) => {
            console.log("Sidebar toggle clicked!");
            event.preventDefault();
            document.body.classList.toggle("sb-sidenav-toggled");
            console.log("Body classes after toggle:", document.body.className);
            localStorage.setItem(
                "sb|sidebar-toggle",
                document.body.classList.contains("sb-sidenav-toggled")
            );
        });
        console.log("Event listener added to sidebar toggle button");
    } else {
        console.error("Sidebar toggle button not found!");
    }

});
