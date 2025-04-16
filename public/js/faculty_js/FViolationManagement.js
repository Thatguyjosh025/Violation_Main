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

// Dropdown JS
function toggleDropdown(dropdownId) {
    let menu = document.getElementById('menu' + dropdownId.slice(-1)); // Corrected line
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function filterOptions(menuId) {
    let input = document.getElementById("search" + menuId.slice(-1)).value.toLowerCase();
    let labels = document.querySelectorAll("#" + menuId + " label");
    labels.forEach(label => {
        label.style.display = label.innerText.toLowerCase().includes(input) ? "block" : "none";
    });
}

document.querySelectorAll('.dropdown-menu input[type="checkbox"]').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        let dropdownId = this.closest('.dropdown').id;
        let selectedContainer = document.getElementById('selected-items' + dropdownId.slice(-1));
        if (this.checked) {
            let item = document.createElement('div');
            item.classList.add('selected-item');
            item.innerHTML = `${this.value} <span onclick="removeItem(this, '${this.value}', '${dropdownId}')">&times;</span>`;
            selectedContainer.appendChild(item);
        } else {
            document.querySelectorAll('.selected-item').forEach(item => {
                if (item.textContent.includes(this.value)) {
                    item.remove();
                }
            });
        }
    });
});

function removeItem(element, value, dropdownId) {
    element.parentElement.remove();
    document.querySelectorAll('#' + dropdownId + ' input[type="checkbox"]').forEach(checkbox => {
        if (checkbox.value === value) {
            checkbox.checked = false;
        }
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    document.querySelectorAll('.dropdown').forEach(dropdown => {
        if (!dropdown.contains(event.target)) {
            dropdown.querySelector('.dropdown-menu').style.display = 'none';
        }
    });
});

// Upload Modal JS
document.addEventListener("DOMContentLoaded", function() {
    const dropZone = document.getElementById("dropZone");
    const fileInput = document.getElementById("fileInput");
    const fileNameDisplay = document.getElementById("fileName");

    dropZone.addEventListener("click", () => fileInput.click());

    fileInput.addEventListener("change", function() {
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = "Selected file: " + fileInput.files[0].name;
            let fileNames = [];
            for (let i = 0; i < fileInput.files.length; i++) {
                fileNames.push(fileInput.files[i].name);
            }
            fileNamesDisplay.textContent = fileNames.length > 0 ? "Selected files: " + fileNames.join(", ") : "";
        }
    });

    dropZone.addEventListener("dragover", (event) => {
        event.preventDefault();
        dropZone.style.borderColor = "#007bff";
    });

    dropZone.addEventListener("dragleave", () => {
        dropZone.style.borderColor = "#ccc";
    });

    dropZone.addEventListener("drop", (event) => {
        event.preventDefault();
        fileInput.files = event.dataTransfer.files;
        if (fileInput.files.length > 0) {
            fileNameDisplay.textContent = "Selected file: " + fileInput.files[0].name;
        }
    });
});