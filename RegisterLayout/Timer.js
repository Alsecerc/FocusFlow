document.addEventListener("DOMContentLoaded", function(event) {
    if (window.location.pathname.toLowerCase().includes('timer')){
        console.log("Running js code on Timer page...");
        let pomodoro = document.getElementById("pomodoro-timer");
        let short = document.getElementById("short-timer");
        let long = document.getElementById("long-timer");

        let TIMER__DISPLAY = document.querySelector(".timer-display");
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

        const CurrentTimerType = (mutationList, observer) => {
            for (const mutation of mutationList) {
                if (mutation.type === "characterData") {
                  console.log(`${mutation.type} data was modified`);
                } else if (mutation.type === "subtree") {
                  console.log(`The ${mutation.subtree} subtree was modified.`);
                }
            }
        }

        function ShowDefaultTimer(){
            const pomodoro_minutes = 25;
            const pomodoro_seconds = 0;
            pomodoro.textContent = `${String(pomodoro_minutes).padStart(2,'0')}:${String(pomodoro_seconds).padStart(2,'0')}`
            pomodoro.style.display = 'block';

            const short_break_minutes = 5;
            const short_break_seconds = 0;

            short.innerHTML = `${String(short_break_minutes).padStart(2,'0')}:${String(short_break_seconds).padStart(2,'0')}`;
            short.style.display = 'none';

            const long_break_minutes = 10;
            const long_break_seconds = 0;

            long.innerHTML = `${String(long_break_minutes).padStart(2,'0')}:${String(long_break_seconds).padStart(2,'0')}`;
            long.style.display = 'none';
        }
        
        function addTime(){
            addButton.addEventListener('click', () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                console.log(`Current add time in ${TIMER__ID.id}`);
                let Time = TIMER__ID.textContent;
                let [minutes, seconds] = Time.split(':').map(Number);
                if (minutes >= 60){
                    console.log('Minutes cannot be more than 60');
                }else{
                    minutes++;
                }
                TIMER__ID.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
            })
        }

        function minusTime(){
            minusButton.addEventListener('click', () => {
                const TIMER__ID = document.getElementById(CurrentTimer());
                console.log(`Current minus time in ${TIMER__ID.id}`);
                let currentTime = TIMER__ID.textContent;
                let [minutes, seconds] = currentTime.split(':').map(Number);
                if (minutes <= 0){
                    console.log('Time cannot be negative');
                }else{
                    minutes--;
                }
                TIMER__ID.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
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
            // Select the timer display container
            const ALL__TIMER__DISPLAY = document.querySelectorAll('.timer-display .time span');
            if (!ALL__TIMER__DISPLAY) {
                console.error('Element with class "timer-display" not found.');
                return;
            }
    
            // Find the timer with display: block
            const activeTimer = Array.from(ALL__TIMER__DISPLAY).find(timer => timer.style.display === 'block'); 
            if (activeTimer) {
                console.log(`Active timer: ${activeTimer.id}`);
                return activeTimer.id;
            } else {
                console.log('No active timer found.');
                return null;
            }           
        }
    

        function display_none_other (BUTTON_TIMER_TYPE_ID = null){
            const BUTTON__CONTAINER = document.querySelectorAll('.timer-display .time span');
            if(BUTTON_TIMER_TYPE_ID === null){
                console.log('Please put iD');
                return;
            }
            console.log(BUTTON_TIMER_TYPE_ID.id);

            //testing
            // const BUTTON__TYPE__ARRAY = Array.from(BUTTON__CONTAINER).map(button => button.id);
            // let BUTTON__CHANGE = BUTTON__TYPE__ARRAY === BUTTON_TIMER_TYPE_ID.id ? BUTTON__TYPE__ARRAY.style.display = 'block': BUTTON__TYPE__ARRAY.style.display = 'none';

            // console.log(BUTTON__TYPE__ARRAY);
            //testing

            // let buttonsArray = Array.from(BUTTON__CONTAINER);

            BUTTON__CONTAINER.forEach(button => {
                if (button.id === BUTTON_TIMER_TYPE_ID.id){
                    // console.log(`${button.id}:${BUTTON_TIMER_TYPE_ID.id}`); //debug
                    console.log(`${button.id}:style change to block`);
                    button.style.display = 'block';
                }else{
                    button.style.display = 'none';
                    console.log(`${button.id}:style change to none`);
                }
            });
        }

        function timerTypeSelection (){
            document.querySelector('.button-container').addEventListener('click', function(event){
                if(event.target.id === session.id){
                    console.log(`timer selection is ${event.target.id}`);
                    display_none_other(pomodoro);
                    
                }else if(event.target.id === shortBreak.id){
                    console.log(`timer selection is ${event.target.id}`);
                    display_none_other(short);
                    

                }else if(event.target.id === longBreak.id){
                    console.log(`timer selection is ${event.target.id}`);
                    display_none_other(long);
                    // console.log(`current target is ${event.target}`);
                }
                clearInterval(intervalId);// stop the timer
            })
        }
        
        /**
         * Start button 
         * 
         * When user press start button it will start counting the time
         * 
         */
        function TimeUpdate(){
            if(isFirstUpdate){
                console.log('Ignore first update');
                isFirstUpdate = false;
                return;
            }
            const TypeTimer = CurrentTimer();
            const timer = document.getElementById(TypeTimer);

            let currentTime = timer.textContent;
            let [minutes, seconds] = currentTime.split(':').map(Number);

            console.log(`Started count at ${TypeTimer}`);
            if (seconds > 0){
                seconds--;
            }else if (seconds == 0){
                if (minutes > 0){
                    minutes--;
                    seconds = 59;
                }else{
                    console.log('Time up');
                    clearInterval(intervalId);
                }
            }
            timer.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }

        function StartButton (){
            startBtn.addEventListener('click', ()=> {
                const TIMER__ID = document.getElementById(CurrentTimer());
                if(intervalId){
                    clearInterval(intervalId);
                }
                console.log('Pressed Start button');
                intervalId = setInterval(TimeUpdate, 1000);
            })
        }

        function StopButton (){
            stopBtn.addEventListener('click', ()=> {
                console.log("Stopped");
                clearInterval(intervalId);
            })
        }

        function test() {
            let timers = document.querySelectorAll('.timer-display .time span');
            
            let observer = new MutationObserver((mutationList) =>{
                mutationList.forEach(mutation =>{
                    if(mutation.type === 'childList'){
                        console.log(`Timer updated: ${mutation.target.id} → ${mutation.target.textContent}`);
                    }
                })
            })
            // Ensure that timers is not empty
            if (timers.length === 0) {
                console.log("No timers found.");
                return; // Exit the function if no timers are found
            }
            let config = {  characterData: true, subtree: true};
            timers.forEach((timer) => { // Loop through each element in the NodeList
                // let style = window.getComputedStyle(timer); // Get the computed style for each element
                if (timer.style.display === 'block') {
                    console.log(`${timer.id}, ${timer.textContent} is displaying`); // Log the id of the timer that is visible
                }else{
                    console.log(`${timer.id} is not displaying`);
                }
                // console.log(`${timer.id}, ${timer.textContent}, ${timer.style.display} is displaying`);
                observer.observe(timer, config);
            });
        }

        // Put function here
        ShowDefaultTimer();
        //testing
        addTime();
        minusTime();
        TimeUpdate();
        StartButton();
        StopButton();
        timerTypeSelection();
        // display_none_other();
        //testing
    }
});
