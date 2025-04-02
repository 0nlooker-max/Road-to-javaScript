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
        } else {
            clone.querySelector(".is_verified").innerHTML = "Not Verified";
        }

        parent.appendChild(clone); // Append the row to the table body
    });
}).fail(function (jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    console.error("Response Text:", jqXHR.responseText); // Log the response text for debugging
    alert("Failed to load table data. Please try again.");
});