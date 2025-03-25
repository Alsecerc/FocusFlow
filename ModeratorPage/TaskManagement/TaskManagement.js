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

        let response = await fetch("TaskManagementBackend.php", {
            method: "POST",
            body: formData
        });

        let data = await response.json();
        return data.name || "";
    }




    async function displayTaskDetails(task) {
        let assignedBy = await getUserName(task.assigned_by);
        let assignedTo = await getUserName(task.assigned_to);

        let taskHtml = "";

        if (task.team_name) {

            taskHtml += `<h3>Team Task Details</h3>`;
            // Group Task Layout
            taskHtml += `
                <div class="TASK__DETAILS">
                    <p><strong>Task Name:</strong> ${task.task_name}</p>
                    <table class="DETAILS__TABLE">
                        <tr>
                            <th>Description</th>
                            <td>${task.task_description}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>${task.status}</td>
                        </tr>
                        <tr>
                            <th>Team Name</th>
                            <td>${task.team_name || "N/A"}</td>
                        </tr>
                        <tr>
                            <th>Username</th>
                            <td>${assignedBy}</td>
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            <td>${task.due_date}</td>
                        </tr>
                    </table>
                </div>
            `;
        } else {
            taskHtml += `<h3>Individual Task Details</h3>`;
            taskHtml += `
                <div class="TASK__DETAILS">
                    <p><strong>Task Name:</strong><br> ${task.task_name}</p>
                    <table class="DETAILS__TABLE">
                        <tr>
                            <th>Description</th>
                            <td>${task.task_description}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>${task.status}</td>
                        </tr>
                        <tr>
                            <th>Assigned By</th>
                            <td>${assignedBy}</td>
                        </tr>
                        <tr>
                            <th>Assigned To</th>
                            <td>${assignedTo}</td>
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            <td>${task.due_date}</td>
                        </tr>
                    </table>
                </div>
            `;
        }

        taskHtml += `
        <div>
        <button class="L-CLR">Update</button>
        <button class="L-RED">Delete</button>
        </div>
        `
        taskWidget.innerHTML = taskHtml;
    }

    displayTaskDetails(taskData);
}

