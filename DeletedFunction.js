// ===== Validate start and end date

let inputElement = element.querySelector(".INPUT__INPUT");
let inputValue = inputElement.value;

if (inputElement.id == "start_date" && inputValue) {
    // it will be assign null if no value is entered
    StartDate = inputValue || null;;
} else if (inputElement.id == "due_date" && inputValue) {
    DueDate = inputValue || null;;
}


// Convert to Date objects for proper comparison
let start = StartDate ? new Date(StartDate) : null;
let due = DueDate ? new Date(DueDate) : null;

if (start > due && (inputElement.id == "start_date" || inputElement.id == "due_date")) {
    InvalidInput(getQuery("#due_date"), getQuery("#due_date_ph"));
    InvalidInput(getQuery("#start_date"), getQuery("#start_date_ph"));
} else if (start) {
    ValidInput(getQuery("#start_date"), getQuery("#start_date_ph"));
} else if (due) {
    ValidInput(getQuery("#due_date"), getQuery("#due_date_ph"));
}