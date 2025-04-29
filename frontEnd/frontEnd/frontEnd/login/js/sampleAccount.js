document.getElementById("loginBtn").addEventListener("click", function () {
  var username = document.querySelector("input[type='text']").value.trim();
  var password = document.getElementById("password").value.trim();

  if (username === "baranggay" && password === "12345678") {
    showSuccess("../baranggay/admin_dashboard.html");
  } else if (username === "resident" && password === "12345678") {
    showSuccess("../resident/resident_dashboard.html");
  } else {
    showFailed();
  }
});

function showSuccess(redirectUrl) {
  var successModal = new bootstrap.Modal(
    document.getElementById("successModal")
  );
  successModal.show();
  setTimeout(() => {
    window.location.href = redirectUrl;
  }, 2000); // Redirect after 2 seconds
}

function showFailed() {
  var failedModal = new bootstrap.Modal(document.getElementById("failedModal"));
  failedModal.show();
}
