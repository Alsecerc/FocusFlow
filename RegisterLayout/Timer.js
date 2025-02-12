document.addEventListener("DOMContentLoaded", function(event) {
    if (window.location.pathname.toLowerCase().includes('timer')){
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

        function ShowDefaultTimer(){
            const pomodoro_minutes = 10;
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
        
        function addTime(ID__TIMER){
            addButton.addEventListener('click', () => {
                let Time = ID__TIMER.textContent;
                let [minutes, seconds] = Time.split(':').map(Number);
                if (minutes >= 60){
                    console.log('Minutes cannot be more than 60');
                }else{
                    minutes++;
                }                ID__TIMER.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
            })
        }

        function minusTime(ID__TIMER){
            minusButton.addEventListener('click', () => {
                console.log('start minus');
                let currentTime = ID__TIMER.textContent;
                let [minutes, seconds] = currentTime.split(':').map(Number);
                
                if (minutes <= 0){
                    console.log('Time cannot be negative');
                }else{
                    minutes--;
                }
                ID__TIMER.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
                console.log('minus complete');
            })
        }

        /**
         * Returns the current type of time.
         * 
         * @returns {Time_type} The current type of time displaying.
         * 
         */
        function CurrentTimer (){
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

        function timerTypeSelection (){

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

            console.log('Started count');
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
            let config = { childList: true};
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
