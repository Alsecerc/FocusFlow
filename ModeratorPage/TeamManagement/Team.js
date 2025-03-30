document.addEventListener("DOMContentLoaded", fetchTeams);

let currentTeamName = null;

// Fetch & display teams
function fetchTeams() {
    fetch("TeamBackend.php?action=fetch_teams")
        .then(response => response.json())
        .then(teams => {
            const tbody = document.querySelector("#teamTable tbody");
            tbody.innerHTML = "";
            teams.forEach(team => {
                let row = `
                    <tr>
                        <td>${team.name}</td>
                        <td>${team.member_count}</td>
                        <td>
                            <button onclick='loadGroupData("${team.name}")'>View</button>
                        </td>
                    </tr>`;
                tbody.innerHTML += row;
            });
        });
}

// Search & Filter Function
function filterTeams() {
    let searchValue = document.getElementById("searchTeam").value.toLowerCase();

    document.querySelectorAll("#teamTable tbody tr").forEach(row => {
        let teamName = row.cells[0].textContent.toLowerCase();
        let show = teamName.includes(searchValue);
        row.style.display = show ? "" : "none";
    });
}

// Load Tasks & Members
function loadGroupData(teamId) {
    document.querySelector(".TEAM__HEADER").innerHTML = "Team <strong>" + teamId + "'s</strong> Tasks & Members"
    console.log(teamId)
    currentTeamName = teamId;

    fetch(`TeamBackend.php?action=fetch_tasks&team_id=${teamId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById("taskList").innerHTML = data.tasks.map(task =>
                `<li><span>${task.task_name} <br> Assigned To : ${task.assigned_user} <br> Assigned By : ${task.assigned_by_user}</span> <button onclick="deleteTask(${task.id})">Remove</button></li>`
            ).join("");

            document.getElementById("memberList").innerHTML = data.members.map(member =>
                `<li><span>ID: ${member.member_id} | Name: ${member.name}</span> <button onclick="removeMember(${member.member_id})">Remove</button></li>`
            ).join("");
        });
}


// Remove Task
function deleteTask(taskId) {
    if (confirm("Are you sure you want to remove this task?")) {
        fetch("TeamBackend.php?action=remove_task", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ task_id: taskId, team_name: currentTeamName }) // Send team_name
        }).then(() => loadGroupData(currentTeamName));
    }
}

// Remove Member
function removeMember(memberId) {
    if (confirm("Are you sure you want to remove this member?")) {
        fetch("TeamBackend.php?action=remove_member", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ member_id: memberId, team_name: currentTeamName }) // Send team_name
        }).then(() => loadGroupData(currentTeamName));
    }
}

