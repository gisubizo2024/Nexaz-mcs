/**
 * Main JavaScript file for WordPlace CMS
 */

document.addEventListener("DOMContentLoaded", () => {
  // Mobile menu toggle
  const menuToggle = document.querySelector(".menu-toggle")
  const mainNav = document.querySelector(".main-nav")

  if (menuToggle && mainNav) {
    menuToggle.addEventListener("click", () => {
      mainNav.classList.toggle("active")
    })
  }

  // Notification auto-hide
  const notification = document.querySelector(".notification")

  if (notification) {
    setTimeout(() => {
      notification.style.opacity = "0"
      setTimeout(() => {
        notification.style.display = "none"
      }, 500)
    }, 3000)
  }
})

