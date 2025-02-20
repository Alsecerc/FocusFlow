document.getElementById("chatForm").addEventListener("submit", sendMSG);

function sendMSG(event) {
    event.preventDefault(); // Prevent form submission refresh

    let messageInput = document.getElementById("message1");
    let messageText = messageInput.value.trim();
    let receiverID = document.querySelector("input[name='receiver_id']").value;

    if (messageText === "") return; // Prevent empty messages

    // Create form data
    let formData = new FormData();
    formData.append("message", messageText);
    formData.append("receiver_id", receiverID);


    // Send the data using Fetch API
    fetch("CommunityDMPageSendMsg.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let conversationSection = document.querySelector(".DMPAGE__CONVERSATION");

            let newMessage = document.createElement("div");
            newMessage.classList.add("CONVERSATION", "SENT"); 
            newMessage.textContent = messageText; 

            conversationSection.appendChild(newMessage);
            conversationSection.scrollTop = conversationSection.scrollHeight; // Auto-scroll

            messageInput.value = ""; // Clear input field
        } else {
            alert("Error: " + data.message); // Show error message
        }
    })
    .catch(error => console.error("Error:", error));
}

let EnterMessage = document.querySelector(".ENTER__MESSAGE");
if (EnterMessage) {
    EnterMessage.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            sendMSG(event);
        }
    });
}






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
    document.getElementById("popupIframe").src = "CommunityPageUpload.php"; 
    document.getElementById("popupOverlay").style.opacity = "1"; 
    document.getElementById("popupOverlay").style.zIndex = "1000"; 
}

function closePopup() {
    document.getElementById("popupOverlay").style.opacity = "0"; 
    document.getElementById("popupOverlay").style.zIndex = "-1"; 
}

function openPopup1() {
    document.getElementById("popupIframe").src = "CommunityPageView.php"; 
    document.getElementById("popupOverlay").style.opacity = "1"; 
    document.getElementById("popupOverlay").style.zIndex = "1000"; 
}