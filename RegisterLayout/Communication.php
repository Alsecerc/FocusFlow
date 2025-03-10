<?php
include "conn.php";
include "AccountVerify.php";
requireAuthentication($_conn);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication Hub</title>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="icon" href="img\SMALL_CLOCK_ICON.ico">
    <link rel="stylesheet" href="Registered.css">
    <link rel="stylesheet" href="Responsive.css">
    <link rel="stylesheet" href="Communication/Communication.css">
</head>

<body>
    <?php
    include "header.php";
    ?>
    <main class="CALENDAR__MAIN">
        <?php
        include "sidebar.php";
        ?>
        
        <div class="communication-container">
            <div class="communication-header">
                <div class="header-top-row">
                    <h2>Communication Hub</h2>
                    <button id="createGroupBtn" class="create-group-btn">
                        <span class="material-icons">add</span>
                        Create Group
                    </button>
                    <button id="addContactBtn" class="add-contact-btn">
                        <span class="material-icons">add</span>
                        Add Contact
                    </button>
                </div>
                <div class="communication-tabs">
                    <button class="tab-button active" data-tab="direct">Direct Messages</button>
                    <button class="tab-button" data-tab="groups">Groups</button>
                </div>
            </div>
            
            <div class="communication-content">
                <!-- Left panel for contacts and groups -->
                <div class="contacts-panel DirectMessages">
                    <div class="search-box">
                        <span class="material-icons">search</span>
                        <input type="text" placeholder="Search contacts or groups...">
                    </div>
                    
                    <div class="contacts-list DirectMessages">
                        <!-- Contacts will be dynamically loaded here -->
                        <div class="contact-item">
                            <div class="contact-avatar">
                                <span class="material-icons">account_circle</span>
                            </div>
                            <div class="contact-info">
                                <h4>John Doe</h4>
                                <p>Last message preview...</p>
                            </div>
                            <div class="contact-time">12:30</div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-avatar">
                                <span class="material-icons">account_circle</span>
                            </div>
                            <div class="contact-info">
                                <h4>Jane Smith</h4>
                                <p>Last message preview...</p>
                            </div>
                            <div class="contact-time">10:45</div>
                        </div>
                        
                    </div>
                </div>
                <div class="contacts-panel group hidden">
                    <div class="search-box">
                        <span class="material-icons">search</span>
                        <input type="text" placeholder="Search contacts or groups...">
                    </div>
                    <div class="contacts-list group">
                    </div>
                </div>
                
                <!-- Right panel for messages -->
                <div class="messages-panel">

                </div>
            </div>
        </div>
    </main>

<script src="Registered.js" defer></script>
<script src="Communication/Message.js" defer></script>
</body>

</html>