function createNewGroup(NameOfContainer, ClassName, headerTag, headerClassName, Content, paragraphTag, paragraphClassName, paragraphContent) {
    console.log("Creating group");
    let container = document.querySelector(`.${NameOfContainer}`);
    if (!container) {
        console.error(`Container with ${NameOfContainer} not found`);
        return;
    }
    const newGroupCard = document.createElement('div');
    newGroupCard.className = ClassName;
    newGroupCard.draggable = 'true';

    const header = document.createElement(headerTag);
    header.className = headerClassName;
    header.textContent = Content;

    const paragraph = document.createElement(paragraphTag)
    paragraph.className = paragraphClassName;
    paragraph.textContent = paragraphContent.length > 500 ? paragraphContent.substring(0, 500) + "..." : paragraphContent;

    newGroupCard.appendChild(header);
    newGroupCard.appendChild(paragraph);

    container.appendChild(newGroupCard);
    console.log("Created group");
}

function CreateNewTask(NameOfContainer, ClassName, paragraphTag, paragraphClassName, paragraphContent) {

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

    const label = document.createElement('label');
    label.setAttribute('for', 'Group');

    const selection = document.createElement('select');
    selection.id = 'GROUP__NAME__TASK';

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
        observer.observe(document.body, { childList: true, subtree: true }); // Resume observing
    }

    UpdateSelection();
    const userinput = document.createElement('input');
    userinput.id = 'taskContent';
    userinput.type = 'text';
    userinput.placeholder = 'Enter the task';
    userinput.required = 'true';
    userinput.id = 'taskContent';

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
    observer.observe(document.body, { childList: true, subtree: true });

    console.log("MutationObserver started: Watching for changes...");
}

function GetGroupName() {
    const groupNameInput = document.getElementById("groupName");
    return groupNameInput.value;
}

document.addEventListener('DOMContentLoaded', function () { // only active the code when it is on the specific file
    if (window.location.pathname.toLowerCase().includes('todo')) {
        console.log("Todo page activated");


        // Get all buttons with the class "TODO__ADD"
        const buttons = document.querySelectorAll(".TODO__ADD");

        // Convert NodeList to an array for easier handling (optional)
        const buttonArray = Array.from(buttons);

        // Find the button that has "Group" as its text content
        const groupButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Group"));

        // Find the button that has "Task" as its text content
        const taskButton = buttonArray.find(btn => btn.textContent.trim().startsWith("Task"));

        // createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', 'To Do', 'p', 'TODO__TASK', 'Get grocery');

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
                                createNewGroup('TODO__CONTAINER', 'TODO__CARD', 'h3', 'TODO__CARD_HEADER', groupName, 'p', 'TODO__TASK', 'Get grocery');
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

        CreateTaskForm();
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
                                console.log(groupChoice.value);
                                console.log(taskContent.value);
                                AddTask.style.display = 'none';
                                overlay.style.display = 'none';
                            }
                        });
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
})