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

// document.addEventListener("DOMContentLoaded", function () {
//     const navLinks = document.querySelectorAll(".sidebar .nav-link");
//     const currentPage = window.location.pathname.split("/").pop();

//     navLinks.forEach(link => {
//         const linkPage = link.getAttribute("href");

//         if (linkPage === currentPage) {
//             link.classList.add("active");
//         } else {
//             link.classList.remove("active");
//         }

//         link.addEventListener("click", function () {
//             navLinks.forEach(nav => nav.classList.remove("active"));
//             this.classList.add("active");
//         });
//     });
// });
// End of Side Bar JS
 
// Calendar JS
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
 
 // Chart.js Graph
 var ctx = document.getElementById("violationChart").getContext("2d");
 var violationChart = new Chart(ctx, {
     type: "bar",
     data: {
         labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
         datasets: [
             {
                 label: "Minor",
                 backgroundColor: "#f1c40f",
                 data: [3, 7, 1, 10, 6, 2, 5]
             },
             {
                 label: "Major",
                 backgroundColor: "#e74c3c",
                 data: [0, 2, 0, 0, 0, 5, 0]
             }
         ]
     },
     options: {
         responsive: true,
         maintainAspectRatio: false,
         scales: {
             y: {
                 beginAtZero: true
             }
         }
     }
 });