function getID(element) {
    return document.getElementById(element);
}

function getClass(element) {
    return document.getElementsByClassName(element);
}

function getQueryAll(element) {
    return document.querySelectorAll(element);
}


// ===================FOR HERO LIST========================
var NAV_CONTAINER = document.querySelector(".HEADER__LIST");

NAV_CONTAINER.addEventListener("click", function (event) {
    var TITLE_ID = event.target.getAttribute("id");

    if (TITLE_ID) {
        var DROPDOWN = document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN`);

        if (DROPDOWN.classList.contains("HEADER__DROPDOWN_SHOW")) {
            DROPDOWN.classList.remove("HEADER__DROPDOWN_SHOW");
            DROPDOWN.classList.remove("HEADER__DROPDOWN_HOVER");
            document.querySelector(`#${DROPDOWN.id}.HEADER__DROPDOWN_MENU`)
                .querySelector(".ARROW").innerHTML = "arrow_drop_down";
        } else {
            document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN_MENU`).querySelector(".ARROW").innerHTML = "arrow_drop_up";
            DROPDOWN.classList.add("HEADER__DROPDOWN_SHOW");
        }

        document.querySelectorAll(".HEADER__DROPDOWN").forEach((dropdown) => {
            if (dropdown.id !== TITLE_ID) {
                dropdown.classList.remove("HEADER__DROPDOWN_SHOW");
                dropdown.classList.remove("HEADER__DROPDOWN_HOVER");
                document.querySelector(`#${dropdown.id}.HEADER__DROPDOWN_MENU`)
                    .querySelector(".ARROW").innerHTML = "arrow_drop_down";
            }
        });
    }

});

document.querySelectorAll(".HEADER__DROPDOWN_MENU").forEach((element) => {
    element.addEventListener("mouseenter", function () {
        var TITLE_ID = element.id;
        if (TITLE_ID) {
            var DROPDOWN = document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN`);

            // detect if hovered element is NOT already clicked
            if (!DROPDOWN.classList.contains("HEADER__DROPDOWN_SHOW")) {
                DROPDOWN.classList.add("HEADER__DROPDOWN_HOVER");
                DROPDOWN.classList.add("FRONT");
                element.querySelector(".ARROW").innerHTML = "arrow_drop_up";
            }
        }
    });

    element.addEventListener("mouseleave", function () {
        var TITLE_ID = element.id;
        if (TITLE_ID) {
            var DROPDOWN = document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN`);

            if (!DROPDOWN.classList.contains("HEADER__DROPDOWN_SHOW")) {
                DROPDOWN.classList.remove("HEADER__DROPDOWN_HOVER");
                DROPDOWN.classList.remove("HEADER__DROPDOWN_SHOW");
                DROPDOWN.classList.remove("FRONT");
                element.querySelector(".ARROW").innerHTML = "arrow_drop_down";
            }
        };
    });
});

document.addEventListener('click', function (event) {
    if (!event.target.closest(".HEADER__LIST")) {
        document.querySelectorAll(".HEADER__DROPDOWN").forEach((box) => {
            box.classList.remove("HEADER__DROPDOWN_SHOW");
            box.classList.remove("HEADER__DROPDOWN_HOVER");
            document.querySelector(`#${box.id}.HEADER__DROPDOWN_MENU`).querySelector(".ARROW").innerHTML = "arrow_drop_down";
        });
    }
});





// ====================FOR SIGNUP=======================
var SIGNUP_BUTTON = document.querySelector(".HEADER__SIGNUP");
SIGNUP_BUTTON.addEventListener('mouseenter', function () {
    SIGNUP_BUTTON.classList.remove("SIGNUP__ANIMATE");
    SIGNUP_BUTTON.classList.add("CLICKABLE");
});
SIGNUP_BUTTON.addEventListener('mouseleave', function () {
    SIGNUP_BUTTON.classList.add("SIGNUP__ANIMATE");
    SIGNUP_BUTTON.classList.remove("CLICKABLE");
});




// ====================REVIEW PROFILE=====================


var IMG_CONTENT = getQueryAll(".REVIEW__CARD__PROFILE")[0];
var COMMENT_CONTENT = getQueryAll(".REVIEW__COMMENT")[0];

const BUTTON_LEFT = getQueryAll(".REVIEW__BUTTON_LEFT")[0];
const BUTTON_RIGHT = getQueryAll(".REVIEW__BUTTON_RIGHT")[0];

const PROFILE_LIST = ["img/REVIEW_PERSON1.jpg", "img/REVIEW_PERSON2.jpg", "img/REVIEW_PERSON3.jpg"];
const COMMENT = ["This platform has completely transformed how I manage my tasks. Super intuitive and efficient!", "A solid productivity tool with great collaboration features. Worth trying out!", "Really helpful for managing my daily tasks. Just wish there were more theme options!"];
const STAR = [5, 4, 5];

var INDEX = 0;

var CONTAINER = getQueryAll(".REVIEW_STAR")[0];

function UpdateCard() {

    for (i = STAR[INDEX]; i > 0; i--) {
        let STAR1 = document.createElement("span");
        STAR1.classList.add("material-symbols-outlined", "REVIEW__STAR");
        STAR1.innerHTML = "star";
        CONTAINER.appendChild(STAR1);
    }

    IMG_CONTENT.src = PROFILE_LIST[INDEX];
    COMMENT_CONTENT.innerHTML = COMMENT[INDEX];
}


BUTTON_LEFT.addEventListener('click', function () {
    CONTAINER.innerHTML = "";
    // IF INDEX is at last location, it will become 0 (when == list length)
    INDEX = (INDEX + 1) % PROFILE_LIST.length;
    UpdateCard();
});
BUTTON_RIGHT.addEventListener('click', function () {
    CONTAINER.innerHTML = "";
    if (INDEX <= 0) {
        INDEX = PROFILE_LIST.length - 1;
    } else {
        INDEX = (INDEX - 1) % PROFILE_LIST.length;
    }
    UpdateCard();
});

UpdateCard();


// ==================SURVEY=================

var INPUTS = getQueryAll('.INPUT__BOX');

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



// ============SCROLL TO TOP===============
const SCROLL_TOP = getClass("BACK_TO_TOP")[0];

window.addEventListener('scroll',function() {
    if (window.scrollY > 200) {
        SCROLL_TOP.classList.add("BACK_TO_TOP__SHOW");
    } else {
        SCROLL_TOP.classList.remove("BACK_TO_TOP__SHOW");
    }
});






