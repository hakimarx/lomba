// js untuk hamburger menu

document.addEventListener("DOMContentLoaded", () => {
    document.querySelector(".menu .box").addEventListener("click", () => {
        // alert(2);
        let isi = document.querySelector(".menu .isi");
        let box = document.querySelector(".menu .box");
        if (isi.style.display == "flex") {
            isi.style.display = "none";
            box.style.transform = "rotate(0deg)";
        } else {
            isi.style.display = "flex";
            box.style.transform = "rotate(90deg)";
        }
    });

    // Dropdown menu toggle for mobile
    document.querySelectorAll('.menu-group > a.menu-parent').forEach(parent => {
        parent.addEventListener('click', (e) => {
            // Only toggle on mobile (viewport < 786px)
            if (window.innerWidth < 786) {
                e.preventDefault();
                const group = parent.closest('.menu-group');
                group.classList.toggle('open');

                // Close other dropdowns
                document.querySelectorAll('.menu-group').forEach(other => {
                    if (other !== group) {
                        other.classList.remove('open');
                    }
                });
            }
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.menu-group')) {
            document.querySelectorAll('.menu-group').forEach(group => {
                group.classList.remove('open');
            });
        }
    });
})