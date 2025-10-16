    <!-- Create Modal -->
    <div class="modal fade" id="handbookModal" tabindex="-1" role="dialog" aria-labelledby="handbookModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title fw-bold" id="handbookModalLabel">Add New Section</h5>
            <button type="button" class="btn-close" id="modalCloseBtn" aria-label="Close"></button>
            </button>
        </div>
        <div class="modal-body">
            <form id="section-form">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Header</label>
                <input type="text" name="header" id="header" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control" required></textarea>
            </div>
            <div id="form-feedback" class="mt-2"></div>
            <button type="submit" class="btn btn-primary">Create Section</button>
            </form>
        </div>
        </div>
    </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editSectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <form id="edit-section-form">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title">Edit Section</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="id" id="edit-section-id">
            <div class="mb-3">
                <label for="edit-header" class="form-label">Header</label>
                <input type="text" class="form-control" name="header" id="edit-header" required>
            </div>
            <div class="mb-3">
                <label for="edit-description" class="form-label">Description</label>
                <textarea class="form-control" name="description" id="edit-description" rows="5" required></textarea>
            </div>
            <div id="edit-feedback" class="mt-2"></div>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
        </div>
    </div>
    </div>
<script src="{{ asset('./vendor/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Open modal
    $('#openModalBtn').on('click', function() {
        $('#handbookModal').modal('show');
    });

    // Close modal manually
    $('#modalCloseBtn').on('click', function() {
        $('#handbookModal').modal('hide');
    });

    // Submit form via AJAX
    $('#section-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('sections.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function(response) {
                // SweetAlert success
                Swal.fire({
                    icon: 'success',
                    title: 'Section Added',
                    text: 'Your new handbook section has been saved.',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Reset form and close modal
                $('#section-form')[0].reset();
                $('#handbookModal').modal('hide');

                // Refresh the Additional Policies section
                $.ajax({
                    url: "{{ route('sections.refresh') }}", // Create this route to return partial HTML
                    method: "GET",
                    success: function(data) {
                        $('.handbook-section-addtional-policies').html(data);
                    }
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let message = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    message += `<li>${value}</li>`;
                });
                message += '</ul></div>';
                $('#form-feedback').html(message);
            }
        });
    });

    // Reset form when modal is closed
    $('#handbookModal').on('hidden.bs.modal', function () {
        $('#section-form')[0].reset();
        $('#form-feedback').html('');
    });
});


$(document).ready(function() {

    // ✅ Use event delegation so newly added/updated buttons still work
    $(document).on('click', '.edit-section-btn', function() {
        const id = $(this).data('id');
        const header = $(this).data('header');
        const description = $(this).data('description');

        $('#edit-section-id').val(id);
        $('#edit-header').val(header);
        $('#edit-description').val(description);
        $('#editSectionModal').modal('show');
    });

    // Submit edit form via AJAX
    $('#edit-section-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit-section-id').val();

        $.ajax({
            url: `/sections/${id}`,
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Section Updated',
                    text: 'Changes have been saved.',
                    timer: 2000,
                    showConfirmButton: false
                });

                $('#editSectionModal').modal('hide');

                // Refresh just the updated section
                $.ajax({
                    url: `/sections/${id}/html`,
                    method: 'GET',
                    success: function(data) {
                        $(`#section-${id}`).html(data);
                    }
                });
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let message = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    message += `<li>${value}</li>`;
                });
                message += '</ul></div>';
                $('#edit-feedback').html(message);
            }
        });
    });
});


$(document).ready(function() {
    $('.delete-section-btn').on('click', function() {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This will permanently delete the section.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/sections/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: 'Section has been removed.',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $(`#section-${id}`).remove();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete section.'
                        });
                    }
                });
            }
        });
    });
});
</script>