$.ajax({
    url: "table.php"
}).done(function (data) {

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

        let profileImg = clone.querySelector(".profile-img");
        if (item.profile) {
            profileImg.src = item.profile_image;
        } else {
            profileImg.src = "profiles/default.jpg"; // Ensure this default image exists
        }
        parent.appendChild(clone);
    });
});