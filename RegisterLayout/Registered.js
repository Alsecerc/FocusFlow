

function getID(element) {
    return document.getElementById(element);
}

function getClass(element) {
    return document.getElementsByClassName(element);
}

function getQuery(element) {
    return document.querySelector(element);
}

function getQueryAll(element) {
    return document.querySelectorAll(element);
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
// window.onload = function () {
//     togglePeriod('week');
// };

// function togglePeriod(Options) {
//     // Button Effect
//     let ButtonList = Array.from(getClass("CALENDAR__HEADER__BUTTON"));
//     ButtonList.forEach((buts) => {
//         // remove select effect from all button
//         buts.classList.remove("SELECTED_BUTTON");
//     });

//     let Button = getID(Options + "Button");
//     Button.classList.add("SELECTED_BUTTON");


//     // Content Display
//     let ContentList = Array.from(getClass("CALENDAR__CONTENT__ITEM"));
//     ContentList.forEach((conts) => {
//         conts.classList.remove("CALENDAR__CONTENT_SHOW");
//     });

//     // Show the selected content div
//     let Content = getID(Options + "Content");
//     Content.classList.add("CALENDAR__CONTENT_SHOW");


//     // Change title
//     let Title = getClass("CALENDAR__TITLE")[0];
//     Title.innerHTML = Options.toUpperCase();
// };




// get date time
const TimeNow = new Date();
const monthList = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];
const daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
const year = TimeNow.getFullYear();
const month = TimeNow.getMonth();    // 1-12 (add 1 because months are 0-based)
const day = TimeNow.getDate();
const dayName = TimeNow.getDay();

let CALENDAR__TITLE1 = getQuery("#calendar__title1");
let DAYNUM_LIST = Array.from(getQueryAll('.DAY_NUM li'));
// as page load the function will be run
let DayOffset = day;
let MonthOffset = month;
let YearOffset = year;

window.onload = function () {
    switch (dayName) {
        case 0:
            DayOffset
            break;
        case 1:
            DayOffset -= 1
            break;
        case 2:
            DayOffset -= 2
            break;
        case 3:
            DayOffset -= 3
            break;
        case 4:
            DayOffset -= 4
            break;
        case 5:
            DayOffset -= 5
            break;
        case 6:
            DayOffset -= 6
            break;
    }
    DAYNUM_LIST.forEach((Item) => {
        // find out date of month
        let totalDaysInMonth = new Date(year, MonthOffset + 1, 0).getDate();
        if (DayOffset < totalDaysInMonth) {
            Item.innerHTML = `${DayOffset}`
            DayOffset += 1;
        } else {
            // reset the date num with new month
            MonthOffset += 1;
            DayOffset = 1;
            Item.innerHTML = `${DayOffset}`
        }
    });

    CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]}`
}




function toggleViewPrevious() {
    DayOffset -= 14;

    while (DayOffset <= 0) {
        // go back 1 month
        MonthOffset -= 1
        if (MonthOffset < 0) {
            // go back previous year December
            MonthOffset = 11;
            YearOffset -= 1;
        }
        let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();
        DayOffset += totalDaysInMonth;
    }


    DAYNUM_LIST.forEach((Item) => {
        Item.innerHTML = `${DayOffset}`
        DayOffset += 1;
    });

    CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]}`
}

function toggleViewNext() {
    let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();
    while (DayOffset >= totalDaysInMonth) {
        MonthOffset += 1
        if (MonthOffset > 11) {
            // go back previous year December
            MonthOffset = 1;
            YearOffset += 1;
        }
        let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();
        DayOffset = Math.abs(totalDaysInMonth - DayOffset - 1);
    }

    DAYNUM_LIST.forEach((Item) => {
        Item.innerHTML = `${DayOffset}`
        DayOffset += 1;
    });

    CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]}`
}







