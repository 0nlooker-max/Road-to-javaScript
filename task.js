$.ajax({
    url: "task_table.php", // Ensure this URL is correct and points to the server-side script
    type: "GET",
    dataType: "json", // Expect JSON data from the server
}).done(function (data) {
    console.log("Server Response:", data); // Log the server response for debugging

    let template = document.querySelector("#produtrowtemplatetask");
    let parent = document.querySelector("#tableBodytask");

    data.forEach(item => {
        let clone = template.content.cloneNode(true);
        clone.querySelector(".title").innerHTML = item.task_title;
        clone.querySelector(".disc").innerHTML = item.discription;
        clone.querySelector(".deads").innerHTML = item.deadline;
        clone.querySelector(".stat").innerHTML = item.status;
        parent.appendChild(clone); 
    });
}).fail(function (jqXHR, textStatus, errorThrown) {
    console.error("AJAX Error:", textStatus, errorThrown);
    console.error("Response Text:", jqXHR.responseText); // Log the response text for debugging
    alert("Failed to load table data. Please try again.");
});