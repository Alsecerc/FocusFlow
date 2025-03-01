function getCookie(name) {
    let cookies = document.cookie.split('; ');
    for (let cookie of cookies) {
        let [key, value] = cookie.split('=');
        if (key === name) {
            // remove the %20 for spaces
            return decodeURIComponent(value);
        }
    }
    return null; // Return null if cookie not found
}



document.addEventListener('DOMContentLoaded', function (event) {
    if (window.location.pathname.includes('Calendar')) {




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
        let StoreWeekDate = [];

        document.getElementById("left").addEventListener("click", toggleViewPrevious);
        document.getElementById("right").addEventListener("click", toggleViewNext);
        document.getElementById("today").addEventListener("click", goToToday);

        window.onload = function () {

            // adjust to start on sunday
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

            let previousMonthDays = new Date(year, MonthOffset, 0).getDate();

            if (DayOffset <= 0) {
                MonthOffset -= 1;
                if (MonthOffset < 0) {
                    MonthOffset = 11;
                    YearOffset -= 1;
                }
                DayOffset = previousMonthDays + DayOffset;
            }




            DAYNUM_LIST.forEach((Item) => {
                let totalDaysInMonth = new Date(year, MonthOffset + 1, 0).getDate();
                // find out date of month
                if (DayOffset > totalDaysInMonth) {
                    // reset the date num with new month
                    MonthOffset += 1;
                    DayOffset = 1;

                    if (MonthOffset > 11) {
                        MonthOffset = 0; // Reset to January
                        YearOffset += 1;
                    }

                } else {
                    Item.innerHTML = `${DayOffset}`
                }

                Item.innerHTML = `${DayOffset}`
                StoreWeekDate.push([DayOffset, MonthOffset + 1, YearOffset]);
                DayOffset += 1;
            });


            HightLightToday();

            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`;
            TITLE_YEAR.innerHTML = `${YearOffset}`;

            RenderTask();
        }

        function toggleViewPrevious() {

            DayOffset -= 14;
            StoreWeekDate = []

            let previousMonthDays = new Date(year, MonthOffset, 0).getDate();
            if (DayOffset <= 0) {
                MonthOffset -= 1;
                if (MonthOffset < 0) {
                    MonthOffset = 11;
                    YearOffset -= 1;
                }
                DayOffset = previousMonthDays + DayOffset;
            }

            DAYNUM_LIST.forEach((Item) => {
                let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();
                if (DayOffset > totalDaysInMonth) {
                    // reset the date num with new month
                    MonthOffset += 1;
                    DayOffset = 1;
                    if (MonthOffset > 11) {
                        MonthOffset = 0; // Reset to January
                        YearOffset += 1;
                    }

                } else {
                    Item.innerHTML = `${DayOffset}`
                }

                Item.innerHTML = `${DayOffset}`
                StoreWeekDate.push([DayOffset, MonthOffset + 1, YearOffset]);
                DayOffset++;
            });


            HightLightToday();
            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`
            TITLE_YEAR.innerHTML = `${YearOffset}`

            RenderTask();
        }

        function toggleViewNext() {

            StoreWeekDate = []

            let previousMonthDays = new Date(year, MonthOffset, 0).getDate();

            if (DayOffset <= 0) {
                MonthOffset -= 1;
                if (MonthOffset < 0) {
                    MonthOffset = 11;
                    YearOffset -= 1;
                }
                DayOffset = previousMonthDays + DayOffset;
            }

            DAYNUM_LIST.forEach((Item) => {
                let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();
                if (DayOffset > totalDaysInMonth) {
                    // Move to next month
                    MonthOffset += 1;
                    if (MonthOffset > 11) {
                        MonthOffset = 0; // Wrap to January
                        YearOffset += 1; // Move to next year
                    }
                    DayOffset = 1; // Reset day count
                }

                StoreWeekDate.push([DayOffset, MonthOffset + 1, YearOffset]);
                Item.innerHTML = `${DayOffset}`;

                DayOffset += 1;
            });

            // console.log(MonthOffset, month + 1)

            HightLightToday();
            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`
            TITLE_YEAR.innerHTML = `${YearOffset}`

            RenderTask();
        }

        function goToToday() {
            // Reset global offsets to today
            DayOffset = day;
            MonthOffset = month;
            YearOffset = year;

            // Adjust DayOffset based on the start of the week (so the first cell is aligned)
            DayOffset -= dayName;

            StoreWeekDate = []

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

                StoreWeekDate.push([DayOffset, MonthOffset + 1, YearOffset]);
                Item.innerHTML = `${DayOffset}`;

                DayOffset++;
            });


            // Highlight today
            HightLightToday();

            // Update calendar title
            TITLE_MONTH.innerHTML = `${monthList[MonthOffset]}`;
            TITLE_YEAR.innerHTML = `${YearOffset}`;


            RenderTask();
        }


        function HightLightToday() {
            let HeaderColor = getQueryAll(".HEADER li");
            let SubHeaderColor = getQueryAll(".DAY_NUM li");
            StoreWeekDate.forEach((Item, Index) => {
                if (Item[0] == day && Item[1] == month + 1) {
                    HeaderColor[Index].classList.add("HEADER-HIGHLIGHT");
                    SubHeaderColor[Index].classList.add("DAY_NUM-HIGHLIGHT");
                } else {
                    HeaderColor[Index].classList.remove("HEADER-HIGHLIGHT");
                    SubHeaderColor[Index].classList.remove("DAY_NUM-HIGHLIGHT");
                }
            });

        }


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
                StoreWeekDate.forEach(ThisDay => {
                    TaskList.forEach(task => {
                        let DateOnly = task['start_date'].split('-');

                        if (parseInt(DateOnly[2]) === ThisDay[0] && parseInt(DateOnly[1]) === ThisDay[1] && parseInt(DateOnly[0]) === ThisDay[2]) { // Only display tasks for the selected date
                            let StartDate = new Date(task['start_date']);
                            let Length = CalcDuration(task['start_time'], task['end_time']);
                            let StartRow = CalcStart(task['start_time']);
                            let StartColumn = StartDate.getDay();

                            let taskElement = document.createElement("div");
                            taskElement.classList.add("EVENT");
                            taskElement.style.gridColumn = `${StartColumn + 1} / ${StartColumn + 2}`;
                            taskElement.style.gridRow = `${StartRow}`;
                            taskElement.style.height = `${Length}px`;
                            taskElement.setAttribute("data-task-id", task['task_id']);

                            // Display task title
                            let taskTitle = document.createElement("span");
                            taskTitle.classList.add("EVENT__NAME");
                            taskTitle.style.textAlign = "center";
                            taskTitle.textContent = task['task_title'];

                            // Create task info div
                            let taskInfo = document.createElement("div");
                            taskInfo.classList.add("EVENT_INFO");

                            let username = getCookie("USERNAME");

                            // First, set the innerHTML (without removing taskTitle)
                            taskInfo.innerHTML = `
                            <h3 class="TASK__TITLE" style='text-align:center;'>${task['task_title']}</h3>
                                <p class="TASK__DESC" ><strong>Description</strong><br> ${task['task_desc']}</p>
                                <p class="TASK__START" ><strong>Start Date:</strong> ${task['start_date']}</p>
                                <p class="TASK__END" ><strong>Time:</strong> ${task['start_time']} - ${task['end_time']}</p>
                                <p class="TASK__CREATED" >
                                <strong>Created At:</strong> ${task['created_at']} <br>
                                <strong>By:</strong> ${username}
                                </p>
                                <p class="TASK__STATUS" ><strong>Status</strong><br> ${task['status']}</p>
                                <p class="TASK__CATEGORY" ><strong>Category</strong><br> ${task['category']}</p>
                                <button class="TASKINFO__BUTTON STATUS CLICKABLE"><span class="material-icons">
                                done
                                </span></button>
                                <button class="TASKINFO__BUTTON DELETE CLICKABLE"><span class="material-icons">
delete
</span></button>
<button class="TASKINFO__BUTTON CLOSE CLICKABLE"><span class="material-icons">
close
</span></button>

                            `;

                            // Append elements
                            taskElement.appendChild(taskInfo);
                            taskElement.appendChild(taskTitle);
                            taskContainer.appendChild(taskElement);
                        }

                    });
                });

                setTimeout(() => {
                    updateEventLayout()
                }, 100);

            }, 0)
        }

        setTimeout(() => {
            let allTasks = Array.from(document.getElementsByClassName("EVENT"));

            // loop for all task
            allTasks.forEach(taskElement => {
                let taskInfo = taskElement.querySelector(".EVENT_INFO");
                let closeButton = taskInfo.querySelector(".CLOSE");
                let taskId = taskElement.getAttribute("data-task-id");
                let ChangeStatus = taskElement.querySelector(".STATUS");
                let DeleteTask = taskElement.querySelector(".DELETE");


                taskElement.addEventListener("click", function (event) {
                    taskElement.querySelectorAll(".EVENT_INFO").forEach(info => {
                        if (info !== taskInfo) {
                            info.classList.remove("EVENTINFO_SHOW");
                        }
                    });

                    taskInfo.classList.toggle("EVENTINFO_SHOW");
                    taskInfo.classList.add("FRONT");
                    // Prevent event from bubbling to document
                    event.stopPropagation();
                });

                // Close button functionality
                closeButton.addEventListener("click", function (event) {
                    taskInfo.classList.remove("EVENTINFO_SHOW");
                    event.stopPropagation(); // Prevent event from triggering the task click event
                });

                ChangeStatus.addEventListener("click", function () {
                    sendData(taskId, "Completed");
                });

                DeleteTask.addEventListener("click", function () {
                    sendData(taskId, "Delete");
                });

            });
        }, 100);

        function sendData(taskId, status) {
            fetch("CalendarDeleteTask.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `task_id=${encodeURIComponent(taskId)}&status=${encodeURIComponent(status)}`
            })
                .then(response => response.json()) // Parse response as JSON
                .then(data => {
                    console.log("Response from PHP:", data);
                    if (data.success) {
                        alert("Task updated successfully!");
                        location.reload(); // Reload page to update task list
                    } else {
                        alert("Failed to delete task: " + data.error);
                    }
                })
                .catch(error => console.error("Error:", error));
        }


        function adjustEventWidth(taskElement, columnSpan) {
            let container = document.querySelector(".EVENT__CONTAINER");
            let columnWidth = container.clientWidth / 7; // 7 columns
            taskElement.style.width = `${columnWidth * columnSpan}px`;

        }

        function updateEventLayout() {
            document.querySelectorAll(".EVENT__NAME").forEach((task) => {
                adjustEventWidth(task, 1);
            });
        }

        setTimeout(() => {
            updateEventLayout()
        }, 100);


        window.addEventListener("resize", function () {
            updateEventLayout();
        });




        // pop up function
        let PopUp = getQuery(".POP_UP");
        let Overlay = getQuery(".OVERLAY");
        let CloseBTN = getQuery(".CONTROLS__CLOSE");

        function OpenPopUp() {
            PopUp.classList.add("ACTIVE");
            PopUp.classList.add("FRONT");
        }

        function ClosePopUp() {
            PopUp.classList.remove("ACTIVE");
            PopUp.classList.remove("FRONT");
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
        let EndDate = null;
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


                // check date validity
                if (inputElement.id == "start_date" && inputValue) {
                    // Assign null if no value is entered
                    StartDate = inputValue || null;
                } else if (inputElement.id == "end_date" && inputValue) {
                    EndDate = inputValue || null;
                }

                if (StartDate && EndDate && StartDate > EndDate) {
                    InvalidInput(getQuery("#start_date"), getQuery("#start_date_ph"));
                    InvalidInput(getQuery("#end_date"), getQuery("#end_date_ph"));
                } else {
                    if (StartDate) {
                        ValidInput(getQuery("#start_date"), getQuery("#start_date_ph"));
                    }
                    if (EndDate) {
                        ValidInput(getQuery("#end_date"), getQuery("#end_date_ph"));
                    }
                }

                // check time validity
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
    }

    // Pop up category select
    fetch("CalendarFetchCat.php") // Fetch categories from PHP
        .then(response => response.json())
        .then(data => {
            let dropdown = document.getElementById("task_group");
            data.forEach(category => {
                let option = document.createElement("option");
                option.value = category;
                option.textContent = category;
                dropdown.appendChild(option);
            });
        });

    let dropdown = document.getElementById("task_group");
    let newCategoryInput = document.getElementById("new_category");
    let addButton = document.getElementById("add_category");

    dropdown.addEventListener("change", function () {
        if (dropdown.value === "add_new") {
            newCategoryInput.style.display = "block";
        } else {
            newCategoryInput.style.display = "none";
        }
    });

    document.getElementById("add_category").addEventListener("click", function () {
        let newCategoryInput = document.getElementById("new_category");
        let dropdown = document.getElementById("task_group");
    
        if (newCategoryInput.style.display === "none") {
            // Show input field to enter new category
            newCategoryInput.style.display = "block";
            newCategoryInput.focus();
        } else {
            let newCategory = newCategoryInput.value.trim();
            if (newCategory) {
                // Add new category as an option in dropdown
                let option = document.createElement("option");
                option.value = newCategory;
                option.textContent = newCategory;
                dropdown.appendChild(option);
                dropdown.value = newCategory; // Set as selected
    
                // Hide input and clear value
                newCategoryInput.style.display = "none";
                newCategoryInput.value = "";
            }
        }
    });

    // validate category input
    let cat_input = document.getElementById("new_category");
    let placeholder = document.getElementsByClassName("INPUT__PLACEHOLDER")[0];
    cat_input.addEventListener('input', function () {
        if (cat_input.value.trim() == '') {
            InvalidInput(cat_input, placeholder);
        } else if (!cat_input.checkValidity()) {
            InvalidInput(cat_input, placeholder);
        } else {
            // If the input is valid, remove the INVALID class
            ValidInput(cat_input, placeholder);
        }
    });

    // allow user to add new category
    document.getElementById("add_category").addEventListener("click", function () {
        let select = document.getElementById("task_group");
        let input = document.getElementById("new_category");
        let button = document.getElementById("add_category");
        let placeholder = document.getElementById("cat_placeholder");

        if (select.style.display === "none") {
            // If input is active, switch back to dropdown
            select.style.display = "block";
            input.style.display = "none";
            input.value = ""; // Clear input field
            button.textContent = "New"; // Reset button text
            select.required = true; // Make dropdown required
            input.required = false; // Remove required from input
        } else {
            // If dropdown is active, switch to input field
            select.style.display = "none";
            input.style.display = "block";
            input.focus();
            button.textContent = "Add"; // Change button text to indicate toggle
            input.required = true; // Make input required
            select.required = false; // Remove required from dropdown
        }
    });

    // when submitting forms
    document.getElementById("submitButton").addEventListener("click", function () {
        let taskTitle = document.getElementById("task_title").value.trim();
        let taskDesc = document.getElementById("task_desc").value.trim();
        let taskGroup = document.getElementById("task_group").value.trim();
        let startDate = document.getElementById("start_date").value;
        let startTime = document.getElementById("start_time").value;
        let endTime = document.getElementById("end_time").value;

        if (taskTitle && taskDesc && taskGroup && startDate && startTime && endTime) {
            let formData = new FormData();
            formData.append("task_title", taskTitle);
            formData.append("task_desc", taskDesc);
            formData.append("task_group", taskGroup);
            formData.append("start_date", startDate);
            formData.append("start_time", startTime);
            formData.append("end_time", endTime);

            fetch("add_task.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(result => {
                    if (result === "success") {
                        alert("Task added successfully!");
                        location.reload();
                    } else {
                        alert("Error adding task.");
                    }
                });
        } else {
            alert("Please fill in all fields.");
        }
    });
});