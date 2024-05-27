var burgerMenu = document.getElementById("burger-menu");
var overlay = document.getElementById("menu");

burgerMenu.addEventListener("click", function () {
this.classList.toggle("close");
overlay.classList.toggle("overlay");
});

const menu = document.getElementById("menu");

function closemenu() {
burgerMenu.classList.toggle("overlay");
burgerMenu.classList.remove("close");
overlay.classList.toggle("close");
overlay.classList.remove("overlay");
}
