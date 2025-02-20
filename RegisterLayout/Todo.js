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

function CreateNewTask(NameOfContainerClass = null, NameOfGroup = null, paragraphContent = null) {
    if (NameOfContainerClass === null || NameOfGroup === null || paragraphContent === null){
        console.log('Please enter something.');
        return;
    }

    let Container = document.querySelectorAll(`.${NameOfContainerClass}`);
    Group = Array.from(Container).find(Name => Name.id === NameOfGroup);
    if (!Group){
        console.log("Cannot find group")
        return;
    }
    console.log(Group.id);
    const paragraph = document.createElement('p')
    paragraph.className = 'TODO__TASK';
    paragraph.textContent = paragraphContent.length > 500 ? paragraphContent.substring(0, 500) + "..." : paragraphContent;
    paragraph.draggable = 'true';

    Group.appendChild(paragraph);
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
    form.method = 'post';
    form.action = "Todo.php";

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
    const userinput = document.createElement('input');
    userinput.value = "";
    userinput.id = 'taskContent';
    userinput.type = 'text';
    userinput.placeholder = 'Enter the task';
    userinput.required = 'true';
    userinput.name = 'USERTASK';

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
    console.log("MutationObserver started: Watching for changes...");



    form.addEventListener('submit', function (event) {

        event.preventDefault(); // Prevent default form submission
        
        const formData = new FormData(form); // Capture form data
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }
        fetch('Todo.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log("Response from PHP:", data);
        })
        .catch(error => console.error("Error:", error));
    });
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

        const GroupCard = document.querySelector('.TODO__CARD');

        const GroupCardHeader = document.querySelector('.TODO__CARD_HEADER');

        const GroupCardTask = document.querySelector('.TODO__TASK');
        // Convert NodeList to an array for easier handling (optional)
        const buttonArray = Array.from(buttons);

        // Find the button that has "Group" as its text content
        const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

        // Find the button that has "Task" as its text content
        const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));

        // let Draggable = document.querySelectorAll(`.${GroupCardTask.className}`);

        // let Droppable = document.querySelectorAll(`.${GroupCard.className}`);

        let debounceTimeout;

        // createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName);
        let observer; // Global variable

        function GroupButton (){
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
                                    createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName);
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
        }

        function TaskButton (){
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
                                    CreateNewTask(GroupCard.className, groupChoice.value, taskContent.value);
                                    console.log(groupChoice.value);
                                    console.log(taskContent.value);
                                    AddTask.style.display = 'none';
                                    overlay.style.display = 'none';
                                    return;
                                }
                            },{once : true});
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
        
                if (relevantMutations) {
                    isUpdating = true; // Prevent looping
        
                    // Pause observer while updating
                    observer.disconnect();
                    
                    try {
                        const draggables = document.querySelectorAll(`.${GroupCardTask.className}`)
                        console.log('hi'); // Debugging
        
                        Drag(draggables);
                        
                    }catch (error){
                        if(error instanceof TypeError){
                            console.error("Caught a TypeError:", error.message);
                        }else {
                            // Handle other types of errors if needed
                            console.error("An unexpected error occurred:", error);
                        }
                    }
                    
                    try{
                        const droppables = document.querySelectorAll(`.${GroupCard.className}`);
                        
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
        DragAndDrop();
        // MovingCard();
        CreateTaskForm();
        GroupButton();
        TaskButton();
        // MovingCard();
    }
})