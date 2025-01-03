//navbar user menu dropdown
let openMenuAct = document.getElementById("toggle-user-dropdown");

function openMenu()
{
    openMenuAct.classList.toggle("open");
}


function changeLabelButton() {
    const inputPass = document.querySelector(".input-pw");
    const toggle = document.querySelector(".pw-toggle-btn");
    const btn = document.querySelector(".editsavebtn");
    const form = document.querySelector(".profile-content");

    if (btn.innerText === "Edit Profile") {
        inputPass.disabled = false;
        inputPass.readOnly = false;

        btn.innerText = "Save Profile";
        

        toggle.addEventListener("click", () => {
            if (inputPass.type === "password") {
                inputPass.type = "text";
                toggle.classList.replace("bi-eye-slash", "bi-eye");
            } else {
                inputPass.type = "password";
                toggle.classList.replace("bi-eye", "bi-eye-slash");
            }
        });
    } else {
        inputPass.disabled = false;
        inputPass.readOnly = true;

        toggle.classList.replace("bi-eye", "bi-eye-slash");
        toggle.disabled = true;
        btn.innerText = "Edit Profile";
        btn.type = "submit";
        form.submit();
    }
}
