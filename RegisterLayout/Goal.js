function togglePopup() {
    let popup = document.querySelector(".GOAL__INPUT");
    let button = document.querySelector(".GOAL__SET");

    // Check if the popup is currently displayed
    if (popup.style.display === "block") {
        button.innerHTML = "Show";
        popup.style.display = "none"; // Hide the popup
    } else {
        popup.style.display = "block"; // Show the popup
        button.innerHTML = "Hide";
    }
}


// set min date for starting and ending date
document.addEventListener("DOMContentLoaded", function () {
    let today = new Date().toISOString().split("T")[0];
    document.getElementById("start_time").setAttribute("min", today);
    document.getElementById("end_time").setAttribute("min", today);
    document.getElementById("end_time").setAttribute("min", today);
});



let INPUTS = getQueryAll('.INPUT__BOX');
let StartTime = null;
let EndTime = null;

INPUTS.forEach((element) => {
    let INPUT = element.querySelector(".INPUT__INPUT");
    let PLACEHOLDER = element.querySelector(".INPUT__PLACEHOLDER");

    INPUT.addEventListener('input', function () {
        // If the input is invalid, add the INVALID class
        if (INPUT.value.trim() == '') {
            InvalidInput(INPUT, PLACEHOLDER);
        } else if (!INPUT.checkValidity()) {
            InvalidInput(INPUT, PLACEHOLDER);
        } else {
            // If the input is valid, remove the INVALID class
            ValidInput(INPUT, PLACEHOLDER);
        }

        let inputElement = element.querySelector(".INPUT__INPUT");
        let inputValue = inputElement.value;


        if (inputElement.id == "start_time" && inputValue) {
            // Assign null if no value is entered
            StartTime = inputValue || null;
        } else if (inputElement.id == "end_time" && inputValue) {
            EndTime = inputValue || null;
        }

        if ((StartTime >= EndTime) && StartTime && EndTime) {
            InvalidInput(getQuery("#end_time"), getQuery("#end_time_ph"));
            InvalidInput(getQuery("#start_time"), getQuery("#start_time_ph"));
        } else {
            if (StartTime) {
                ValidInput(getQuery("#start_time"), getQuery("#start_time_ph"));
            }
            if (EndTime) {
                ValidInput(getQuery("#end_time"), getQuery("#end_time_ph"));
            }
        }

    });
});


function ValidInput(INPUT, PLACEHOLDER) {
    INPUT.classList.remove("INVALID_BORDER");
    PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
    INPUT.classList.add("VALID_BORDER");
    PLACEHOLDER.classList.add("VALID_PLACEHOLDER");
}

function InvalidInput(INPUT, PLACEHOLDER) {
    INPUT.classList.add("INVALID_BORDER");
    PLACEHOLDER.classList.add("INVALID_PLACEHOLDER");
    INPUT.classList.remove("VALID_BORDER");
    PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
}
