// Additional fixes for drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    // Only run this script on pages that include todo functionality
    if (window.location.href.indexOf('todo') === -1) return;
    
    console.log('Drag fix script loaded');
    
    // Firefox compatibility fix for drag and drop
    document.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('TODO__TASK')) {
            // Set data for Firefox (required for drag to work)
            e.dataTransfer.setData('text/plain', 'dragging');
        }
    });
    
    // Handle empty categories
    handleEmptyCategories();
    
    // Watch for content changes
    observeDragChanges();
});

// Handle empty categories by adding placeholders
function handleEmptyCategories() {
    const categories = document.querySelectorAll('.TODO__CARD');
    
    categories.forEach(category => {
        const body = category.querySelector('.TODO__BODY');
        const tasks = body.querySelectorAll('.TODO__TASK');
        
        if (tasks.length === 0 && !body.querySelector('.empty-category-placeholder')) {
            addEmptyPlaceholder(category);
        }
        
        // Make the category a valid drop target
        category.addEventListener('dragover', function(e) {
            e.preventDefault();
            category.classList.add('drag-highlight');
        });
        
        category.addEventListener('dragleave', function() {
            category.classList.remove('drag-highlight');
        });
        
        // Handle drops directly on the category
        category.addEventListener('drop', function(e) {
            e.preventDefault();
            category.classList.remove('drag-highlight');
            
            const draggedTask = document.querySelector('.is-dragging');
            if (!draggedTask) return;
            
            // Move the task to this category
            if (typeof insertTaskAtDropPosition === 'function') {
                // Use the new positioning function if available
                insertTaskAtDropPosition(e, draggedTask, category);
            } else {
                // Fall back to the old method if function not available
                body.appendChild(draggedTask);
            }
            
            // Remove the placeholder if it exists
            const placeholder = body.querySelector('.empty-category-placeholder');
            if (placeholder) {
                placeholder.remove();
            }
            
            // Get the original parent
            const originalParent = window.originalDragParent;
            if (!originalParent) return;
            
            // Get category names - use IDs instead of innerText for more reliable identification
            const originalCategory = originalParent.id;
            const targetCategory = category.id;
            
            // Skip if same category
            if (originalCategory === targetCategory) return;
            
            // Get task title
            const taskTitle = draggedTask.querySelector('.TODO__TASK__HEAD h4').innerText;
            
            // Get task content for better identification
            const taskContent = draggedTask.querySelector('.TODO__TASK__CONTENT');
            const taskDescription = taskContent ? taskContent.textContent : '';
            
            // Get task ID if available (preferred method)
            const taskId = draggedTask.dataset.taskId;
            
            console.log(`Fixing drop: Moving task "${taskTitle}" from "${originalCategory}" to "${targetCategory}"`);
            
            // Prepare request data
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
            } else if (taskDescription) {
                // Include description for better identification if no ID available
                requestData.description = taskDescription;
            }
            
            // Update the database
            fetch('TodoBackend.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Server response:', data);
                
                if (data.status === 'success') {
                    console.log('Task moved successfully');
                    
                    // Update task data attributes
                    if (taskContent) {
                        taskContent.dataset.category = targetCategory;
                        console.log(`Updated task data-category to: ${targetCategory}`);
                    }
                    
                    // Check if original category is now empty
                    if (data.data && data.data.categoryNowEmpty) {
                        addEmptyPlaceholder(originalParent);
                    }
                } else {
                    console.error('Error from server:', data.error);
                    // Move task back if there was an error
                    if (originalParent && originalParent.querySelector('.TODO__BODY')) {
                        originalParent.querySelector('.TODO__BODY').appendChild(draggedTask);
                    }
                }
            })
            .catch(error => {
                console.error('Error moving task:', error);
                // Move task back if there was an error
                if (originalParent && originalParent.querySelector('.TODO__BODY')) {
                    originalParent.querySelector('.TODO__BODY').appendChild(draggedTask);
                }
            });
        });
    });
}

// Add empty placeholder to a category
function addEmptyPlaceholder(category) {
    const body = category.querySelector('.TODO__BODY');
    if (!body) return;
    
    if (!body.querySelector('.empty-category-placeholder')) {
        const placeholder = document.createElement('div');
        placeholder.className = 'empty-category-placeholder';
        placeholder.textContent = 'No tasks in this category';
        body.appendChild(placeholder);
    }
}

// Observe DOM for changes to apply fixes
function observeDragChanges() {
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length || mutation.removedNodes.length) {
                // Check for newly added categories or removed tasks
                handleEmptyCategories();
                
                // Make sure status toggles are working
                ensureStatusTogglesAreVisible();
            }
        });
    });
    
    // Start observing document for changes
    observer.observe(document.querySelector('.TODO__CONTAINER') || document.body, {
        childList: true,
        subtree: true
    });
}

// Ensure status toggles are visible and functional
function ensureStatusTogglesAreVisible() {
    document.querySelectorAll('.TODO__TASK').forEach(task => {
        // Check if task already has a status toggle
        let statusToggle = task.querySelector('.status-toggle');
        
        if (!statusToggle) {
            // Create one if missing
            const taskFoot = task.querySelector('.TODO__TASK__FOOT');
            if (!taskFoot) {
                // Create task footer if missing
                const taskFoot = document.createElement('div');
                taskFoot.className = 'TODO__TASK__FOOT';
                task.appendChild(taskFoot);
            }
            
            statusToggle = document.createElement('button');
            statusToggle.className = 'status-toggle';
            
            // Determine status based on task classes
            let status = 'incomplete';
            if (task.classList.contains('task-complete')) {
                status = 'complete';
            } else if (task.classList.contains('task-timeout')) {
                status = 'timeout';
            }
            
            statusToggle.dataset.status = status;
            
            // Set appropriate icon
            if (status === 'complete') {
                statusToggle.textContent = '✓';
                statusToggle.title = 'Mark as Incomplete';
            } else if (status === 'timeout') {
                statusToggle.textContent = '⏱';
                statusToggle.title = 'Mark as Complete';
            } else {
                statusToggle.textContent = '○';
                statusToggle.title = 'Mark as Complete';
            }
            
            // Add event listener
            statusToggle.addEventListener('click', function() {
                const taskTitle = task.querySelector('.TODO__TASK__HEAD h4').textContent;
                const taskContent = task.querySelector('.TODO__TASK__CONTENT');
                const category = taskContent.dataset.category || task.closest('.TODO__CARD').id;
                const taskId = task.dataset.taskId; // Get task ID if available
                
                if (typeof toggleTaskStatus === 'function') {
                    toggleTaskStatus(taskTitle, category, this);
                } else {
                    // If toggleTaskStatus is not available, update directly
                    let currentStatus = this.dataset.status || 'incomplete';
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
                    
                    // Prefer using task ID if available
                    if (taskId) {
                        requestData.task_id = taskId;
                    } else {
                        // Fallback to title and category
                        requestData.title = taskTitle;
                        requestData.category = category;
                        // Include description to help identify the specific task
                        const description = taskContent.textContent;
                        if (description) {
                            requestData.description = description;
                        }
                    }
                    
                    fetch('TodoBackend.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(requestData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Update UI
                            if (newStatus === 'complete') {
                                this.textContent = '✓';
                                this.title = 'Mark as Incomplete';
                                task.classList.remove('task-incomplete', 'task-timeout');
                                task.classList.add('task-complete');
                            } else if (newStatus === 'incomplete') {
                                this.textContent = '○';
                                this.title = 'Mark as Complete';
                                task.classList.remove('task-complete', 'task-timeout');
                                task.classList.add('task-incomplete');
                            } else if (newStatus === 'timeout') {
                                this.textContent = '⏱';
                                this.title = 'Mark as Complete';
                                task.classList.remove('task-complete', 'task-incomplete');
                                task.classList.add('task-timeout');
                            }
                            this.dataset.status = newStatus;
                        }
                    })
                    .catch(error => console.error('Error updating task status:', error));
                }
            });
            
            // Append to footer
            taskFoot.appendChild(statusToggle);
        }
        
        // Ensure it's visible
        statusToggle.style.opacity = '1';
        statusToggle.style.visibility = 'visible';
    });
}

// Fix for dragend event to recalculate task statuses
document.addEventListener('dragend', function() {
    // Check expired tasks after dragging
    document.querySelectorAll('.task-countdown').forEach(function(timer) {
        if (!timer.dataset.endDate || !timer.dataset.endTime) return;
        
        const endDateTime = new Date(`${timer.dataset.endDate}T${timer.dataset.endTime}`);
        const now = new Date();
        
        if (endDateTime < now) {
            const task = timer.closest('.TODO__TASK');
            if (task && !task.classList.contains('task-complete')) {
                task.classList.add('task-timeout');
                timer.textContent = 'Expired';
                timer.classList.add('time-expired');
                
                // Update in database
                const taskTitle = task.querySelector('.TODO__TASK__HEAD h4').textContent;
                const taskContent = task.querySelector('.TODO__TASK__CONTENT');
                const category = taskContent.dataset.category || task.closest('.TODO__CARD').id;
                const taskId = task.dataset.taskId; // Get task ID if available
                
                // Prepare request data
                const requestData = {
                    type: 'update_task_status',
                    status: 'timeout'
                };
                
                // Prefer using task ID if available
                if (taskId) {
                    requestData.task_id = taskId;
                } else {
                    // Fallback to title and category
                    requestData.title = taskTitle;
                    requestData.category = category;
                }
                
                fetch('TodoBackend.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(requestData)
                }).catch(error => console.error('Error updating expired task:', error));
            }
        }
    });
});

// Fix for window resize events
window.addEventListener('resize', function() {
    const draggingTask = document.querySelector('.is-dragging');
    if (draggingTask) {
        // Adjust position for the resized window
        draggingTask.style.position = 'relative';
        
        // Remove and reapply dragging class to reset position
        draggingTask.classList.remove('is-dragging');
        setTimeout(() => draggingTask.classList.add('is-dragging'), 0);
    }
}); 