document.getElementById("pop").style.width = "0";
document.getElementById("pop").style.height = "0";
document.getElementById("btnclose").style.visibility = "hidden";
window.onscroll = scrollFunction;

function scrollFunction() {
  if (
    document.body.scrollTop > 20 ||
    document.documentElement.scrollTop > 300
  ) {
    document.getElementById("btnscroll").style.display = "block";
  } else {
    document.getElementById("btnscroll").style.display = "none";
  }
}

function topFunction() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}

// document.getElementById("11").addEventListener("click", ()=>alert("click"));

document.querySelectorAll(".bookbtn").forEach((e) => {
  e.addEventListener("click", (a) =>{
    document.getElementById("frame").src = e.id;
    document.getElementById("pop").style.width = "100%";
  document.getElementById("pop").style.height = "100%";
  document.getElementById("btnclose").style.visibility = "visible";
  
  });
});

document.getElementById("btnclose").addEventListener("click", open);

// function close(id) {
//   document.getElementById("pop").style.width = "100%";
//   document.getElementById("pop").style.height = "100%";
//   document.getElementById("btnclose").style.visibility = "visible";
//   document.getElementById("frame").src = id;
// }


function open() {
  document.getElementById("pop").style.width = "0";
  document.getElementById("pop").style.height = "0";
  document.getElementById("btnclose").style.visibility = "hidden";
}


