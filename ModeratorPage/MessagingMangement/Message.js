function showUserDetails(userData) {
    const chatBox = document.getElementById("chatBox");
    const conversationContainer = document.querySelector('.WIDGET.msg_two');

    if (!conversationContainer) {
        console.error("Conversation container not found.");
        return;
    }

    // Update conversation title
    conversationContainer.innerHTML = `
        <h3>Conversation with ${userData.name}</h3>
        <div class="CHAT__CONTAINER">
            <div id="chatBox"></div>
        </div>
    `;

    // Fetch messages from the server
    fetch(`MessageBackend.php?user_id=${userData.id}`)
        .then(response => response.json())
        .then(messages => {
            chatBox.innerHTML = ""; // Clear previous messages

            messages.forEach(msg => {
                let messageClass = msg.sender_id == userData.id ? "LEFT" : "RIGHT";
                let messageElement = `
                    <div class="MESSAGE ${messageClass}">
                        <p>${msg.message_text}</p>
                        <small>${msg.sent_at}</small>
                    </div>
                `;
                chatBox.innerHTML += messageElement;
            });
        })
        .catch(error => console.error("Error loading messages:", error));
}

let selectedUserId = null;
let otherUserId = null;

function showReceiverDropdown(senderId) {
    fetch(`MessageBackend.php?action=fetch_receivers&sender_id=${senderId}`)
        .then(response => response.json())
        .then(receivers => {
            const dropdown = document.getElementById("receiverDropdown");
            const receiverList = document.getElementById("receiverList");

            receiverList.innerHTML = "";

            if (receivers.length === 0) {
                receiverList.innerHTML = "<li>No receivers found</li>";
            } else {
                receivers.forEach(receiverId => {
                    const listItem = document.createElement("li");
                    listItem.textContent = `Receiver ID: ${receiverId}`;
                    listItem.onclick = () => selectReceiver(senderId, receiverId);
                    receiverList.appendChild(listItem);
                });
            }

            dropdown.style.display = "block";
        })
        .catch(error => console.error("Error fetching receivers:", error));
}

function selectReceiver(senderId, receiverId) {
    console.log(`Selected conversation: Sender ID ${senderId}, Receiver ID ${receiverId}`);
    fetchMessages(senderId, receiverId);
    document.getElementById("receiverDropdown").style.display = "none";
}

// Fetch messages dynamically
function fetchMessages(senderId, receiverId) {
    fetch(`MessageBackend.php?action=fetch_messages&sender_id=${senderId}&receiver_id=${receiverId}`)
        .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById("chatBox");
            chatBox.innerHTML = "";

            messages.forEach(msg => {
                let messageClass = msg.sender_id == senderId ? "RIGHT" : "LEFT";
                let messageElement = `
                    <div class="MESSAGE ${messageClass}">
                        <p>${msg.message_text}</p>
                        <small>${msg.sent_at}</small>
                    </div>
                `;
                chatBox.innerHTML += messageElement;
            });

            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => console.error("Error fetching messages:", error));
}

function displayMessages(messages, currentUserId) {
    const chatBox = document.getElementById("chatBox");
    chatBox.innerHTML = "";

    messages.forEach(msg => {
        let messageClass = msg.sender_id == currentUserId ? "RIGHT" : "LEFT";
        let messageElement = `
            <div class="MESSAGE ${messageClass}">
                <p>${msg.message_text}</p>
                <small>${msg.sent_at}</small>
            </div>
        `;
        chatBox.innerHTML += messageElement;
    });

    // Scroll to latest message
    chatBox.scrollTop = chatBox.scrollHeight;
}


function showReceiverDropdown(senderId) {
    fetch(`MessageBackend.php?action=fetch_receivers&sender_id=${senderId}`)
        .then(response => response.json())
        .then(receivers => {
            const dropdown = document.getElementById("receiverDropdown");
            const receiverList = document.getElementById("receiverList");

            // Clear previous receivers
            receiverList.innerHTML = "";

            if (receivers.length === 0) {
                receiverList.innerHTML = "<li>No receivers found</li>";
            } else {
                receivers.forEach(receiverId => {
                    const listItem = document.createElement("li");
                    listItem.textContent = `Receiver ID: ${receiverId}`;
                    listItem.onclick = () => selectReceiver(senderId, receiverId);
                    receiverList.appendChild(listItem);
                });
            }

            // Show dropdown near clicked user
            dropdown.style.display = "block";
        })
        .catch(error => console.error("Error fetching receivers:", error));
}

function selectReceiver(senderId, receiverId) {
    console.log(`Selected conversation: Sender ID ${senderId}, Receiver ID ${receiverId}`);

    // Here, you would fetch and display messages between the selected sender and receiver
    fetchMessages(senderId, receiverId);

    // Hide dropdown after selection
    document.getElementById("receiverDropdown").style.display = "none";
}

function fetchMessages(senderId, receiverId) {
    fetch(`fetch_messages.php?sender_id=${senderId}&receiver_id=${receiverId}`)
        .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById("chatBox");
            chatBox.innerHTML = "";

            messages.forEach(msg => {
                let messageClass = msg.sender_id == senderId ? "RIGHT" : "LEFT";
                let messageElement = `
                    <div class="MESSAGE ${messageClass}">
                        <p>${msg.message_text}</p>
                        <small>${msg.sent_at}</small>
                    </div>
                `;
                chatBox.innerHTML += messageElement;
            });

            // Scroll to the bottom for the latest messages
            chatBox.scrollTop = chatBox.scrollHeight;
        })
        .catch(error => console.error("Error fetching messages:", error));
}

