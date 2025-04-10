// Side Bar JS
const sidebar = document.getElementById("sidebar");
const toggleBtn = document.getElementById("toggleSidebar");
const closeBtn = document.getElementById("closeSidebar");
const violationToggleBtn = document.querySelector(".violation-toggle");
const violationAccordion = document.getElementById("collapseViolation");

// Toggle sidebar
function toggleSidebar() {
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("show");
    } else {
        sidebar.classList.toggle("collapsed");

        // Close Violation Accordion if sidebar is collapsed
        if (sidebar.classList.contains("collapsed")) {
            const collapseInstance = bootstrap.Collapse.getInstance(violationAccordion);
            if (collapseInstance) {
                collapseInstance.hide();
            }
        }
    }
}

// Handle screen size changes
function handleResize() {
    if (window.innerWidth <= 768) {
        sidebar.classList.remove("collapsed");
        closeBtn.style.display = sidebar.classList.contains("show") ? "block" : "none";
    } else {
        sidebar.classList.remove("show");
        closeBtn.style.display = "none";
    }
}

// Toggle and close events
toggleBtn.addEventListener("click", toggleSidebar);
closeBtn.addEventListener("click", () => {
    sidebar.classList.remove("show");
});

window.addEventListener("load", handleResize);
window.addEventListener("resize", handleResize);

// Highlight current active nav link
document.addEventListener("DOMContentLoaded", () => {
    const navLinks = document.querySelectorAll(".sidebar .nav-link");
    const currentPage = window.location.pathname.split("/").pop();

    navLinks.forEach(link => {
        const linkPage = link.getAttribute("href");
        if (linkPage === currentPage) {
            link.classList.add("active");
        }

        link.addEventListener("click", function () {
            navLinks.forEach(nav => nav.classList.remove("active"));
            this.classList.add("active");
        });
    });
});

// Prevent Violation accordion from expanding when sidebar is collapsed
violationToggleBtn.addEventListener("click", function (e) {
    if (sidebar.classList.contains("collapsed")) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Keep the active-parent class based on current page
    const currentPage = window.location.pathname.split("/").pop();
    const violationPages = ["ViolationType.html", "Referral.html", "Penalty.html"];
    if (violationPages.includes(currentPage)) {
        setTimeout(() => {
            violationToggleBtn.classList.add("active-parent");
        }, 300); // wait for Bootstrap collapse transition
    }
});


// Auto-expand Violation Management section if subpage is active
document.addEventListener("DOMContentLoaded", () => {
    const currentPage = window.location.pathname.split("/").pop();
    const violationPages = ["ViolationType.html", "Referral.html", "Penalty.html"];

    if (violationPages.includes(currentPage)) {
        const collapseViolation = new bootstrap.Collapse(violationAccordion, {
            toggle: true
        });

        violationToggleBtn.classList.add("active-parent");
    }
});
// End of Side Bar JS