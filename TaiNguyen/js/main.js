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
         // Toggle nav trên mobile
document.querySelector('.cp-burger')?.addEventListener('click', ()=>{
  document.querySelector('.cp-header')?.classList.toggle('is-open');
});

// Đếm ngược đơn giản
const cd = document.querySelector('.cp-countdown');
if (cd) {
  const end = new Date(cd.dataset.end);
  const tick = () => {
    const s = Math.max(0, Math.floor((end - new Date())/1000));
    const h = String(Math.floor(s/3600)).padStart(2,'0');
    const m = String(Math.floor((s%3600)/60)).padStart(2,'0');
    const ss= String(s%60).padStart(2,'0');
    cd.textContent = `${h}:${m}:${ss}`;
  };
  tick(); setInterval(tick, 1000);
}
   
        