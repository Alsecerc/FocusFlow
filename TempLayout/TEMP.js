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



// ============SCROLL TO TOP===============
const SCROLL_TOP = getClass("BACK_TO_TOP")[0];

window.addEventListener('scroll',function() {
    if (window.scrollY > 200) {
        SCROLL_TOP.classList.add("BACK_TO_TOP__SHOW");
    } else {
        SCROLL_TOP.classList.remove("BACK_TO_TOP__SHOW");
    }
});






