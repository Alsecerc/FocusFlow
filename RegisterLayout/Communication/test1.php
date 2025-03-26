<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Contact API</title>
    <style>
        body {
            background-color: #121212;
            color: #f0f0f0;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        #test {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        #test:hover {
            background-color: #555;
        }
        #result {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #444;
            background-color: #1e1e1e;
            border-radius: 4px;
            color: #ddd;
        }
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <button id="test">Test</button>
    <div id="result" style="margin-top: 20px; padding: 10px; border: 1px solid #ddd;"></div>
    
    <script>
document.getElementById("test").addEventListener("click", function() {
    const resultDiv = document.getElementById("result");
    resultDiv.innerHTML = "Sending request...";
    
    fetchDataOrsendData("test.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            case: "GetContactListForDM",  // Changed from "action" to "case" to match server expectation
            name: "sigma",
            description: "testing",
            members: ["poopie@gmail.com", 'janesmith@example.com', 'michaelbrown@example.com', 'test@gmail.com'],
            role: "MEMBER",
            Email: "loltian8112@gmail.com",
            FriendID: 1,
            message: "Hello, this is a test message.",
            MessageType: "TEXT",
            status: "None",
            ContactID: 1
        })
    })
    .then(data => {
        if (data) {
            resultDiv.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `<div style="color:red">Error: ${error.message}</div>`;
    });
});

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
    </script>
</body>
</html>
