// jQuery Version of Sidebar and Table Scripts
$(document).ready(function () {
  const $sidebar = $('#sidebar');
  const $toggleBtn = $('#toggleSidebar');
  const $closeBtn = $('#closeSidebar');
  const $violationToggleBtn = $('.violation-toggle');
  const $violationAccordion = $('#collapseViolation');

  // Toggle sidebar
  function toggleSidebar() {
      if ($(window).width() <= 768) {
          $sidebar.toggleClass('show');
      } else {
          $sidebar.toggleClass('collapsed');

          // Collapse violation accordion if sidebar is collapsed
          if ($sidebar.hasClass('collapsed')) {
              const collapseInstance = bootstrap.Collapse.getInstance($violationAccordion[0]);
              if (collapseInstance) {
                  collapseInstance.hide();
              }
          }
      }
  }

  // Handle screen size changes
  function handleResize() {
      if ($(window).width() <= 768) {
          $sidebar.removeClass('collapsed');
          $closeBtn.css('display', $sidebar.hasClass('show') ? 'block' : 'none');
      } else {
          $sidebar.removeClass('show');
          $closeBtn.css('display', 'none');
      }
  }

  // Event bindings
  $toggleBtn.on('click', toggleSidebar);
  $closeBtn.on('click', function () {
      $sidebar.removeClass('show');
  });

  $(window).on('load resize', handleResize);

  // Highlight current nav link
  const currentPage = window.location.pathname.split('/').pop();
  $('.sidebar .nav-link').each(function () {
      const $link = $(this);
      const linkPage = $link.attr('href');

      if (linkPage === currentPage) {
          $link.addClass('active');
      }

      $link.on('click', function () {
          $('.sidebar .nav-link').removeClass('active');
          $(this).addClass('active');
      });
  });

  // Prevent accordion expand if sidebar is collapsed
  $violationToggleBtn.on('click', function (e) {
      if ($sidebar.hasClass('collapsed')) {
          e.preventDefault();
          e.stopPropagation();
      }

      const violationPages = ['ViolationType.html', 'Referral.html', 'Penalty.html'];
      if (violationPages.includes(currentPage)) {
          setTimeout(() => {
              $violationToggleBtn.addClass('active-parent');
          }, 300);
      }
  });

  // Auto-expand violation section if subpage is active
  const violationPages = ['ViolationType.html', 'Referral.html', 'Penalty.html'];
  if (violationPages.includes(currentPage)) {
      new bootstrap.Collapse($violationAccordion[0], { toggle: true });
      $violationToggleBtn.addClass('active-parent');
  }


  const $tableBody = $('#table-body');
  const $searchInput = $('#search');
  const $applyDateFilterBtn = $('#applyDateFilter');

  function renderTable(filteredData = data) {
      $tableBody.empty();
      $.each(filteredData, function (index, item) {
          $tableBody.append(`
              <tr>
                  <td>${item.id}</td>
                  <td>${item.name}</td>
                  <td>${item.role}</td>
                  <td>${item.access}</td>
                  <td>${item.date}</td>
              </tr>
          `);
      });

      applyTableLabels();
  }

  renderTable();

  $searchInput.on('keyup', function () {
      const searchValue = $(this).val().toLowerCase();
      const filtered = data.filter(item =>
          item.name.toLowerCase().includes(searchValue) ||
          item.role.toLowerCase().includes(searchValue)
      );
      renderTable(filtered);
  });

  $applyDateFilterBtn.on('click', function () {
      const fromDate = new Date($('#fromDate').val());
      const toDate = new Date($('#toDate').val());

      if (!isNaN(fromDate) && !isNaN(toDate)) {
          const filtered = data.filter(item => {
              const itemDate = new Date(item.date);
              return itemDate >= fromDate && itemDate <= toDate;
          });
          renderTable(filtered);
      } else {
          renderTable();
      }

      const modal = bootstrap.Modal.getInstance($('#dateFilterModal')[0]);
      modal.hide();
  });

  function applyTableLabels() {
      const headers = $('table thead th').map(function () {
          return $(this).text().trim();
      }).get();

      $('table tbody tr').each(function () {
          $(this).find('td').each(function (index) {
              $(this).attr('data-label', headers[index]);
          });
      });
  }
});
