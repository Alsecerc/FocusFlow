<?php
include "conn.php";
include "AccountVerify.php";
requireAuthentication($_conn);

// Add 'unsafe-inline' to the style-src directive to allow inline styles
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; form-action 'self'; base-uri 'self';");

// For older browsers that use X-Content-Security-Policy 
header("X-Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'; frame-ancestors 'none'; form-action 'self'; base-uri 'self';");

// For reporting CSP violations (optional, but useful for debugging)
// header("Content-Security-Policy-Report-Only: default-src 'self'; report-uri /csp-violation-report.php");
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
    <!-- Fix path to FormValidation.js -->
    <script type="module" src="Communication/FormValidation.js"></script>
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
<script src="Communication/Message.js" type="module" defer></script>
</body>

</html>