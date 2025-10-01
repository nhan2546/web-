// validation ví dụ 
function    validateForm() {
    const form = document.getElementById(formID);
    if (!form ) return;
     
    form.addEventListener("submit", function(e) {
        const inputs = form.querySelectorAll("inputs[required]");
        for (let inputs of inputs) {
            if (!inputs.vallua.trim() == ""){
                alert("Vui lòng điền đầy đủ thông tin!.")
                e.preventDefault();
                return; 
            }
        }
     });
    }
    // ajax ví dụ
       function addToCart(productId) {
        fetch ("add_to_cart.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "id=" +productId
        })
        .then(reponse => reponse.text ())
        .then(data => {
            document.getElementById("cart-count").innerText = data;
     });
    }
            
        