function autoReply() {
    setTimeout(() => {
        let botReply = document.createElement("div");
        botReply.classList.add("CONVERSATION", "RECEIVE");
        botReply.textContent = "I'm just a bot 🤖";
        document.querySelector(".DMPAGE__CONVERSATION").appendChild(botReply);
    }, 1000);
}

function sendMSG() {
    let messageInput = document.getElementById("message");
    let messageText = messageInput.value.trim();

    if (messageText === "") return;

    let conversationSection = document.querySelector(".DMPAGE__CONVERSATION");

    let newMessage = document.createElement("div");
    newMessage.classList.add("CONVERSATION", "SENT");
    newMessage.textContent = messageText;

    conversationSection.appendChild(newMessage);
    messageInput.value = "";
    conversationSection.scrollTop = conversationSection.scrollHeight;

    autoReply(); // Trigger bot response
}
