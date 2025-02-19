function getID(element) {
    return document.getElementById(element);
}

function getClass(element) {
    return document.getElementsByClassName(element);
}

function getQueryAll(element) {
    return document.querySelectorAll(element);
}


// TODO: Generic
// ===================FOR HERO LIST========================
let NAV_CONTAINER = document.querySelector(".HEADER__LIST");

NAV_CONTAINER.addEventListener("click", function (event) {
    let TITLE_ID = event.target.getAttribute("id");

    if (TITLE_ID) {
        let DROPDOWN = document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN`);

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

// document.querySelectorAll(".HEADER__DROPDOWN_MENU").forEach((element) => {
//     element.addEventListener("mouseenter", function () {
//         let TITLE_ID = element.id;
//         if (TITLE_ID) {
//             let DROPDOWN = document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN`);

//             // detect if hovered element is NOT already clicked
//             if (!DROPDOWN.classList.contains("HEADER__DROPDOWN_SHOW")) {
//                 DROPDOWN.classList.add("HEADER__DROPDOWN_HOVER");
//                 DROPDOWN.classList.add("FRONT");
//                 element.querySelector(".ARROW").innerHTML = "arrow_drop_up";
//             }
//         }
//     });

//     element.addEventListener("mouseleave", function () {
//         let TITLE_ID = element.id;
//         if (TITLE_ID) {
//             let DROPDOWN = document.querySelector(`#${TITLE_ID}.HEADER__DROPDOWN`);

//             if (!DROPDOWN.classList.contains("HEADER__DROPDOWN_SHOW")) {
//                 DROPDOWN.classList.remove("HEADER__DROPDOWN_HOVER");
//                 DROPDOWN.classList.remove("HEADER__DROPDOWN_SHOW");
//                 DROPDOWN.classList.remove("FRONT");
//                 element.querySelector(".ARROW").innerHTML = "arrow_drop_down";
//             }
//         };
//     });
// });

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
let SIGNUP_BUTTON = document.querySelector(".HEADER__SIGNUP");
SIGNUP_BUTTON.addEventListener('mouseenter', function () {
    SIGNUP_BUTTON.classList.remove("SIGNUP__ANIMATE");
    SIGNUP_BUTTON.classList.add("CLICKABLE");
});
SIGNUP_BUTTON.addEventListener('mouseleave', function () {
    SIGNUP_BUTTON.classList.add("SIGNUP__ANIMATE");
    SIGNUP_BUTTON.classList.remove("CLICKABLE");
});

// ============SCROLL TO TOP===============
const SCROLL_TOP = getClass("BACK_TO_TOP")[0];

window.addEventListener('scroll', function () {
    if (window.scrollY > 200) {
        SCROLL_TOP.classList.add("BACK_TO_TOP__SHOW");
    } else {
        SCROLL_TOP.classList.remove("BACK_TO_TOP__SHOW");
    }
});









// TODO: Homepage.php
// ====================REVIEW PROFILE=====================
let IMG_CONTENT = getQueryAll(".REVIEW__CARD__PROFILE")[0];
let COMMENT_CONTENT = getQueryAll(".REVIEW__COMMENT")[0];

const BUTTON_LEFT = getQueryAll(".REVIEW__BUTTON_LEFT")[0];
const BUTTON_RIGHT = getQueryAll(".REVIEW__BUTTON_RIGHT")[0];

const PROFILE_LIST = ["img/REVIEW_PERSON1.jpg", "img/REVIEW_PERSON2.jpg", "img/REVIEW_PERSON3.jpg"];
const COMMENT = ["This platform has completely transformed how I manage my tasks. Super intuitive and efficient!", "A solid productivity tool with great collaboration features. Worth trying out!", "Really helpful for managing my daily tasks. Just wish there were more theme options!"];
const STAR = [5, 4, 5];

let INDEX = 0;

let CONTAINER = getQueryAll(".REVIEW_STAR")[0];

function UpdateCard() {
    if (IMG_CONTENT && COMMENT_CONTENT) {
        for (i = STAR[INDEX]; i > 0; i--) {
            let STAR1 = document.createElement("span");
            STAR1.classList.add("material-symbols-outlined", "REVIEW__STAR");
            STAR1.innerHTML = "star";
            CONTAINER.appendChild(STAR1);
        }

        IMG_CONTENT.src = PROFILE_LIST[INDEX];
        COMMENT_CONTENT.innerHTML = COMMENT[INDEX];
    }
}

if (BUTTON_LEFT) {
    BUTTON_LEFT.addEventListener('click', function () {
        CONTAINER.innerHTML = "";
        // IF INDEX is at last location, it will become 0 (when == list length)
        INDEX = (INDEX + 1) % PROFILE_LIST.length;
        UpdateCard();
    });
}

if (BUTTON_RIGHT) {
    BUTTON_RIGHT.addEventListener('click', function () {
        CONTAINER.innerHTML = "";
        if (INDEX <= 0) {
            INDEX = PROFILE_LIST.length - 1;
        } else {
            INDEX = (INDEX - 1) % PROFILE_LIST.length;
        }
        UpdateCard();
    });
}

UpdateCard();

// ==================SURVEY=================
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







// TODO: plans.php

const PLAN_BENEFIT = [
    [1, 1, 1, 0, 0, 0, 1, 0],
    [1, 1, 1, 0, 1, 0, 1, 1],
    [1, 1, 1, 1, 1, 1, 1, 1]
];
let PLAN_NUM = 0;

// convert node list into array
let PLAN = Array.from(getQueryAll(".PLANS__LIST"));

PLAN.forEach((LIST) => {
    let PLAN1_LIST = Array.from(LIST.children);
    let ICON_NUM = 0;
    PLAN1_LIST.forEach((ITEM) => {

        let ICON = document.createElement("span");
        ICON.classList.add("material-symbols-outlined", "BOLD");
        if (PLAN_BENEFIT[PLAN_NUM][ICON_NUM] == 0) {
            ICON.innerHTML = "close";
            ICON.classList.add("RED");
        } else {
            ICON.innerHTML = "check";
            ICON.classList.add("GREEN");
        }
        ITEM.appendChild(ICON);
        ICON_NUM += 1;
    });
    PLAN_NUM += 1
});


let ContainerArray = Array.from(getQueryAll(".PLANS__LIST"));
let ItemArray = Array.from(getQueryAll(".SIDEBAR__LIST .SIDEBAR__ITEM"));

// HOVER to enlarge row
// only works on when hover sidebar item
document.querySelectorAll(".SIDEBAR__LIST .SIDEBAR__ITEM").forEach((ITEM) => {
    let ItemIndex = ItemArray.indexOf(ITEM);

    ITEM.addEventListener("mouseenter", function () {
        ContainerArray.forEach((CONTAINER) => {
            let Item = Array.from(CONTAINER.children)[ItemIndex];
            ITEM.classList.add("ENLARGE");
            Item.classList.add("ENLARGE2");

            console.log(Item.classList);
        });
    });

    ITEM.addEventListener("mouseleave", function () {
        ContainerArray.forEach((CONTAINER) => {
            let Item = Array.from(CONTAINER.children)[ItemIndex];
            ITEM.classList.remove("ENLARGE");
            Item.classList.remove("ENLARGE2");

            console.log(Item.classList);
        });
    });
});

const PLAN__BUTTON = getQueryAll(".TYPE")[0];
const PLAN_SWITCH = getQueryAll(".TYPE__MONTH")[0];
let PricingArray = Array.from(getQueryAll("#PLANS__PRICE"));
let Price = [["RM0/year", "RM558/year", "RM1200/year"], ["RM0/month", "RM49/month", "RM100/month"]];

if (PLAN__BUTTON) {
    PLAN__BUTTON.addEventListener('click', function () {
        PLAN_SWITCH.classList.toggle("TYPE__ANNUAL");

        if (PLAN_SWITCH.classList.contains("TYPE__ANNUAL")) {
            let index = 0;
            PLAN_SWITCH.innerHTML = "Y";
            PricingArray.forEach((text) => {
                text.innerHTML = Price[0][index];
                index++;
            })

        } else {
            let index = 0;
            PLAN_SWITCH.innerHTML = "M";
            PricingArray.forEach((text) => {
                text.innerHTML = Price[1][index];
                index++;
            })

        }
    });
}


// TODO: Page: Get help page
// PAGE: Customer Service
document.addEventListener("DOMContentLoaded", function () {
    const dropdownContainers = document.querySelectorAll(".dropdown-container");

    dropdownContainers.forEach((container) => {
        const button = container.querySelector(".dropdown-button");

        if (button) {
            button.addEventListener("click", function (event) {
                event.stopPropagation(); // Prevent the click from closing immediately
                container.classList.toggle("open");

                // Close other dropdowns when one is opened
                dropdownContainers.forEach((otherContainer) => {
                    if (otherContainer !== container) {
                        otherContainer.classList.remove("open");
                    }
                });
            });
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener("click", function (event) {
        dropdownContainers.forEach((container) => {
            if (!container.contains(event.target)) {
                container.classList.remove("open");
            }
        });
    });
});







