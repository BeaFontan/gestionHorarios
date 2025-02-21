document.addEventListener("DOMContentLoaded", () => {
    const menuLinks = document.querySelectorAll(".container-left ul li a");
    
    // Obtener solo el nombre del archivo actual sin parámetros ni directorios
    const currentPage = window.location.pathname.split("/").pop().split("?")[0];

    menuLinks.forEach(link => {
        // Obtener solo la parte final del href del enlace (nombre del archivo)
        const linkPage = link.getAttribute("href").split("/").pop().split("?")[0];

        // Comparar exactamente el archivo actual con el href del enlace
        if (linkPage === currentPage) {
            link.classList.add("active"); // Resalta el enlace actual
        }

        // Permitir cambiar la clase cuando se hace clic
        link.addEventListener("click", () => {
            menuLinks.forEach(l => l.classList.remove("active")); // Quita la clase de los demás
            link.classList.add("active"); // Agrega solo al clickeado
        });
    });
});