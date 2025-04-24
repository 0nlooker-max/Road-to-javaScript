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
        clone.querySelector(".stat").textContent = item.status;
        parent.appendChild(clone);
    });
})
.fail(function (jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    console.error("Response Text:", jqXHR.responseText); // Log server's raw response
    alert("Failed to load table data. Please try again.");
});


 // Add Student
 $("#addStudentForm").on("submit", function (event) {
    event.preventDefault();

    let formData = new FormData(this);

    $.ajax({
        url: "add.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json"
    }).done(function (result) {
        console.log("Server Response:", result);

        if (result.res === "success") {
            alert("Student added successfully");
            $("#addStudentModal").modal("hide");
            window.location.reload();
        } else {
            alert("Error: " + result.msg);
        }
    }).fail(function (jqXHR, textStatus, errorThrown) {
        console.log("AJAX Error:", textStatus, errorThrown);
        console.log("Response Text:", jqXHR.responseText); // Log the response text
    });
});