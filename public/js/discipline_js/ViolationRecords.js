// Side Bar JS
const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggleSidebar");
const closeBtn = document.getElementById("closeSidebar");

function toggleSidebar() {
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("show");
    } else {
        sidebar.classList.toggle("collapsed");
    }
}

function handleResize() {
    if (window.innerWidth <= 768) {
        // Mobile view
        sidebar.classList.remove("collapsed");
        closeBtn.style.display = sidebar.classList.contains("show") ? "block" : "none";
    } else {
        // Desktop view
        sidebar.classList.remove("show");
        closeBtn.style.display = "none";
    }
}

toggleBtn.addEventListener("click", toggleSidebar);
closeBtn.addEventListener("click", function() {
    sidebar.classList.remove("show");
});

window.addEventListener('load', handleResize);
window.addEventListener('resize', handleResize);

document.addEventListener("DOMContentLoaded", function () {
    const navLinks = document.querySelectorAll(".sidebar .nav-link");
    const currentPage = window.location.pathname.split("/").pop();

    navLinks.forEach(link => {
        const linkPage = link.getAttribute("href");

        if (linkPage === currentPage) {
            link.classList.add("active");
        } else {
            link.classList.remove("active");
        }

        link.addEventListener("click", function () {
            navLinks.forEach(nav => nav.classList.remove("active"));
            this.classList.add("active");
        });
    });
});

// End of Side Bar JS

document.getElementById("searchInput").addEventListener("keyup", function () {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll("#violationTable tr");

    rows.forEach(row => {
        let name = row.cells[1].textContent.toLowerCase();
        let email = row.cells[2].textContent.toLowerCase();
        if (name.includes(filter) || email.includes(filter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

document.getElementById("statusFilter").addEventListener("change", function () {
    let filter = this.value;
    let rows = document.querySelectorAll("#violationTable tr");

    rows.forEach(row => {
        let status = row.cells[4].textContent;
        if (filter === "All" || status === filter) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});
