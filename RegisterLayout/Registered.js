

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
let SIDEBAR = getClass("SIDEBAR")[0];
let MENU_BUTTON = getClass("HEADER__MENU_BUTTON")[0];
let MENU_ICON = getClass("HEADER__MENU_ICON")[0];

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

// let NOTI__BUTTON = document.querySelector("#notiButton");
// if (NOTI__BUTTON) {
//     NOTI__BUTTON.addEventListener("click", function () {
//         let popup = document.getElementById("notificationPopup");

//         if (popup.style.display === "none" || popup.style.display === "") {
//             popup.style.display = "block";
//         } else {
//             popup.style.display = "none";
//         }
//     });
// }

let = openNoti = false

document.getElementById("notiButton").addEventListener("click", function () {
    let notificationPopup = document.getElementById("notificationPopup");

    // Toggle visibility
    if (notificationPopup.style.display === "none" || notificationPopup.style.display === "") {
        notificationPopup.style.display = "block";

        // if there is unread message 
        if (document.querySelector(".NOTI__ITEM.UNREAD")) {
            // trigger to mark message read when close
            openNoti = true;
        }

    } else {
        notificationPopup.style.display = "none";

        if (openNoti) {
            markNotificationsAsRead();
            openNoti = false;
        }
    }
});

function markNotificationsAsRead() {
    fetch("1Notification.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
    })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                console.log("Notifications marked as read");
            } else {
                console.error("Failed to update notifications");
            }
        })
        .catch(error => console.error("Error:", error));
}

// create new team
let ToggleNewComms = document.querySelector(".NAV__TITLE__ADD");

if (ToggleNewComms) {
    ToggleNewComms.addEventListener('click', function() {
        let NEW__TEAM__SURVEY = document.querySelector(".NEW__TEAM__SURVEY");
        NEW__TEAM__SURVEY.classList.toggle("NEW__TEAM__SURVEY_SHOW");
    });
}

let INPUTSB = getQueryAll('.INPUT__BOX__SIDEBAR');

INPUTSB.forEach((element) => {
    let INPUT = element.querySelector(".INPUT__INPUT__SB");
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
    });
});



let resetButton = document.querySelector(".TEAM__RESET");
if (resetButton) {
    resetButton.addEventListener('click', function () {
        let INPUTSB = getQueryAll('.INPUT__BOX__SIDEBAR');

        INPUTSB.forEach((element) => {
            let INPUT = element.querySelector(".INPUT__INPUT__SB");
            let PLACEHOLDER = element.querySelector(".INPUT__PLACEHOLDER");
            PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
            INPUT.classList.remove("INVALID_BORDER");
            PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
            INPUT.classList.remove("VALID_BORDER");
        });
    })
}




function ValidInput(INPUT, PLACEHOLDER) {
    INPUT.classList.remove("INVALID_BORDER");
    INPUT.classList.add("VALID_BORDER");
    if (PLACEHOLDER != "") {
        PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
        PLACEHOLDER.classList.add("VALID_PLACEHOLDER");
    }
}

function InvalidInput(INPUT, PLACEHOLDER) {
    INPUT.classList.add("INVALID_BORDER");
    INPUT.classList.remove("VALID_BORDER");
    if (PLACEHOLDER != "") {
        PLACEHOLDER.classList.add("INVALID_PLACEHOLDER");
        PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
    }
}


// TODO: Goals noti

// Function to check reminders
function sendGoalReminder() {
    fetch("GoalReminder.php")
        .then(response => response.text())
        .then(data => console.log("Goal Reminder Triggered:", data))
        .catch(error => console.error("Error:", error));
}

// Run checkReminders every minute
setInterval(sendGoalReminder, 300000);

sendGoalReminder();






// TODO: Todo

function createNewGroup(NameOfContainer, ClassName, headerTag, headerClassName, Content, paragraphTag, paragraphClassName, paragraphContent) {
    console.log("Creating group");
    let container = document.querySelector(`.${NameOfContainer}`);
    if (!container) {
        console.error(`Container with ${NameOfContainer} not found`);
        return;
    }
    const newGroupCard = document.createElement('div');
    newGroupCard.className = ClassName;
    newGroupCard.draggable = 'true';

    const header = document.createElement(headerTag);
    header.className = headerClassName;
    header.textContent = Content;

    const paragraph = document.createElement(paragraphTag)
    paragraph.className = paragraphClassName;
    paragraph.textContent = paragraphContent.length > 500 ? paragraphContent.substring(0, 500) + "..." : paragraphContent;

    newGroupCard.appendChild(header);
    newGroupCard.appendChild(paragraph);

    container.appendChild(newGroupCard);
    console.log("Created group");
}

function CreateNewTask(NameOfContainer, ClassName, paragraphTag, paragraphClassName, paragraphContent) {

}

function CreateTaskForm() {

    console.log("Creating Task Form......");

    if (document.getElementById('taskForm')) {
        console.log("Form already exists");
        return;
    }
    const main = document.createElement('div');
    main.className = 'TODO__TASK__ADD';
    main.style.display = 'none';

    const header = document.createElement('h2');
    header.textContent = "Choose Your group:";

    const form = document.createElement('form');
    form.id = 'taskForm';

    const label = document.createElement('label');
    label.setAttribute('for', 'Group');

    const selection = document.createElement('select');
    selection.id = 'GROUP__NAME__TASK';

    const observer = new MutationObserver(UpdateSelection);

    function UpdateSelection() {
        observer.disconnect();
        selection.innerHTML = '';
        document.querySelectorAll('.TODO__CARD_HEADER').forEach(header => {
            const text = header.textContent.trim();
            if (text) {
                const option = document.createElement('option');
                option.value = text;
                option.textContent = text;
                selection.appendChild(option);
            }
        })
        observer.observe(document.body, { childList: true, subtree: true }); // Resume observing
    }

    UpdateSelection();
    const userinput = document.createElement('input');
    userinput.id = 'taskContent';
    userinput.type = 'text';
    userinput.placeholder = 'Enter the task';
    userinput.required = 'true';
    userinput.id = 'taskContent';

    const submission_Button = document.createElement('button');
    submission_Button.type = 'submit';
    submission_Button.textContent = 'Add task';

    const cancelButton = document.createElement('button');
    cancelButton.textContent = "Cancel";
    cancelButton.type = 'button';
    cancelButton.addEventListener('click', () => {
        console.log("deleteting form");
        form.remove();
    })

    form.appendChild(label);
    form.appendChild(selection);
    form.appendChild(userinput);
    form.appendChild(submission_Button);

    main.appendChild(header);
    main.appendChild(form);

    document.body.appendChild(main);
    console.log("created Task Form");
    observer.observe(document.body, { childList: true, subtree: true });

    console.log("MutationObserver started: Watching for changes...");
}

function GetGroupName() {
    const groupNameInput = document.getElementById("groupName");
    return groupNameInput.value;
}

document.addEventListener('DOMContentLoaded', function () { // only active the code when it is on the specific file
    if (window.location.pathname.includes('todo')) {
        console.log("Todo page activated");


        // Get all buttons with the class "TODO__ADD"
        const buttons = document.querySelectorAll(".TODO__ADD");

        // Convert NodeList to an array for easier handling (optional)
        const buttonArray = Array.from(buttons);

        // Find the button that has "Group" as its text content
        const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

        // Find the button that has "Task" as its text content
        const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));

        // createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', 'To Do', 'p', 'TODO__TASK', 'Get grocery');

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
                        groupFrom.addEventListener("submit", function (event) {
                            event.preventDefault(); // Prevent the default form submission behavior

                            // Call the function to retrieve the input value
                            const groupName = GetGroupName();
                            console.log("Group Name:", groupName);
                            box.style.display = 'none';
                            if (groupFrom) {
                                createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName, 'p', 'TODO__TASK', 'Get grocery');
                                overlay.style.display = 'none';
                            }
                        }, { once: true });
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

        CreateTaskForm();
        if (taskButton) {
            taskButton.addEventListener('click', () => {
                const AddTask = document.querySelector('.TODO__TASK__ADD');
                const overlay = document.querySelector('.Hiddenlayer');
                const taskForm = document.getElementById('taskForm');
                const groupChoice = document.getElementById('GROUP__NAME__TASK');
                const taskContent = document.getElementById('taskContent');
                if (AddTask) {
                    if (AddTask.style.display === 'none') {
                        AddTask.style.display = 'block'; // make the form visisble
                        overlay.style.display = 'block';
                        taskForm.addEventListener('submit', function (event) {
                            event.preventDefault();
                            if (taskForm) {
                                console.log(groupChoice.value);
                                console.log(taskContent.value);
                                AddTask.style.display = 'none';
                                overlay.style.display = 'none';
                            }
                        });
                    } else {
                        AddTask.style.display = 'none';
                        overlay.style.display = 'none';
                    }
                } else {
                    console.log("not enough length")
                }
            });
        }
    }
})




document.addEventListener("DOMContentLoaded", function (event) {
    if (window.location.pathname.toLowerCase().includes('timer')) {
        console.log("Running js code on Timer page...");
        let pomodoro = document.getElementById("pomodoro-timer");
        let short = document.getElementById("short-timer");
        let long = document.getElementById("long-timer");

        // let timers = document.querySelectorAll(".timer-display");
        let session = document.getElementById("pomodoro-session");
        let shortBreak = document.getElementById("short-break");
        let longBreak = document.getElementById("long-break");

        let startBtn = document.getElementById("start");
        let stopBtn = document.getElementById("stop");

        let timerMsg = document.getElementById("timer-message");
        let button = document.querySelector(".button");

        let addButton = document.getElementById('plus-btn');
        let minusButton = document.getElementById('minus-btn');

        let currentTimer = null;
        let myInterval = null;
        let SetTimer = null;
        let isFirstUpdate = true;
        let intervalId;

        function ShowDefaultTimer() {
            const pomodoro_minutes = 10;
            const pomodoro_seconds = 0;
            pomodoro.textContent = `${String(pomodoro_minutes).padStart(2, '0')}:${String(pomodoro_seconds).padStart(2, '0')}`
            pomodoro.style.display = 'block';

            const short_break_minutes = 5;
            const short_break_seconds = 0;

            short.innerHTML = `${String(short_break_minutes).padStart(2, '0')}:${String(short_break_seconds).padStart(2, '0')}`;
            short.style.display = 'none';

            const long_break_minutes = 10;
            const long_break_seconds = 0;

            long.innerHTML = `${String(long_break_minutes).padStart(2, '0')}:${String(long_break_seconds).padStart(2, '0')}`;
            long.style.display = 'none';
        }

        function addTime(ID__TIMER) {
            addButton.addEventListener('click', () => {
                let Time = ID__TIMER.textContent;
                let [minutes, seconds] = Time.split(':').map(Number);
                if (minutes >= 60) {
                    console.log('Minutes cannot be more than 60');
                } else {
                    minutes++;
                } ID__TIMER.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            })
        }

        function minusTime(ID__TIMER) {
            minusButton.addEventListener('click', () => {
                console.log('start minus');
                let currentTime = ID__TIMER.textContent;
                let [minutes, seconds] = currentTime.split(':').map(Number);

                if (minutes <= 0) {
                    console.log('Time cannot be negative');
                } else {
                    minutes--;
                }
                ID__TIMER.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                console.log('minus complete');
            })
        }

        /**
         * Returns the current type of time.
         * 
         * @returns {Time_type} The current type of time displaying.
         * 
         */
        function CurrentTimer() {
            let TIMERS = document.querySelectorAll('.timer-display .time span');
            let CURRENT__TIMER = null;
            console.log('looking for current timer');
            if (TIMERS.length === 0) {
                console.log("No timers found.");
                return; // Exit the function if no timers are found
            }

            let timer = Array.from(TIMERS).find(timer => timer.style.display = 'block');

            console.log(`The return id is ${timer.id}`);
            return timer.id;
        }

        function timerTypeSelection() {

        }

        /**
         * Start button 
         * 
         * When user press start button it will start counting the time
         * 
         */
        function TimeUpdate() {

            if (isFirstUpdate) {
                console.log('Ignore first update');
                isFirstUpdate = false;
                return;
            }
            const TypeTimer = CurrentTimer();
            const timer = document.getElementById(TypeTimer);

            let currentTime = timer.textContent;
            let [minutes, seconds] = currentTime.split(':').map(Number);

            console.log('Started count');
            if (seconds > 0) {
                seconds--;
            } else if (seconds == 0) {
                if (minutes > 0) {
                    minutes--;
                    seconds = 59;
                } else {
                    console.log('Time up');
                    clearInterval(intervalId);
                }
            }
            timer.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function StartButton() {
            startBtn.addEventListener('click', () => {
                if (intervalId) {
                    clearInterval(intervalId);
                }
                console.log('Pressed Start button');
                intervalId = setInterval(TimeUpdate, 1000);
            })
        }

        function StopButton() {
            stopBtn.addEventListener('click', () => {
                console.log("Stopped");
                clearInterval(intervalId);
            })
        }

        function test() {
            let timers = document.querySelectorAll('.timer-display .time span');

            let observer = new MutationObserver((mutationList) => {
                mutationList.forEach(mutation => {
                    if (mutation.type === 'childList') {
                        console.log(`Timer updated: ${mutation.target.id} → ${mutation.target.textContent}`);
                    }
                })
            })
            // Ensure that timers is not empty
            if (timers.length === 0) {
                console.log("No timers found.");
                return; // Exit the function if no timers are found
            }
            let config = { childList: true };
            timers.forEach((timer) => { // Loop through each element in the NodeList
                // let style = window.getComputedStyle(timer); // Get the computed style for each element
                if (timer.style.display === 'block') {
                    console.log(`${timer.id}, ${timer.textContent} is displaying`); // Log the id of the timer that is visible
                } else {
                    console.log(`${timer.id} is not displaying`);
                }
                // console.log(`${timer.id}, ${timer.textContent}, ${timer.style.display} is displaying`);
                observer.observe(timer, config);
            });
        }
        const timer_id = CurrentTimer();
        const CURRENT__TYPE__TIMER = document.getElementById(`${timer_id}`);
        ShowDefaultTimer();
        //testing
        // console.log(CURRENT__TYPE__TIMER);
        addTime(CURRENT__TYPE__TIMER);
        minusTime(CURRENT__TYPE__TIMER);
        test();
        TimeUpdate();
        StartButton();
        StopButton();
        //testing
    }
});


// Settings page
function changeTheme(theme) {
    if (theme === 'default') {
        document.documentElement.removeAttribute('data-theme');
        localStorage.removeItem('theme'); // Remove stored theme
    } else {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme); // Save theme in localStorage

    }
}

// Apply the saved theme on page load
window.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    }
});


// search
function searchFunction() {
    let query = document.getElementById("searchInput").value;
    let resultDiv = document.getElementById("searchResults");

    if (query.length < 2) {
        resultDiv.style.display = "none";
        return;
    }

    fetch(`1Search.php?query=${query}`)
        .then(response => response.json())
        .then(data => {
            resultDiv.innerHTML = "";
            if (data.length === 0) {
                resultDiv.innerHTML = "<p class='SEARCH__NO_RESULT'>No results found</p>";
            } else {
                let resultList = "<ul>";
                data.forEach(item => {
                    if (item.type === "task") {
                        resultList += `<li onclick="redirectToTask(${item.id})"><span class="material-icons">task_alt</span> Task :  ${item.name}</li>`;
                    } else if (item.type === "page") {
                        resultList += `<li onclick="redirectToPage('${item.link}')"><span class="material-icons">tab</span> Page : ${item.name}</li>`;
                    } else if (item.type === "member") {
                        resultList += `<li onclick="redirectToDM('${item.id}','${item.name}')"><span class="material-icons">chat</span> User : ${item.name}</li>`;
                    } else if (item.type === "group_task") {
                        let assignmentText = item.assignment_type === "assigned_to_me"
                            ? `(Task assigned to you in ${item.name})`
                            : `(You assigned this task in ${item.name})`;

                        resultList += `<li onclick="redirectToTeam('${item.id}','${item.name}')">
                                        <span class="material-icons">groups</span>
                                        <div class='RESULT__GROUP_TASK'>
                                            <div>Task: ${item.taskName}</div>
                                            <small>${assignmentText}</small>
                                        </div>
                                      </li>`;
                    }
                    else if (item.type === "goal") {
                        resultList += `<li onclick="redirectToGoal()"><span class="material-icons">track_changes</span> Goal : ${item.name}</li>`;
                    }
                });
                resultList += "</ul>";
                resultDiv.innerHTML = resultList;
            }
            resultDiv.style.display = "block";
        });
}

function redirectToTask(taskId) {
    window.location.href = `Todo.php?task_id=${taskId}`;
}

function redirectToPage(pageLink) {
    window.location.href = pageLink;
}

function redirectToDM(memberId, memberName) {
    window.location.href = `CommunityDMPage.php?receiver_id=${memberId}&name=${memberName}`;
}

function redirectToGoal() {
    window.location.href = `Goal.php`;
}

function redirectToTeam(teamID, teamName) {
    window.location.href = `CommunityPage.php?team_id=${teamID}&team=${teamName}`;
}





