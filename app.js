// Mobile menu toggle script (supports both #mobile-menu and #hamburger)
document.addEventListener('DOMContentLoaded', function () {
  const navLinks = document.querySelector('.nav-links');
  const triggers = [
    document.getElementById('mobile-menu'),
    document.getElementById('hamburger')
  ].filter(Boolean);

  if (navLinks && triggers.length) {
    triggers.forEach(btn => {
      btn.addEventListener('click', () => {
        navLinks.classList.toggle('active');
        // Optional style display fallback for inline-controlled menus
        if (navLinks.classList.contains('active')) {
          navLinks.style.display = 'flex';
        } else {
          navLinks.style.display = 'none';
        }
        btn.classList.toggle('active');
      });
    });

    // Initial responsive state
    const handleResize = () => {
      if (window.innerWidth <= 768) {
        navLinks.classList.remove('active');
        navLinks.style.display = 'none';
      } else {
        navLinks.classList.remove('active');
        navLinks.style.display = 'flex';
      }
    };
    window.addEventListener('resize', handleResize);
    handleResize();
  }
});


