function createNewGroup(NameOfContainer, ClassName, headerTag, headerClassName, Content) {
    console.log("Creating group");
    let container = document.querySelector(`.${NameOfContainer}`);
    if (!container) {
        console.error(`Container with ${NameOfContainer} not found`);
        return;
    }
    const newGroupCard = document.createElement('div');
    newGroupCard.classList.add(ClassName)
    newGroupCard.id = Content;

    const header = document.createElement(headerTag);
    header.classList.add(headerClassName);
    header.textContent = Content;

    newGroupCard.appendChild(header);

    container.appendChild(newGroupCard);
    console.log("Created group");
    console.log(container)
}

function CreateNewTask(NameOfContainerClass = null, NameOfGroup = null, TaskTitle = null, TaskContent = null) {
    if (NameOfContainerClass === null || NameOfGroup === null || TaskTitle === null || TaskContent === null){
        console.log('Please enter something.');
        return;
    }

    let Container = document.querySelectorAll(`.${NameOfContainerClass}`);
    Group = Array.from(Container).find(Name => Name.id === NameOfGroup);
    if (!Group){
        console.log(Group, Container);
        console.log("Cannot find group")
        return;
    }
    const formattedTime = "00:10:00";

    console.log(Group.id);

    const header = document.createElement('h3');
    header.classList.add('TODO__TASK');
    header.textContent = TaskTitle.length > 500 ? TaskTitle.substring(0, 500) + "..." : TaskTitle;
    header.draggable = 'true';

    const paragraph = document.createElement('p');
    paragraph.classList.add('TODO__TASK__CONTENT');
    paragraph.textContent = TaskContent;

    const timestamp = document.createElement('p2');
    timestamp.classList.add('TODO__TASK__TIME');
    timestamp.id = formattedTime;
    timestamp.textContent = formattedTime;

    header.appendChild(paragraph);
    header.appendChild(timestamp);
    Group.appendChild(header);
}

function preventTyping(){
    document.querySelectorAll('.timer-inputs').forEach(input =>{
        input.addEventListener('keydown',function(event){
            event.preventDefault();
        });

        input.addEventListener('paste', function(event){
            event.preventDefault();
        });
    })
}

function timeleftToCompleteTask(Cate, taskTitle, taskContent){
    const now = new Date();
    const formattedDate = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-${String(now.getDate()).padStart(2, '0')} 
    ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;

    console.log(formattedDate);

    fetch("TodoBackend.php",{
        method: "POST",
        headers: {"Content-Type": "application/json" },
        body: JSON.stringify({
            type: "fetch_task",
            Category: Cate,
            title: taskTitle,
            Content: taskContent
        })
    })
    .then(response => response.json())  // Parse as JSON
    .then(data => console.log("Received Task Data:", data))  // Log received data
    .catch(error => console.error("Error fetching data:", error));
    
    fetch("TodoBackend.php?type=products", {
        method: "GET",
        headers: { "Content-Type": "application/json" },
    })
    .then(response => response.json()) // Convert response to JSON
    .then(data => console.log("Received:", data)) // Log response
    .catch(error => console.error("Error fetching data:", error));
    
    
    let second = now.getSeconds();

    // if(week >= 1){
    //     console.log(week + " week left");
    // }else if (day >= 1){
    //     console.log(day + " day left");
    // }else if(hours >= 1){
    //     console.log(hours + " hour left");
    // }else if (mins >= 1){
    //     console.log(mins + " min left");
    // }else{
    //     console.log(seconds + " second left");
    // }
}

function FinalDate (days, hours, mins, seconds){
    const now = new Date();
    
    let finalSeconds = now.getSeconds() + seconds;
    let extraMinutes = Math.floor(finalSeconds / 60);
    finalSeconds = finalSeconds % 60;  // Keep seconds in range 0-59

    let finalMinutes = now.getMinutes() + mins + extraMinutes;
    let extraHours = Math.floor(finalMinutes / 60);
    finalMinutes = finalMinutes % 60;  // Keep minutes in range 0-59

    let finalHours = now.getHours() + hours + extraHours;
    let extraDays = Math.floor(finalHours / 24);
    finalHours = finalHours % 24;  // Keep hours in range 0-23

    let finalDays = now.getDate() + days + extraDays;
    let finalDate = new Date(now.getFullYear(), now.getMonth(), finalDays, finalHours, finalMinutes, finalSeconds);

    let wordarray = finalDate.toString().split(" ");
    wordarray[1] = finalDate.toLocaleString('en-US', { month: 'long' });
    return wordarray;
}

function convertMonthToNumber(month) {
    const months = {
        "January": 1, "February": 2, "March": 3, "April": 4, 
        "May": 5, "June": 6, "July": 7, "August": 8, 
        "September": 9, "October": 10, "November": 11, "December": 12
    };
    return months[month] || -1;  // Returns -1 if the month is invalid
}

function SetFinaltimerUpateTodatabase (category, taskTitle, taskContent, days, hours, mins, seconds){
    let finalDate = FinalDate(days, hours, mins, seconds);
    finalDate.forEach((time, index) => {
        console.log(index, time)
    })

    let month = convertMonthToNumber(finalDate[1])
    DATE = finalDate[3] + " " + String(month).padStart(2, '0') + " " + String(finalDate[2]).padStart(2, '0');
    console.log(DATE);

    TIME = finalDate[4];
    console.log(TIME);

    fetch('TodoBackend.php', {
        method: 'POST',  // or 'GET' depending on your requirement
        headers: {
            'Content-Type': 'application/json' // For form data
        },
        body: JSON.stringify({
            cate : category,
            title: taskTitle,
            content : taskContent,
            time : TIME,
            date : DATE
        })
    })
    .then(response => response.text())  // Handle response
    .then(data => console.log('Response:', data))
    .catch(error => console.error('Error:', error));
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

    const TASKBUTTON = document.createElement('button');
    TASKBUTTON.id = 'closeTaskButton';
    TASKBUTTON.textContent = 'x';

    const header = document.createElement('h2');
    header.textContent = "Choose Your group:";

    const form = document.createElement('form');
    form.id = 'taskForm';
    form.method = 'post';
    form.action = "TodoBackend.php";

    const label = document.createElement('label');
    label.setAttribute('for', 'Group');

    const selection = document.createElement('select');
    selection.id = 'GROUP__NAME__TASK';
    selection.name = 'GROUPNAMECHOICE[]';

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
        observer.observe(document.querySelector('.TODO__CONTAINER'), { childList: true, subtree: true }); // Resume observing
    }

    UpdateSelection();

    const TaskTitle = document.createElement('input');
    TaskTitle.value = "";
    TaskTitle.id = "taskTitle";
    TaskTitle.type = "text";
    TaskTitle.placeholder = 'Enter the Task title';
    TaskTitle.required = true;
    TaskTitle.name = 'TASKTITLE';

    const userinput = document.createElement('input');
    userinput.value = "";
    userinput.id = 'taskContent';
    userinput.type = 'text';
    userinput.placeholder = 'Enter the task';
    userinput.required = true;
    userinput.name = 'USERTASK';

    const taskDays = document.createElement('input');
    taskDays.type = 'number';
    taskDays.id = 'taskDays';
    taskDays.placeholder = 'Days';
    taskDays.min = '0';
    taskDays.step = '1';
    taskDays.onkeydown = "return false";
    taskDays.required = true;
    taskDays.value = '';
    taskDays.classList.add('TIMER__INPUT');

    const taskHours = document.createElement('input');
    taskHours.type = 'number';
    taskHours.id = 'taskHours';
    taskHours.placeholder = 'Hours';
    taskHours.min = '0';
    taskHours.max = '23';
    taskHours.step = '1';
    taskHours.onkeydown = "return false";
    taskHours.required = true;
    taskHours.classList.add('TIMER__INPUT');

    const taskMinutes = document.createElement('input');
    taskMinutes.type = 'number';
    taskMinutes.id = 'taskMinutes';
    taskMinutes.placeholder = 'Minutes';
    taskMinutes.min = '0';
    taskMinutes.max = '59';
    taskMinutes.step = '1';
    taskMinutes.onkeydown = "return false";
    taskMinutes.required = true;
    taskMinutes.classList.add('TIMER__INPUT');

    const taskSeconds = document.createElement('input');
    taskSeconds.type = 'number';
    taskSeconds.id = 'taskSeconds';
    taskSeconds.placeholder = 'Seconds';
    taskSeconds.min = '0';
    taskSeconds.max = '59';
    taskSeconds.step = '1';
    taskSeconds.onkeydown = "return false";
    taskSeconds.required = true;
    taskSeconds.classList.add('TIMER__INPUT');

    const timerInput = document.createElement('div');
    timerInput.classList.add('timer-inputs');

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

    timerInput.appendChild(taskDays);
    timerInput.appendChild(taskHours);
    timerInput.appendChild(taskMinutes);
    timerInput.appendChild(taskSeconds);

    form.appendChild(label);
    form.appendChild(selection);
    form.appendChild(TaskTitle);
    form.appendChild(userinput);
    form.appendChild(timerInput);
    form.appendChild(submission_Button);

    
    main.appendChild(header);
    main.appendChild(TASKBUTTON);
    main.appendChild(form);

    document.body.appendChild(main);
    console.log("created Task Form");
    console.log("MutationObserver started: Watching for changes...");

}

function GetGroupName() {
    const groupNameInput = document.getElementById("groupName");
    return groupNameInput.value;
}

document.addEventListener('DOMContentLoaded', function () { // only active the code when it is on the specific file
    if (window.location.pathname.toLowerCase().includes('todo')) {
        console.log("Todo page activated");

        const GroupContainer = document.querySelector('.TODO__CONTAINER');
        // const AllGroupContainer = document.querySelectorAll('.TODO__CONTAINER');

        // Get all buttons with the class "TODO__ADD"
        const buttons = document.querySelectorAll(".TODO__ADD");

        let GroupCard = document.querySelector('.TODO__CARD');

        const GroupCardHeader = document.querySelector('.TODO__CARD_HEADER');

        let GroupCardTask = document.querySelector('.TODO__TASK');
        // Convert NodeList to an array for easier handling (optional)
        const buttonArray = Array.from(buttons);

        // Find the button that has "Group" as its text content
        const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

        // Find the button that has "Task" as its text content
        const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));

        let debounceTimeout;

        // createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName);
        let observer; // Global variable

        function updateTodataBase(){
            fetch('TodoBackend.php')
            .then(response => {
                if (!response.ok) {
                throw new Error('Network response was not ok');
                }
                // Convert the response to JSON
                return response.json();
            })
            .then(data => {
                // Use the retrieved data
                console.log('Data from PHP:', data);
                
                // For example, update the DOM or process the data further
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
        }

        function GroupButton (){
            const closeGroupAdd = document.getElementById("closeGroupAdd");

            closeGroupAdd.addEventListener("click", handleGroupNameCloseBtn);
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

                             // Call the function to retrieve the input value of the group name
                            if (!groupFrom) {
                                console.error("groupForm is not found in the DOM");
                                return;
                            }
                            groupFrom.removeEventListener("submit", handleGroupNameSubmitWrapper);
                            groupFrom.addEventListener("submit", handleGroupNameSubmitWrapper, { once: true });
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
        }

        function CheckIfgroupNameSame(GROUPNAME){
            const GROUPNAMELIST = document.querySelectorAll('.TODO__CARD');
            console.log(GROUPNAMELIST);
            if(GROUPNAMELIST.length > 0){
                
                for (let i = 0; i < GROUPNAMELIST.length; i++){
                    if (GROUPNAME === GROUPNAMELIST[i].id){
                        console.log("its the same");
                        return true;
                        
                    }else{
                        return false;
                    }
                }
            }else{
                console.log("length is 0");
            }
        }

        function GroupNameAvaliablity (){
            const GROUPNAMELIST = document.querySelectorAll('.TODO__CARD');
            if(GROUPNAMELIST.length > 0){
                return true;
            }else{
                return false;
            }
        }

        function handleGroupNameSubmit (event, box, overlay, groupFrom){
            event.preventDefault(); // Prevent the default form submission behavior

            let formData = new FormData(this);

            // Send the form data to PHP using fetch
            fetch('TodoBackend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log('Server response:', data);
                // Optionally, handle the server response here
            })
            .catch(error => console.error('Error:', error));
            const groupName = GetGroupName();
            
            box.style.display = 'none';
            if (groupFrom) {
                if(CheckIfgroupNameSame(groupName)){
                    alert('please dont enter the same groupname');
                    document.querySelector('.Hiddenlayer').style.display = "none";
                    document.getElementById("groupName").value = '';
                    groupFrom.removeEventListener("submit", handleGroupNameSubmit);
                    return;
                }else{
                    createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName);
                    console.log("submited: ", groupName);
                    overlay.style.display = 'none';
                    document.getElementById("groupName").value = '';
                }
            }
        }

        function handleGroupNameSubmitWrapper(event){
            let boxes = document.getElementsByClassName('TODO__GROUP__ADD');
            const box = boxes[0];
            const overlay = document.querySelector('.Hiddenlayer');
            const groupFrom = document.getElementById("groupForm");
            handleGroupNameSubmit.call(this, event, box, overlay, groupFrom)
        }

        function handleGroupNameCloseBtn(){
            document.querySelector('.TODO__GROUP__ADD').style.display = "none";
            document.querySelector('.Hiddenlayer').style.display = "none";
            document.getElementById("groupName").value = '';
            document.getElementById('taskTitle').value = '';

            let groupFrom = document.getElementById("groupForm");

            if (groupFrom != null){
                groupFrom.removeEventListener('submit', handleGroupNameSubmitWrapper);
            }
        }

        function handleTaskNameSubmit (event, AddTask, overlay, groupChoice, taskTitle ,taskContent, taskForm){
            event.preventDefault(); // Prevent default form submission (block all the data submit to the php file)
        
            const formData = new FormData(this); // Capture form data
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
            fetch('TodoBackend.php', { // to send the form data to the php file when submition
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log("Response from PHP:", data);
            })
            .catch(error => console.error("Error:", error));
            if (taskForm) {
                console.log(GroupCard);

                const day = document.querySelector('.TIMER__INPUT[placeholder="Days"]');
                const hours = document.querySelector('.TIMER__INPUT[placeholder="Hours"]');
                const minutes = document.querySelector('.TIMER__INPUT[placeholder="Minutes"]');
                const seconds = document.querySelector('.TIMER__INPUT[placeholder="Seconds"]');
                
                CreateNewTask(GroupCard.className, groupChoice.value, taskTitle.value, taskContent.value);
                console.log(groupChoice.value);
                console.log(taskContent.value);
                document.getElementById('taskContent').value = '';
                document.getElementById('taskTitle').value = '';
                const TIMERINPUT = document.querySelectorAll('.TIMER__INPUT');
                TIMERINPUT.forEach(input => {
                    input.value = '';
                })
                AddTask.style.display = 'none';
                overlay.style.display = 'none';
                return;
            }
        }

        function handleTaskNameCloseBtn(){
            console.log("Closed task form page");
            // clear all the input when close the form
            document.querySelector('.TODO__TASK__ADD').style.display = "none";
            document.querySelector('.Hiddenlayer').style.display = "none";
            document.getElementById('taskContent').value = '';
            document.getElementById('taskTitle').value = '';

            const TIMERINPUT = document.querySelectorAll('.TIMER__INPUT');
            TIMERINPUT.forEach(input => {
                input.value = '';
            })

            let taskForm = document.getElementById('taskForm');
            if (typeof taskForm !== "undefined" && taskForm) {
                taskForm.removeEventListener("submit", handleTaskNameSubmitWrapper);
            }
        }

        function handleTaskNameSubmitWrapper(event) {
            // Assuming the parameters are available globally or you retrieve them here
            const addTask = document.querySelector('.TODO__TASK__ADD');
            const overlay = document.querySelector('.Hiddenlayer');
            const taskForm = document.getElementById('taskForm');
            const groupChoice = document.getElementById('GROUP__NAME__TASK');
            const taskContent = document.getElementById('taskContent');
            const TaskTItle = document.getElementById('taskTitle');
            console.log(TaskTItle);
            handleTaskNameSubmit.call(this, event, addTask, overlay, groupChoice, TaskTItle, taskContent, taskForm);
        }

        function TaskButton (){
            const closeTaskButton = document.getElementById('closeTaskButton');
            if (closeTaskButton){
                closeTaskButton.addEventListener('click', handleTaskNameCloseBtn);
            }else{
                console.error("close button not found");
            }

            if (taskButton) {
                taskButton.addEventListener('click', () => {
                    if(!GroupNameAvaliablity()){
                        alert('No Group avaliable please go to enter group first');
                        return;
                    }
                    const AddTask = document.querySelector('.TODO__TASK__ADD');
                    const overlay = document.querySelector('.Hiddenlayer');
                    const taskForm = document.getElementById('taskForm');
                    const groupChoice = document.getElementById('GROUP__NAME__TASK');
                    const taskContent = document.getElementById('taskContent');
                    console.log(AddTask)
                    if (AddTask) {
                        if (AddTask.style.display === 'none') {
                            AddTask.style.display = 'block'; // make the form visisble
                            overlay.style.display = 'block';
                            // taskForm.removeEventListener("submit", handleTaskNameSubmitWrapper);
                            taskForm.addEventListener('submit', handleTaskNameSubmitWrapper,{once : true});
                        } else {
                            AddTask.style.display = 'none';
                            overlay.style.display = 'none';
                        }
                    } else {
                        console.log("not enough length");
                    }
                });
            }
        }

        function selectelement (id){
            let addedId = document.getElementById(id);
            return addedId;
        }

        function DragAndDrop() {
            let isUpdating = false; 
        
            observer = new MutationObserver((mutations) => {
                if (isUpdating) return; // Prevent re-entry
        
                // Check if any relevant nodes were added
                const relevantMutations = mutations.some(mutation => 
                    mutation.addedNodes && mutation.addedNodes.length > 0
                );

                GroupCard = document.querySelector('.TODO__CARD');
                GroupCardTask = document.querySelector('.TODO__TASK');
        
                if (relevantMutations) {
                    isUpdating = true; // Prevent looping
        
                    // Pause observer while updating
                    observer.disconnect();
                    if(GroupCardTask === null){
                        console.log("its null ignore");
                    }else{
                        try {
                            const draggables = document.querySelectorAll(`.${GroupCardTask.className}`)
                            console.log(draggables); // Debugging
            
                            Drag(draggables);
                            
                        }catch (error){
                            if(error instanceof TypeError){
                                console.error("Caught a TypeError:", error.message);
                            }else {
                                // Handle other types of errors if needed
                                console.error("An unexpected error occurred:", error);
                            }
                        }
                    }
                    
                    try{
                        const droppables = document.querySelectorAll(`.${GroupCard.className}`);
                        // console.log(droppables)
                        Drop(droppables);

                    }catch (error){
                        if(error instanceof TypeError){
                            console.error("Caught a TypeError for droppables:", error.message);
                        }else {
                            // Handle other types of errors if needed
                            console.error("An unexpected error occurred:", error);
                        }
                    }

                    // Resume observer **AFTER** execution to avoid recursive calls
                    setTimeout(() => {
                        observer.observe(GroupContainer, { childList: true, subtree: true });
                        isUpdating = false; 
                    }, 50); // Delay prevents excessive triggering
                }
            });
        
            // Start observing for changes
            observer.observe(GroupContainer, { childList: true, subtree: true });
        }
        
        function Drag(draggables) {
            draggables.forEach(element => {
                element.addEventListener('dragstart', () => {
                    element.classList.add('is-dragging');
        
                    // Temporarily stop observing to avoid loops
                    if (observer) observer.disconnect();
                });
        
                element.addEventListener('dragend', () => {
                    element.classList.remove('is-dragging');
        
                    // Restart observing with a slight delay to prevent instant recursion
                    setTimeout(() => {
                        if (observer) observer.observe(GroupContainer, { childList: true, subtree: true });
                    }, 50);
                });
            });
        }
        
        function Drop(droppables) {
            if (droppables === null) return;
            droppables.forEach(zone => {
                console.log(zone.dataset)
                if (!zone.dataset.listener) { // Avoid adding multiple event listeners
                    
                    zone.dataset.listener = "true"; 
        
                    zone.addEventListener("dragover", (e) => {
                        e.preventDefault(); // Required to allow dropping
        
                        const curTask = document.querySelector(".is-dragging");
                        const closestTask = insertAboveTask(zone, e.clientY);
        
                        if (!closestTask) {
                            // If there's no task below, append at the end
                            zone.appendChild(curTask);
                        } else {
                            // Insert before the closest task
                            zone.insertBefore(curTask, closestTask);
                        }
                    });
        
                    zone.addEventListener("drop", (e) => {
                        e.preventDefault(); // Prevent default drop behavior
                    });
                }
            });
        }
        
        function insertAboveTask(zone, mouseY) {
            const tasks = [...zone.querySelectorAll(".TODO__CARD:not(.is-dragging)")]; // Get all tasks except the one being dragged
        
            let closestTask = null;
            let closestOffset = Number.POSITIVE_INFINITY; // Use positive infinity to find the smallest offset
        
            tasks.forEach(task => {
                const { top, height } = task.getBoundingClientRect();
                const middleY = top + height / 2; // Middle of the task box
                const offset = mouseY - middleY; // Difference between mouse and middle of task
        
                if (offset < 0 && Math.abs(offset) < Math.abs(closestOffset)) {
                    closestOffset = offset;
                    closestTask = task; // Set the closest task above the mouse
                }
            });
        
            return closestTask; // Return the closest task, or null if none found
        }
        
        // setTimeout(()=>{
        //     createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', 'chen');
        // }, 1000)
        
        // Drag();
        // Drop();
        
        // MovingCard();

        // updateTodataBase();
        // MovingCard();

        DragAndDrop();
        CreateTaskForm();
        GroupButton();
        TaskButton();
        preventTyping();
        // if (document.querySelectorAll(`.${GroupCardTask.className}`).length === 0){
        //     console.log("its a null");
        // }else{
        //     
        // }

        timeleftToCompleteTask();
        SetFinaltimerUpateTodatabase("hello", "well", null, 0, 24, 59, 0);
 // Output: "2025-02-25 14:30:45"
    }
})