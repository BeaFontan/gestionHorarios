//funcion para controlar que se vea la pass
document.getElementById("togglePassword").addEventListener("click", () => {
  const passwordInput = document.getElementById("txtPass");
  const icon = document.querySelector("#togglePassword i");

  passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  icon.classList.toggle("fa-eye-slash");
  icon.classList.toggle("fa-eye");
});
