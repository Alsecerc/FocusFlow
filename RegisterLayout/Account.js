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

fetch("Account.php") // Fetch JSON data from PHP file

document.getElementById("profile_name").textContent = User.name;
document.getElementById("profile_email").textContent = User.email;
const maskedPassword = "*".repeat(User.password.length); // Convert to ******
document.getElementById("profile_password").textContent = maskedPassword;


let form = document.getElementsByClassName("PROFILE__DETAILS")[0];

form.addEventListener('submit', function () {
    fetch("Account.php") // Fetch JSON data from PHP file

    document.getElementById("profile_name").textContent = User.name;
    document.getElementById("profile_email").textContent = User.email;
    const maskedPassword = "*".repeat(User.password.length); // Convert to ******
    document.getElementById("profile_password").textContent = maskedPassword;

});





