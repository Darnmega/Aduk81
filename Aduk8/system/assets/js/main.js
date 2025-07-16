document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const container = document.querySelector('.container');
    const submenuItems = document.querySelectorAll('.has-submenu');

    // Toggle sidebar on menu button click
    menuToggle.addEventListener('click', function() {
        container.classList.toggle('sidebar-open');
    });

    // Toggle submenus - updated to work on all screen sizes
    submenuItems.forEach(item => {
        const link = item.querySelector('a');
        link.addEventListener('click', function(e) {
            // Prevent default only if it's a submenu toggle
            if (item.classList.contains('has-submenu')) {
                e.preventDefault();

                // Close other open submenus
                submenuItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                    }
                });

                // Toggle current submenu
                item.classList.toggle('active');
            }
        });
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 992 &&
            !sidebar.contains(e.target) &&
            e.target !== menuToggle &&
            !menuToggle.contains(e.target)) {
            container.classList.remove('sidebar-open');
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            container.classList.remove('sidebar-open');
        }
    });

    // Typing effect for the heading
    function typeWriter(element, text, speed) {
        let i = 0;
        element.innerHTML = '';

        function typing() {
            if (i < text.length) {
                element.innerHTML += text.charAt(i);
                i++;
                setTimeout(typing, speed);
            } else {
                // Remove cursor after typing completes
                element.style.setProperty('--cursor-visibility', 'hidden');
            }
        }

        typing();
    }

    const heading = document.getElementById('typing-heading');
    const text = heading.textContent.trim();
    const speed = 100;

    typeWriter(heading, text, speed);
});