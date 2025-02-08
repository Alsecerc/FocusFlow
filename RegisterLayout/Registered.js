function getID(element) {
    return document.getElementById(element);
}

function getClass(element) {
    return document.getElementsByClassName(element);
}


// HOMEPAGE
var SIDEBAR = getClass("SIDEBAR")[0];
var MENU_BUTTON = getClass("HEADER__MENU_BUTTON")[0];
var MENU_ICON = getClass("HEADER__MENU_ICON")[0];

MENU_BUTTON.addEventListener('click', function() {
    if (MENU_ICON.classList.contains("ACTIVE")) {
        MENU_ICON.classList.remove("ACTIVE");
        MENU_ICON.classList.toggle("NOT_ACTIVE");
    } else {
        MENU_ICON.classList.toggle("ACTIVE");
        MENU_ICON.classList.remove("NOT_ACTIVE");
    }
    SIDEBAR.classList.toggle("SIDEBAR_SHOW");
});

// Todo

// Get all buttons with the class "TODO__ADD"
const buttons = document.querySelectorAll(".TODO__ADD");

// Convert NodeList to an array for easier handling (optional)
const buttonArray = Array.from(buttons);

// Find the button that has "Group" as its text content
const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

// Find the button that has "Task" as its text content
const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));

groupButton.addEventListener('click', () => {
    alert("User pressed group button");
})

taskButton.addEventListener('click', () => {
    alert("User pressed group button");
})