function toggleSidebar() {
  var sidebar = document.getElementById("sidebar");
  var toggleBtn = document.getElementById("toggleBtn");

  if (sidebar.style.transform === "translateX(-250px)") {
    sidebar.style.transform = "translateX(0)";
    toggleBtn.style.transform = "translateX(250px)";
  } else {
    sidebar.style.transform = "translateX(-250px)";
    toggleBtn.style.transform = "translateX(0)";
  }
}
function confirmLogout() {
  return confirm("Are you sure you want to logout?");
}
