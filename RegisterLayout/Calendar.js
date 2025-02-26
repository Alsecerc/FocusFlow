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
            let totalDaysInMonth = new Date(year, MonthOffset + 1, 0).getDate();

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

            DAYNUM_LIST.forEach((Item) => {
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
                let totalDaysInMonth = new Date(YearOffset, MonthOffset, 0).getDate();
                if (DayOffset > totalDaysInMonth) {
                    // reset the date num with new month
                    MonthOffset--;
                    DayOffset = 1;
                    if (MonthOffset == -1) {
                        MonthOffset = 12; // Reset to January
                        YearOffset--;
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
            let totalDaysInMonth = new Date(YearOffset, MonthOffset + 1, 0).getDate();
            StoreWeekDate = []

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
                        let DateOnly = task['start_date'].split('-')[2];
                        if (DateOnly == ThisDay[0]) { // Only display tasks for the selected date
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

            }, 1000)
        }






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