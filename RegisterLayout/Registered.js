

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

function createNewGroup (NameOfContainer, ClassName, headerTag, headerClassName, Content, paragraphTag, paragraphClassName, paragraphContent){
    let container = document.querySelector(`.${NameOfContainer}`);
    const newGroupCard = document.createElement('div');
    newGroupCard.className = ClassName;

    const header = document.createElement(headerTag);
    header.className = headerClassName;
    header.textContent = Content;

    const paragraph = document.createElement(paragraphTag)
    paragraph.className = paragraphClassName;
    paragraph.textContent = paragraphContent;

    const draggables = document.createElement('draggable');
    draggables.className = headerClassName;
    draggables.draggable = 'true';

    newGroupCard.appendChild(header);
    newGroupCard.appendChild(paragraph);
    newGroupCard.appendChild(draggables);

    container.appendChild(newGroupCard);
}

function GetGroupName (){
    const groupNameInput = document.getElementById("groupName");
    return groupNameInput.value;
}

// Get all buttons with the class "TODO__ADD"
const buttons = document.querySelectorAll(".TODO__ADD");

// Convert NodeList to an array for easier handling (optional)
const buttonArray = Array.from(buttons);

// Find the button that has "Group" as its text content
const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

// Find the button that has "Task" as its text content
const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));


// createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', 'To Do', 'p', 'TODO__TASK', 'Get grocery');
// console.log(groupButton)

if (groupButton) {
    groupButton.addEventListener('click', () => {
        let classname = 'TODO__GROUP__ADD';
        let boxes = document.getElementsByClassName(classname);
        // Check if any elements were found
        if (boxes.length > 0) {
            // Access the first element in the collection
            const box = boxes[0];
            const overlay = document.querySelector('.Hiddenlayer');
            const groupFrom = document.getElementById("groupForm");
            // Toggle the display property
            if (box.style.display === 'none') {
                box.style.display = 'block';
                overlay.style.display = 'block';
                groupFrom.addEventListener("submit", function(event) {
                    event.preventDefault(); // Prevent the default form submission behavior
                    
                    // Call the function to retrieve the input value
                    const groupName = GetGroupName();
                    console.log("Group Name:", groupName);
                    box.style.display = 'none';
                    if (groupFrom){
                        createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName, 'p', 'TODO__TASK', 'Get grocery');
                        overlay.style.display = 'none';
                    }
                });
                console.log("Box is now visible");
                
            } else {
                box.style.display = 'none';
                overlay.style.display = 'none';
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
