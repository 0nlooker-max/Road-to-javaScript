$.ajax({
    url: "table.php", // Ensure this URL is correct and points to the server-side script
    type: "GET",
    dataType: "json", // Expect JSON data from the server
}).done(function (data) {
    console.log("Server Response:", data); // Log the server response for debugging

    let template = document.querySelector("#produtrowtemplate");
    let parent = document.querySelector("#tableBody");

    data.forEach(item => {
        let clone = template.content.cloneNode(true);
        clone.querySelector(".fname").innerHTML = item.first_name;
        clone.querySelector(".lname").innerHTML = item.last_name;
        clone.querySelector(".course").innerHTML = item.course;
        clone.querySelector(".address").innerHTML = item.user_address;

        // Check is_verified value and display appropriate text
        if (item.is_verified == 1) {
            clone.querySelector(".is_verified").innerHTML = "Verified";
            clone.querySelector(".is_verified").classList.add("bg-success", "text-white"); // Add green background and white text
        } else {
            clone.querySelector(".is_verified").innerHTML = "Not Verified";
            clone.querySelector(".is_verified").classList.add("bg-danger", "text-white"); // Add red background and white text
        }
        parent.appendChild(clone); // Append the row to the table body
    });
}).fail(function (jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    console.error("Response Text:", jqXHR.responseText); // Log the response text for debugging
    alert("Failed to load table data. Please try again.");
});


// Add the new code for the edit modal functionality
document.addEventListener("DOMContentLoaded", function () {
    const editToggleBtn = document.getElementById("editToggleBtn");
    const editProfileForm = document.getElementById("editProfileForm");
    const inputs = editProfileForm.querySelectorAll("input");

    let isEditing = false; // Track whether the form is in edit mode

    editToggleBtn.addEventListener("click", function () {
        if (!isEditing) {
            // Enable input fields for editing
            inputs.forEach(input => {
                input.removeAttribute("readonly");
                input.removeAttribute("disabled");
            });
            editToggleBtn.textContent = "Save"; // Change button text to "Save"
            isEditing = true;
        } else {
            // Submit the form to update the profile
            const formData = new FormData(editProfileForm);

            fetch("edit_profile.php", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.res === "success") {
                        alert(data.msg);
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert("Error: " + data.msg);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("An error occurred while updating the profile.");
                });

            // Disable input fields after saving
            inputs.forEach(input => {
                input.setAttribute("readonly", "true");
                if (input.type === "file") {
                    input.setAttribute("disabled", "true");
                }
            });
            editToggleBtn.textContent = "Edit"; // Change button text back to "Edit"
            isEditing = false;
        }
    });
});

$(".logoutBtn").on("click", function (event) {
    event.preventDefault(); // Prevent the default link behavior

    if (confirm("Are you sure you want to log out?")) {
        $.ajax({
            url: "logout.php", // The server-side script to handle logout
            type: "GET", // Use GET since no data is being sent
            dataType: "json" // Expect a JSON response
        })
            .done(function (response) {
                console.log("Server Response:", response);

                if (response.res === "success") {
                    alert(response.msg); // Show success message
                    window.location.href = "../index.html"; // Redirect to the login page
                } else {
                    alert("Error: " + response.msg); // Show error message
                }
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
                console.error("Response Text:", jqXHR.responseText); // Log the response text
                alert("An error occurred while processing your logout request.");
            });
    }
});