// when click user it will pop down receiver
function showReceiverDropdown(senderId) {
    fetch(`MessageBackend.php?action=fetch_receivers&sender_id=${senderId}`)
        .then(response => response.json())
        .then(receivers => {
            const dropdown = document.getElementById("receiverPopup");
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

function closePopup() {
    document.getElementById('receiverPopup').style.display = "none";
}

function selectReceiver(senderId, receiverId) {
    console.log(`Selected conversation: Sender ID ${senderId}, Receiver ID ${receiverId}`);

    fetchMessages(senderId, receiverId);

    document.getElementById("receiverPopup").style.display = "none";
}

function fetchMessages(senderId, receiverId) {
    let convo_from = document.querySelector(".CONVO_FROM");
    let convo_to = document.querySelector(".CONVO_TO");

    console.log(senderId)
    // Fetch sender name
    fetch(`MessageBackend.php?action=fetch_user_name&user_id=${senderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                convo_from.textContent = `From: ${data.name}`;
            } else {
                convo_from.textContent = "From: Unknown";
            }
        })
        .catch(error => console.error("Error fetching sender name:", error));

    // Fetch receiver name
    fetch(`MessageBackend.php?action=fetch_user_name&user_id=${receiverId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                convo_to.textContent = `To: ${data.name}`;
            } else {
                convo_to.textContent = "To: Unknown";
            }
        })
        .catch(error => console.error("Error fetching receiver name:", error));


    fetch(`MessageBackend.php?action=fetch_messages&sender_id=${senderId}&receiver_id=${receiverId}`)
        .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById("chatBox");
            chatBox.innerHTML = "";

            messages.forEach(msg => {

                let messageClass = msg.SenderID == senderId ? "RIGHT" : "LEFT";
                let messageElement = `
                <div class="MESSAGE ${messageClass}" id="message-${msg.DirectMessageID}">
                        <p id="msg-text-${msg.DirectMessageID}">${msg.MessageText}</p>
                        <small>${msg.CreatedTime}</small>
                        <br>    
                        <button onclick="deleteMessage(${msg.DirectMessageID}, ${senderId}, ${receiverId})" class="delete-btn">Delete</button>
                    </div>
                `;
                chatBox.innerHTML += messageElement;
            });

            chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to latest message
        })
        .catch(error => console.error("Error fetching messages:", error));
}

function deleteMessage(messageId, senderId, receiverId) {
    if (confirm("Are you sure you want to delete this message? This action cannot be undone.")) {
        console.log(messageId)
        fetch(`MessageBackend.php?action=delete_message&message_id=${messageId}`, { method: "GET" })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    fetchMessages(senderId, receiverId);
                } else {
                    alert("Failed to delete message: " + data.error);
                }
            })
            .catch(error => console.error("Error deleting message:", error));
    }
}

