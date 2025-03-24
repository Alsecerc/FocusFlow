let SIDEBAR = document.getElementsByClassName("SIDEBAR")[0];
let MENU_BUTTON = document.getElementsByClassName("HEADER__MENU_BUTTON")[0];
let MENU_ICON = document.getElementsByClassName("HEADER__MENU_ICON")[0];

MENU_BUTTON.addEventListener('click', function () {
    if (MENU_ICON.classList.contains("ACTIVE")) {
        MENU_ICON.classList.remove("ACTIVE");
        MENU_ICON.classList.toggle("NOT_ACTIVE");
    } else {
        MENU_ICON.classList.toggle("ACTIVE");
        MENU_ICON.classList.remove("NOT_ACTIVE");
    }
    SIDEBAR.classList.toggle("show");
});