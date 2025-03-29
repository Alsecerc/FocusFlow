import RemindLibrary from "../RemindLibrary.js";

document.addEventListener("DOMContentLoaded", function(event) {
    if (window.location.pathname.toLowerCase().includes('timer')) {
        console.log("Running JS code on Timer page...");

        let pomodoro = document.getElementById("pomodoro-timer");
        let short = document.getElementById("short-timer");
        let long = document.getElementById("long-timer");

        let session = document.getElementById("pomodoro-session");
        let shortBreak = document.getElementById("short-break");
        let longBreak = document.getElementById("long-break");
        
        let startBtn = document.getElementById("start");
        let stopBtn = document.getElementById("stop");
        let addButton = document.getElementById("plus-btn");
        let minusButton = document.getElementById("minus-btn");
        let addsecondButton = document.getElementById("plus-btn-second");
        let minussecondButton = document.getElementById("minus-btn-second");

        let intervalMinusButton = document.getElementById("interval-minus");
        let intervalPlusButton = document.getElementById("interval-plus");
        let intervalDisplay = document.getElementById("long-break-interval-display");

        let intervalId = null;
        let isFirstUpdate = true;
        let POMODORO__TIMES = 0;
        let LONG__BREAK__TIMES = 4;

        let pomodoro_minutes = 15;
        let pomodoro_seconds = 0;
        let short_break_minutes = 5;
        let short_break_seconds = 0;
        let long_break_minutes = 10;
        let long_break_seconds = 0;

        function ShowDefaultTimer() {
            pomodoro.textContent = formatTime(pomodoro_minutes, pomodoro_seconds);
            short.textContent = formatTime(short_break_minutes, short_break_seconds);
            long.textContent = formatTime(long_break_minutes, long_break_seconds);
            pomodoro.style.display = 'block';
            short.style.display = 'none';
            long.style.display = 'none';
        }

        function formatTime(minutes, seconds) {
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function CurrentTimer() {
            const timers = document.querySelectorAll('.timer-display .time span');
            return Array.from(timers).find(timer => timer.style.display === 'block')?.id || null;
        }

        function DisplayTimer(timer, minutes, seconds) {
            timer.textContent = formatTime(minutes, seconds);
        }

        function display_none_other(activeTimer) {
            const timers = document.querySelectorAll('.timer-display .time span');
            timers.forEach(timer => {
                timer.style.display = timer.id === activeTimer.id ? 'block' : 'none';
            });
            startBtn.disabled = false;
            stopBtn.disabled = true;
        }

        function restartInterval() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(TimeUpdate, 1000);
            stopBtn.disabled = false;
        }

        function TimeUpdate() {
            if (isFirstUpdate) {
                isFirstUpdate = false;
                return;
            }

            const activeTimerId = CurrentTimer();
            if (!activeTimerId) return;

            const timer = document.getElementById(activeTimerId);
            let [minutes, seconds] = timer.textContent.split(':').map(Number);

            if (seconds > 0) {
                seconds--;
            } else if (minutes > 0) {
                minutes--;
                seconds = 59;
            } else {
                handleTimerEnd(timer);
                return;
            }

            DisplayTimer(timer, minutes, seconds);
        }

        function handleTimerEnd(timer) {
            if (timer.id === pomodoro.id) {
                POMODORO__TIMES++;
                console.log(`Pomodoro session completed. Count: ${POMODORO__TIMES}, Long break threshold: ${LONG__BREAK__TIMES}`);
                
                // Check if we've reached the number of pomodoros needed for a long break
                if (POMODORO__TIMES >= LONG__BREAK__TIMES) {
                    console.log("Taking long break now");
                    RemindLibrary.showSuccessToast("Pomodoro session completed. Time for a long break!");
                    // Reset pomodoro count after reaching the long break threshold
                    POMODORO__TIMES = 0;
                    // Update UI for long break
                    longBreak.style.backgroundColor = "rgb(0, 128, 0)";
                    session.style.backgroundColor = "#2F3E46";
                    shortBreak.style.backgroundColor = "#2F3E46";
                    display_none_other(long);
                } else {
                    console.log("Taking short break now");
                    RemindLibrary.showSuccessToast("Pomodoro session completed. Time for a short break!");
                    // Update UI for short break
                    shortBreak.style.backgroundColor = "rgb(0, 128, 0)";
                    session.style.backgroundColor = "#2F3E46";
                    longBreak.style.backgroundColor = "#2F3E46";
                    display_none_other(short);
                }
                
                // Reset and restart the timer
                clearInterval(intervalId);
                isFirstUpdate = true; // Reset this flag to avoid initial timer jump
                intervalId = null;
                intervalId = setInterval(TimeUpdate, 1000);
                startBtn.disabled = true;
                stopBtn.disabled = false;
                return;
            } else {
                // Reset pomodoro counter when a long break ends
                if (timer.id === long.id) {
                    POMODORO__TIMES = 0;
                    console.log("Long break ended. Resetting pomodoro count to 0");
                }
                
                // Update UI for pomodoro
                session.style.backgroundColor = "rgb(0, 128, 0)";
                shortBreak.style.backgroundColor = "#2F3E46";
                longBreak.style.backgroundColor = "#2F3E46";
                
                resetTime();
                display_none_other(pomodoro);
                clearInterval(intervalId);
                isFirstUpdate = true; // Reset this flag to avoid initial timer jump
                intervalId = null;
                intervalId = setInterval(TimeUpdate, 1000);
                startBtn.disabled = true;
                stopBtn.disabled = false;
            }
        }

        function StartButton() {
            startBtn.addEventListener("click", () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                console.log(`Starting timer: ${TIMER__ID.id}`);

                if (intervalId) clearInterval(intervalId);
                intervalId = setInterval(TimeUpdate, 1000);
                startBtn.disabled = true;
                stopBtn.disabled = false;
            });
        }

        function StopButton() {
            stopBtn.addEventListener("click", () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                console.log(`Stopping timer: ${TIMER__ID.id}`);

                if (intervalId) {
                    clearInterval(intervalId);
                    intervalId = null;
                }
                startBtn.disabled = false;
                stopBtn.disabled = true;
            });
        }

        function addTime(ButtonType, timeType) {
            let holdInterval;
            const delay = 150; // milliseconds between repeated actions when button is held
            
            // Function to execute for adding time
            const addTimeFunction = () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                let [minutes, seconds] = TIMER__ID.textContent.split(":").map(Number);
                
                if (timeType === "min") {
                    if(minutes < 60){
                        minutes++;
                        if (minutes === 60) {
                            minutes = 59;
                            seconds = 59;
                        }
                    } else {
                        console.log("Minutes cannot be more than 60");
                        return; // Don't update if at max
                    }
                } else if (timeType === "sec") {
                    if(minutes === 60){
                        console.log("cannot add seconds when minutes is 60");
                        return; // Don't update if at max
                    }else{
                        if(seconds < 59){
                            seconds++;
                        } else {
                            minutes++;
                            seconds = 0;
                            if (minutes === 60) {
                                minutes = 59;
                                seconds = 59;
                            }
                        }
                    }
                }
                
                updateGlobalTime(TIMER__ID, minutes, seconds);
            };
            
            // Mouse down - start repeating
            ButtonType.addEventListener("mousedown", () => {
                addTimeFunction(); // Execute once immediately
                holdInterval = setInterval(addTimeFunction, delay); // Then repeat
            });
            
            // Clear the interval when mouse up or mouse leaves button
            ButtonType.addEventListener("mouseup", () => {
                clearInterval(holdInterval);
            });
            
            ButtonType.addEventListener("mouseleave", () => {
                clearInterval(holdInterval);
            });
            
            // Keep the click event for mobile devices
            ButtonType.addEventListener("click", (e) => {
                // Click is already handled by the mousedown event on desktop
                // This is mainly for mobile support
                e.stopPropagation(); // Prevent duplicate execution
            });
        }

        function minusTime(ButtonType, timeType) {
            let holdInterval;
            const delay = 150; // milliseconds between repeated actions when button is held
            
            // Function to execute for removing time
            const minusTimeFunction = () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                let [minutes, seconds] = TIMER__ID.textContent.split(":").map(Number);
                
                if (timeType === "min") {
                    if(minutes > 0){
                        minutes--;
                    }
                    else {
                        console.log("Time cannot be negative");
                        return; // Don't update if already at minimum
                    }
                } else if (timeType === "sec") {
                    if(seconds > 0){
                        seconds--;
                    }else if (seconds === 0 && minutes === 0) {
                        console.log("Time cannot be negative");
                        return; // Don't update if already at minimum
                    }
                    else {
                        minutes--;
                        seconds = 59;
                    }
                }
                
                updateGlobalTime(TIMER__ID, minutes, seconds);
            };
            
            // Mouse down - start repeating
            ButtonType.addEventListener("mousedown", () => {
                minusTimeFunction(); // Execute once immediately
                holdInterval = setInterval(minusTimeFunction, delay); // Then repeat
            });
            
            // Clear the interval when mouse up or mouse leaves button
            ButtonType.addEventListener("mouseup", () => {
                clearInterval(holdInterval);
            });
            
            ButtonType.addEventListener("mouseleave", () => {
                clearInterval(holdInterval);
            });
            
            // Keep the click event for mobile devices
            ButtonType.addEventListener("click", (e) => {
                // Click is already handled by the mousedown event on desktop
                // This is mainly for mobile support
                e.stopPropagation(); // Prevent duplicate execution
            });
        }

        function updateGlobalTime(TIMER__ID, minutes, seconds) {
            if (TIMER__ID.id === pomodoro.id) {
                pomodoro_minutes = minutes;
                pomodoro_seconds = seconds;
            } else if (TIMER__ID.id === short.id) {
                short_break_minutes = minutes;
                short_break_seconds = seconds;
            } else if (TIMER__ID.id === long.id) {
                long_break_minutes = minutes;
                long_break_seconds = seconds;
            }

            TIMER__ID.textContent = formatTime(minutes, seconds);
            console.log(`Updated ${TIMER__ID.id} to ${minutes}:${seconds}`);
        }

        function resetTime() {
            pomodoro.textContent = formatTime(pomodoro_minutes, pomodoro_seconds);
            short.textContent = formatTime(short_break_minutes, short_break_seconds);
            long.textContent = formatTime(long_break_minutes, long_break_seconds);
        }

        function timerTypeSelection() {
            document.querySelector(".button-container").addEventListener("click", (event) => {
                if (event.target.id === session.id) {
                    session.style.backgroundColor = "rgb(0, 128, 0)"; // green colour
                    shortBreak.style.backgroundColor = "#2F3E46";
                    longBreak.style.backgroundColor = "#2F3E46";
                    display_none_other(pomodoro);
                    console.log("Switched to Pomodoro timer");
                } else if (event.target.id === shortBreak.id) {
                    shortBreak.style.backgroundColor = "rgb(0, 128, 0)"; // green colour";";
                    session.style.backgroundColor = "#2F3E46";
                    longBreak.style.backgroundColor = "#2F3E46";
                    display_none_other(short);
                    console.log("Switched to Short Break timer");
                } else if (event.target.id === longBreak.id) {
                    longBreak.style.backgroundColor = "rgb(0, 128, 0)"; // green colour";";
                    session.style.backgroundColor = "#2F3E46";
                    shortBreak.style.backgroundColor = "#2F3E46";
                    display_none_other(long);
                    console.log("Switched to Long Break timer");
                }
                clearInterval(intervalId);
                startBtn.disabled = false;
                stopBtn.disabled = true;
            });
        }

        function setLongBreakInterval() {
            let holdInterval;
            const delay = 300; // slightly longer delay for interval adjustment
            
            // Function to increase interval
            const increaseIntervalFunction = () => {
                if (LONG__BREAK__TIMES < 10) {
                    LONG__BREAK__TIMES++;
                    intervalDisplay.textContent = LONG__BREAK__TIMES;
                    console.log(`Long break interval set to: ${LONG__BREAK__TIMES}`);
                }
            };
            
            // Function to decrease interval
            const decreaseIntervalFunction = () => {
                if (LONG__BREAK__TIMES > 1) {
                    LONG__BREAK__TIMES--;
                    intervalDisplay.textContent = LONG__BREAK__TIMES;
                    console.log(`Long break interval set to: ${LONG__BREAK__TIMES}`);
                }
            };
            
            // Mouse down for increasing - start repeating
            intervalPlusButton.addEventListener("mousedown", () => {
                increaseIntervalFunction(); // Execute once immediately
                holdInterval = setInterval(increaseIntervalFunction, delay); // Then repeat
            });
            
            // Mouse down for decreasing - start repeating
            intervalMinusButton.addEventListener("mousedown", () => {
                decreaseIntervalFunction(); // Execute once immediately
                holdInterval = setInterval(decreaseIntervalFunction, delay); // Then repeat
            });
            
            // Clear the interval when mouse up or mouse leaves button
            intervalPlusButton.addEventListener("mouseup", () => {
                clearInterval(holdInterval);
            });
            
            intervalPlusButton.addEventListener("mouseleave", () => {
                clearInterval(holdInterval);
            });
            
            intervalMinusButton.addEventListener("mouseup", () => {
                clearInterval(holdInterval);
            });
            
            intervalMinusButton.addEventListener("mouseleave", () => {
                clearInterval(holdInterval);
            });
            
            // Keep the click event for mobile devices
            intervalPlusButton.addEventListener("click", (e) => {
                e.stopPropagation(); // Prevent duplicate execution
            });
            
            intervalMinusButton.addEventListener("click", (e) => {
                e.stopPropagation(); // Prevent duplicate execution
            });
        }

        // Initialize everything
        session.style.backgroundColor = "rgb(0, 128, 0)"; // green colour
        ShowDefaultTimer();
        startBtn.disabled = false;
        stopBtn.disabled = true;
        StartButton();
        StopButton();
        addTime(addButton, "min");
        addTime(addsecondButton, "sec");
        minusTime(minusButton, "min");
        minusTime(minussecondButton, "sec");
        setLongBreakInterval();
        timerTypeSelection();
        
        // Make sure we start with a clean counter
        POMODORO__TIMES = 0;
        console.log("Timer initialized with pomodoro count: 0, long break threshold: " + LONG__BREAK__TIMES);
    }
});