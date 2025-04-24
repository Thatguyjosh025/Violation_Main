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

// End of Side Bar JS

// Calendar Initialization
document.addEventListener("DOMContentLoaded", function () {
    const monthYear = document.getElementById("monthYear");
    const calendarDates = document.getElementById("calendarDates");

    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth();

    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    monthYear.textContent = `${months[month]} ${year}`;

    // Fill initial empty slots
    for (let i = 0; i < firstDay; i++) {
        const emptyDiv = document.createElement("div");
        calendarDates.appendChild(emptyDiv);
    }

    // Fill dates
    for (let i = 1; i <= daysInMonth; i++) {
        const day = document.createElement("div");
        day.textContent = i;
        if (
            i === date.getDate() &&
            year === date.getFullYear() &&
            month === date.getMonth()
        ) {
            day.classList.add("today");
        }
        calendarDates.appendChild(day);
    }
});
// End of Calendar JS

// Good Moral JS
document.addEventListener("DOMContentLoaded", function () {
    let ctx = document.getElementById("moralScoreChart").getContext("2d");
    new Chart(ctx, {
        type: "doughnut",
        data: {
            datasets: [{
                data: [97, 3],
                backgroundColor: ["#007bff", "#e0e0e0"],
                borderWidth: 0,
            }]
        },
        options: {
            cutout: "80%",
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: { enabled: false },
                legend: { display: false },
            }
        }
    });
});
