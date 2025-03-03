$.ajax({
    url : "products.php"
}).done(function(data) {

    console.log(data);
    let result = JSON.parse(data);

    let template = document.querySelector("#produtrowtemplate");
    let parent = document.querySelector("#tableBody");

    result.forEach(item => {
        let clone = template.content.cloneNode(true);
        clone.querySelector(".tdId").innerHTML = item.student_id;
        clone.querySelector(".fname").innerHTML = item.first_name;
        clone.querySelector(".lname").innerHTML = item.last_name;
        clone.querySelector(".email").innerHTML = item.email;
        clone.querySelector(".gender").innerHTML = item.gender;
        clone.querySelector(".course").innerHTML = item.course;
        clone.querySelector(".address").innerHTML = item.user_address;
        clone.querySelector(".age").innerHTML = calculateAge(item.birthdate);
        clone.querySelector(".age").setAttribute("data-birthdate", item.birthdate);

        parent.appendChild(clone);
    });
});

function calculateAge(birthdate) {
    let birthDate = new Date(birthdate);
    let today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    let monthDifference = today.getMonth() - birthDate.getMonth();

    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}

// $("h1").click(function(){
//     console.log("H1 is clicked");
   
// });

$(document).ready(function () {

    console.log("Document is ready");

    $("#addBtn").click(function () {
        console.log("Add button clicked"); 
    });

    // Add Student
    $("#addStudentForm").on("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "productCreate.php",
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
            console.log("Response Text:", jqXHR.responseText); 
        });
    });

    // Delete Student
   $(document).on("click", "#delete", function () {
        let row = $(this).closest("tr");
        let studentId = row.find(".tdId").text();

        $.ajax({
            url: "productDelete.php",
            type: "POST",
            dataType: "json",
            data: {
                id: studentId
            }
        }).done(function (result) {
            if (result.res === "success") {
                alert("Student deleted successfully");
                row.remove();
            } else {
                alert("Error deleting student: " + result.msg);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error:", textStatus, errorThrown);
        });
    });
    // Edit Student
    $(document).on("click", ".edit-btn", function () {
        let row = $(this).closest("tr");
        let studentId = row.find(".tdId").text();
        let firstName = row.find(".fname").text();
        let lastName = row.find(".lname").text();
        let email = row.find(".email").text();
        let gender = row.find(".gender").text();
        let course = row.find(".course").text();
        let userAddress = row.find(".address").text();
        let birthdate = row.find(".age").attr("data-birthdate");

        console.log("bitrhdate", birthdate);

        $("#editStudentId").val(studentId);
        $("#editFirstName").val(firstName);
        $("#editLastName").val(lastName);
        $("#editEmail").val(email);
        $("#editGender").val(gender);
        $("#editCourse").val(course);
        $("#editUserAddress").val(userAddress);
        $("#editBirthdate").val(birthdate);
    });

    // Update Student
    $("#editStudentForm").on("submit", function (event) {
        event.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            url: "productUpdate.php",
            type: "POST",
            data: formData,
            dataType: "json"
        }).done(function (result) {
            if (result.res === "success") {
                alert("Student updated successfully");
                $("#editStudentModal").modal("hide");
                window.location.reload();
            } else {
                alert("Error updating student: " + result.msg);
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            console.log("AJAX Error:", textStatus, errorThrown);
        });
    });
});