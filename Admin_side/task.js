function loadStudents(selectId = 'assignedStudent') {
    fetch('../fetch_students_to_task.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log("Server Response (Parsed):", data);

            const select = document.getElementById(selectId);
            if (!select) return;

            select.innerHTML = ''; // Clear existing options

            // Placeholder for Choices.js
            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.disabled = true;
            placeholderOption.selected = true;
            placeholderOption.textContent = 'Select Students';
            select.appendChild(placeholderOption);

            data.forEach(student => {
                const option = document.createElement('option');
                option.value = student.student_id;
                option.textContent = `${student.first_name} ${student.last_name}`;
                select.appendChild(option);
            });

            // Destroy previous Choices instance if it exists
            if (select.choicesInstance) {
                select.choicesInstance.destroy();
            }

            // Attach new Choices instance
            const choices = new Choices(select, {
                removeItemButton: true,
                searchEnabled: true,
                placeholderValue: 'Select Students',
                itemSelectText: '',
                maxItemCount: 5,
                duplicateItemsAllowed: false,
            });

            // Store instance on element
            select.choicesInstance = choices;
        })
        .catch(error => console.error('Error loading students:', error));
}

// Populate the task table
function loadTasks() {
    $.ajax({
        url: "task_table.php",
        type: "GET",
        dataType: "json",
    })
        .done(function (data) {
            console.log("Server Response (Task Table):", data);

            const template = document.querySelector("#produtrowtemplatetask");
            const parent = document.querySelector("#tableBodytask");

            parent.innerHTML = "";

            data.forEach(item => {
                const clone = template.content.cloneNode(true);
                clone.querySelector(".title").textContent = item.task_title;
                clone.querySelector(".disc").textContent = item.discription;
                clone.querySelector(".deads").textContent = item.deadline;

                // Set the correct task ID for the view and edit buttons
                clone.querySelector("#view").setAttribute("data-task-id", item.task_id);
                const editBtn = clone.querySelector(".edit-btn");
                editBtn.setAttribute("data-task-id", item.task_id);

                parent.appendChild(clone);
            });
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            console.error("AJAX Error:", textStatus, errorThrown);
            console.error("Response Text:", jqXHR.responseText);
            alert("Failed to load table data. Please try again.");
        });
}

// Handle view button clicks
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('tableBodytask').addEventListener('click', function (event) {
        if (event.target && event.target.id === 'view') {
            const taskId = event.target.dataset.taskId;
            console.log(`View button clicked for Task ID: ${taskId}`);
            fetchTaskDetails(taskId);
        }
    });

    // Load tasks and students when the page loads
    loadTasks();
    loadStudents();
});

// Mark a submission as completed
function markCompleted(assignment_id) {
    fetch(`mark_completed.php?assignment_id=${assignment_id}`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.res === 'success') {
                alert(data.msg);
                location.reload();
            } else {
                alert('Error: ' + data.msg);
            }
        })
        .catch(error => console.error('Error marking submission as completed:', error));
}

function fetchTaskDetails(taskId) {
    fetch(`get_task_details.php?task_id=${taskId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch task details');
            }
            return response.json();
        })
        .then(data => {
            console.log("Task Details:", data);

            if (data.error) {
                console.error(data.error);
                alert("Error fetching task details: " + data.error);
                return;
            }

            // Populate task details in the modal
            document.getElementById('modalTaskTitle').textContent = data.task_title || 'N/A';
            document.getElementById('modalTaskDescription').textContent = data.description || 'N/A';
            document.getElementById('modalTaskDeadline').textContent = data.deadline || 'N/A';

            // Populate pending assignments
            const pendingTable = document.getElementById('pendingAssignments');
            pendingTable.innerHTML = '';
            data.pending.forEach(student => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${student.name}</td>
                    <td>No File</td>
                    <td>Pending</td>
                `;
                pendingTable.appendChild(row);
            });

            // Populate submitted assignments with download + open links
            const submittedTable = document.getElementById('submittedAssignments');
            submittedTable.innerHTML = '';
            data.submitted.forEach(student => {
                const row = document.createElement('tr');
                // Normalize values
                const file = student.attach_file && student.attach_file !== 'NULL' && student.attach_file !== '' ? student.attach_file : '';
                const link = student.attach_link && student.attach_link !== 'NULL' && student.attach_link !== '' ? student.attach_link : '';

                let fileCell = '';
                if (file && !link) {
                    fileCell = `<a href="${file.startsWith('/') ? file : '/' + file}" download>Download</a>`;
                } else if (link && !file) {
                    fileCell = `<a href="${link}" target="_blank" rel="noopener noreferrer">Open Link</a>`;
                } else if (file && link) {
                    fileCell = `<a href="${file.startsWith('/') ? file : '/' + file}" download>Download</a> | <a href="${link}" target="_blank" rel="noopener noreferrer">Open Link</a>`;
                } else {
                    fileCell = 'No File';
                }

                row.innerHTML = `
                    <td>${student.name || student.student_name}</td>
                    <td>${fileCell}</td>
                    <td>Submitted</td>
                    <td>
                        <button class="btn btn-success" onclick="markCompleted(${student.assignment_id})">
                            Mark Completed
                        </button>
                    </td>
                `;
                submittedTable.appendChild(row);
            });

            // Show the modal
            const taskDetailsModal = new bootstrap.Modal(document.getElementById('taskDetailsModal'));
            taskDetailsModal.show();
        })
        .catch(error => console.error('Error fetching task details:', error));
}

// Load students into the edit modal's select and pre-select assigned ones
function populateEditAssignedStudents(allStudents, assignedStudents) {
    const select = document.getElementById('editAssignedStudents');
    select.innerHTML = '';

    allStudents.forEach(student => {
        const option = document.createElement('option');
        option.value = student.student_id;
        option.textContent = student.name || (student.first_name + ' ' + student.last_name);
        // Pre-select if assigned
        if (assignedStudents.some(assigned => assigned.student_id == student.student_id)) {
            option.selected = true;
        }
        select.appendChild(option);
    });

    // Destroy previous Choices instance if it exists
    if (select.choicesInstance) {
        select.choicesInstance.destroy();
    }
    // Attach new Choices instance
    const choices = new Choices(select, {
        removeItemButton: true,
        searchEnabled: true,
        placeholderValue: 'Select Students',
        itemSelectText: '',
        maxItemCount: 5,
        duplicateItemsAllowed: false,
    });
    select.choicesInstance = choices;

    // Set selected values in Choices.js
    choices.setValue(
        assignedStudents.map(s => ({ value: String(s.student_id), label: s.name || (s.first_name + ' ' + s.last_name) }))
    );
}

document.addEventListener('DOMContentLoaded', function () {
    // Define the badge rendering function in this scope
    function renderAssignedBadges(assignedStudents) {
        const assignedList = document.getElementById('assignedStudentsList');
        assignedList.innerHTML = '';
        assignedStudents.forEach(student => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary text-white m-1 d-flex align-items-center';
            badge.innerHTML = `
                ${student.name || student.first_name + ' ' + student.last_name}
                <button type="button" class="btn btn-sm btn-danger ms-2 remove-student-btn" data-student-id="${student.student_id}">&times;</button>
            `;
            assignedList.appendChild(badge);
        });
    }

    document.getElementById('tableBodytask').addEventListener('click', function (event) {
        if (event.target && event.target.classList.contains('edit-btn')) {
            const taskId = event.target.dataset.taskId;

            fetch(`get_task_details.php?task_id=${taskId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error fetching task details: ' + data.error);
                        return;
                    }

                    // Populate modal fields
                    document.getElementById('editTaskTitle').value = data.task_title || '';
                    document.getElementById('editTaskDescription').value = data.description || '';
                    document.getElementById('editTaskDeadline').value = new Date(data.deadline).toISOString().slice(0, 16);
                    document.getElementById('editTaskId').value = taskId;

                    // Load students into select and pre-select assigned
                    loadStudents('editAssignedStudents');
                    setTimeout(() => {
                        const studentSelect = document.getElementById('editAssignedStudents');
                        const assignedStudents = Array.isArray(data.assigned_students) ? data.assigned_students : [];

                        // Pre-select assigned students in the select
                        Array.from(studentSelect.options).forEach(option => {
                            option.selected = assignedStudents.some(
                                assigned => String(assigned.student_id) === String(option.value)
                            );
                        });
                        if (studentSelect.choicesInstance) {
                            studentSelect.choicesInstance.setValue(
                                assignedStudents.map(s => String(s.student_id))
                            );
                        }

                        // Render assigned students as badges
                        renderAssignedBadges(assignedStudents);

                        // Listen for changes in the select to update badges
                        studentSelect.addEventListener('change', function () {
                            const selectedIds = Array.from(studentSelect.selectedOptions).map(opt => String(opt.value));
                            const updatedAssigned = assignedStudents.filter(s => selectedIds.includes(String(s.student_id)));
                            renderAssignedBadges(updatedAssigned);
                        });

                    }, 300);

                    // Show the modal
                    const editTaskModal = new bootstrap.Modal(document.getElementById('editTaskModal'));
                    editTaskModal.show();
                })
                .catch(error => console.error('Error fetching task details:', error));
        }
    });

    // Handle badge removal
    document.getElementById('assignedStudentsList').onclick = function (event) {
        if (event.target.classList.contains('remove-student-btn')) {
            const studentId = event.target.getAttribute('data-student-id');
            // Remove from select
            const select = document.getElementById('editAssignedStudents');
            const option = select.querySelector(`option[value="${studentId}"]`);
            if (option) {
                option.selected = false;
                if (select.choicesInstance) {
                    select.choicesInstance.removeActiveItemsByValue(studentId);
                }
                // Trigger change event to update badges
                select.dispatchEvent(new Event('change'));
            }
            // Remove badge
            event.target.parentElement.remove();
        }
    };


    // Handle Save Changes (AJAX)
    document.getElementById('saveTaskChanges').addEventListener('click', function () {
        const taskId = document.getElementById('editTaskId').value;
        const title = document.getElementById('editTaskTitle').value.trim();
        const description = document.getElementById('editTaskDescription').value.trim();
        const deadline = document.getElementById('editTaskDeadline').value;
        const assignedStudents = Array.from(document.getElementById('editAssignedStudents').selectedOptions)
            .map(option => option.value);

        if (!title || !description || !deadline) {
            alert("Please fill in all fields.");
            return;
        }
        if (assignedStudents.length === 0) {
            alert("Please select at least one student.");
            document.getElementById('editAssignedStudents').focus();
            return;
        }

        fetch('update_task.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                task_id: taskId,
                task_title: title,
                task_description: description,
                task_deadline: deadline,
                assigned_students: assignedStudents
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.res === 'success') {
                    alert("Task updated successfully!");
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editTaskModal'));
                    if (modal) modal.hide();
                    if (typeof loadTasks === "function") loadTasks();
                    location.reload();
                } else {
                    alert("Failed to update task: " + (data.msg || "Unknown error"));
                }
            })
            .catch(error => {
                alert("Request failed.");
                console.error(error);
            });
    });
});

// When the Edit Task modal is opened, reload students for the dropdown
document.addEventListener('DOMContentLoaded', function () {
    const editTaskModal = document.getElementById('editTaskModal');
    if (editTaskModal) {
        editTaskModal.addEventListener('show.bs.modal', function () {
            loadStudents('editAssignedStudents');
        });
    }
});

// Handle add task form (AJAX)
$(document).ready(function () {
    $('#taskForm').on('submit', function (e) {
        e.preventDefault();

        const selectedStudents = Array.from(document.getElementById("assignedStudent").selectedOptions)
            .map(option => option.value)
            .filter(val => val !== "");

        if (selectedStudents.length === 0) {
            alert("Please select at least one student.");
            document.querySelector('.choices__input').focus();
            return false;
        }

        // Gather form data
        var formData = new FormData(this);

        $.ajax({
            url: 'Task_add.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                if (response.res === 'success') {
                    alert(response.msg);
                    location.reload();
                } else {
                    alert(response.msg);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred: ' + error);
            }
        });
    });
});


