

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

let NOTI__BUTTON = getQuery("#notiButton");
NOTI__BUTTON.addEventListener("click", function() {
    let popup = document.getElementById("notificationPopup");
    
    // Toggle display property
    if (popup.style.display === "none" || popup.style.display === "") {
        popup.style.display = "block";
    } else {
        popup.style.display = "none";
    }

});




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

// TODO: Calendar Page
// Calendar
document.addEventListener('DOMContentLoaded', function (event) {
    if (window.location.pathname.includes('Calendar')) {
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

        let TITLE_MONTH = getQuery("#calendar__title1 #MONTH");
        let TITLE_YEAR = getQuery("#calendar__title1 #YEAR");
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

            HightLightToday();

            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`
            TITLE_YEAR.innerHTML = `${YearOffset}`
        }


        document.getElementById("left").addEventListener("click", toggleViewPrevious)
        function toggleViewPrevious() {
            DayOffset -= 14;

            // if date number is less than or 0 
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

                Item.innerHTML = `${DayOffset}`;
                DayOffset++;
            });

            HightLightToday();

            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`
            TITLE_YEAR.innerHTML = `${YearOffset}`

            RenderTask();
        }


        document.getElementById("right").addEventListener("click", toggleViewNext);
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
                if (DayOffset > totalDaysInMonth) {
                    // Move to next month
                    MonthOffset += 1;
                    if (MonthOffset > 11) {
                        MonthOffset = 0; // Wrap to January
                        YearOffset += 1; // Move to next year
                    }
                    DayOffset = 1; // Reset day count
                }

                Item.innerHTML = `${DayOffset}`

                DayOffset += 1;
            });

            HightLightToday();

            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`
            TITLE_YEAR.innerHTML = `${YearOffset}`

            RenderTask();
        }

        document.getElementById("today").addEventListener("click", goToToday)
        function goToToday() {
            // Reset global offsets to today
            DayOffset = day;
            MonthOffset = month;
            YearOffset = year;

            // Adjust DayOffset based on the start of the week (so the first cell is aligned)
            DayOffset -= dayName;

            if (DayOffset <= 0) {
                // If DayOffset is negative, move to the previous month
                MonthOffset -= 1;
                if (MonthOffset < 0) {
                    MonthOffset = 11; // Wrap to December
                    YearOffset -= 1;
                }
                let prevMonthDays = new Date(YearOffset, MonthOffset + 1, 0).getDate();
                DayOffset += prevMonthDays;
            }

            // Update the calendar days
            DAYNUM_LIST.forEach((Item) => {
                let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();

                if (DayOffset > totalDaysInMonth) {
                    // Move to next month
                    MonthOffset += 1;
                    if (MonthOffset > 11) {
                        MonthOffset = 0;
                        YearOffset += 1;
                    }
                    DayOffset = 1;
                }


                Item.innerHTML = `${DayOffset}`;
                DayOffset++;
            });

            // Highlight today
            HightLightToday();

            // Update calendar title
            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`
            TITLE_YEAR.innerHTML = `${YearOffset}`;

            RenderTask();
        }
        // document.getElementById("today").addEventListener("click", goToToday);
        // function goToToday() {
        //     let now = new Date();
        //     DayOffset = now.getDate();
        //     MonthOffset = now.getMonth();
        //     YearOffset = now.getFullYear();
        //     adjustCalendar();
        // }

        function HightLightToday() {
            let HeaderColor = getQueryAll(".HEADER li");
            let SubHeaderColor = getQueryAll(".DAY_NUM li");
            SubHeaderColor.forEach((Item, Index) => {
                if (Item.innerHTML == day && MonthOffset == month) {
                    HeaderColor[Index].classList.add("HEADER-HIGHLIGHT");
                    SubHeaderColor[Index].classList.add("DAY_NUM-HIGHLIGHT");
                } else {
                    HeaderColor[Index].classList.remove("HEADER-HIGHLIGHT");
                    SubHeaderColor[Index].classList.remove("DAY_NUM-HIGHLIGHT");
                }
            });
        }



        // add card into calendar
        console.log(TaskList)

        // let taskList = [TaskObject];

        function CalcDuration(startTime, endTime) {
            let [sHour, sMin, sSec] = startTime.split(":");
            let [eHour, eMin, eSec] = endTime.split(":");
            // 15 is to offset
            let timeDifferencePX = ((((eHour - sHour) * 60) + (eMin - sMin)))
            return timeDifferencePX;
        }

        function CalcStart(startTime) {
            let [sHour, sMin, sSec] = startTime.split(":");
            let RoundedRow = Math.round(((sHour * 60) + parseInt(sMin)) / 15);
            return RoundedRow + 1;
        }

        function RenderTask() {
            let taskContainer = document.querySelector(".EVENT__CONTAINER");
            taskContainer.innerHTML = "";


            // time out to allow the document to be fully loaded
            setTimeout(() => {

                const monthIndex = new Date(`${TITLE_MONTH.innerHTML} 1, ${TITLE_YEAR.innerHTML}`).getMonth();
                let FirstDayofWeek = new Date(TITLE_YEAR.innerHTML, monthIndex, DAYNUM_LIST[0].innerHTML);

                let CurrentWeek = [];
                for (let i = 0; i < 7; i++) {
                    let tempDay = new Date(FirstDayofWeek);
                    tempDay.setDate(FirstDayofWeek.getDate() + i);

                    let paddedDate = String(tempDay.getDate() + 1).padStart(2, '0');
                    let paddedMonth = String(tempDay.getMonth() + 1).padStart(2, '0');
                    CurrentWeek.push(`${tempDay.getFullYear()}-${paddedMonth}-${paddedDate}`);
                }

                CurrentWeek.forEach(ThisDay => {
                    TaskList.forEach(task => {
                        if (task['start_date'] === ThisDay) { // Only display tasks for the selected date
                            let StartDate = new Date(task['start_date']);
                            let Length = CalcDuration(task['start_time'], task['end_time']);
                            let StartRow = CalcStart(task['start_time']);
                            let StartColumn = StartDate.getDay();

                            let taskElement = document.createElement("div");
                            taskElement.classList.add("EVENT");
                            taskElement.style.gridColumn = `${StartColumn + 1} / ${StartColumn + 2}`;
                            taskElement.style.gridRow = `${StartRow}`;
                            taskElement.style.height = `${Length}px`;
                            taskElement.innerHTML = `<span class="EVENT__NAME" style="text-align: center;">${task['task_title']}</span>`;

                            taskContainer.appendChild(taskElement);
                        }
                    });
                });

            }, 100)
        }

        RenderTask();




        // pop up function
        let PopUp = getQuery(".POP_UP");
        let Overlay = getQuery(".OVERLAY");
        let CloseBTN = getQuery(".CONTROLS__CLOSE");

        function OpenPopUp() {
            PopUp.classList.add("ACTIVE");
        }

        function ClosePopUp() {
            PopUp.classList.remove("ACTIVE");
            ResetInput;
        }

        function CreatePopUp() {
            Overlay.addEventListener('click', ClosePopUp);
            CloseBTN.addEventListener('click', ClosePopUp);

            return OpenPopUp;
        }

        document.getElementById("resetButton").addEventListener("click", ResetInput);
        function ResetInput() {
            let INPUTS = getQueryAll('.INPUT__BOX');

            INPUTS.forEach((element) => {
                let INPUT = element.querySelector(".INPUT__INPUT");
                let PLACEHOLDER = element.querySelector(".INPUT__PLACEHOLDER");
                INPUT.classList.remove("INVALID_BORDER");
                PLACEHOLDER.classList.remove("INVALID_PLACEHOLDER");
                INPUT.classList.remove("VALID_BORDER");
                PLACEHOLDER.classList.remove("VALID_PLACEHOLDER");
            });
        }


        document.querySelector(".OPEN_POP_UP").addEventListener("click", CreatePopUp());

        // Pop up survey validation
        let INPUTS = getQueryAll('.INPUT__BOX');
        // initiate for checking if duedate if after
        let StartDate = null;
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

        let form = document.getElementById("popUpForm");

        form.addEventListener("submit", function (event) {
            console.log("Form submission triggered");

            let startTime = document.getElementById("start_time").value;
            let endTime = document.getElementById("end_time").value;


            if ((startTime >= endTime) && startTime && endTime) {
                console.log("Validation failed: End Time must be later than Start Time.");
                alert("End Time must be later than Start Time.");
                event.preventDefault();
                return;
            }

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
    }
});

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




