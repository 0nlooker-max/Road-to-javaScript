$.ajax({
    url : "products.php"
}).done(function(data) {

    console.log(data);
    let result = JSON.parse(data);

    let template = document.querySelector("#produtrowtemplate");
    let parent = document.querySelector("#tableBody");

    result.forEach(item =>{
        let clone = template.content.cloneNode(true);
        clone.querySelector(".tdId").innerHTML = item.product_id;
        clone.querySelector(".tdName").innerHTML = item.Product_name;
        parent.appendChild(clone);   
    })
})

// $("h1").click(function(){
//     console.log("H1 is clicked");
   
// });
$("#add").click(function(){
    $.ajax({
        url: "productCreate.php",
        type: "GET",
        dataType: "json",
        data: {
            pname: "sadddd",
        }
    }).done(function(result) {
        console.log("Server Response:", result); 

        if (result.res === "success") {
            alert("Product added successfully");
            window.location.reload();
        } else {
            alert("Error: " + result.msg); 
        }
    })
});


$(document).on("click", "#delete", function() {
    var row = $(this).closest('tr');
    var productId = row.find('.tdId').text();

    console.log("Deleting product ID:", productId);

    $.ajax({
        url: "productDelete.php",
        type: "GET",
        dataType: "json",
        data: {
            pid: productId
        }
    }).done(function(result) {
        if (result.res == "success") {
            alert("Product deleted successfully");
            row.remove();
        } else {
            alert("Product not deleted");
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.log("AJAX Error:", textStatus, errorThrown);
    });
});



$(document).on("click", "#edit", function() {
    var row = $(this).closest('tr');
    var productId = row.find('.tdId').text();

    $.ajax({
        url: "productUpdate.php",
        type: "GET",
        dataType: "json", // Fix incorrect "datatype" spelling
        data: {
            pid: productId,
            pname: "watataps"
        }
    }).done(function(result) {
        console.log("Server Response:", result);
        
        if (result.res === "success") {
            alert("Product updated successfully");
            window.location.reload();
        } else {
            alert("Product not updated");
        }
    })
});

