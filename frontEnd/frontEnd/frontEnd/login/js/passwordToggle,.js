document.addEventListener("DOMContentLoaded", function () {
  const togglePassword = document.getElementById("togglePassword");
  const passwordField = document.getElementById("password");

  if (togglePassword && passwordField) {
    togglePassword.addEventListener("click", function () {
      const isPasswordVisible = passwordField.type === "text";
      passwordField.type = isPasswordVisible ? "password" : "text";
      togglePassword.classList.toggle("fa-eye");
      togglePassword.classList.toggle("fa-eye-slash");
    });
  }
});
