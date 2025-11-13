//cart
let cartIcon = document.querySelector("#cart-icon");
let cart = document.querySelector(".cart");
let closeCart = document.querySelector("#close-cart");

//open cart
cartIcon.onclick = () => {
    cart.classList.add("active");
};

//close cart
closeCart.onclick = () => {
    cart.classList.remove("active");
};

//cart working
if (document.readyState == "loading") {
    document.addEventListener("DOMContentLoaded", ready);
} else {
    ready();
}

//making function
function ready() {
    //remove booking from cart
    var removeBookButtons = document.getElementsByClassName("booking-remove");
    console.log(removeBookButtons);
    for (var i = 0; i < removeBookButtons.length; i++) {
        var button = removeBookButtons[i];
        button.addEventListener("click", removeButtons);
    }
}
    //remove booking from cart
    function removeButtons(event){
        var buttonClicked = event.target;
        buttonClicked.parentElement.remove();
    }

    //update total
    function updateTotal() {
        var cartContent = document.getElementsByClassName("booking-content")[0];
        var cartBoxes = cartContent.getElementsByClassName("booking-box");
        var total = 0;
        for (var i = 0; i < cartBoxes.length; i++) {
          var cartBox = cartBoxes[i];
          var priceElement = cartBox.getElementsByClassName("booking-price")[0];
          var quantityElement = cartBox.getElementsByClassName("booking-quantity")[0];
          var price = parseFloat(priceElement.innerText.replace("RM", ""));
          var quantity = quantityElement.value;
          total = total + price * quantity;
        }
        document.getElementsByClassName("total-price")[0].innerText = "RM" + total;
      }
      