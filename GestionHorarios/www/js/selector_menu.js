document.addEventListener("DOMContentLoaded", function () {
  const menuLinks = document.querySelectorAll(".container-left ul li a");
  const currentPage = window.location.pathname.split("/").pop().split("?")[0];

  menuLinks.forEach((link) => {
    const linkPage = link.getAttribute("href").split("/").pop().split("?")[0];
    if (linkPage === currentPage) {
      link.classList.add("active");
    }
    link.addEventListener("click", () => {
      menuLinks.forEach((l) => l.classList.remove("active"));
      link.classList.add("active");
    });
  });
});
