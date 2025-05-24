// Place this after your modal HTML and after jQuery/Bootstrap are loaded
$(document).ready(function () {
    // Open modal and set task id
    $(document).on('click', '.attach-file-btn', function () {
        const taskId = $(this).data('task-id');
        $('#modalTaskId').val(taskId);
        $('#submitTaskModal').modal('show');
    });

    // Handle form submission (AJAX)
    $('#submitTaskForm').on('submit', function (e) {
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
            success: function (response) {
                alert('Submission successful!');
                $('#submitTaskModal').modal('hide');
                location.reload();
            },
            error: function () {
                alert('Submission failed. Please try again.');
            }
        });
    });
});

$(document).on('click', '.view-submission-btn', function () {
    const title = $(this).data('title');
    const description = $(this).data('description');
    const deadline = $(this).data('deadline');
    const status = $(this).data('status');
    const file = $(this).data('file');
    const link = $(this).data('link');
    const type = $(this).data('type');
    const submitted = $(this).data('submitted') || '';

    let fileRow = '';
    if (type === 'file' && file) {
        fileRow = `<b>File:</b> <a href="${file}" target="_blank" download>Download File</a>`;
    } else if (type === 'link' && link) {
        fileRow = `<b>Link:</b> <a href="${link}" target="_blank">${link}</a>`;
    }

    $('#submissionDetailsBody').html(`
        <b>Title:</b> ${title}<br>
        <b>Description:</b> ${description}<br>
        <b>Deadline:</b> ${deadline}<br>
        <b>Submission Type:</b> ${type}<br>
        <b>Submitted At:</b> ${submitted}<br>
        ${fileRow}
    `);

    $('#submissionDetailsModal').modal('show');
});