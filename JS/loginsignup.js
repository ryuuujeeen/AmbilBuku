//show/hide password 
const inputPass = document.querySelector(".input-pw");
const toggle = document.querySelector(".pw-toggle-btn");
toggle.addEventListener("click", ()=>{
    if(inputPass.type === "password"){
        inputPass.type = "text";
        toggle.classList.replace("bi-eye-slash", "bi-eye");
        console.log(btn);
    }
    else 
    {
        inputPass.type = "password";
        toggle.classList.replace("bi-eye", "bi-eye-slash");
        console.log(btn);
    }
}) 



