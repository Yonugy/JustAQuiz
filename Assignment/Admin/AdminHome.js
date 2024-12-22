let open = document.querySelector(".del-button");
let close = document.getElementById("close-button");
let target = document.querySelector(".popup-confirm");
let submit = document.getElementById("pop-up-No");
let close2 = document.getElementById("close-button2");
let open2 = document.querySelector(".profilepic");
let target2 = document.querySelector(".popup");


open2.addEventListener("click",()=>{
    target2.style.display = "flex";
  })
  
close2.addEventListener("click",()=>{
    target2.style.display = "none";
  })

open.addEventListener("click",()=>{
    target.style.display = "flex";
  })
  
close.addEventListener("click",()=>{
    target.style.display = "none";
  })

submit.addEventListener("click",()=>{
    target.style.display = "none";
})

