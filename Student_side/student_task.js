// Place this after your modal HTML and after jQuery/Bootstrap are loaded
$(document).ready(function() {
    // Open modal and set task id
    $(document).on('click', '.attach-file-btn', function() {
        const taskId = $(this).data('task-id');
        $('#modalTaskId').val(taskId);
        $('#submitTaskModal').modal('show');
    });

    // Handle form submission (AJAX)
    $('#submitTaskForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        // Optional: Check if at least file or link is provided
        if (
            !$('input[name="attach_file"]').val() &&
            !$('input[name="attach_link"]').val()
        ) {
            alert('Please upload a file or provide a link.');
            return;
        }

        $.ajax({
            url: 'submit_task.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Submission successful!');
                $('#submitTaskModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Submission failed. Please try again.');
            }
        });
    });
});