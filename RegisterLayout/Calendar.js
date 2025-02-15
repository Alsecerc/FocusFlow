document.addEventListener('DOMContentLoaded', function () {
    if (window.location.pathname.toLowerCase().includes('Calender')) {
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

            HightLightToday();

            CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]} ${YearOffset}`
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

                Item.innerHTML = `${DayOffset}`;
                DayOffset++;
            });

            HightLightToday();

            CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]} ${YearOffset}`
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

            CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]} ${YearOffset}`
        }

        function goToToday() {
            DAYNUM_LIST.forEach((Item) => {
                if (Item.innerHTML == day) {
                    return;
                }
            });

            let DayOffset = day;
            let MonthOffset = month;
            let YearOffset = year;

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

            CALENDAR__TITLE1.innerHTML = `${monthList[MonthOffset]} ${YearOffset}`
        }

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

        INPUTS.forEach((element) => {
            let INPUT = element.querySelector(".INPUT__INPUT");
            let PLACEHOLDER = element.querySelector(".INPUT__PLACEHOLDER");

            let INPUTID = INPUT.id;

            if (INPUTID == "start_date") {
                let StartDate = INPUT.value;
            }
            else if (INPUTID == "due_date") {
                let DueDate = INPUT.value;
            }



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



                if (StartDate && DueDate) {
                    console.log(StartDate, DueDate)
                    if (StartDate > DueDate) {
                        alert("Due date must be later than start date");
                        InvalidInput(INPUT, PLACEHOLDER);
                    } else {
                        ValidInput(INPUT, PLACEHOLDER);
                    }
                }
            });

            // Optional: Check the validity on form submit or on blur
            INPUT.addEventListener('blur', function () {
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