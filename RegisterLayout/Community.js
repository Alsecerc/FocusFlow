function autoReply() {
    setTimeout(() => {
        let botReply = document.createElement("div");
        botReply.classList.add("CONVERSATION", "RECEIVE");
        botReply.textContent = "Example reply...";
        document.querySelector(".DMPAGE__CONVERSATION").appendChild(botReply);
    }, 200);
}


function sendMSG(event) {
    event.preventDefault(); // Prevent form from refreshing

    let messageInput = document.getElementById("message1"); // Get the input field
    let messageText = messageInput.value.trim(); // Get the trimmed text

    if (messageText === "") return; // Prevent sending empty messages

    let conversationSection = document.querySelector(".DMPAGE__CONVERSATION");

    let newMessage = document.createElement("div");
    newMessage.classList.add("CONVERSATION", "SENT"); // Add CSS class
    newMessage.textContent = messageText; // Set text content

    conversationSection.appendChild(newMessage); // Append to chat container
    conversationSection.scrollTop = conversationSection.scrollHeight; // Auto-scroll to the latest message

    messageInput.value = ""; // Clear input field after sending

    autoReply(); // Simulate bot response
}

// Attach event listener to form
// document.getElementById("chatForm").addEventListener("submit", sendMSG);

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


