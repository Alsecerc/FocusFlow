
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

        let intervalId = null;
        let isFirstUpdate = true;
        let POMODORO__TIMES = 0;
        const LONG__BREAK__TIMES = 4;

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
                if (POMODORO__TIMES === LONG__BREAK__TIMES) {
                    display_none_other(long);
                } else {
                    display_none_other(short);
                }
                restartInterval();
                return;
            } else {
                resetTime();
                display_none_other(pomodoro);
                clearInterval(intervalId);
                intervalId = null;
                intervalId = setInterval(TimeUpdate, 1000);
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
            ButtonType.addEventListener("click", () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                console.log(TIMER__ID.textContent);
                console.log(`Adding time to: ${TIMER__ID.id}`);

                let [minutes, seconds] = TIMER__ID.textContent.split(":").map(Number);
                console.log(minutes, seconds);
                if (timeType === "min") {
                    
                    if(minutes < 60){
                        minutes++;
                        if (minutes === 60) {
                            minutes = 59;
                            seconds = 59;
                        }
                    } else {
                        console.log("Minutes cannot be more than 60");
                    }
                } else if (timeType === "sec") {
                    if(minutes === 60){
                        console.log("cannot add seconds when minutes is 60");
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
            });
        }

        function minusTime(ButtonType, timeType) {
            ButtonType.addEventListener("click", () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                
                console.log(`Removing time from: ${TIMER__ID.id}`);

                let [minutes, seconds] = TIMER__ID.textContent.split(":").map(Number);
                if (timeType === "min") {
                    if(minutes > 0){
                        minutes--;
                    }
                    else {
                        console.log("Time cannot be negative");
                    }
                } else if (timeType === "sec") {
                    if(seconds > 0){
                        seconds--;
                    }else if (seconds === 0 && minutes === 0) {
                        console.log("Time cannot be negative");
                    }
                    else {
                        minutes--;
                        seconds = 59;
                    }
                }

                updateGlobalTime(TIMER__ID, minutes, seconds);
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
        timerTypeSelection();
    }
});