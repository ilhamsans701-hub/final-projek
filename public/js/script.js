document.addEventListener('DOMContentLoaded', function () {
  // Fitur 1: Auto-Hide Flash Message (Alert)
  const alert = document.querySelector('.alert');

  if (alert) {
    setTimeout(function () {
      alert.classList.remove('show');
      alert.classList.add('fade');

      setTimeout(function () {
        alert.remove();
      }, 500); 
    }, 3000);
  }
});
