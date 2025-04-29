document.addEventListener("DOMContentLoaded", function () {
  let passwordStrengthScore = 0;

  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirmPassword");
  const strengthIndicator = document.getElementById("passwordStrength");
  const form = document.querySelector("form");
  const mismatchMessage = document.getElementById("passwordMismatch");

  passwordInput.addEventListener("input", function () {
    const value = passwordInput.value;
    passwordStrengthScore = 0;

    if (value.length >= 8) passwordStrengthScore++;
    if (/[A-Z]/.test(value)) passwordStrengthScore++;
    if (/[a-z]/.test(value)) passwordStrengthScore++;
    if (/\d/.test(value)) passwordStrengthScore++;
    if (/[\W_]/.test(value)) passwordStrengthScore++;

    // Display strength
    switch (passwordStrengthScore) {
      case 0:
      case 1:
        strengthIndicator.textContent = "Very Weak";
        strengthIndicator.style.color = "red";
        break;
      case 2:
        strengthIndicator.textContent = "Weak";
        strengthIndicator.style.color = "orange";
        break;
      case 3:
        strengthIndicator.textContent = "Moderate";
        strengthIndicator.style.color = "goldenrod";
        break;
      case 4:
        strengthIndicator.textContent = "Strong";
        strengthIndicator.style.color = "green";
        break;
      case 5:
        strengthIndicator.textContent = "Very Strong";
        strengthIndicator.style.color = "darkgreen";
        break;
    }
  });

  form.addEventListener("submit", function (e) {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    if (passwordStrengthScore < 4) {
      e.preventDefault();
      alert("Please enter a stronger password (at least Strong or Very Strong).");
      return;
    }

    if (password !== confirmPassword) {
      e.preventDefault();
      mismatchMessage.style.display = "block";
    } else {
      mismatchMessage.style.display = "none";
    }
  });
});
