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

// Calendar JS
 $(document).ready(async function() {
    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();

    // Set the month and year in the header
    $("#monthYear").text(`${months[month]} ${year}`);

    // Fetch schedules from your backend
    const schedules = await fetchSchedules();

    // Fill initial empty slots
    for (let i = 0; i < firstDay; i++) {
        $("<div>").appendTo("#calendarDates");
    }

    // Fill dates
    for (let i = 1; i <= daysInMonth; i++) {
        const dayDiv = $("<div>").text(i);

        // Check if this date has schedules
        const hasSchedule = schedules.some(schedule => {
            const scheduleDate = new Date(schedule.start_date);
            return (
                scheduleDate.getDate() === i &&
                scheduleDate.getMonth() === month &&
                scheduleDate.getFullYear() === year
            );
        });

        if (hasSchedule) {
            dayDiv.addClass("has-schedule");
            dayDiv.on("click", () => showSchedulesForDate(i, month, year, schedules));
        }
        if (i === date.getDate() && year === date.getFullYear() && month === date.getMonth()) {
            dayDiv.addClass("today");
        }

        dayDiv.appendTo("#calendarDates");
    }
});

// Function to fetch schedules (replace with your actual API call)
async function fetchSchedules() {
    const response = await fetch("/schedules");
    const schedules = await response.json();
    return schedules;
}

// Function to show schedules for a clicked date
function showSchedulesForDate(day, month, year, schedules) {
    const modalList = $("#scheduleList");
    modalList.empty(); // Clear previous content

    const selectedDate = new Date(year, month, day);
    const filteredSchedules = schedules.filter(schedule => {
        const scheduleDate = new Date(schedule.start_date);
        return (
            scheduleDate.getDate() === day &&
            scheduleDate.getMonth() === month &&
            scheduleDate.getFullYear() === year
        );
    });

    if (filteredSchedules.length === 0) {
        modalList.html("<p>No schedules for this date.</p>");
    } else {
        filteredSchedules.forEach(schedule => {
            const scheduleItem = $(`
                <div class="session-item mt-3">
                    <span><strong>${schedule.student_name}</strong></span>
                    <span>Time: ${schedule.start_time} - ${schedule.end_time}</span>
                    <p>Date: ${schedule.start_date}</p>
                </div>
            `);
            modalList.append(scheduleItem);
        });
    }

    // Show the modal
    $("#scheduleModal").modal("show");
}
 // End of Calendar JS
  
 //Notification JS
 function dismissNotification(button) {
         const card = button.closest('.notification-card');
         card.style.transition = 'opacity 0.3s ease';
         card.style.opacity = '0';
         setTimeout(() => card.remove(), 300);
     }