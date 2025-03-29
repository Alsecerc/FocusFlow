function toggleForm() {
    const form = document.querySelector(".task-form");
    const button = document.querySelector(".toggle-btn");

    if (form.classList.contains("show")) {
        form.classList.remove("show");
        setTimeout(() => {
            form.style.display = "none";
        }, 300); // Matches CSS transition duration
        
        button.innerText = "Create Task";
        button.style.backgroundColor = "#6FCF97"; 
    } else {
        form.style.display = "flex";
        setTimeout(() => {
            form.classList.add("show");
        }, 10); // Slight delay for smooth transition
        
        button.innerText = "Close Form";
        button.style.backgroundColor = "#DC6666"; 
    }
}


document.addEventListener("DOMContentLoaded", fetchUsers);

async function fetchUsers() {
    let formData = new FormData();
    formData.append("action", "fetch_user");

    try {
        let response = await fetch("TaskManagementBackend.php", {
            method: "POST",
            body: formData
        });

        let data = await response.json();

        // convert object into array 
        users = data.users || [];
        let select = document.getElementById("assigned_to");

        users.forEach(user => {
            let option = document.createElement("option");
            option.value = user.id;
            option.textContent = "ID:" + user.id + " ) " + user.name;
            select.appendChild(option);
        });
    } catch (error) {
        console.error("Failed to load users:", error);
    }
}

document.getElementById("createTaskForm").addEventListener("submit", async function (event) {
    event.preventDefault(); // Prevent page reload

    let taskTitle = document.getElementById("task_title").value.trim();
    let category = document.getElementById("category").value.trim();
    let taskDesc = document.getElementById("task_desc").value.trim();
    let assignedTo = document.getElementById("assigned_to").value;
    let dueDate = document.getElementById("due_date").value;

    let errorMessage = "";

    if (!taskTitle) errorMessage = "Task title is required.";
    else if (!taskDesc) errorMessage = "Task description is required.";
    else if (!category) errorMessage = "Task Category is required.";
    else if (!assignedTo) errorMessage = "Please assign the task to a user.";
    else if (!dueDate) errorMessage = "Due date is required.";
    else if (new Date(dueDate) < new Date()) errorMessage = "Due date must be in the future.";

    if (errorMessage) {
        alert(errorMessage);
        return;
    }

    // Prepare form data
    let formData = new FormData();
    formData.append("action", "createTask");
    formData.append("task_title", taskTitle);
    formData.append("category", category);
    formData.append("task_desc", taskDesc);
    formData.append("assigned_to", assignedTo);
    formData.append("due_date", dueDate);

    try {
        let response = await fetch("TaskManagementBackend.php", {
            method: "POST",
            body: formData
        });

        let result = await response.json();
        alert(result.message);

        if (result.success) {
            document.getElementById("createTaskForm").reset();
        }
    } catch (error) {
        console.error("Failed to create task:", error);
        alert("An error occurred while creating the task.");
    }
});


function fetchTasks(type) {
    let rows = document.querySelectorAll("#taskTableBody tr");
    let teamColumn = document.getElementById("teamColumn");
    let teamCells = document.querySelectorAll(".teamColumn"); // Select all Team Name cells

    if (!teamColumn) {
        console.error("teamColumn not found in the document.");
        return;
    }

    if (type === "team") {
        rows.forEach(row => row.style.display = row.dataset.type === "team" ? "" : "none");
        teamColumn.style.display = "table-cell"; // Show header
        teamCells.forEach(cell => cell.style.display = "table-cell");
    } else {
        rows.forEach(row => row.style.display = row.dataset.type === "individual" ? "" : "none");
        teamColumn.style.display = "none";
        teamCells.forEach(cell => cell.style.display = "none");
    }
}

function showTaskDetails(taskData) {
    const taskWidget = document.querySelector(".TASK__WIDGET.three");

    async function getUserName(userId) {
        if (!userId) return "";

        let formData = new FormData();
        formData.append("user_id", userId);
        formData.append("action", "getDetails");

        let response = await fetch("TaskManagementBackend.php", {
            method: "POST",
            body: formData
        });

        let data = await response.json();
        return data.name || "Unknown";
    }




    async function displayTaskDetails(task) {

        let assignedBy = await getUserName(task.assigned_by);

        // ! convert it to boolean second ! convert it back to the right value
        let isTeamTask = !!task.team_name;

        let assignedTo = (isTeamTask && task.assigned_to && !isNaN(task.assigned_to))
            ? await getUserName(Number(task.assigned_to))
            : task.assigned_to;

        let taskHtml = "";

        taskHtml += `<h3>${isTeamTask ? "Team" : "Individual"} Task Details</h3>`;

        if (task.team_name) {
            // Group Task Layout
            taskHtml += `
            <div class="TASK__DETAILS">
                <p><input type="text" name="taskTitle" id="taskTitle" 
                    placeholder="${task.task_name}" value="${task.task_name}"></p>
                <table class="DETAILS__TABLE">
                    <tr>
                        <th>Description : </th>
                        <td class="TASK__INDI__DESC">
                            <input type="text" name="taskDesc" id="taskDesc" 
                                placeholder="${task.task_description}" value="${task.task_description}">
                        </td>
                    </tr>
                    <tr class="TASK__INDI__STATUS">
                        <th>Status : </th>
                        <td>
                            <select id="taskStatus">
                                <option value="pending" ${task.status === "pending" ? "selected" : ""}>Pending</option>
                                <option value="in progress" ${task.status === "in progress" ? "selected" : ""}>In Progress</option>
                                <option value="completed" ${task.status === "completed" ? "selected" : ""}>Completed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Team : </th>
                        <td>${task.team_name || "N/A"}</td>
                    </tr>
                    <tr>
                        <th>Assign By : </th>
                        <td>${assignedBy}</td>
                    </tr>
                    <tr>
                        <th>Assign To : </th>
                        <td>${assignedTo}</td>
                    </tr>
                    <tr>
                        <th>Due Date : </th>
                        <td><input type="date" id="taskDueDate" 
                            value="${task.due_date}" placeholder="${task.due_date}"></td>
                    </tr>
                </table>
            </div>
        `;
        } else {
            taskHtml += `
            <div class="TASK__DETAILS">
                <p class="wrap-text">
                    <input type="text" name="taskTitle" id="taskTitle" 
                        placeholder="${task.task_name}" value="${task.task_name}">
                </p>
                
                <table class="DETAILS__TABLE">
                    <tr>
                        <th>Category : </th>
                        <td>
                            <input type="text" name="taskCat" id="taskCat" 
                                placeholder="${task.assigned_to}" value="${task.assigned_to}">
                        </td>
                    </tr>
                    <tr>
                        <th>Description : </th>
                        <td class="TASK__INDI__DESC">
                            <input type="text" name="taskDesc" id="taskDesc" 
                                placeholder="${task.task_description}" value="${task.task_description}">
                        </td>
                    </tr>
                    <tr class="TASK__INDI__STATUS">
                        <th>Status : </th>
                        <td>
                            <select id="taskStatus">
                                <option value="Timeout" ${task.status === "Timeout" ? "selected" : ""}>Timeout</option>
                                <option value="Incomplete" ${task.status === "Incomplete" ? "selected" : ""}>Incomplete</option>
                                <option value="Complete" ${task.status === "Complete" ? "selected" : ""}>Completed</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Assigned By : </th>
                        <td>${assignedBy}</td>
                    </tr>
                    <tr>
                        <th>Due Date : </th>
                        <td><input type="date" id="taskDueDate" 
                            value="${task.due_date}" placeholder="${task.due_date}"></td>
                    </tr>
                </table>
            </div>
        `;
        }
        taskHtml += `
        <div class="TASK__DETAILS__BUTTON">
        <button class="L-CLR" id="saveTask">Update</button>
        <button class="L-RED"id="deleteTask">Delete</button>
        </div>
        `
        taskWidget.innerHTML = taskHtml;

        document.getElementById("saveTask").addEventListener("click", () => updateTask(task.id, isTeamTask, task.assigned_by));
        document.getElementById("deleteTask").addEventListener("click", () => deleteTask(task.id));
    }

    displayTaskDetails(taskData);
}

document.querySelectorAll(".taskTableBody tr").forEach(row => {
    row.addEventListener("click", function () {
        const taskData = JSON.parse(this.getAttribute("data-task"));

        showTaskDetails(taskData)

        setTimeout(() => {
            const statusCell = document.querySelector(".TASK__INDI__STATUS td"); // Get updated status
            if (!statusCell) return;

            const statusText = statusCell.textContent.trim().toLowerCase();


            statusCell.classList.remove("status-warning", "status-info", "status-success", "status-error");


            if (["pending", "in progress", "completed"].includes(statusText)) {
                statusCell.classList.add("team-status");
            } else if (["incomplete", "complete", "timeout"].includes(statusText)) {
                statusCell.classList.add("individual-status");
            }


            if (statusText === "incomplete" || statusText === "in progress") {
                statusCell.classList.add("L-YEL");
            } else if (statusText === "completed" || statusText === "complete") {
                statusCell.classList.add("L-GRE");
            } else if (statusText === "pending" || statusText === "timeout") {
                statusCell.classList.add("L-RED");
            }
        }, 50);
    });
});

function updateTask(taskId, isTeamTask, assignTo) {

    const updatedTask = {
        action: "updateTask",
        task_id: taskId,
        task_name: document.getElementById("taskTitle").value,
        task_category: (!isTeamTask ? document.getElementById("taskCat").value : ""),
        task_description: document.getElementById("taskDesc").value,
        status: document.getElementById("taskStatus").value,
        due_date: document.getElementById("taskDueDate").value,
        is_team_task: isTeamTask,
        assignTo: assignTo
    };


    fetch("TaskManagementBackend.php", {
        method: "POST",
        body: new URLSearchParams(updatedTask),
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        });
}

function deleteTask(taskId) {
    if (!confirm("Are you sure you want to delete this task?")) return;

    fetch("TaskManagementBackend.php", {
        method: "POST",
        body: new URLSearchParams({ action: "deleteTask", task_id: taskId }),
    })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.success) {
                location.reload();
            }
        });
}

function showAlert(message) {
    document.getElementById("alertMessage").innerText = message;
    document.getElementById("customAlert").classList.add("show-alert");
}

function closeAlert() {
    const alertBox = document.getElementById("customAlert");
    alertBox.classList.remove("show-alert");
}
