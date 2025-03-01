// Add global variables at the top of the file
let GroupCard = null;
let GroupContainer = null;
let GroupCardTask = null;

// Add input validation helper
function validateInput(value, maxLength = 500) {
    if (!value || typeof value !== 'string') return false;
    return value.trim().length > 0 && value.trim().length <= maxLength;
}

// Improve createNewGroup with validation

function createNewGroup(NameOfContainer, ClassName, headerTag, headerClassName, Content) {
    if (!validateInput(Content)) {
        console.error('Invalid group name');
        return false;
    }
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

// Add error handling to CreateNewTask
function CreateNewTask(NameOfContainerClass, NameOfGroup, TaskTitle, TaskContent) {
    try {
        if (!validateInput(TaskTitle) || !validateInput(TaskContent, 1000)) {
            throw new Error('Invalid task data');
        }
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
    } catch (error) {
        console.error('Task creation failed:', error);
        return false;
    }
}

// Improve fetch calls with timeout and error handling
function timeleftToCompleteTask(Cate, taskTitle, taskContent) {
    // Add input validation
    if (!Cate || !taskTitle || !taskContent) {
        console.error('Missing required parameters');
        return;
    }

    const controller = new AbortController();
    const timeout = setTimeout(() => controller.abort(), 5000);
    console.log("Fetching task data...");
    
    return fetch("TodoBackend.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest"
        },
        signal: controller.signal,
        body: JSON.stringify({
            type: "fetch_task",
            function: "Send_Task_info_check_to_database_select",
            Category: Cate,
            title: taskTitle,
            Content: taskContent
        })
    })
    .then(async response => {
        clearTimeout(timeout);
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Response received:', data); // Now we'll see the actual data
        return data;
    })
    .catch(error => {
        clearTimeout(timeout);
        console.error("Error:", error);
        throw error;
    });
}

// And where you call it, add await:
async function someFunction() {
    try {
        const result = await timeleftToCompleteTask("Academics", "asd", "asd");
        console.log("Task data:", result);
    } catch (error) {
        console.error("Failed to get task data:", error);
    }
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
            type : "update_task",
            cate : category,
            title: taskTitle,
            content : taskContent,
            time : TIME,
            date : DATE
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); // Expecting JSON)  // Handle response
     }) 
    //  .then(text => {
    //     console.log("Raw Response:", text); // Log raw response
    //     return JSON.parse(text); // Attempt to parse JSON
    // })
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

// Improve form submission security
function handleTaskNameSubmit(event, AddTask, overlay, groupChoice, taskTitle, taskContent, taskForm) {
    event.preventDefault();

    // Update GroupCard reference
    GroupCard = document.querySelector('.TODO__CARD');
    
    if (!GroupCard) {
        console.error('No group card found. Please create a group first.');
        alert('Please create a group before adding tasks.');
        return;
    }

    if (!groupChoice || !groupChoice.value) {
        console.error('No group selected');
        alert('Please select a group first.');
        return;
    }

    const formData = new FormData(this);
    
    // Send form data to server
    fetch('TodoBackend.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log("Response from PHP:", data);
        
        if (taskForm) {
            try {
                // Create the task UI element
                CreateNewTask(GroupCard.className, groupChoice.value, taskTitle.value, taskContent.value);
                
                // Clear form fields
                document.getElementById('taskContent').value = '';
                document.getElementById('taskTitle').value = '';
                document.querySelectorAll('.TIMER__INPUT').forEach(input => {
                    input.value = '';
                });
                
                // Hide the form
                AddTask.style.display = 'none';
                overlay.style.display = 'none';
            } catch (error) {
                console.error('Error creating task:', error);
                alert('Failed to create task. Please try again.');
            }
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert('Failed to save task. Please try again.');
    });
}

// Move utility functions to the top of the file
function preventTyping(){
    document.querySelectorAll('.timer-inputs').forEach(input =>{
        input.addEventListener('keydown',function(event){
            event.preventDefault();
        });

        input.addEventListener('paste', function(event){
            event.preventDefault();
        });
    });
}

// Move DragAndDrop and related functions before the DOMContentLoaded event
function DragAndDrop() {
    let isUpdating = false;
    let pendingMutations = false;
    let observer = null;

    // Check for initial elements
    const container = document.querySelector('.TODO__CONTAINER');
    if (!container) {
        console.error('Container not found');
        return;
    }

    const handleMutations = () => {
        if (isUpdating) return;
        isUpdating = true;

        try {
            const cards = document.querySelectorAll('.TODO__CARD');
            const tasks = document.querySelectorAll('.TODO__TASK');

            // Initialize drag for tasks
            if (tasks.length > 0) {
                Drag(tasks);
            }

            // Initialize drop zones for cards
            if (cards.length > 0) {
                Drop(cards);
            }
        } catch (error) {
            console.error('Drag and Drop error:', error);
        } finally {
            isUpdating = false;
        }
    };

    // Create and start observer
    observer = new MutationObserver(handleMutations);
    observer.observe(container, { 
        childList: true, 
        subtree: true 
    });

    // Initial setup
    handleMutations();
}

function Drag(elements) {
    elements.forEach(element => {
        // Remove existing listeners first
        element.removeEventListener('dragstart', handleDragStart);
        element.removeEventListener('dragend', handleDragEnd);

        // Add new listeners
        element.addEventListener('dragstart', handleDragStart);
        element.addEventListener('dragend', handleDragEnd);
    });
}

function handleDragStart(e) {
    e.target.classList.add('is-dragging');
}

function handleDragEnd(e) {
    e.target.classList.remove('is-dragging');
}

function Drop(zones) {
    zones.forEach(zone => {
        if (zone.dataset.dropInitialized) return;
        zone.dataset.dropInitialized = 'true';

        zone.addEventListener('dragover', handleDragOver);
        zone.addEventListener('drop', handleDrop);
    });
}

function handleDragOver(e) {
    e.preventDefault();
    const draggable = document.querySelector('.is-dragging');
    if (!draggable) return;

    const closestTask = insertAboveTask(e.currentTarget, e.clientY);
    if (!closestTask) {
        e.currentTarget.appendChild(draggable);
    } else {
        e.currentTarget.insertBefore(draggable, closestTask);
    }
}

function handleDrop(e) {
    e.preventDefault();
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

// Cleanup function
function cleanup() {
    if (observer) {
        observer.disconnect();
    }
    document.querySelectorAll('.TODO__TASK').forEach(task => {
        task.removeEventListener('dragstart', null);
        task.removeEventListener('dragend', null);
    });
}

function GroupButton() {
    const closeGroupAdd = document.getElementById("closeGroupAdd");
    if (!closeGroupAdd) {
        console.error("closeGroupAdd button not found");
        return;
    }

    closeGroupAdd.addEventListener("click", handleGroupNameCloseBtn);
    const groupButton = Array.from(document.querySelectorAll(".TODO__ADD"))
        .find(btn => btn.textContent.trim().startsWith("Group"));

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
        });
    }
}

function TaskButton() {
    const taskButton = Array.from(document.querySelectorAll(".TODO__ADD"))
        .find(btn => btn.textContent.trim().startsWith("Task"));
    const closeTaskButton = document.getElementById('closeTaskButton');
    
    if (closeTaskButton) {
        closeTaskButton.addEventListener('click', handleTaskNameCloseBtn);
    } else {
        console.error("close button not found");
    }

    if (taskButton) {
        taskButton.addEventListener('click', () => {
            // Update GroupCard reference before checking
            GroupCard = document.querySelector('.TODO__CARD');
            
            if (!GroupCard || !GroupNameAvaliablity()) {
                alert('No groups available. Please create a group first.');
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

document.addEventListener('DOMContentLoaded', function () {
    if (!window.location.pathname.toLowerCase().includes('todo')) {
        return;
    }
    
    console.log("Todo page activated");
    
    try {
        // Initialize functionality
        CreateTaskForm();
        GroupButton();
        TaskButton();
        preventTyping();
        
        // Initialize drag and drop after a short delay to ensure DOM is ready
        setTimeout(DragAndDrop, 100);

        // Initialize global DOM elements after forms are created
        GroupContainer = document.querySelector('.TODO__CONTAINER');
        if (!GroupContainer) {
            throw new Error('Required container element not found');
        }

        // Add cleanup on page unload
        window.addEventListener('unload', cleanup);
        
        // Test functions only if groups exist
        ObserveChanges('TODO__CONTAINER');
        
    } catch (error) {
        console.error('Initialization error:', error);
    }
});

function ObserveChanges(className) {
    const container = document.querySelector(`.${className}`);
    if (!container) {
        console.error(`Container with class ${className} not found`);
        return;
    }

    const observer = new MutationObserver(() => {
        console.log(`Changes detected in ${className}`);
        if (GroupNameAvaliablity()) {
            SetFinaltimerUpateTodatabase("Academics", "asd", "asd", 0, 24, 59, 0);
            timeleftToCompleteTask("Academics", "asd", "asd");
        }
    });

    observer.observe(container, { childList: true, subtree: true });

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

function selectelement (id){
    let addedId = document.getElementById(id);
    return addedId;
}