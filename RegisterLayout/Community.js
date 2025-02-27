






// Function to fetch and display messages
// function fetchMessages() {
//     fetch("CommunityBackend.php") // Replace with your PHP endpoint
//         .then(response => response.json()) // Convert response to JSON
//         .then(data => {

//             console.log("Fetched messages:", data); // Debugging

//             let chatBox = document.getElementById("chatBox"); // Get the chat container
//             chatBox.innerHTML = ""; // Clear previous messages

//             // Loop through each message and create an HTML element
//             data.forEach((msg, index) => {
//                 let messageElement = document.createElement("div");
//                 messageElement.classList.add("message"); // Add CSS class
//                 messageElement.innerHTML = `<strong>${index + 1}:</strong> ${msg.message} <span class="timestamp">${msg.timestamp}</span>`;
//                 chatBox.appendChild(messageElement); // Append message to chat box
//             });
//         })
//         .catch(error => console.error("Error fetching messages:", error));
// }

// // Call the function to load messages on page load
// fetchMessages();


// Community Pop up
function openPopup() {
    const teamID = new URLSearchParams(window.location.search).get("team_id");
    const teamName = new URLSearchParams(window.location.search).get("team");

    // Open the upload page with team_id passed in URL
    document.getElementById("popupIframe").src = "CommunityPageUpload?team_id=" + encodeURIComponent(teamID) + "&team=" + encodeURIComponent(teamName);
    document.getElementById("popupOverlay").style.opacity = "1";
    document.getElementById("popupOverlay").style.zIndex = "1000";
}

function closePopup() {
    document.getElementById("popupOverlay").style.opacity = "0";
    document.getElementById("popupOverlay").style.zIndex = "-1";
}

function openPopup1() {

    const teamID = new URLSearchParams(window.location.search).get("team_id");
    const teamName = new URLSearchParams(window.location.search).get("team");

    // Open the upload page with team_id passed in URL
    document.getElementById("popupIframe").src = "CommunityPageView?team_id=" + encodeURIComponent(teamID) + "&team=" + encodeURIComponent(teamName);
    document.getElementById("popupOverlay").style.opacity = "1";
    document.getElementById("popupOverlay").style.zIndex = "1000";
}