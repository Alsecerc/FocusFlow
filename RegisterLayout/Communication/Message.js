// const pc = new RTCPeerConnection(configuration);
// const dataChannel = pc.createDataChannel("chat");

// let senderId = "";

// dataChannel.onopen = () => {
//   senderId
//   dataChannel.send("Hello!");
// };

// dataChannel.onmessage = event => {
//   console.log("Message received: " + event.data);
// };

document.addEventListener('DOMContentLoaded', function() {
    // Existing tab functionality
	const createContactBtn = document.getElementById('addContactBtn');
	const messagepanel = document.querySelectorAll('.contacts-panel');
    const tabButtons = document.querySelectorAll('.tab-button');
    const createGroupBtn = document.getElementById('createGroupBtn');
	const contactsPanelGroup = document.querySelector('.contacts-panel.group');
	const contactsPanelDM = document.querySelector('.contacts-panel.DirectMessages');

	console.log('Message Panel:', messagepanel);
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const tabType = this.dataset.tab;
            // Show/hide create group button based on active tab
            if (tabType === 'groups') {
				createContactBtn.style.display = 'none';
                createGroupBtn.style.display = 'flex';
				contactsPanelGroup.classList.remove('hidden');
				contactsPanelDM.classList.add('hidden');
            } else {
				createContactBtn.style.display = 'flex';
                createGroupBtn.style.display = 'none';
				contactsPanelGroup.classList.add('hidden');
				contactsPanelDM.classList.remove('hidden');

            }
        });
    });
	
	loadDefaultPage();
	setTimeout(function() {
		
		addEventListenerToMessage();
        // Remove duplicate function calls
        setupSendButton();
    }, 500);

    // Initially hide the button if not on groups tab
    if (!document.querySelector('.contacts-panel.group').classList.contains('hidden')) {// this 
    }else{
		createGroupBtn.style.display = 'none';
	}
    createGroupBtn.addEventListener('click', function() {
      GroupForm();
    });

	createContactBtn.addEventListener('click', function() {
	  DirectMessageForm();
	});


});

function GroupForm() { 
	// Create overlay for the modal
	const overlay = document.createElement('div');
	overlay.className = 'group-form-overlay';
	overlay.id = 'group-form-overlay';
	
	// Create the form container
	const GroupForm = document.createElement('div');
	GroupForm.className = 'group-form';
	GroupForm.id = 'group-form';
	
	// Add title
	const title = document.createElement('h3');
	title.textContent = 'Create a New Group';
	GroupForm.appendChild(title);
	
	// Close button
	const closeButton = document.createElement('button');
	closeButton.className = 'close-form';
	closeButton.innerHTML = '&times;'; // × symbol
	closeButton.addEventListener('click', () => {
		document.body.removeChild(overlay);
	});
	GroupForm.appendChild(closeButton);
	
	// Group name input
	const GroupName = document.createElement('input');
	GroupName.type = 'text';
	GroupName.placeholder = 'Group Name';
	GroupName.className = 'group-name';
	GroupName.id = 'group-name';

	// Group description input
	const GroupDesc = document.createElement('input');
	GroupDesc.type = 'text';
	GroupDesc.placeholder = 'Group Description';
	GroupDesc.className = 'group-desc';
	GroupDesc.id = 'group-desc';

	// Group members input
	const GroupMembers = document.createElement('input');
	GroupMembers.type = 'text';
	GroupMembers.placeholder = 'Emails of Group Members (comma separated)';
	GroupMembers.className = 'group-members';
	GroupMembers.id = 'group-members';

	// Create button
	const GroupSubmit = document.createElement('button');
	GroupSubmit.textContent = 'Create Group';
	GroupSubmit.className = 'group-submit';
	GroupSubmit.id = 'group-submit';

	// Add form event handler
	GroupSubmit.addEventListener('click', function() {
		// Form validation
		if (!GroupName.value.trim()) {
		alert('Please enter a group name');
		return;
		}
		let adminEmail = getCookieValue('email');
		const adminData = {
			name: GroupName.value.trim(),
			description: GroupDesc.value.trim(),
			members: adminEmail,
			role: 'ADMIN'
		}

		createGroup(adminData);
		let GroupMembersNum = 0;
		GroupMembers.value.split(',').forEach(email => {
			GroupMembersNum++;
			if (!validateEmail(email.trim())) {
				alert('Please enter a valid email address');
				return;
			}
		});

		if (!GroupMembersNum) {
			alert('No group members entered');
			return;
		}
		for (let i = 0; i < GroupMembersNum; i++) {
			// Get form data
			const groupData = {
			name: GroupName.value.trim(),
			description: GroupDesc.value.trim(),
			members: GroupMembers.value
						.split(',')[i].trim()
			};
			createGroup(groupData);
		}

		let groupData = {
			name: GroupName.value.trim(),
			description: GroupDesc.value.trim(),
		};

		renderGroups(groupData);

		// Create the group
		// Close the modal after submission
		document.body.removeChild(overlay);
	});

	// Add all elements to the form
	GroupForm.appendChild(GroupName);
	GroupForm.appendChild(GroupDesc);
	GroupForm.appendChild(GroupMembers);
	GroupForm.appendChild(GroupSubmit);
	
	// Add the form to the overlay and the overlay to the body
	overlay.appendChild(GroupForm);
	document.body.appendChild(overlay);
	
	// Focus on the first field
	GroupName.focus();
}

function validateEmail(email) {
	const re = /\S+@gmail\.com/;

	return re.test(email);
}

function createGroup(GroupData) {
    console.log('Sending group data:', GroupData);

    // Add Type to the GroupData object instead of the fetch options
    GroupData.Type = 'createGroup';

    // Send the group data to the server
    fetch('/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(GroupData)
        // Remove the incorrect Type parameter here
    })
    .then(response => {
        // For debugging - log the raw response
        response.clone().text().then(text => {
            console.log('Raw response:', text);
        });
        
        if (!response.ok && response.status !== 202) { // 202 is for our special "warning" case
            return response.text().then(text => {
                // Try to parse as JSON first
                try {
                    const errorData = JSON.parse(text);
                    throw new Error(errorData.message || `Server error: ${response.status}`);
                } catch (e) {
                    // If not valid JSON, use text
                    throw new Error(`Server error: ${response.status} ${text}`);
                }
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response:', data);
        
        if (data.status === 'success') {
            // Success case - no special handling needed
            console.log('Success:', data);
        }
        else if (data.status === 'warning') {
            // This is for users that don't exist yet
            console.log('Warning:', data.message);
            // Optional: Show a notification that the user doesn't exist yet
            // but will be invited when they register
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        // Display error message to user
        alert(`Failed to create group: ${error.message}`);
    });
}

function loadDefaultPage() {
	// Create the group communication list
	GroupCommunicationList();
    fetch('/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetDataLoadDefaultPage', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Default Page Success:', data);
        // Check if data is an object and has properties
        if (data && typeof data === 'object') {
            // Loop through the group objects and render each one
            Object.values(data).forEach(group => {
				
                renderGroups(group);
            });
        } else {
            console.log('No groups found or invalid data format');
        }
    })
    .catch(error => {
        console.error('Default Page Error:', error);
        // Handle the error appropriately
    });
}

function createdTime() {
	const date = new Date();
	const options = {
		timeZone: 'Asia/Kuala_Lumpur', // Specify desired timezone
		year: 'numeric',
		month: 'numeric',
		day: 'numeric',
		hour: 'numeric',
		minute: 'numeric',
		second: 'numeric',
		hour12: false
	};
	return (date.toLocaleString('en-US', options));
}

function GetTime(){
	const date = new Date();
	const options = {
		timeZone: 'Asia/Kuala_Lumpur', // Specify desired timezone
		hour: 'numeric',
		minute: 'numeric',
		hour12: false
	};
	console.log(date.toLocaleString('en-US', options).trim(" "));
	return (date.toLocaleString('en-US', options));
}

function GroupCommunicationList(){
	let contactpanel = document.createElement('div');
	contactpanel.className = 'contacts-panel group hidden';

}

//GroupData must have name, message, members, time
function renderGroups(GroupData) {

	// Clear existing content
	let contactpanel = document.querySelector('.contacts-panel.group');
	let contactlist = document.querySelector('.contacts-list.group');
	// Create the group item
	let Group = document.createElement('div');
	Group.className = 'contact-item';
	Group.id = GroupData.id;
	Group.dataset.groupName = GroupData.name;

	let avatar = document.createElement('div');
	avatar.className = 'contact-avatar';
	Group.appendChild(avatar);

	let avatarspan = document.createElement('span');
	avatarspan.className = 'material-icons';
	avatarspan.textContent = 'group';
	avatar.appendChild(avatarspan);

	let groupInfo = document.createElement('div');
	groupInfo.className = 'contact-info';
	Group.appendChild(groupInfo);

	let groupInfoHeader = document.createElement('h4');
	groupInfoHeader.textContent = GroupData.name;
	groupInfo.appendChild(groupInfoHeader);

	let groupInfoDesc = document.createElement('p');
	groupInfoDesc.textContent = GroupData.message? GroupData.message : 'Enter Something here';
	groupInfo.appendChild(groupInfoDesc);

	let groupDate = document.createElement('div');
	groupDate.className = 'contact-time';
	groupDate.id = GroupData.Time ? GroupData.Time : createdTime();
	if (GroupData.Time){
		groupDate.textContent = DateAndTimeDisplay(GroupData.Time);
	}else{
		groupDate.textContent = GetTime();
	}
	Group.appendChild(groupDate);

	
	contactlist.appendChild(Group);
	contactpanel.appendChild(contactlist);


	// Add event listener to the newly created group
    Group.addEventListener('click', function() {
        const contactItems = document.querySelectorAll('.contact-item');
        contactItems.forEach(contact => contact.classList.remove('active'));
        this.classList.add('active');
        
    });
	
	console.log('Group added:', GroupData.name);
	
}

// To parse cookies into an object:
function getCookieValue(item) {
	// Split document.cookie into an array of "name=value" strings.
	const cookies = document.cookie.split(';');
	
	// Loop through each cookie.
	for (let cookie of cookies) {
	  // Split each cookie into name and value, and trim extra spaces.
	  let [name, value] = cookie.split('=').map(c => c.trim());
	  
	  // Check if the name matches the requested cookie (using toUpperCase() if needed).
	  if (name === item.toUpperCase()) {
		// Return the decoded value directly.
		return decodeURIComponent(value);
	  }
	}
	// If not found, return null.
	return null;
}

function currentDateTime() {
	const date = new Date();
	const options = {
		timeZone: 'Asia/Kuala_Lumpur', // Specify desired timezone
		hour: 'numeric',
		minute: 'numeric',
		hour12: false
	};
	return (date.toLocaleString('en-US', options));
}

function DisplayHourMin(lastTime){
	const oneDayMs = 24 * 60 * 60 * 1000; // 86400000 ms
	const date = new Date();
	const options = {
		timeZone: 'Asia/Kuala_Lumpur', // Specify desired timezone
		year: 'numeric',
		month: 'numeric',
		day: 'numeric',
		hour: 'numeric',
		minute: 'numeric',
		second: 'numeric',
		hour12: false
	};
	let newTime = formatDate(lastTime);
	let newtimeStamp = getTimestamp(newTime);
	const currentTime = date.toLocaleString('en-US', options);
	const currentTimestamp = new Date(currentTime).getTime();
	const timeDifference = currentTimestamp - newtimeStamp;

	console.log("Current Time:",currentTime);
	console.log("CUrrent time stamp",currentTimestamp);
	console.log("Time Diff:",timeDifference);
	if (timeDifference > oneDayMs){
		return "yesterday";
	}else {
		return lastTime.split(" ")[1].slice(0,5);
	}
}

// display time when the page is loaded
function DateAndTimeDisplay(lastTime){
	const oneDayMs = 24 * 60 * 60 * 1000; // 86400000 ms
	const date = new Date();
	const options = {
		timeZone: 'Asia/Kuala_Lumpur', // Specify desired timezone
		year: 'numeric',
		month: 'numeric',
		day: 'numeric',
		hour: 'numeric',
		minute: 'numeric',
		second: 'numeric',
		hour12: false
	};
	let newTime = formatDate(lastTime);
	let newtimeStamp = getTimestamp(newTime);
	const currentTime = date.toLocaleString('en-US', options);
	const currentTimestamp = new Date(currentTime).getTime();
	const timeDifference = currentTimestamp - newtimeStamp;

	console.log("CUrrent time stamp",currentTimestamp, "Current Time:",newtimeStamp);
	console.log("Time Diff:",timeDifference);
	if (timeDifference > oneDayMs){
		return "yesterday";
	}else {
		return lastTime.split(" ")[1];
	}
}

function formatDate(Time) {
	let replacements = { "-": "/", " ": ", "};
	let newTime = Time.replace(/[-, ]/g,match => replacements[match]);
    let date = new Date(newTime);

    // Extract components
    let month = date.getMonth() + 1; // Months are 0-based
    let day = date.getDate();
    let year = date.getFullYear();
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let seconds = date.getSeconds();

    // Format with leading zeros if needed
    month = month.toString();
    day = day.toString();
    hours = hours.toString().padStart(2, "0");
    minutes = minutes.toString().padStart(2, "0");
    seconds = seconds.toString().padStart(2, "0");

    return `${month}/${day}/${year}, ${hours}:${minutes}:${seconds}`;
}

function getTimestamp(dateString) {
    return new Date(dateString).getTime();
}
//name, message, messageType, membersRole, time, id, role, description, status
function renderMessagePage(MessagePageData){
	let messagepanel = document.querySelector('.messages-panel');
	// Clear existing content
	messagepanel.innerHTML = '';
	// Create message header
	let messageHeader = document.createElement('div');
	messageHeader.className = 'message-header';
	messagepanel.appendChild(messageHeader);

	let messageRecipient = document.createElement('div');
	messageRecipient.className = 'message-recipient';
	messageHeader.appendChild(messageRecipient);

	let recipientAvatar = document.createElement('div');
	recipientAvatar.className = 'recipient-avatar';
	messageRecipient.appendChild(recipientAvatar);

	let avatarSpan = document.createElement('span');
	avatarSpan.className = 'material-icons';
	avatarSpan.textContent = 'account_circle';
	recipientAvatar.appendChild(avatarSpan);

	let recipientInfo = document.createElement('div');
	recipientInfo.className = 'recipient-info';
	messageRecipient.appendChild(recipientInfo);

	let recipientName = document.createElement('h3');
	recipientName.textContent = MessagePageData?.name || 'John Doe';
	recipientInfo.appendChild(recipientName);

	let recipientStatus = document.createElement('p');
	recipientStatus.textContent = MessagePageData?.status || 'Online';
	recipientInfo.appendChild(recipientStatus);

	let messageActions = document.createElement('div');
	messageActions.className = 'message-actions';
	messageHeader.appendChild(messageActions);

	let actionButton1 = document.createElement('button');
	actionButton1.className = 'action-button';
	messageActions.appendChild(actionButton1);

	let actionButton1Span = document.createElement('span');
	actionButton1Span.className = 'material-icons';
	actionButton1Span.textContent = 'call';
	actionButton1.appendChild(actionButton1Span);

	let actionButton2 = document.createElement('button');
	actionButton2.className = 'action-button';
	messageActions.appendChild(actionButton2);

	let actionButton2Span = document.createElement('span');
	actionButton2Span.className = 'material-icons';
	actionButton2Span.textContent = 'videocam';
	actionButton2.appendChild(actionButton2Span);

	let actionButton3 = document.createElement('button');
	actionButton3.className = 'action-button';
	messageActions.appendChild(actionButton3);

	let actionButton3Span = document.createElement('span');
	actionButton3Span.className = 'material-icons';
	actionButton3Span.textContent = 'more_vert';
	actionButton3.appendChild(actionButton3Span);

	let messageContent = document.createElement('div');
	messageContent.className = 'message-content';
	messagepanel.appendChild(messageContent);

	console.log('Message page rendered:', MessagePageData.messages);
	const messages = MessagePageData.messages || [];

	// Create messages with sender profiles
	messages.forEach(msg => {
		let messageDiv = document.createElement('div');
		messageDiv.className = `message ${msg.type}`;
		
		// Add sender profile
		let senderProfile = document.createElement('div');
		senderProfile.className = 'message-sender';
		
		let senderAvatar = document.createElement('div');
		senderAvatar.className = 'message-avatar';
		
		let avatarIcon = document.createElement('span');
		avatarIcon.className = 'material-icons';
		avatarIcon.textContent = 'account_circle';
		
		senderAvatar.appendChild(avatarIcon);
		senderProfile.appendChild(senderAvatar);
		
		let senderName = document.createElement('div');
		senderName.className = 'sender-name';
		senderName.textContent = msg.sender;
		senderProfile.appendChild(senderName);
		
		// Content wrapper
		let contentWrapper = document.createElement('div');
		contentWrapper.className = 'message-content-wrapper';
		
		let messageBubble = document.createElement('div');
		messageBubble.className = 'message-bubble';
		
		let messageText = document.createElement('p');
		messageText.textContent = msg.content;
		messageBubble.appendChild(messageText);
		
		let messageTime = document.createElement('span');
		messageTime.className = 'message-time';
		messageTime.textContent = msg.time;
		messageBubble.appendChild(messageTime);
		
		contentWrapper.appendChild(messageBubble);
		
		// Assemble the message
		messageDiv.appendChild(senderProfile);
		messageDiv.appendChild(contentWrapper);
		messageContent.appendChild(messageDiv);
	});

	// Add this at the end of the function after all messages are rendered
    setTimeout(scrollToBottom, 100); // Small delay to ensure messages are rendered properly

	// Message input area
	let messageInput = document.createElement('div');
	messageInput.className = 'message-input';
	messagepanel.appendChild(messageInput);

	let attachmentButton = document.createElement('button');
	attachmentButton.className = 'attachment-button';
	messageInput.appendChild(attachmentButton);

	let attachmentButtonSpan = document.createElement('span');
	attachmentButtonSpan.className = 'material-icons';
	attachmentButtonSpan.textContent = 'attach_file';
	attachmentButton.appendChild(attachmentButtonSpan);

	let inputText = document.createElement('input');
	inputText.type = 'text';
	inputText.placeholder = 'Type a message...';
	messageInput.appendChild(inputText);

	let emojiButton = document.createElement('button');
	emojiButton.className = 'emoji-button';
	messageInput.appendChild(emojiButton);

	let emojiButtonSpan = document.createElement('span');
	emojiButtonSpan.className = 'material-icons';
	emojiButtonSpan.textContent = 'sentiment_satisfied_alt';
	emojiButton.appendChild(emojiButtonSpan);

	let sendButton = document.createElement('button');
	sendButton.className = 'send-button';
	sendButton.id = 'sendMessageBtn'; // Add an ID for easier selection
	messageInput.appendChild(sendButton);

	let sendButtonSpan = document.createElement('span');
	sendButtonSpan.className = 'material-icons';
	sendButtonSpan.textContent = 'send';
	sendButton.appendChild(sendButtonSpan);
}

function sendMessageToServer(messageData) {
	// Send the message to the server using POST method
	messageData.Type = "sendMessageToServer";
	fetch("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php",{
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json"
		},
		body: JSON.stringify(messageData)
	})
	.then(response => {
		if (!response.ok) {
			throw new Error(`HTTP error! Status: ${response.status}`);
		}
		return response.json();
	})
	.then(data => {
		console.log('Message sent:', data);
	})
	.catch(error => {
		console.error('Message send error:', error);
		alert('Failed to send message');
		// Handle the error appropriately
	});
}

/**
 * Adds click event listeners to all contact items in the contacts list.
 * When a contact is clicked, it:
 * 1. Highlights the selected contact by adding 'active' class
 * 2. If the contact item has an ID (represents a group):
 *    - Fetches message information from server via AJAX
 *    - Renders the message page with real data from the server
 * 3. If the contact has no ID:
 *    - Renders the message page with mock conversation data
 * 
 * The function manages the UI state by removing the 'active' class from all contacts
 * and applying it only to the selected contact.
 * 
 * @returns {void}
 */
function addEventListenerToMessage() {
    // Select all contact items inside the contacts-list
    const contactItems = document.querySelectorAll('.contacts-list.group .contact-item');
	console.log('Contact Items:', document.querySelector('.contacts-list.group'));

    contactItems.forEach(item => {
        item.addEventListener('click', function() {
			const name = this.querySelector('h4').textContent;

            // Highlight the selected item by removing active class from all and adding to clicked one
            contactItems.forEach(contact => contact.classList.remove('active'));
            this.classList.add('active');
            if (item.id){
				console.log('Selected group:', item.id);
				fetch(`/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetMessageInfo&GroupID=${item.id}`,{
					method: "GET",
					headers: {
						"Content-Type": "application/json",
						"Accept": "application/json"
					},
				})
				.then(response => {
					if (!response.ok) {
						throw new Error(`HTTP error! Status: ${response.status}`);
					}
					return response.text();
				})
				.then(data => {
					
					const messageData = JSON.parse(data);
					
					console.log('Message Info:', messageData);
					const Data = {
						name: name,
						messages: SaveMessageIntoArray(messageData),
						message: 'TEXT',
						membersRole: "ADMIN",
					}
					console.log('Message Data:', SaveMessageIntoArray(messageData));
					renderMessagePage(Data);
				})
				.catch(error => {
					console.error('Message Info error:', error);
					alert('Failed to get message info');
					// Handle the error appropriately
				});
			}else{
				let messageType = 'TEXT';
				renderMessagePage({
				    name: name,
					messages: [{ sender: 'John Doe', content: 'Hi there! How\'s your project coming along?', time: '10:30', type: 'received' },
					{ sender: 'You', content: 'Hey! It\'s going well, just finishing up the last few tasks.', time: '10:32', type: 'sent' },
					{ sender: 'John Doe', content: 'Great! Let me know if you need any help.', time: '10:33', type: 'received' }],
					message: messageType,
					membersRole: 'ADMIN',
					time: GetTime(),
					
				    status: 'Online'
				});
			}
            // Get the group/contact name from the h4 element inside this contact item
            
            console.log('Selected contact/group:', name);
            
            // Render message page for the selected contact/group
			// name, message, messageType, membersRole, time, id, role, description, status
        });
    });
    
    console.log(`Added event listeners to ${contactItems.length} contact items`);
}

/**
 * Appends a new message to the chat interface
 * @param {Object} messageData - The data for the message to be appended
 * @param {string} messageData.type - Type of message ("sent" or "received")
 * @param {string} messageData.sender - Name of the message sender
 * @param {string} messageData.content - The message content text
 * @param {string} messageData.time - The timestamp for the message
 */

function appendMessageToChat(messageData) {
	const messageContent = document.querySelector('.message-content');
	let messageDiv = document.createElement('div');
	messageDiv.className = `message ${messageData.type}`;
	
	// Add sender profile
	let senderProfile = document.createElement('div');
	senderProfile.className = 'message-sender';
	
	let senderAvatar = document.createElement('div');
	senderAvatar.className = 'message-avatar';
	
	let avatarIcon = document.createElement('span');
	avatarIcon.className = 'material-icons';
	avatarIcon.textContent = 'account_circle';
	
	senderAvatar.appendChild(avatarIcon);
	senderProfile.appendChild(senderAvatar);
	
	let senderName = document.createElement('div');
	senderName.className = 'sender-name';
	senderName.textContent = messageData.sender;
	senderProfile.appendChild(senderName);
	
	// Content wrapper
	let contentWrapper = document.createElement('div');
	contentWrapper.className = 'message-content-wrapper';
	
	let messageBubble = document.createElement('div');
	messageBubble.className = 'message-bubble';
	
	let messageText = document.createElement('p');
	messageText.textContent = messageData.content;
	messageBubble.appendChild(messageText);
	
	let messageTime = document.createElement('span');
	messageTime.className = 'message-time';
	messageTime.textContent = messageData.time;
	messageBubble.appendChild(messageTime);
	
	contentWrapper.appendChild(messageBubble);
	
	// Assemble the message
	messageDiv.appendChild(senderProfile);
	messageDiv.appendChild(contentWrapper);
	messageContent.appendChild(messageDiv);

	// Scroll to bottom after adding the new message
    scrollToBottom();
}

function setupSendButton() {
    console.log("Setting up send button");

    // Common function to process the send action
    function processSendAction() {
        const messageInput = document.querySelector('.message-input input');
        if (!messageInput) {
            console.error('Message input not found');
            return;
        }
        
        const message = messageInput.value.trim();
        if (!message) return;
        
        // Find the active group/contact
        const activeItem = document.querySelector('.contact-item.active');
        if (!activeItem) {
            console.error('No active conversation selected');
            return;
        }
        
        // Append message to chat UI
        appendMessageToChat({
            sender: 'You',
            content: message,
            time: currentDateTime(),
            type: 'sent'
        });
        console.log('Active item:', activeItem);
        
        // Prepare message data
        const messageData = {
            message: message,
            GroupID: activeItem.id || 0,
            messageType: 'TEXT',
        };
        
        console.log('Sending message data:', messageData);
        sendMessageToServer(messageData);
        
        // Clear the input field after sending
        messageInput.value = '';
    }

    // Keydown listener for Enter key on the send button
    document.addEventListener('keydown', function(e) {
		if (
			e.key === 'Enter' && 
			(e.target.classList.contains('input') || 
			(e.target.parentElement && e.target.parentElement.classList.contains('message-input')))
		) {
			e.preventDefault();
			processSendAction();
		}
		
    });
    
    // Click listener using event delegation for the send button
    document.addEventListener('click', function(e) {
		console.log('Send Button:', e.target);
        if (
            (e.target && e.target.classList.contains('send-button')) ||
            (e.target.parentElement && e.target.parentElement.classList.contains('send-button'))
        ) {
            processSendAction();
        }
    });
}


/**
 * Scrolls the message content area to the bottom
 */
function scrollToBottom() {
    const messageContent = document.querySelector('.message-content');
    if (messageContent) {
        messageContent.scrollTop = messageContent.scrollHeight;
    }
}

function SaveMessageIntoArray(messageData){
	let messageArray = [];
	for (let i = 0; i < messageData.length; i++){
		let username = getCookieValue('username');
		if(messageData[i].username === username){
			let NewMessageData = {
				sender: "You",
				content: messageData[i].message,
				time: DisplayHourMin(messageData[i].timestamp),
				type: "sent",
				messageStatus: messageData[i].messageStatus
			};
			messageArray.push(NewMessageData);
		}
		else{
			let NewMessageData = {
				sender: messageData[i].username,
				content: messageData[i].message,
				time: DisplayHourMin(messageData[i].timestamp),
				type: "received",
				messageStatus: messageData[i].messageStatus
			};
			messageArray.push(NewMessageData);
		}
	}
	return messageArray;
}

function DirectMessageForm() {
	// Create overlay for the modal
	const overlay = document.createElement('div');
	overlay.className = 'DM-form-overlay';
	overlay.id = 'DM-form-overlay';
	
	// Create the form container
	const GroupForm = document.createElement('div');
	GroupForm.className = 'DM-form';
	GroupForm.id = 'DM-form';
	
	// Add title
	const title = document.createElement('h3');
	title.textContent = 'Create a New Direct Message';
	GroupForm.appendChild(title);
	
	// Close button
	const closeButton = document.createElement('button');
	closeButton.className = 'close-form';
	closeButton.innerHTML = '&times;'; // × symbol
	closeButton.addEventListener('click', () => {
		document.body.removeChild(overlay);
	});
	GroupForm.appendChild(closeButton);
	
	// Group name input
	const GroupName = document.createElement('input');
	GroupName.type = 'text';
	GroupName.placeholder = 'Recipient Name';
	GroupName.className = 'user-name';
	GroupName.id = 'user-name';

	// Group description input
	const GroupDesc = document.createElement('input');
	GroupDesc.type = 'text';
	GroupDesc.placeholder = 'Message';
	GroupDesc.className = 'group-desc';
	GroupDesc.id = 'group-desc';

	// Create button
	const GroupSubmit = document.createElement('button');
	GroupSubmit.textContent = 'Send Message';
	GroupSubmit.className = 'group-submit';
	GroupSubmit.id = 'group-submit';

	// Add form event handler
	GroupSubmit.addEventListener('click', function() {
		// Form validation
		if (!GroupName.value.trim()) {
		alert('Please enter a recipient name');
		return;
		}
		if (!GroupDesc.value.trim()) {
		alert('Please enter a message');
		return;
		}
		let recipient = GroupName.value.trim();
		let message = GroupDesc.value.trim();
		let sender = getCookieValue('username');
		let time = GetTime();
		let messageType = 'TEXT';
		let messageStatus = 'SENT';
		let messageData = {
			recipient: recipient,
			message: message,
			sender: sender,
			time: time,
			messageType: messageType,
			messageStatus: messageStatus
		}
		sendDirectMessage(messageData);
		// Create the group
		// Close the modal after submission
		document.body.removeChild(overlay);
	});

	// Add all elements to the form
}