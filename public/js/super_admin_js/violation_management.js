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
        
        // Reset all links first
        link.classList.remove("active", "active-parent");

        // Check if current page matches the link or its sub-links
        if (linkPage === currentPage) {
            link.classList.add("active");
            
            // If it's a sub-link, highlight its parent (Violation Management)
            if (link.classList.contains("sub-link")) {
                const parentAccordion = link.closest(".accordion-collapse");
                const parentToggle = document.querySelector(`[data-bs-target="#${parentAccordion.id}"]`);
                parentToggle.classList.add("active-parent");
            }
        }
    });

    // Special case: Highlight Violation Management if a subpage is active
    const violationPages = ["ViolationType.html", "Referral.html", "Penalty.html"];
    if (violationPages.includes(currentPage)) {
        violationToggleBtn.classList.add("active-parent");
        
        // Also expand the accordion by default
        const collapseInstance = new bootstrap.Collapse(violationAccordion, {
            toggle: true
        });
    }
});

// Prevent Violation accordion from expanding when sidebar is collapsed
violationToggleBtn.addEventListener("click", function (e) {
    if (sidebar.classList.contains("collapsed")) {
        e.preventDefault();
        e.stopPropagation();
    }
});
// End of Sidebar JS

$(document).ready(function () {
    $('#violationTable').DataTable();
    $('#referralTable').DataTable();
    $('#penaltyTable').DataTable();
});

// Section navigation
document.querySelectorAll('.sub-link').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Remove active class from all sub-links
        document.querySelectorAll('.sub-link').forEach(el => {
            el.classList.remove('active');
        });
        
        // Add active class to clicked link
        this.classList.add('active');
        
        // Show the corresponding section
        const section = this.getAttribute('data-section');
        showSection(section);
    });
});

function showSection(sectionId) {
    // Hide all sections
    document.querySelectorAll('.content-section').forEach(section => {
        section.classList.remove('active');
    });
    
    // Show the selected section
    document.getElementById(`${sectionId}-section`).classList.add('active');
}

// Add button functionality
document.getElementById('addViolationBtn').addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('violationtype-modal')).show();
});

document.getElementById('addReferralBtn').addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('referral-modal')).show();
});

document.getElementById('addPenaltyBtn').addEventListener('click', function() {
    new bootstrap.Modal(document.getElementById('penalty-modal')).show();
});

// Archive functionality
let archiveTargetRow = null;

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-action') && e.target.textContent.trim() === 'Archive') {
        archiveTargetRow = e.target.closest('tr');
        new bootstrap.Modal(document.getElementById('archiveConfirmModal')).show();
    }
});

document.getElementById('confirmArchiveBtn').addEventListener('click', function() {
    if (archiveTargetRow) {
        archiveTargetRow.remove();
        archiveTargetRow = null;
        bootstrap.Modal.getInstance(document.getElementById('archiveConfirmModal')).hide();
    }
});