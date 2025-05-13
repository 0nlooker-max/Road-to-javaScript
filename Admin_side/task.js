$.ajax({
    url: "task_table.php", // Check if this URL is correct
    type: "GET",
    dataType: "json" // We expect a JSON array from the server
})
.done(function (data) {
    console.log("Server Response:", data); // Debug: check structure of the data

    let template = document.querySelector("#produtrowtemplatetask"); // Make sure this ID exists in your HTML
    let parent = document.querySelector("#tableBodytask");

    data.forEach(item => {
        let clone = template.content.cloneNode(true);
        clone.querySelector(".title").textContent = item.task_title;
        clone.querySelector(".disc").textContent = item.discription; // Confirm the key name is correct
        clone.querySelector(".deads").textContent = item.deadline;
        parent.appendChild(clone);
    });
})
.fail(function (jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    console.error("Response Text:", jqXHR.responseText); // Log server's raw response
    alert("Failed to load table data. Please try again.");
});

document.addEventListener("DOMContentLoaded", function () {
    const taskForm = document.getElementById("taskForm");
    const addTaskButton = document.getElementById("AddTToggleBtn");

    addTaskButton.addEventListener("click", function () {
        const formData = new FormData(taskForm);

        // Send AJAX request to add the task
        $.ajax({
            url: "Task_add.php", // Backend script to handle task addition
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
        })
            .done(function (response) {
                if (response.res === "success") {
                    alert(response.msg);
                    location.reload(); // Reload the page to reflect the new task
                } else {
                    alert("Error: " + response.msg);
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
                alert("Failed to add the task. Please try again.");
            });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const assignedStudentSelect = document.getElementById("assignedStudent");

    // Initialize Choices.js
    const choices = new Choices(assignedStudentSelect, {
        removeItemButton: true, // Allow removing selected items
        placeholder: true,
        placeholderValue: "Select Students",
        searchPlaceholderValue: "Search Students",
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const assignedStudentSelect = document.getElementById("assignedStudent");

    // Initialize Choices.js
    const choices = new Choices(assignedStudentSelect, {
        removeItemButton: true, // Allow removing selected items
        placeholder: true,
        placeholderValue: "Select Students",
        searchPlaceholderValue: "Search Students",
    });
});