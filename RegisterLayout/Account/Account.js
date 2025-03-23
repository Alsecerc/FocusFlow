function getID(element) {
    return document.getElementById(element);
}

function getClass(element) {
    return document.getElementsByClassName(element);
}

function getQueryAll(element) {
    return document.querySelectorAll(element);
}

function getQuery(element) {
    return document.querySelector(element);
}


let INPUTS = getQueryAll('.INPUT__BOX');

INPUTS.forEach((element) => {
    let INPUT = element.querySelector(".INPUT__INPUT");
    let PLACEHOLDER = element.querySelector(".INPUT__PLACEHOLDER");

    INPUT.addEventListener('input', function () {

        // If the input is invalid, add the INVALID class
        if (INPUT.value.trim() == '') {
            INPUT.classList.remove("INVALID_BORDER");
            PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
            INPUT.classList.remove("VALID_BORDER");
            PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
        } else if (!INPUT.checkValidity()) {
            INPUT.classList.add("INVALID_BORDER");
            PLACEHOLDER.classList.add("INVALID_PLACEHOLDER");
            INPUT.classList.remove("VALID_BORDER");
            PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
        } else {
            // If the input is valid, remove the INVALID class
            INPUT.classList.remove("INVALID_BORDER");
            PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
            INPUT.classList.add("VALID_BORDER");
            PLACEHOLDER.classList.add("VALID_PLACEHOLDER");
        }
    });

    // Optional: Check the validity on form submit or on blur
    INPUT.addEventListener('blur', function () {
        if (INPUT.value.trim() == '') {
            INPUT.classList.remove("INVALID_BORDER");
            PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
            INPUT.classList.remove("VALID_BORDER");
            PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
        } else if (!INPUT.checkValidity()) {
            INPUT.classList.add("INVALID_BORDER");
            PLACEHOLDER.classList.add("INVALID_PLACEHOLDER");
            INPUT.classList.remove("VALID_BORDER");
            PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
        } else {
            // If the input is valid, remove the INVALID class
            INPUT.classList.remove("INVALID_BORDER");
            PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
            INPUT.classList.add("VALID_BORDER");
            PLACEHOLDER.classList.add("VALID_PLACEHOLDER");
        }
    });
});

fetch("../FocusFlow/RegisterLayout/Account/Account.js")

document.getElementById("profile_name").textContent = User.name;
document.getElementById("profile_email").textContent = User.email;


let form = document.getElementsByClassName("PROFILE__DETAILS")[0];

form.addEventListener('submit', function () {
    fetch("../FocusFlow/RegisterLayout/Account.php")
    document.getElementById("profile_name").textContent = User.name;
    document.getElementById("profile_email").textContent = User.email;
});

document.querySelectorAll(".ACC__BUTTON").forEach(button => {
    button.addEventListener("click", function () {
        document.querySelectorAll(".ACCOUNT__SECTION").forEach(div => div.classList.remove("ACTIVE"));

        const targetId = this.getAttribute("data-target");
        document.getElementById(targetId).classList.add("ACTIVE");
    });
});

document.querySelectorAll(".SETTING__BUTTON").forEach(button => {
    button.addEventListener("click", function () {
        setTimeout(() => {
            var theme = document.querySelector("html").getAttribute("data-theme");
            const radio = document.querySelector(`button[data-theme="default2"]`).querySelector('.ACCOUNT__RADIO');
            const radio1 = document.querySelector(`button[data-theme="theme_earth2"]`).querySelector('.ACCOUNT__RADIO');
            const radio2 = document.querySelector(`button[data-theme="theme_neon2"]`).querySelector('.ACCOUNT__RADIO');
            const radio3 = document.querySelector(`button[data-theme="theme_forest2"]`).querySelector('.ACCOUNT__RADIO');

            radio.innerHTML = "radio_button_unchecked"
            radio1.innerHTML = "radio_button_unchecked"
            radio2.innerHTML = "radio_button_unchecked"
            radio3.innerHTML = "radio_button_unchecked"

            switch (theme) {
                case "theme_earth":
                    radio1.innerHTML = "radio_button_checked"
                    break;
                case "theme_neon":
                    radio2.innerHTML = "radio_button_checked"
                    break;
                case "theme_forest":
                    radio3.innerHTML = "radio_button_checked"
                    break;
                default:
                    radio.innerHTML = "radio_button_checked"
            }
        }, 100);
    });
});

document.getElementsByClassName("ACCOUNT__SECTION")[0].classList.add("ACTIVE");

setTimeout(() => {
    var theme = document.querySelector("html").getAttribute("data-theme");
    const radio = document.querySelector(`button[data-theme="default2"]`).querySelector('.ACCOUNT__RADIO');
    const radio1 = document.querySelector(`button[data-theme="theme_earth2"]`).querySelector('.ACCOUNT__RADIO');
    const radio2 = document.querySelector(`button[data-theme="theme_neon2"]`).querySelector('.ACCOUNT__RADIO');
    const radio3 = document.querySelector(`button[data-theme="theme_forest2"]`).querySelector('.ACCOUNT__RADIO');

    radio.innerHTML = "radio_button_unchecked"
    radio1.innerHTML = "radio_button_unchecked"
    radio2.innerHTML = "radio_button_unchecked"
    radio3.innerHTML = "radio_button_unchecked"

    switch (theme) {
        case "theme_earth":
            radio1.innerHTML = "radio_button_checked"
            break;
        case "theme_neon":
            radio2.innerHTML = "radio_button_checked"
            break;
        case "theme_forest":
            radio3.innerHTML = "radio_button_checked"
            break;
        default:
            radio.innerHTML = "radio_button_checked"

    }
}, 100);



