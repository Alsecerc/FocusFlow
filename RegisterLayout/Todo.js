// Add global variables at the top of the file
let dragging = false;
let dragTarget = null;
let originalDragParent = null; // Store the original parent element during drag

// DOM loaded event listener
document.addEventListener('DOMContentLoaded', function() {
    // Hide forms and overlay by default
    const groupForm = document.querySelector('.TODO__GROUP__ADD');
    const taskForm = document.querySelector('.TODO__TASK__ADD');
    const overlay = document.querySelector('.Hiddenlayer');
    
    if (groupForm) groupForm.style.display = 'none';
    if (taskForm) taskForm.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
    
    console.log('Forms hidden by default');
    
    // Initialize the application
    initTodoApp();
});

// Function to initialize the Todo app
function initTodoApp() {
    // Set up event listeners for buttons
    setupEventListeners();
    
    // Load groups and tasks from the database
    loadGroupAndTaskByDefault();
    
    // Initialize drag and drop functionality
    initDragAndDrop();
    
    // Start countdown timers for tasks
    initCountdownTimers();
}

// Function to set up event listeners
function setupEventListeners() {
    // Group button click handler
    const groupButtons = document.querySelectorAll('.TODO__ADD');
    if (groupButtons.length >= 1) {
        groupButtons[0].addEventListener('click', function() {
            showGroupForm();
        });
    }
    
    // Task button click handler
    if (groupButtons.length >= 2) {
        groupButtons[1].addEventListener('click', function() {
            const groupCount = document.querySelectorAll('.TODO__CARD').length;
            if (groupCount > 0) {
                showTaskForm();
            } else {
                alert('Please create at least one group first');
            }
        });
    }
    
    // Close Group form button
    const closeGroupButton = document.getElementById('closeGroupAdd');
    if (closeGroupButton) {
        closeGroupButton.addEventListener('click', function() {
            hideGroupForm();
        });
    }
    
    // Group form submission
    const groupForm = document.getElementById('groupForm');
    if (groupForm) {
        groupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitGroupForm();
        });
    }
}

// Function to show the group form
function showGroupForm() {
    const groupForm = document.querySelector('.TODO__GROUP__ADD');
    const overlay = document.querySelector('.Hiddenlayer');
    
    if (groupForm && overlay) {
        groupForm.style.display = 'block';
        overlay.style.display = 'block';
        
        // Instead of disabling all pointer events, just set background to be non-interactive
        // DO NOT disable pointer events on main - that makes the form unclickable too
        
        // Focus on the input field
        const groupNameInput = document.getElementById('groupName');
        if (groupNameInput) {
            groupNameInput.focus();
        }
    }
}

// Function to hide the group form
function hideGroupForm() {
    const groupForm = document.querySelector('.TODO__GROUP__ADD');
    const overlay = document.querySelector('.Hiddenlayer');
    
    if (groupForm && overlay) {
        groupForm.style.display = 'none';
        overlay.style.display = 'none';
        
        // Reset the form
        const groupNameInput = document.getElementById('groupName');
        if (groupNameInput) {
            groupNameInput.value = '';
        }
    }
}

// Function to submit the group form
function submitGroupForm() {
    const groupNameInput = document.getElementById('groupName');
    if (!groupNameInput || !groupNameInput.value.trim()) {
        alert('Please enter a group name');
        return;
    }
    
    const groupName = groupNameInput.value.trim();
    
    // Check if the group name already exists
    if (document.getElementById(groupName)) {
        alert('A group with this name already exists');
        return;
    }
    
    // Create group in the database
    fetch('TodoBackend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            type: 'create_group',
            group_name: groupName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Create group in the UI
            createNewGroup(groupName);
            
            // Hide the form
            hideGroupForm();
        } else {
            alert('Error creating group: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error creating group:', error);
        alert('Failed to create group. Please try again.');
    });
}

// Function to create a new group in the UI
function createNewGroup(groupName) {
    // Create the group card
    const cardDiv = document.createElement('div');
    cardDiv.className = 'TODO__CARD';
    cardDiv.id = groupName;
    
    // Create the card header
    const headerDiv = document.createElement('div');
    headerDiv.className = 'TODO__HEAD';
    
    // Create the header title
    const headerTitle = document.createElement('h3');
    headerTitle.textContent = groupName;
    
    // Create the card body
    const bodyDiv = document.createElement('div');
    bodyDiv.className = 'TODO__BODY';
    
    // Add empty category placeholder
    const emptyPlaceholder = document.createElement('div');
    emptyPlaceholder.className = 'empty-category-placeholder';
    emptyPlaceholder.textContent = 'No tasks in this category';
    
    // Assemble the card
    headerDiv.appendChild(headerTitle);
    cardDiv.appendChild(headerDiv);
    bodyDiv.appendChild(emptyPlaceholder);
    cardDiv.appendChild(bodyDiv);
    
    // Add the card to the container
    const container = document.querySelector('.TODO__CONTAINER');
    if (container) {
        container.appendChild(cardDiv);
    }
    
    console.log(`Group '${groupName}' created successfully`);
}

// Function to show the task form
function showTaskForm() {
    // Create the task form if it doesn't exist
    createTaskForm();
    
    const taskForm = document.querySelector('.TODO__TASK__ADD');
    const overlay = document.querySelector('.Hiddenlayer');
    
    if (taskForm && overlay) {
        // Populate the group dropdown
        populateGroupDropdown();
        
        taskForm.style.display = 'block';
        overlay.style.display = 'block';
        
        // Do not disable pointer events on main - that makes the form unclickable
    }
}

// Function to create the task form
function createTaskForm() {
    // Check if form already exists
    if (document.querySelector('.TODO__TASK__ADD')) {
        return;
    }
    
    // Create container
    const formContainer = document.createElement('div');
    formContainer.className = 'TODO__TASK__ADD';
    formContainer.style.display = 'none';
    
    // Create form content
    formContainer.innerHTML = `
        <h2>Add New Task</h2>
        <button id="closeTaskButton">&times;</button>
        <form id="taskForm" method="post">
            <label for="taskGroup">Select Group:</label>
            <select id="taskGroup" name="taskGroup" required>
                <option value="">Select a group</option>
            </select>
            
            <label for="taskTitle">Task Title:</label>
            <input type="text" id="taskTitle" name="taskTitle" placeholder="Enter task title" required>
            
            <label for="taskContent">Task Description:</label>
            <textarea id="taskContent" name="taskContent" placeholder="Enter task description" rows="3" required></textarea>
            
            <div class="timer-container">
                <h3>Set Deadline</h3>
                <div class="timer-inputs">
                    <div class="timer-input-group">
                        <label for="timerDays">Days</label>
                        <input type="number" id="timerDays" class="TIMER__INPUT" min="0" max="30" value="0">
                    </div>
                    <div class="timer-input-group">
                        <label for="timerHours">Hours</label>
                        <input type="number" id="timerHours" class="TIMER__INPUT" min="0" max="23" value="0">
                    </div>
                    <div class="timer-input-group">
                        <label for="timerMinutes">Minutes</label>
                        <input type="number" id="timerMinutes" class="TIMER__INPUT" min="0" max="59" value="0">
                    </div>
                    <div class="timer-input-group">
                        <label for="timerSeconds">Seconds</label>
                        <input type="number" id="timerSeconds" class="TIMER__INPUT" min="0" max="59" value="0">
                    </div>
                </div>
            </div>
            
            <button type="submit">Create Task</button>
        </form>
    `;
    
    // Add to document
    document.body.appendChild(formContainer);
    
    // Create overlay if it doesn't exist
    if (!document.querySelector('.Hiddenlayer')) {
        const overlay = document.createElement('div');
        overlay.className = 'Hiddenlayer';
        overlay.style.display = 'none';
        document.body.appendChild(overlay);
    }
    
    // Set up event listeners
    setupTaskFormListeners();
}

// Function to populate the group dropdown
function populateGroupDropdown() {
    const groupSelect = document.getElementById('taskGroup');
    if (!groupSelect) return;
    
    // Clear existing options
    groupSelect.innerHTML = '<option value="">Select a group</option>';
    
    // Get all groups
    const groups = document.querySelectorAll('.TODO__CARD');
    
    // Add each group as an option
    groups.forEach(group => {
        const groupName = group.id;
        const option = document.createElement('option');
        option.value = groupName;
        option.textContent = groupName;
        groupSelect.appendChild(option);
    });
}

// Function to set up task form event listeners
function setupTaskFormListeners() {
    // Close button
    const closeTaskButton = document.getElementById('closeTaskButton');
    if (closeTaskButton) {
        closeTaskButton.addEventListener('click', hideTaskForm);
    }
    
    // Form submission
    const taskForm = document.getElementById('taskForm');
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitTaskForm();
        });
    }
}

// Function to hide the task form
function hideTaskForm() {
    const taskForm = document.querySelector('.TODO__TASK__ADD');
    const overlay = document.querySelector('.Hiddenlayer');
    
    if (taskForm && overlay) {
        taskForm.style.display = 'none';
        overlay.style.display = 'none';
        
        // Reset the form
        const taskForm = document.getElementById('taskForm');
        if (taskForm) {
            taskForm.reset();
        }
    }
}

// Function to submit the task form
function submitTaskForm() {
    // Gather form data
    const group = document.getElementById('taskGroup').value;
    const title = document.getElementById('taskTitle').value;
    const content = document.getElementById('taskContent').value;
    const days = parseInt(document.getElementById('timerDays').value) || 0;
    const hours = parseInt(document.getElementById('timerHours').value) || 0;
    const minutes = parseInt(document.getElementById('timerMinutes').value) || 0;
    const seconds = parseInt(document.getElementById('timerSeconds').value) || 0;
    
    // Validate form data
    if (!group || !title.trim() || !content.trim()) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Check if at least one time unit is set
    if (days === 0 && hours === 0 && minutes === 0 && seconds === 0) {
        alert('Please set a deadline for the task');
        return;
    }
    
    // Create task in the database
    fetch('TodoBackend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            type: 'create_task',
            category: group,
            title: title,
            content: content,
            timer: {
                days: days,
                hours: hours,
                minutes: minutes,
                seconds: seconds
            }
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Create task in the UI
            createTask(data.data);
            
            // Hide the form
            hideTaskForm();
        } else {
            alert('Error creating task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error creating task:', error);
        alert('Failed to create task. Please try again.');
    });
}

// Function to create a task in the UI
function createTask(taskData) {
    // Find the group container
    const groupCard = document.getElementById(taskData.category);
    if (!groupCard) {
        console.error(`Group ${taskData.category} not found`);
        return;
    }
    
    console.log(`Creating task "${taskData.title}" in category "${taskData.category}"`);
    
    // Create task element
    const task = document.createElement('div');
    task.className = 'TODO__TASK';
    task.draggable = true;
    task.dataset.title = taskData.title; // Add data-title attribute
    
    // Store task ID if available - this is crucial for unique identification
    if (taskData.id) {
        task.dataset.taskId = taskData.id;
        console.log(`Assigned task ID ${taskData.id} to task element`);
    }
    
    // Create task header
    const taskHead = document.createElement('div');
    taskHead.className = 'TODO__TASK__HEAD';
    
    // Add task title
    const taskTitle = document.createElement('h4');
    taskTitle.textContent = taskData.title;
    taskHead.appendChild(taskTitle);
    
    // Add countdown timer if there's an end date/time
    if (taskData.end_date && taskData.end_time) {
        const timer = document.createElement('div');
        timer.className = 'task-countdown';
        timer.textContent = 'Loading timer...';
        timer.dataset.endDate = taskData.end_date;
        timer.dataset.endTime = taskData.end_time;
        taskHead.appendChild(timer);
    }
    
    // Create task content
    const taskContent = document.createElement('div');
    taskContent.className = 'TODO__TASK__CONTENT';
    taskContent.textContent = taskData.description;
    taskContent.dataset.category = taskData.category;
    taskContent.dataset.title = taskData.title;
    
    // Create task footer
    const taskFoot = document.createElement('div');
    taskFoot.className = 'TODO__TASK__FOOT';
    
    // Add status toggle button
    const statusToggle = document.createElement('button');
    statusToggle.className = 'status-toggle';
    statusToggle.dataset.status = taskData.status || 'incomplete';
    
    // Set appropriate icon based on status
    if (taskData.status === 'complete') {
        statusToggle.textContent = '✓';
        statusToggle.title = 'Mark as Incomplete';
        task.classList.add('task-complete');
    } else if (taskData.status === 'timeout') {
        statusToggle.textContent = '⏱';
        statusToggle.title = 'Mark as Complete';
        task.classList.add('task-timeout');
    } else {
        statusToggle.textContent = '○';
        statusToggle.title = 'Mark as Complete';
    }
    
    // Add event listener for status toggle
    statusToggle.addEventListener('click', function() {
        const taskElement = this.closest('.TODO__TASK');
        const taskId = taskElement.dataset.taskId;
        
        // Create a task data object to pass to the toggle function
        const taskDataForToggle = {
            id: taskId,
            title: taskData.title,
            category: taskData.category,
            description: taskData.description
        };
        
        // Call the toggle function with correct parameters
        toggleTaskStatus(this, taskElement, taskDataForToggle);
    });
    
    // Assemble the task
    taskFoot.appendChild(statusToggle);
    task.appendChild(taskHead);
    task.appendChild(taskContent);
    task.appendChild(taskFoot);
    
    // Remove any empty placeholder if it exists
    const emptyPlaceholder = groupCard.querySelector('.empty-category-placeholder');
    if (emptyPlaceholder) {
        emptyPlaceholder.remove();
    }
    
    // Add the task to the group
    const groupBody = groupCard.querySelector('.TODO__BODY');
    if (groupBody) {
        groupBody.appendChild(task);
    }
    
    // Start the countdown timer
    updateTaskCountdown(task.querySelector('.task-countdown'));
}

// Function to toggle task status
function toggleTaskStatus(button, task, taskData) {
    // Determine the new status
    let currentStatus = button.dataset.status || 'incomplete';
    let newStatus;
    
    if (currentStatus === 'incomplete') {
        newStatus = 'complete';
    } else if (currentStatus === 'complete') {
        newStatus = 'incomplete';
    } else if (currentStatus === 'timeout') {
        newStatus = 'complete';
    }
    
    // Prepare request data
    const requestData = {
        type: 'update_task_status',
        status: newStatus
    };
    
    // Prefer task ID if available
    if (taskData.id) {
        requestData.task_id = taskData.id;
        console.log(`Using task ID ${taskData.id} for status update`);
    } else {
        // Fallback to title and category
        requestData.title = taskData.title;
        requestData.category = taskData.category;
        // Include description for better identification
        if (taskData.description) {
            requestData.description = taskData.description;
        }
    }
    
    // Update status in the database
    fetch('TodoBackend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Update status in the UI
            updateTaskStatusUI(button, newStatus);
        } else {
            alert('Error updating task status: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error updating task status:', error);
        alert('Failed to update task status. Please try again.');
    });
}

// Function to update task status in the UI
function updateTaskStatusUI(button, newStatus) {
    const task = button.closest('.TODO__TASK');
    
    // Update button dataset
    button.dataset.status = newStatus;
    
    // Remove all status classes
    task.classList.remove('task-complete', 'task-incomplete', 'task-timeout');
    
    // Add appropriate class
    task.classList.add(`task-${newStatus}`);
    
    // Update button text and title
    if (newStatus === 'complete') {
        button.textContent = '✓';
        button.title = 'Mark as Incomplete';
    } else if (newStatus === 'incomplete') {
        button.textContent = '○';
        button.title = 'Mark as Complete';
    } else if (newStatus === 'timeout') {
        button.textContent = '⏱';
        button.title = 'Mark as Complete';
    }
}

// Function to load groups and tasks by default
function loadGroupAndTaskByDefault() {
    // Show loading indicator
    const container = document.querySelector('.TODO__CONTAINER');
    if (container) {
        container.innerHTML = '<div class="loading">Loading your tasks...</div>';
    }
    
    // Hide forms and overlay at startup
    const groupForm = document.querySelector('.TODO__GROUP__ADD');
    const taskForm = document.querySelector('.TODO__TASK__ADD');
    const overlay = document.querySelector('.Hiddenlayer');
    
    if (groupForm) groupForm.style.display = 'none';
    if (taskForm) taskForm.style.display = 'none';
    if (overlay) overlay.style.display = 'none';
    
    // Fetch groups and tasks from server
    fetch('TodoBackend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            type: 'fetch_group_and_task'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Clear loading indicator
            if (container) {
                container.innerHTML = '';
            }
            
            // Create groups and tasks
            if (data.data && data.data.length > 0) {
                data.data.forEach(group => {
                    // Create group
                    createNewGroup(group.group);
                    
                    // Create tasks for this group
                    if (group.tasks && group.tasks.length > 0) {
                        group.tasks.forEach(task => {
                            createTask({
                                id: task.id, // Include task ID
                                category: group.group,
                                title: task.title,
                                description: task.description,
                                status: task.status,
                                end_date: task.end_date,
                                end_time: task.end_time
                            });
                        });
                    }
                });
            } else {
                // No groups found
                if (container) {
                    container.innerHTML = '<div class="no-groups">No groups found. Click the "Group" button to create one.</div>';
                }
            }
        } else {
            console.error('Error loading groups and tasks:', data.error);
            if (container) {
                container.innerHTML = '<div class="error">Failed to load your tasks. Please refresh the page to try again.</div>';
            }
        }
    })
    .catch(error => {
        console.error('Error loading groups and tasks:', error);
        if (container) {
            container.innerHTML = '<div class="error">Failed to load your tasks. Please refresh the page to try again.</div>';
        }
    });
}

// Function to initialize drag and drop functionality
function initDragAndDrop() {
    // Global drag event handlers
    document.addEventListener('dragstart', (e) => {
        if (!e.target.classList.contains('TODO__TASK')) return;
        
        console.log('Global dragstart captured');
        dragging = true;
        dragTarget = e.target;
        
        // Add dragging class
        e.target.classList.add('is-dragging');
        
        // Store the original parent when drag starts
        originalDragParent = e.target.closest('.TODO__CARD');
        
        if (originalDragParent) {
            console.log('Drag started from category:', originalDragParent.id);
        } else {
            console.error('Could not find parent card for dragged element');
        }
    });
    
    document.addEventListener('dragover', (e) => {
        e.preventDefault();
        if (!dragging) return;
        
        // Find the nearest card
        const card = e.target.closest('.TODO__CARD');
        if (card) {
            // Highlight drop target
            card.classList.add('drag-highlight');
        }
    });
    
    document.addEventListener('dragleave', (e) => {
        // Remove highlight from cards
        document.querySelectorAll('.drag-highlight').forEach(card => {
            card.classList.remove('drag-highlight');
        });
    });
    
    document.addEventListener('drop', (e) => {
        e.preventDefault();
        handleDrop(e);
        
        // Remove highlight and reset variables
        document.querySelectorAll('.drag-highlight').forEach(card => {
            card.classList.remove('drag-highlight');
        });
        
        dragging = false;
    });
    
    document.addEventListener('dragend', () => {
        if (dragTarget) {
            dragTarget.classList.remove('is-dragging');
        }
        
        // Remove highlight and reset variables
        document.querySelectorAll('.drag-highlight').forEach(card => {
            card.classList.remove('drag-highlight');
        });
        
        dragging = false;
        dragTarget = null;
    });
}

// Function to handle dropping tasks
function handleDrop(e) {
    e.preventDefault();
    
    // Get the dropped element and target container
    const draggedTask = document.querySelector('.is-dragging');
    const targetCard = e.target.closest('.TODO__CARD');
    
    if (!draggedTask || !targetCard) {
        console.error('Missing dragged task or target card');
        return;
    }
    
    // Get the original parent category if it exists, or try to determine it from the data
    if (!originalDragParent) {
        console.warn('Original drag parent not set, attempting to recover from task data');
        // Attempt to find original category from task data attributes if possible
        const taskContent = draggedTask.querySelector('.TODO__TASK__CONTENT');
        if (taskContent && taskContent.dataset.category) {
            console.log('Recovered original category:', taskContent.dataset.category);
            const origCategoryId = taskContent.dataset.category;
            originalDragParent = document.getElementById(origCategoryId);
        }
    }
    
    if (!originalDragParent) {
        console.error('Could not determine original category for task');
        return;
    }
    
    // Get the original and target category names
    const originalCategory = originalDragParent.id;
    const targetCategory = targetCard.id;
    
    console.log(`Moving task from "${originalCategory}" to "${targetCategory}"`);
    
    if (originalCategory === targetCategory) {
        console.log('Task dropped in the same category, no backend update needed');
        // Still need to position the task appropriately within the category
        // Find the nearest task to insert before/after
        insertTaskAtDropPosition(e, draggedTask, targetCard);
        draggedTask.classList.remove('is-dragging');
        return;
    }
    
    // Extract the task title from the heading
    const taskTitle = draggedTask.querySelector('.TODO__TASK__HEAD h4').innerText;
    
    // Get task content description for better identification
    const taskContent = draggedTask.querySelector('.TODO__TASK__CONTENT');
    const taskDescription = taskContent ? taskContent.textContent : '';
    
    // Get task ID if available (preferred method of identification)
    const taskId = draggedTask.dataset.taskId;
    
    console.log(`Moving task "${taskTitle}" from "${originalCategory}" to "${targetCategory}"`);
    
    // Move the task to the target container at the appropriate position
    insertTaskAtDropPosition(e, draggedTask, targetCard);
    
    // Prepare the request data
    const requestData = {
        type: 'move_task',
        oldCategory: originalCategory,
        newCategory: targetCategory,
        title: taskTitle
    };
    
    // Use task ID if available (more precise)
    if (taskId) {
        requestData.task_id = taskId;
        console.log(`Using task ID ${taskId} for precise movement`);
    } else {
        // Include description for better identification if no ID available
        if (taskDescription) {
            requestData.description = taskDescription;
            console.log(`No task ID available, using description for identification`);
        }
    }
    
    // Update the task's category in the database
    fetch('TodoBackend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Server responded with status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Server response:', data);
        
        if (data.status === 'success') {
            console.log('Task moved successfully in database');
            
            // If the original category is now empty (no tasks left), add empty placeholder
            if (data.data && data.data.categoryNowEmpty) {
                addEmptyPlaceholder(originalDragParent);
            }
            
            // Remove empty placeholder if the target category had one
            const emptyPlaceholder = targetCard.querySelector('.empty-category-placeholder');
            if (emptyPlaceholder) {
                emptyPlaceholder.remove();
            }
            
            // Update any data attributes or task content if needed
            if (taskContent && taskContent.dataset) {
                taskContent.dataset.category = targetCategory;
            }
        } else {
            console.error('Failed to move task in database:', data.error);
            
            // If the move failed in the database, move the task back to original container
            originalDragParent.querySelector('.TODO__BODY').appendChild(draggedTask);
        }
        
        // Remove the dragging class
        draggedTask.classList.remove('is-dragging');
        
        // Reset global variables
        originalDragParent = null;
    })
    .catch(error => {
        console.error('Error moving task:', error);
        
        // If there was an error, move the task back to original container
        originalDragParent.querySelector('.TODO__BODY').appendChild(draggedTask);
        draggedTask.classList.remove('is-dragging');
        
        // Reset global variables
        originalDragParent = null;
    });
}

// Helper function to insert a task at the correct position based on drop location
function insertTaskAtDropPosition(e, draggedTask, targetCard) {
    const targetBody = targetCard.querySelector('.TODO__BODY');
    const tasks = targetBody.querySelectorAll('.TODO__TASK:not(.is-dragging)');
    
    // Check if we have any other tasks to position around
    if (tasks.length === 0) {
        // If no other tasks, just append
        targetBody.appendChild(draggedTask);
        return;
    }
    
    // Get mouse position
    const mouseY = e.clientY;
    
    // Find the closest task to insert near
    let closestTask = null;
    let closestDistance = Number.POSITIVE_INFINITY;
    let insertAfter = false;
    
    tasks.forEach(task => {
        const taskRect = task.getBoundingClientRect();
        const taskMiddle = taskRect.top + taskRect.height / 2;
        const distance = Math.abs(mouseY - taskMiddle);
        
        if (distance < closestDistance) {
            closestDistance = distance;
            closestTask = task;
            insertAfter = mouseY > taskMiddle;
        }
    });
    
    // Insert before or after the closest task
    if (insertAfter) {
        closestTask.after(draggedTask);
    } else {
        closestTask.before(draggedTask);
    }
    
    console.log(`Task inserted ${insertAfter ? 'after' : 'before'} nearest task`);
}

// Helper function to add empty category placeholder
function addEmptyPlaceholder(categoryCard) {
    const body = categoryCard.querySelector('.TODO__BODY');
    if (!body.querySelector('.empty-category-placeholder')) {
        const placeholder = document.createElement('div');
        placeholder.className = 'empty-category-placeholder';
        placeholder.textContent = 'No tasks in this category';
        body.appendChild(placeholder);
    }
}

// Function to initialize countdowns for all tasks
function initCountdownTimers() {
    // Start interval to update all countdowns
    setInterval(function() {
        document.querySelectorAll('.task-countdown').forEach(updateTaskCountdown);
    }, 1000);
}

// Function to update a task countdown
function updateTaskCountdown(timerElement) {
    if (!timerElement || !timerElement.dataset.endDate || !timerElement.dataset.endTime) {
        return;
    }
    
    // If the parent task is complete, display "Completed"
    const task = timerElement.closest('.TODO__TASK');
    if (task && task.classList.contains('task-complete')) {
        timerElement.textContent = 'Completed';
        timerElement.classList.remove('time-warning', 'time-urgent', 'time-expired');
        return;
    }
    
    // Calculate time remaining
    const endDateStr = timerElement.dataset.endDate;
    const endTimeStr = timerElement.dataset.endTime;
    const endDateTime = new Date(`${endDateStr}T${endTimeStr}`);
    const now = new Date();
    
    // Calculate difference in milliseconds
    let diff = endDateTime - now;
    
    // Handle expired tasks
    if (diff <= 0) {
        timerElement.textContent = 'Expired';
        timerElement.classList.add('time-expired');
        
        // Set task status to timeout if not already complete
        if (task && !task.classList.contains('task-complete')) {
            task.classList.add('task-timeout');
            
            // Get task data for database update
            const taskContent = task.querySelector('.TODO__TASK__CONTENT');
            const statusButton = task.querySelector('.status-toggle');
            
            // Prepare request data
            const requestData = {
                type: 'update_task_status',
                status: 'timeout'
            };
            
            // Prefer using task ID if available
            const taskId = task.dataset.taskId;
            if (taskId) {
                requestData.task_id = taskId;
            } else if (taskContent && taskContent.dataset.title && taskContent.dataset.category) {
                // Fallback to title and category
                requestData.title = taskContent.dataset.title;
                requestData.category = taskContent.dataset.category;
                // Include description for better identification
                if (taskContent.textContent) {
                    requestData.description = taskContent.textContent;
                }
            }
            
            // Update status in database
            fetch('TodoBackend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update button
                    if (statusButton) {
                        updateTaskStatusUI(statusButton, 'timeout');
                    }
                }
            })
            .catch(error => console.error('Error updating task status:', error));
        }
        
        return;
    }
    
    // Calculate days, hours, minutes, seconds
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    diff -= days * (1000 * 60 * 60 * 24);
    
    const hours = Math.floor(diff / (1000 * 60 * 60));
    diff -= hours * (1000 * 60 * 60);
    
    const minutes = Math.floor(diff / (1000 * 60));
    diff -= minutes * (1000 * 60);
    
    const seconds = Math.floor(diff / 1000);
    
    // Display the biggest unit
    let timeText = '';
    if (days > 0) {
        timeText = `${days} day${days > 1 ? 's' : ''} left`;
    } else if (hours > 0) {
        timeText = `${hours} hour${hours > 1 ? 's' : ''} left`;
    } else if (minutes > 0) {
        timeText = `${minutes} minute${minutes > 1 ? 's' : ''} left`;
    } else {
        timeText = `${seconds} second${seconds > 1 ? 's' : ''} left`;
    }
    
    // Apply warning classes based on time left
    timerElement.classList.remove('time-warning', 'time-urgent', 'time-expired');
    
    if (days === 0) {
        if (hours === 0 && minutes < 30) {
            timerElement.classList.add('time-urgent');
        } else if (hours < 2) {
            timerElement.classList.add('time-warning');
        }
    }
    
    timerElement.textContent = timeText;
}

// Function to delete a task
function deleteTask(taskElement) {
    if (!taskElement) return;
    
    // Get task details
    const title = taskElement.querySelector('.TODO__TASK__HEAD h4').textContent;
    const categoryCard = taskElement.closest('.TODO__CARD');
    const category = categoryCard ? categoryCard.id : null;
    
    if (!title || !category) {
        console.error('Missing task title or category');
        return;
    }
    
    // Prepare request data
    const requestData = {
        type: 'delete_task',
        title: title,
        category: category
    };
    
    // If task has an ID, use it for more precise deletion
    const taskId = taskElement.dataset.taskId;
    if (taskId) {
        requestData.task_id = taskId;
        console.log(`Using task ID ${taskId} for precise deletion`);
    } else {
        // Include description for better identification
        const description = taskElement.querySelector('.TODO__TASK__CONTENT').textContent;
        if (description) {
            requestData.description = description;
        }
    }
    
    // Confirm deletion
    if (!confirm(`Are you sure you want to delete the task "${title}"?`)) {
        return;
    }
    
    // Send delete request
    fetch('TodoBackend.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Remove task from UI
            taskElement.remove();
            
            // Check if category is now empty
            if (data.data && data.data.categoryNowEmpty) {
                addEmptyPlaceholder(categoryCard);
            }
        } else {
            alert('Error deleting task: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error deleting task:', error);
        alert('Failed to delete task. Please try again.');
    });
}
