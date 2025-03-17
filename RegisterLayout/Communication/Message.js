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
	// setInterval(ContinousUpdatePage, 1000);
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

	const GroupNameErrorMessage = document.createElement('p');
	GroupNameErrorMessage.className = 'group-name-error';
	GroupNameErrorMessage.textContent = '';
	GroupNameErrorMessage.style.display = 'none';
	GroupNameErrorMessage.style.color = 'red';

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
	
	const EmailErrorMessage = document.createElement('p');
	EmailErrorMessage.className = 'email-error';
	EmailErrorMessage.textContent = '';
	EmailErrorMessage.style.display = 'none';
	EmailErrorMessage.style.color = 'red';

	// Create button
	const GroupSubmit = document.createElement('button');
	GroupSubmit.textContent = 'Create Group';
	GroupSubmit.className = 'group-submit';
	GroupSubmit.id = 'group-submit';

	// Add form event handler
	const adminEmail = getCookieValue('EMAIL');
	GroupSubmit.addEventListener('click', function() {
		let groupmemberArray = GroupMembers.value.split(',').map(email => email.trim());
		groupmemberArray.push(adminEmail);
		console.log('Group members:', groupmemberArray);
		const GroupData = {
			name: GroupName.value.trim(),
			description: GroupDesc.value.trim(),
			members: groupmemberArray // it should be an array of emails
		}

		console.log('Group Data:', GroupData);
		EmailErrorMessage.style.display = 'none';
		GroupNameErrorMessage.style.display = 'none';
		// Form validation
		if (!GroupName.value.trim() || !GroupMembers.value.trim()) {
			GroupNameErrorMessage.textContent = 'Please enter a group name';
			EmailErrorMessage.textContent = 'Please enter group members';
			EmailErrorMessage.style.display = 'block';
			GroupNameErrorMessage.style.display = 'block';
		return;
		}
		let errorMessage = [];
		GroupMembers.value.split(',').forEach(email => {
			let UserOwnEmail = getCookieValue('EMAIL');
			if (UserOwnEmail === email.trim()) {
				errorMessage.push('You cannot add yourself to the group');
			}
			if (!validateEmail(email.trim())) {
				errorMessage.push('Invalid email address');
			}

		});
		if (errorMessage.length > 0) {
			EmailErrorMessage.textContent = errorMessage.join('. '); // Join errors with a dot
			EmailErrorMessage.style.display = 'block';
		} else {
			EmailErrorMessage.style.display = 'none'; // Hide if no errors
			CheckExistEmail(GroupData);
			overlay.remove();
		}

	},{ once: true });

	// Add all elements to the form
	GroupForm.appendChild(GroupName);
	GroupForm.appendChild(GroupNameErrorMessage);
	GroupForm.appendChild(GroupDesc);
	GroupForm.appendChild(GroupMembers);
	GroupForm.appendChild(EmailErrorMessage);
	GroupForm.appendChild(GroupSubmit);
	
	// Add the form to the overlay and the overlay to the body
	overlay.appendChild(GroupForm);
	document.body.appendChild(overlay);
	
	// Focus on the first field
	GroupName.focus();
}

function CheckExistEmail(GroupData){
	// Check if the email exists in the database
	console.log(GroupData.members);
	const overlay = document.getElementById('group-form-overlay');
	const EmailErrorMessage = document.querySelector('.email-error');
	const GroupMemberID = document.querySelector('.group-members');
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=CheckEmail&Email="+GroupData.members, {
		method: "GET",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json"
		}
	})
	.then(data => {
		console.log('Email checked:', data);
		$CheckIfAllEmaiExist = true;
		data.forEach(email => {
			console.log('Email:', email);
			if (email.status === 'warning') {
				EmailErrorMessage.textContent = 'Email not found Please enter a valid email address';
				EmailErrorMessage.style.display = 'block';
				GroupMemberID.value = '';
				GroupMemberID.focus();
				$CheckIfAllEmaiExist = false;
			}else if(email.status === 'error'){
				EmailErrorMessage.textContent = 'Email'+ email.email +' not found Please enter a valid email address';
				EmailErrorMessage.style.display = 'block';
				GroupMemberID.value = '';
				GroupMemberID.focus();
				$CheckIfAllEmaiExist = false;
			}
		});
		if ($CheckIfAllEmaiExist) {
			console.log('Email found Creating Group');

		let GroupMembersNum = GroupData.members.length;
		
		if (!GroupMembersNum) {
			return;
		}
		// Get form data
		const NewgroupData = {
		name: GroupData.name,
		description: GroupData.description,
		members: GroupData.members,
		role: "MEMBER"
		};
		createGroup(NewgroupData);
		

		// let groupData = {
		// 	name: GroupName.value.trim(),
		// 	description: GroupDesc.value.trim(),
		// };

		renderGroups(GroupData);
		
		// document.body.removeChild(overlay);
		return;
		}else if(data.status === 'warning'){
			alert('Email not found');
		}else {
			EmailErrorMessage.textContent = 'Email not found Please enter a valid email address';
			EmailErrorMessage.style.display = 'block';
			GroupMemberID.value = '';
			GroupMemberID.focus();
		}
	})
	.catch(error => {
		console.error('Email check error:', error);
		alert('Failed to check email');
		// Handle the error appropriately
	});
}

function validateEmail(email) {
	const re = /\S+@gmail\.com/;

	return re.test(email);
}

function ContinousUpdatePage(){
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetGroupContactList", {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
	.then(data => {
		console.log('Update contact Group list every second:', data);
		if (data.status === 'success') {
			const NewData = data.message;
			console.log('New Data:', NewData);
			NewData.forEach(contact => {
				EditGroupContactList({
					ContactID: contact.GroupID,
					name: contact.GroupName,
					message: contact.GroupMessages,
					time: contact.GroupMessageTime
				});
			});
		}
	})
	.catch(error => {
		console.error('Contact List error:', error);
	});
}

function EditGroupContactList(GrouplistData){
	const GroupContactList = document.querySelectorAll('.contacts-list.group .contact-item');
	GroupContactList.forEach(contact => {
		if (contact.id === String(GrouplistData.ContactID)) {
			contact.querySelector('h4').textContent = GrouplistData.name;
			contact.querySelector('p').textContent = GrouplistData.message;
			contact.querySelector('.contact-time').textContent = DisplayHourMin(GrouplistData.time);
		}
	});
}

function createGroup(GroupData) {

    // Add Type to the GroupData object instead of the fetch options
    GroupData.Type = 'createGroup';

	// Send the group data to the server using POST method
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json"
		},
		body: JSON.stringify(GroupData)
	})
	.then(data => {
		console.log('Group created:', data);
	})
	.catch(error => {
		console.error('Group create error:', error);
		alert('Failed to create group');
		// Handle the error appropriately
	});
}

function loadDefaultPage() {
	// Fetch group contact list from server
	fetchGroupContactListFromServer();
	// Fetch DM contact list from server
	VerifyDMContactListExist();
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
	groupInfoDesc.textContent = GroupData.message? GroupData.message : '';
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
	if (!lastTime) {
        return "now";
    }
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

	};
	let newTime = formatDate(lastTime);
	let newtimeStamp = getTimestamp(newTime);
	const currentTime = date.toLocaleString('en-US', options);
	const currentTimestamp = new Date(currentTime).getTime();
	const timeDifference = currentTimestamp - newtimeStamp;
	let newEditedTime = lastTime.split(" ")[0].slice(5,10).replace("-","/");
	console.log('Last time:', lastTime.split(" ")[0].slice(0,4));
	if (timeDifference < 60000) {
        return "now"; // Less than 1 min ago
    } else if (timeDifference < oneDayMs) {
        return lastTime.split(" ")[1].slice(0, 5); // Return HH:MM
    } else if (timeDifference < oneDayMs * 2) {
        return "yesterday";
    }else if(GetCurrentYear() !== lastTime.split(" ")[0].slice(0,4)){
		let EditedTime = lastTime.split(" ")[0].replace(/-/g,"/");
		return EditedTime;
	} else {
        return newEditedTime; // Return full date (YYYY-MM-DD)
    }
}

// display time when the page is loaded
function DateAndTimeDisplay(lastTime){
	if (!lastTime) {
        return "now";
    }
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

	if (timeDifference > oneDayMs){
		return "yesterday";
	}else {
		return lastTime.split(" ")[1];
	}
}

function convertToMilliseconds(timeString) {
    let date = new Date(timeString);
    return date.getTime(); // Returns milliseconds since Unix epoch
}

function GetCurrentYear(){
	const date = new Date();
	const options = {
		timeZone: 'Asia/Kuala_Lumpur', // Specify desired timezone
		year: 'numeric',
		hour12: false
	};
	return (date.toLocaleString('en-US', options));
}

function formatDate(Time) {

	if (Time === "now") {
        const now = new Date();
        return now.toLocaleString('en-US', {
            month: 'numeric',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric'
        });
    }
    // Check if Time is undefined or null
    if (!Time) {
        console.warn("Received undefined or null timestamp");
        // Return current time as fallback
        const now = new Date();
        return now.toLocaleString('en-US', {
            month: 'numeric',
            day: 'numeric',
            year: 'numeric',
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric'
        });
    }
    
    try {
        let replacements = { "-": "/", " ": ", "};
        let newTime = Time.replace(/[-, ]/g, match => replacements[match]);
        let date = new Date(newTime);
        
        // Check if date is valid
        if (isNaN(date.getTime())) {
            throw new Error("Invalid date created");
        }

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
    } catch (e) {
        console.error("Error formatting date:", e, "Time value was:", Time);
        return "Invalid date";
    }
}

function getTimestamp(dateString) {
    return new Date(dateString).getTime();
}
//name, message, messageType, membersRole, time, id, role, description, status
function renderMessagePage(MessagePageData){
	console.log(MessagePageData)
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
	recipientName.textContent = MessagePageData?.name || 'None';
	recipientInfo.appendChild(recipientName);

	let recipientStatus = document.createElement('p');
	recipientStatus.textContent = MessagePageData?.status || 'Offline';
	if (recipientStatus && recipientStatus.textContent.trim().toLowerCase() === "offline") {
		recipientStatus.style.color = "red";
	}
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

	let messages = null;
	if(MessagePageData.messages.length === 0){
		messages = "Enter Something to continue"
	}
	console.log('Message page rendered:', MessagePageData.messages);
	messages = MessagePageData.messages;

	// Create messages with sender profiles
	console.log(messages)
	let previousTime = null; // Move this outside the loop

	// Convert time to milliseconds and sort messages in ascending order
	messages.sort((a, b) => convertToMilliseconds(a.time) - convertToMilliseconds(b.time));
	
	const sortedMessages = [];
	
	messages.forEach(msg => {
		const currentUsername = getCookieValue('USERNAME');
		const isCurrentUser = msg.sender === 'You' || msg.sender === currentUsername;
		let NewTime = convertToMilliseconds(msg.time);
	
		if (previousTime === null || NewTime > previousTime) {
			console.log('New Time:', NewTime, 'Previous Time:', previousTime);
			previousTime = NewTime;
		}
	
		const ProcessedMsg = {
			type: isCurrentUser ? 'sent' : 'received',
			sender: msg.sender === currentUsername ? 'You' : msg.sender,
			content: msg.content,
			time: DisplayHourMin(msg.time)
		};
	
		sortedMessages.push(ProcessedMsg); // Store sorted messages in an array
	});
	
	// Now, sortedMessages contains the messages in ascending order based on time
	console.log('Sorted Messages:', sortedMessages);
	
	// You can now append them to the chat in the correct order
	sortedMessages.forEach(msg => appendMessageToChat(msg));
	

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

	// After rendering the message content, check if we should show suggestion
    // For demo purposes, let's show the suggestion if there are no messages
    if (MessagePageData.messages.length === 0 && MessagePageData.name !== 'None') {
        // Add a slight delay so the popup appears after the page renders
		
        setTimeout(() => {
            showFriendSuggestion({ 
                name: MessagePageData.name,
                // You could include email if available
                // email: MessagePageData.email 
            });
        }, 300);
    }
}

function sendGroupMessageToServer(messageData) {
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

function sendUserMessageToServer(messageData) {
	console.log('Sending message:', messageData.messageType);
	messageData.Type = 'sendUserMessageToServer'
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json"
		},
		body: JSON.stringify(messageData)
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
    console.log('Adding event listeners to contacts...');
    
    // Use event delegation for better reliability with dynamically added elements
    document.addEventListener('click', function(event) {
        // Find the closest contact-item parent (if any)
        const contactItem = event.target.closest('.contact-item');
        if (!contactItem) return; // Not a contact item click
        
        console.log('Contact item clicked:', contactItem);

        // Check which panel this contact belongs to
        const isGroupContact = contactItem.closest('.contacts-panel.group');
        const isDMContact = contactItem.closest('.contacts-panel.DirectMessages');
        
        // Get all similar contacts in the same category
        let allContacts;
        if (isGroupContact) {
            allContacts = document.querySelectorAll('.contacts-panel.group .contact-item');
            handleGroupContact(contactItem, allContacts);
        } else if (isDMContact) {
			console.log('DM Contact Clicked');
            allContacts = document.querySelectorAll('.contacts-panel.DirectMessages .contact-item');
            handleDMContact(contactItem, allContacts);
        }
    });
    
    // Helper function for group contacts
    function handleGroupContact(contactItem, allContacts) {
        const name = contactItem.querySelector('h4')?.textContent || 'Unknown';
        console.log('Selected group contact:', name, contactItem.id);
        // Remove active class from all and add to clicked one
        allContacts.forEach(contact => contact.classList.remove('active'));
        contactItem.classList.add('active');
        
        console.log('Selected group contact:', name, contactItem.id);
        
        if (contactItem.id) {
            fetchDataOrsendData(`/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetMessageInfo&GroupID=${contactItem.id}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
            .then(messageData => {
                console.log('Message Info:', messageData);
                const data = {
                    name: name,
                    messages: SaveMessageIntoArray(messageData),
                    message: 'TEXT',
                    membersRole: "ADMIN",
                };
                renderMessagePage(data);
            })
            .catch(error => {
                console.error('Message Info error:', error);
                alert('Failed to get message info');
            });
        }
    }
    
    // Helper function for DM contacts
    function handleDMContact(contactItem, allContacts) {
        const name = contactItem.querySelector('h4')?.textContent || 'Unknown';
        
        // Remove active class from all and add to clicked one
        allContacts.forEach(contact => contact.classList.remove('active'));
        contactItem.classList.add('active');
                
        if (contactItem.id) {
            // Show loading indicator or placeholder
            const messagepanel = document.querySelector('.messages-panel');
            messagepanel.innerHTML = '<div class="loading-messages">Loading messages...</div>';
            
            fetchDataOrsendData(`/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetMessageInfoForDM&FriendID=${contactItem.id}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
            .then(data => {
                console.log('DM Message Info (raw):', data);
				if (data.status === 'error') {
					console.error('Error fetching DM message info:', data.message);
				}else if (data.status === 'warning') {
					console.warn('Warning:', data.message);
					console.log('No chat log found - rendering empty message page');
					renderMessagePage({
						name: name,
						messages: [],
						status: 'Offline'
					});
				}else if (data.status === 'success') {
					if (data && data.data) {
						const messageData = data.data;
						console.log('Message Data Type:', messageData);
						
						// Convert message data to appropriate format if not "No Chat Log"

						// Handle empty chat
						console.log('No chat log found - rendering empty message page');
						renderMessagePage({
							name: name,
							messages: [],
							status: 'Offline'
						});
						
					} else {
						const ContactName = contactItem.querySelector('h4').textContent;
						const messageData = data.message;
						console.log('Message Data Type:', messageData);
							renderMessagePage({
								name: ContactName,
								messages: data.message,
								status: 'Offline'
							});

					}
				}
                

            })
            .catch(error => {
                console.error('DM Message Info error:', error);
                // Show error message in message panel
                const messagepanel = document.querySelector('.messages-panel');
                messagepanel.innerHTML = `<div class="error-message">Failed to load messages: ${error.message}</div>`;
            });
        }
    }
    
    console.log('Event delegation set up for contact items');
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
	console.log("Appending message to chat:", messageData);
	const messageContent = document.querySelector('.message-content');
	
	// Determine if this is the current user's message based on sender name
	const isCurrentUser = messageData.sender === 'You' || 
                          messageData.sender === getCookieValue('USERNAME');
	
	let messageDiv = document.createElement('div');
	// Set class based on sender rather than relying on message.type
	messageDiv.className = `message ${isCurrentUser ? 'sent' : 'received'}`;
	
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
		const GroupStatus = document.querySelector('.contacts-panel.group');
        
		if (GroupStatus.classList.contains('hidden')) {
			appendMessageToChat({
				sender: 'You',
				content: message,
				time: currentDateTime(),
				type: 'sent'
			});
			console.log('Active item:', activeItem.id);
			
			// Prepare message data
			const messageData = {
				message: message,
				FriendID: activeItem.id,
				messageType: 'TEXT',
				status: 'sent'
			};
			sendUserMessageToServer(messageData);
			messageInput.value = '';
		}else{
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
				status: 'sent'
			};
			
			console.log('Sending message data:', messageData);
			sendGroupMessageToServer(messageData);
			
			// Clear the input field after sending
			messageInput.value = '';
		}
        // Append message to chat UI
 
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

function SaveMessageIntoArray(messageData) {
    let messageArray = [];
    
    // Add debugging to see what we're receiving
    console.log("SaveMessageIntoArray input:", messageData);
    
    if (!Array.isArray(messageData)) {
        console.warn("messageData is not an array:", messageData);
        return [];
    }
    
	const Username = getCookieValue('USERNAME');
    
    for (let i = 0; i < messageData.length; i++) {
        const messageItem = messageData[i];
        
        // Set default values for missing properties
        const timestamp = messageItem.timestamp || messageItem.CreatedTime || null;
        const messageContent = messageItem.message || messageItem.MessageText || "";
        
        // Check if this message was sent by the current user
        const isSentByCurrentUser = messageItem.username === Username;
        console.log(`Message ${i} - isSentByCurrentUser: ${isSentByCurrentUser}`);
        
        let NewMessageData = {
            sender: isSentByCurrentUser ? "You" : (messageItem.SenderName || "Unknown"),
            content: messageContent,
            time: timestamp ? DisplayHourMin(timestamp) : "now",
            type: isSentByCurrentUser ? "sent" : "received",
            messageStatus: messageItem.messageStatus || "delivered"
        };
        
        messageArray.push(NewMessageData);
    }
    
    console.log("Processed messages:", messageArray);
    return messageArray;
}

function DirectMessageForm(prefilledEmail = '') {
	// Create overlay for the modal
	const overlay = document.createElement('div');
	overlay.className = 'DM-form-overlay';
	overlay.id = 'DM-form-overlay';
	
	// Create the form container
	const DMForm = document.createElement('div');
	DMForm.className = 'DM-form';
	DMForm.id = 'DM-form';
	
	// Add title
	const title = document.createElement('h3');
	title.textContent = 'Add friend';
	DMForm.appendChild(title);
	
	// Close button
	const closeButton = document.createElement('button');
	closeButton.className = 'close-form';
	closeButton.innerHTML = '&times;'; // × symbol
	closeButton.addEventListener('click', () => {
		document.body.removeChild(overlay);
	});
	DMForm.appendChild(closeButton);
	
	// Recipient email input
	const recipientEmail = document.createElement('input');
	recipientEmail.type = 'email';
	recipientEmail.placeholder = 'user Email';
	recipientEmail.className = 'recipient-email';
	recipientEmail.id = 'recipient-email';
	if (prefilledEmail) {
        recipientEmail.value = prefilledEmail;
    }

	const errorMessage = document.createElement('p');
	errorMessage.className = 'error-message-DM hidden';
	errorMessage.id = 'error-message-DM';
	errorMessage.textContent = '';
	errorMessage.style = 'color: red; font-size: 0.8rem; margin-top: 0.5rem;';

	const suggestions = document.createElement('ul');
	suggestions.id = 'suggestions';
	suggestions.style = "list-style: none; padding: 0;";

	// Create button
	const sendButton = document.createElement('button');
	sendButton.textContent = 'Add contact';
	sendButton.className = 'send-message-btn';
	sendButton.id = 'send-message-btn';

	// Add form event handler
	sendButton.addEventListener('click', function(e) {
		if (!errorMessage.classList.contains('hidden')){
			errorMessage.classList.add('hidden');
		}
		e.preventDefault();
		AddContact(recipientEmail, overlay);
	});

	DMForm.addEventListener('keydown', function(e) {
		console.log('Key:', e.key);
		console.log(DMForm);
		if (!errorMessage.classList.contains('hidden')){
			errorMessage.classList.add('hidden');
		}
		
		if (e.key === 'Escape') {
			document.body.removeChild(overlay);
		}
		if(e.key === 'Enter'){
			console.log('Enter pressed');
			e.preventDefault();
			AddContact(recipientEmail, overlay);
		}
	});

	// Add all elements to the form
	DMForm.appendChild(recipientEmail);
	DMForm.appendChild(errorMessage);
	DMForm.appendChild(suggestions);
	DMForm.appendChild(sendButton);
	
	// Add the form to the overlay and the overlay to the body
	overlay.appendChild(DMForm);
	document.body.appendChild(overlay);
	
	// Focus on the first field
	recipientEmail.focus();
	suggestionsForContact();

	

}

// Helper function to render a new direct message contact in the UI need to have ContactID, name, message, time
function renderDirectMessageContact(contactData) {
	const contactsList = document.querySelector('.contacts-panel.DirectMessages .contacts-list');
	// Create new contact item
	const contactItem = document.createElement('div');
	contactItem.className = 'contact-item';
	contactItem.id = contactData.ContactID;
	
	// Avatar
	const avatar = document.createElement('div');
	avatar.className = 'contact-avatar';
	const avatarIcon = document.createElement('span');
	avatarIcon.className = 'material-icons';
	avatarIcon.textContent = 'person';
	avatar.appendChild(avatarIcon);
	
	// Contact info
	const info = document.createElement('div');
	info.className = 'contact-info';
	const name = document.createElement('h4');
	name.textContent = contactData.name;
	const message = document.createElement('p');
	message.textContent = contactData.message;
	info.appendChild(name);
	info.appendChild(message);
	
	// Time
	const time = document.createElement('div');
	time.className = 'contact-time';
	time.textContent = DisplayHourMin(contactData.time);
	
	// Add all elements to contact item
	contactItem.appendChild(avatar);
	contactItem.appendChild(info);
	contactItem.appendChild(time);
	
	// Add to contacts list
	contactsList.appendChild(contactItem);
}

/**
 * Creates a debounced version of a function that delays its execution until after a specified delay
 * has passed since the last time it was invoked.
 * 
 * @param {Function} func - The function to debounce
 * @param {number} delay - The delay time in milliseconds
 * @returns {Function} A debounced function that will invoke the original function only after
 * the delay has passed without any new invocations
 */
function debounce(func, delay) {
	let timeout;
	return function() {
		const context = this;
		const args = arguments;
		clearTimeout(timeout);
		timeout = setTimeout(() => func.apply(context, args), delay);
	};
}

/**
 * Adds autocomplete functionality to the user email input field.
 * This function attaches an event listener to the email input which triggers
 * a debounced AJAX request to fetch email suggestions when the user types.
 * 
 * The suggestions appear only when the input contains at least 3 characters.
 * Each suggestion is displayed as a list item in the 'suggestions' element.
 * 
 * @function suggestionsForContact
 * @returns {void} This function does not return a value
 * @example
 * // Initialize email suggestions functionality
 * suggestionsForContact();
 */
function suggestionsForContact(){
	const recipientEmail = document.getElementById('recipient-email');
	recipientEmail.addEventListener('input', debounce(function (e){
		const ErrorElement = document.getElementById('error-message-DM');
		const suggestions = document.getElementById('suggestions');
		const recipientEmail = document.getElementById('recipient-email');
		const email = recipientEmail.value.trim();
		if (email.length < 3) {
			suggestions.innerHTML = '';
			return;
		}
		
		const query = e.target.value.trim();
		console.log('Query:', query);
		if (query.length < 3) {
			suggestions.innerHTML = '';
			return;
		}

		fetchDataOrsendData(`/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetSuggestions&Email=${email}`, {
			method: 'GET',
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			}
		})
		.then(data => {
			if (data) {
				console.log('Suggestions:', data);
				ErrorElement.classList.add('hidden');
				// Check if the response is an error
				if (data.status === 'error') {
					if (data.message === 'No user found with that email') {
						ErrorElement.textContent = data.message;
						ErrorElement.classList.remove('hidden');
						recipientEmail.focus();
						suggestions.innerHTML = '';
						console.log('No user found');
						return;
					}
				}
				// Clear the suggestions list
				if (data.length) {
					suggestions.innerHTML = '';
					data.forEach(suggestion => {
						suggestionListClickable(suggestion.email);
					});
				}
			}
		})
		.catch(error => {
			resultDiv.innerHTML = `<div style="color:red">Error: ${error.message}</div>`;
		});
	}
	, 500));

}

function suggestionListClickable(suggestedEmail){
	const suggestions = document.getElementById('suggestions');
	const li = document.createElement('li');
	const recipientEmail = document.getElementById('recipient-email');
	li.textContent = suggestedEmail;
	li.style = "cursor: pointer; padding: 5px;";
	suggestions.appendChild(li);
	li.addEventListener('click', function() {
		document.getElementById('recipient-email').value = this.textContent;
		suggestions.innerHTML = '';
		recipientEmail.focus();
	});
}

function VerifyDMContactListExist(){	
	fetchDataOrsendData ("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=VerifyDMContactListExist", {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
	.then(data => {
		if(data.status === 'success'){
			console.log('User already have DM contact list (Ignore)');
			// Fetch the contacts list from the server
			console.log("fetching DM contact list from database");
			renderDMContactList();
		}else if (data.status === 'warning' || data.status === 'error') {
			console.log('Warning:', data.message);
			console.log('User does not have DM contact list Create a DM contact list for user');
			// Create the contacts list in database
			CreateDMContactListForUser();
		}
	})
	.catch(error => {
		console.error('Contact List error:', error);
	})
}

function CreateDMContactListForUser(){
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php", {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json'
		},
		body: JSON.stringify({
			Type: 'CreateDMContactListForUser'
		})
	})
	.then(data => {
		if (data.status === 'success') {
			console.log('DM Contact List Created:', data);
			renderDMContactList();
		}else if(data.status === 'warning'){
			console.log('Warning:', data.message);
		}else{
			console.log('No groups found or invalid data format');
		}
	})
	.catch(error => {
		console.error('Default Page Error:', error);
		// Handle the error appropriately
	});
}

function renderDMContactList(){
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetContactListForDM", {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
	.then(data => {
		console.log('DM page success get data:', data);
		if (data.status === 'success') {
		// Check if data is an object and has properties
			if (data && typeof data === 'object') {
				// Loop through the group objects and render each one
				console.log(Object.values(data));
				Object.values(data.message).forEach(DM => {
					//ContactID, name, message, time
					const NewDMContactlist = {
						ContactID: DM.ID,
						name: DM.friendName,
						message: DM.MessageText,
						time: DM.created_at
					}
					renderDirectMessageContact(NewDMContactlist);
				});
			} else {
				console.log('No groups found or invalid data format');
			}
		}else if(data.status === 'warning'){
			console.log('Warning:', data.message);
		}else{
			console.log('No groups found or invalid data format');
		}
	})
	.catch(error => {
		console.error('Default Page Error:', error);
		// Handle the error appropriately
	});
}

function AddContact(recipientEmail, overlay){
	
	const ErrorElement = document.getElementById('error-message-DM');
	const suggestions = document.getElementById('suggestions');
	let errorMessage = [];
	if (!recipientEmail.value.trim()) {
		errorMessage.push('Please enter an email address');
	}
	
	if (!validateEmail(recipientEmail.value.trim())) {
		errorMessage.push('Please enter a valid email address');
	}
	if (recipientEmail.value.trim() === getCookieValue('email')) {
		errorMessage.push('You cannot add yourself as a contact');
	}

	if (errorMessage.length > 0) {
		console.log('Error:', errorMessage);
		ErrorElement.textContent = errorMessage.join(', ');
		ErrorElement.classList.remove('hidden');
		recipientEmail.value = '';
		recipientEmail.focus();
		return;
	}
	ErrorElement.classList.add('hidden');
	console.log('Adding contact:', recipientEmail.value.trim());
	// Check if the email is already in the contact list
	checkIfFriendInFriendContactList(recipientEmail, overlay, ErrorElement, errorMessage, suggestions);
}

function checkIfFriendInFriendContactList(recipientEmail, overlay, ErrorElement, errorMessage, suggestions){
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php", {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json'
		},
		body: JSON.stringify({
			Type: 'checkIfFriendInFriendContactList',
			Email: recipientEmail.value.trim()
		})
	})
	.then(data => {
		console.log('Check if the email is valid:', data);
		if (data.status === 'success') {
			if(data.message === 'Contact already exists' || data.message === 'Friend already exists in Contact List'){
				errorMessage.push('Contact already exists');
				ErrorElement.textContent = errorMessage.join(', ');
				ErrorElement.classList.remove('hidden');
				recipientEmail.focus();
				suggestions.innerHTML = '';
				return;
			}
		}				
		else if (data.status === 'warning') {
			if(data.message === 'Friend does not exist' || data.message === 'Contact does not exist'){
				console.log('Friend does not exist');
				// Send the email to the server to add the contact
				sendAddContact(recipientEmail, overlay);
			}
		}
		else{
			if(data.message === 'Friend does not exist in friend table'){
				sendAddContact(recipientEmail, overlay);

			}
			errorMessage.push('Failed to add contact');
			ErrorElement.textContent = errorMessage.join(', ');
			ErrorElement.classList.remove('hidden');
			recipientEmail.focus();
			suggestions.innerHTML = '';
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('Failed to add contact');
	})
}

function sendAddContact(recipientEmail, overlay){
	console.log('Adding contact:', recipientEmail.value.trim());
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php", {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'Accept': 'application/json'
		},
		body: JSON.stringify({
			Type: 'sendAddContact',
			Email: recipientEmail.value.trim()
		})
	})
	.then(data => {
		console.log(data)
		if (data.status === 'success') {
			if (data.message === 'User Does not exist in the contact list'){
				console.log('User Does not exist in the contact list');
				suggestions.innerHTML = '';
			}else{
				GetDMContactList(recipientEmail);
			}
			// Success case - no special handling needed
			console.log('Success:', data);
		}
		else if (data.status === 'warning') {
			// This is for users that don't exist yet
			console.log('Warning:', data.message);
			// Optional: Show a notification that the user doesn't exist yet
			// but will be invited when they register
		}
		recipientEmail.value = '';
		recipientEmail.focus();
		document.body.removeChild(overlay);
	})
	.catch(error => {
		console.error('Error:', error);
		alert('Failed to add contact');
	})
}

function GetDMContactList(recipientEmail){
	console.log('Get DM Contact List:', recipientEmail.value.trim());
	fetchDataOrsendData(`/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetOnlyOneContactListForDM&Email=${recipientEmail.value.trim()}`, {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
	.then(data => {
		console.log(data.message[0].ID);
		if (data.status === 'success') {
			// Check if data is an object and has properties
			if (data && typeof data === 'object') {
				// Loop through the group objects and render each one
				console.log(Object.values(data).ID);
				console.log(data.message.ID);
					//ContactID, name, message, time
				const NewDMContactlist = {
					FriendID: data.message[0].ID,
					name: data.message[0].friendName,
					message: data.message[0].MessageText,
					time: DisplayHourMin(data.message[0].created_at)
				}
				appendContactToContactList(NewDMContactlist);
			} else {
				console.log('No groups found or invalid data format');
			}
		}else if(data.status === 'warning'){
			console.log('Warning:', data.message);
		}else{
			console.log('No groups found or invalid data format');
		}
	})
	.catch(error => {
		console.error('Default Page Error:', error);
		// Handle the error appropriately
	});
}

/** 
 * Fetches the contact list from the server and renders it in the UI.
 * This function is called when the page loads.
 * 
 * @returns {void}
 * @example
 * // Fetch and render the contact list
 * fetchAndRenderContactList();
 * 
 * @note This function is called when the page loads
 * 
 * @todo Implement the function to fetch and render the contact list
 * 
 * @todo Implement the function to append a new contact to the contact list
 * 
 * @todo Implement the function to fetch the last message and time for each contact
 * 
 * @contactData {Object} contactData - The data for the contact to be appended
 * @contactData {string} contactData.name - The name of the contact
 * @contactData {string} contactData.message - The last message from the contact
 * @contactData {string} contactData.time - The time of the last message
 * @contactData {string} contactData.FriendID - The ID of the contact
 * 
 * @example
 * // Append a new contact to the contact list
 * appendContactToContactList({
 *   name: 'John Doe',
 *  message: 'Hello there!',
 * time: '12:30 PM',
 * FriendID: 123
 * });
 * 
 * @example
 * // Fetch the last message and time for each contact
 * fetchLastMessageAndTime();
 * 
*/

function appendContactToContactList(contactData) {
	const contactsList = document.querySelector('.contacts-panel.DirectMessages .contacts-list');
	// Create new contact item
	const contactItem = document.createElement('div');
	contactItem.className = 'contact-item';
	contactItem.id = contactData.FriendID;
	
	// Avatar
	const avatar = document.createElement('div');
	avatar.className = 'contact-avatar';
	const avatarIcon = document.createElement('span');
	avatarIcon.className = 'material-icons';
	avatarIcon.textContent = 'person';
	avatar.appendChild(avatarIcon);
	
	// Contact info
	const info = document.createElement('div');
	info.className = 'contact-info';
	const name = document.createElement('h4');
	name.textContent = contactData.name;
	const message = document.createElement('p');
	message.textContent = contactData.message;
	info.appendChild(name);
	info.appendChild(message);
	
	// Time
	const time = document.createElement('div');
	time.className = 'contact-time';
	time.textContent = DisplayHourMin(contactData.time);
	
	// Add all elements to contact item
	contactItem.appendChild(avatar);
	contactItem.appendChild(info);
	contactItem.appendChild(time);
	
	// Add to contacts list
	contactsList.appendChild(contactItem);
}

function fetchGroupContactListFromServer(){
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetGroupContactList", {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
	.then(data => {
		console.log('Contact List Group:', data);
		if (data.status === 'success') {
			const NewData = data.message;
			console.log('New Data:', NewData);
			NewData.forEach(contact => {
				let newGroupName = contact.GroupName.split("_");
				renderGroupContact({
					ContactID: contact.GroupID,
					name: newGroupName[0],
					message: contact.GroupMessages,
					time: contact.GroupMessageTime
				});
			});
		}
	})
	.catch(error => {
		console.error('Contact List error:', error);
	});
}

function renderGroupContact(contactData) {
	console.log('Contact:', contactData);
	const contactsList = document.querySelector('.contacts-panel.group .contacts-list.group');
	// Create new contact item
	const contactItem = document.createElement('div');
	contactItem.className = 'contact-item';
	contactItem.id = contactData.ContactID;

	// Avatar
	const avatar = document.createElement('div');
	avatar.className = 'contact-avatar';
	const avatarIcon = document.createElement('span');
	avatarIcon.className = 'material-icons';
	avatarIcon.textContent = 'group';
	avatar.appendChild(avatarIcon);

	// Contact info
	const info = document.createElement('div');
	info.className = 'contact-info';
	const name = document.createElement('h4');
	name.textContent = contactData.name;
	const message = document.createElement('p');
	message.textContent = contactData.message;
	info.appendChild(name);
	info.appendChild(message);

	// Time
	const time = document.createElement('div');
	time.className = 'contact-time';
	time.textContent = DisplayHourMin(contactData.time);

	// Add all elements to contact item
	contactItem.appendChild(avatar);
	contactItem.appendChild(info);
	contactItem.appendChild(time);
	
	// Add to contacts list
	contactsList.appendChild(contactItem);
}

function GetTheLastMessageAndTime(){
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php?Type=GetTheLastMessageAndTime", {
		method: 'GET',
		headers: {
			'Accept': 'application/json',
			'Content-Type': 'application/json'
		}
	})
	.then(data => {
		if(data.status === 'success'){
			data.message.forEach(contact => {
				console.log('Contact:', contact);
				renderDirectMessageContact({
					name: contact.friendName,
					message: contact.MessageText,
					time: contact.created_at,
					ContactID: contact.ID
				});
			});
		}
		// bind the contactData with the last message and time
	})
}

/**
 * Fetches data from or sends data to a specified URL.
 * 
 * @param {string} url - The URL to fetch from or send data to
 * @param {Object} options - The fetch configuration options
 * @param {string} options.method - The HTTP method (GET, POST, PUT, DELETE, etc.)
 * @param {Object} [options.headers] - The HTTP headers to include in the request
 * @param {string|Object} [options.body] - The request payload
 * @returns {Promise<Object|undefined>} A promise that resolves to the response data if successful, 
 *                                     or undefined if there's an error
 * @throws {Error} Throws an error if the HTTP response is not OK
 * 
 * @example
 * // GET request example
 * fetchDataOrsendData('https://api.example.com/data', { method: 'GET' })
 *   .then(data => console.log(data));
 * 
 * @example
 * // POST request example
 * fetchDataOrsendData('https://api.example.com/users', {
 *   method: 'POST',
 *   headers: { 'Content-Type': 'application/json' },
 *   body: JSON.stringify({ name: 'John', type: 'admin' }) // Remember to include Type in the body
 * });
 * 
 * @note Remember to specify Type in the request body when sending data
 */
async function fetchDataOrsendData(url, options) {
    try {
        const response = await fetch(url, options);
        
        // Try to get response text first to debug issues
        const text = await response.text();
        
        // Check if the response looks like HTML (indicates PHP error)
        if (text.trim().startsWith('<')) {
            console.error("Server returned HTML instead of JSON:", text);
            throw new Error("Server returned HTML instead of JSON. Check server logs.");
        }
        
        // Parse as JSON if it looks valid
        try {
            const data = JSON.parse(text);
            return data;
        } catch (jsonError) {
            console.error("JSON Parse Error:", jsonError);
            console.error("Raw response:", text);
            throw new Error("Failed to parse server response as JSON");
        }
    } catch(error) {
        console.error('Fetch Error:', error);
        throw error;
    }
}

/**
 * Creates and displays a friend suggestion popup in the message content area
 * @param {Object} userData - User data for the person to suggest adding as friend
 * @param {string} userData.name - Name of the user
 * @param {string} userData.email - Email of the user (optional)
 */
function showFriendSuggestion(userData) {
    const messageContent = document.querySelector('.message-content');
    if (!messageContent) return;
    
    // Check if suggestion already exists
    if (document.querySelector('.friend-suggestion-popup')) {
        return;
    }
    
    // Create the suggestion popup
    const popup = document.createElement('div');
    popup.className = 'friend-suggestion-popup';
    
    // Content section (left side)
    const content = document.createElement('div');
    content.className = 'friend-suggestion-content';
    
    // Avatar
    const avatar = document.createElement('div');
    avatar.className = 'friend-suggestion-avatar';
    const avatarIcon = document.createElement('span');
    avatarIcon.className = 'material-icons';
    avatarIcon.textContent = 'person';
    avatar.appendChild(avatarIcon);
    content.appendChild(avatar);
    
    // User info
    const info = document.createElement('div');
    info.className = 'friend-suggestion-info';
    const name = document.createElement('h4');
    name.textContent = userData.name;
    const description = document.createElement('p');
    description.textContent = 'Add as your friend';
    info.appendChild(name);
    info.appendChild(description);
    content.appendChild(info);
    
    popup.appendChild(content);
    
    // Actions section (right side)
    const actions = document.createElement('div');
    actions.className = 'friend-suggestion-actions';
    
    // Add friend button
    const addButton = document.createElement('button');
    addButton.className = 'add-friend-btn';
    addButton.innerHTML = '<span class="material-icons">person_add</span> Add friend';
    addButton.addEventListener('click', function() {
        // Handle adding the contact
        handleAddFriend(userData);
        popup.remove();
    });
    actions.appendChild(addButton);
    
    // Dismiss button
    const dismissButton = document.createElement('button');
    dismissButton.className = 'dismiss-suggestion-btn';
    dismissButton.innerHTML = '<span class="material-icons">close</span>';
    dismissButton.addEventListener('click', function() {
        popup.remove();
    });
    actions.appendChild(dismissButton);
    
    popup.appendChild(actions);
    
    // Insert at the top of the message content
    messageContent.insertBefore(popup, messageContent.firstChild);
}

/**
 * Handles adding a friend from the suggestion popup
 * @param {Object} userData - User data for the person being added
 */
function handleAddFriend(userData) {
	const CurrentUserID = document.querySelector('.contact-item.active');
    console.log(`Adding ${userData.name} as a contact`);
    console.log('Current User:', CurrentUserID.id);
    // // If there's an email available, use it to pre-fill the add contact form
	if (CurrentUserID){
		SendFriendRequest({
			CurrentUserID: CurrentUserID.id,
			CurrentFriendName: userData.name,
			status: 'Pending'
		});
	}
}

// no type in php
function SendFriendRequest(messageData){
	messageData.Type = 'SendFriendRequest';
	fetchDataOrsendData("/RWD_assignment/FocusFlow/RegisterLayout/Communication/Message.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
			"Accept": "application/json"
		},
		body: JSON.stringify(messageData)
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
 * Modified DirectMessageForm that can accept a pre-filled email
 * @param {string} prefilledEmail - Optional email to pre-fill in the form
 */

// You might also want to add a function to check friendship status
// This is a placeholder that would normally check against your database
function checkIfFriends(userId) {
    // For demo purposes, randomly return true or false
    // In a real app, this would check your database
    const isFriend = Math.random() > 0.5;
    return isFriend;
}