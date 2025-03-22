$(document).ready(function () {
    console.log("Document is ready");

    // Handle Login Form Submission
    $("#loginForm").on("submit", function (event) {
        event.preventDefault(); // Prevent the default form submission

        let formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: "login.php", // The server-side script to handle login
            type: "POST",
            data: formData,
            dataType: "json"
        }).done(function (response) {
            console.log("Server Response:", response);

            if (response.res === "success") {
                alert("Login successful!");
                window.location.href = "dashboard.php"; // Redirect to the dashboard or another page
            } else {
                alert("Error: " + response.msg); // Show error message
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error:", textStatus, errorThrown);
            console.log("Response Text:", jqXHR.responseText); // Log the response text
            alert("An error occurred while processing your request.");
        });
    });
});