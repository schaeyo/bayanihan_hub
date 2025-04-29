document.getElementById("signupBtn").addEventListener("click", function (e) {
    e.preventDefault(); // Prevent form submission for demonstration purposes
  
    // Get input fields
    const username = document.querySelector("input[placeholder='Username']").value.trim();
    const email = document.querySelector("input[placeholder='Email Address']").value.trim();
    const password = document.getElementById("password").value.trim();
  
    // Check if any field is empty
    if (!username || !email || !password) {
      alert("Please fill in all the fields before signing up.");
      return; // Stop further execution
    }
  
    // Check password strength
    const strength = checkPasswordStrength(password);
    if (strength === "Weak") {
      alert("Your password is too weak. Please use a stronger password.");
      return; // Stop further execution
    }
  
    // Show the success modal if all fields are valid
    const signupSuccessModal = new bootstrap.Modal(
      document.getElementById("signupSuccessModal")
    );
    signupSuccessModal.show();
  });
  
  function redirectToLogin() {
    window.location.href = "signin.php"; // Redirect to the login page
  }
  
  // Function to check password strength
  function checkPasswordStrength(password) {
    const strengthIndicator = document.getElementById("passwordStrength");
    let strength = "";
  
    if (password.length < 6) {
      strength = "Weak";
      strengthIndicator.innerHTML = "<span style='color: red;'>Weak: Too short</span>";
    } else if (
      /[a-z]/.test(password) &&
      /[A-Z]/.test(password) &&
      /[0-9]/.test(password) &&
      /[@$!%*?&#]/.test(password)
    ) {
      strength = "Strong";
      strengthIndicator.innerHTML = "<span style='color: green;'>Strong</span>";
    } else if (
      (/[a-z]/.test(password) || /[A-Z]/.test(password)) &&
      /[0-9]/.test(password)
    ) {
      strength = "Medium";
      strengthIndicator.innerHTML = "<span style='color: orange;'>Medium</span>";
    } else {
      strength = "Weak";
      strengthIndicator.innerHTML = "<span style='color: red;'>Weak</span>";
    }
  
    return strength;
  }