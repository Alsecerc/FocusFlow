console.log("hello")

document.addEventListener("DOMContentLoaded", fetchFiles);

function fetchFiles() {
    fetch("FileBackend.php?action=fetch")
        .then(response => response.json())
        .then(data => displayFiles(data))
        .catch(error => console.error("Error fetching files:", error));
}

function displayFiles(files) {
    const fileList = document.getElementById("fileList");
    fileList.innerHTML = "";

    files.forEach(file => {
        let row = document.createElement("tr");
        row.innerHTML = `
            <td>${file.file_name}</td>
            <td>${file.file_type}</td>
            <td>${file.file_size} KB</td>
            <td>${file.uploaded_at}</td>
            <td>
                <button class="btn btn-view" onclick="viewFile('${file.id}', '${file.file_name}', '${file.file_type}', '${file.file_size}', '${file.uploaded_at}')">View</button>
                <button class="btn btn-delete" onclick="deleteFile('${file.id}')">Delete</button>
            </td>
        `;
        fileList.appendChild(row);
    });
}

// download file change php and js

function viewFile(id, name, type, size, date) {
    document.getElementById("fileName").innerText = name;
    document.getElementById("fileType").innerText = type;
    document.getElementById("fileSize").innerText = size + " KB";
    document.getElementById("fileDate").innerText = date;

    document.getElementById("downloadBtn").onclick = () => downloadFile(id);
    document.getElementById("deleteBtn").onclick = () => confirmDelete(id, name);

    const previewContainer = document.getElementById("filePreviewContainer");
    previewContainer.innerHTML = ""; 

    let fileUrl = `FileBackend.php?action=preview&id=${id}`; 

    if (type.startsWith("image/")) {
        previewContainer.innerHTML = `<img src="${fileUrl}" alt="${name}" style="max-width:100%; border-radius:10px;">`;
    } else if (type.startsWith("video/")) {
        previewContainer.innerHTML = `<video controls style="max-width:100%;">
                                          <source src="${fileUrl}" type="${type}">
                                          Your browser does not support the video tag.
                                      </video>`;
    } else if (type === "application/pdf") {
        previewContainer.innerHTML = `<iframe src="${fileUrl}" style="width:100%; height:400px; border:none;"></iframe>`;
    } else {
        previewContainer.innerHTML = `<p>Preview not available for this file type.</p>`;
    }
}

// TODO: DOWNLOAD
function downloadFile(id) {
    window.location.href = `FileBackend.php?action=download&id=${id}`;
}

//TODO: DELETE FUNCTION
function deleteFile(id) {
    if (!confirm("Are you sure you want to delete this file? This action cannot be undone.")) {
        return;
    }

    fetch(`FileBackend.php?action=delete&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                location.reload();
            } else {
                alert(data.error);
            }
        })
        .catch(error => console.error("Error deleting file:", error));
}

// TODO: SEARCH AND FILTER
function searchFiles() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let rows = document.querySelectorAll("#fileTable tbody tr");

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

function filterFiles() {
    let selectedFilter = document.querySelector('input[name="filterType"]:checked').value;
    let rows = document.querySelectorAll("#fileTable tbody tr");

    rows.forEach(row => {
        let fileType = row.children[1].innerText; // Assuming file type is in the 2nd column
        row.style.display = (selectedFilter === "" || fileType === selectedFilter) ? "" : "none";
    });
}

function sortFiles() {
    let selectedSort = document.querySelector('input[name="sortOption"]:checked').value;
    let table = document.getElementById("fileTable");
    let rows = Array.from(table.querySelectorAll("tbody tr"));

    rows.sort((a, b) => {
        let valA, valB;
        if (selectedSort === "name") {
            valA = a.children[0].innerText.toLowerCase(); // File Name
            valB = b.children[0].innerText.toLowerCase();
        } else if (selectedSort === "size") {
            valA = parseInt(a.children[2].innerText); // File Size
            valB = parseInt(b.children[2].innerText);
        } else if (selectedSort === "date") {
            valA = new Date(a.children[3].innerText); // Uploaded At
            valB = new Date(b.children[3].innerText);
        }
        return valA > valB ? 1 : -1;
    });

    let tbody = table.querySelector("tbody");
    tbody.innerHTML = "";
    rows.forEach(row => tbody.appendChild(row));
}