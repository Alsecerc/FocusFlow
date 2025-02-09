

function getID(element) {
    return document.getElementById(element);
}

function getClass(element) {
    return document.getElementsByClassName(element);
}


// TODO: HOMEPAGE
var SIDEBAR = getClass("SIDEBAR")[0];
var MENU_BUTTON = getClass("HEADER__MENU_BUTTON")[0];
var MENU_ICON = getClass("HEADER__MENU_ICON")[0];

MENU_BUTTON.addEventListener('click', function () {
    if (MENU_ICON.classList.contains("ACTIVE")) {
        MENU_ICON.classList.remove("ACTIVE");
        MENU_ICON.classList.toggle("NOT_ACTIVE");
    } else {
        MENU_ICON.classList.toggle("ACTIVE");
        MENU_ICON.classList.remove("NOT_ACTIVE");
    }
    SIDEBAR.classList.toggle("SIDEBAR_SHOW");
});


// TODO: Todo

// Get all buttons with the class "TODO__ADD"
const buttons = document.querySelectorAll(".TODO__ADD");

// Convert NodeList to an array for easier handling (optional)
const buttonArray = Array.from(buttons);

// Find the button that has "Group" as its text content
const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

// Find the button that has "Task" as its text content
const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));

// console.log(groupButton)
if (groupButton) {
    groupButton.addEventListener('click', () => {
        let classname = "TODO__GROUP__ADD"
        let boxes = document.getElementsByClassName(classname);
        // Check if any elements were found
        if (boxes.length > 0) {
            // Access the first element in the collection
            let box = boxes[0];

            // Toggle the display property
            if (box.style.display === 'none') {
                box.style.display = 'block';
                console.log("Box is now visible");
            } else {
                box.style.display = 'none';
                console.log("Box is now hidden");
            }
        } else {
            console.log("No elements found with the class 'TODO__GROUP__ADD'");
        }
    })
}

if (taskButton) {
    taskButton.addEventListener('click', () => {
        alert("User pressed task button");
    });
}



// TODO: Calendar Page
// Calendar
// Toggle function as webpage load
window.onload = function () {
    togglePeriod('week');
};

function togglePeriod(Options) {
    // Button Effect
    let ButtonList = Array.from(getClass("CALENDAR__HEADER__BUTTON"));
    ButtonList.forEach((buts) => {
        // remove select effect from all button
        buts.classList.remove("SELECTED_BUTTON");
    });

    let Button = getID(Options + "Button");
    Button.classList.add("SELECTED_BUTTON");


    // Content Display
    let ContentList = Array.from(getClass("CALENDAR__CONTENT__ITEM"));
    ContentList.forEach((conts) => {
        conts.classList.remove("CALENDAR__CONTENT_SHOW");
    });

    // Show the selected content div
    let Content = getID(Options + "Content");
    Content.classList.add("CALENDAR__CONTENT_SHOW");


    // Change title
    let Title = getClass("CALENDAR__TITLE")[0];
    Title.innerHTML = Options.toUpperCase();
};





