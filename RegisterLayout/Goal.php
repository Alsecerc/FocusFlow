<form action="GoalAdd.php" method="POST">
    <label>Goal Title:</label>
    <input type="text" name="goal_title" required>

    <label>Description:</label>
    <textarea name="goal_description"></textarea>

    <label>Goal Type:</label>
    <select name="goal_type" required>
        <option value="short-term">Short-Term</option>
        <option value="long-term">Long-Term</option>
    </select>

    <label>Start Date:</label>
    <input type="date" name="start_date" required>

    <label>End Date:</label>
    <input type="date" name="end_date" required>

    <label>Reminder Time:</label>
    <input type="datetime-local" name="reminder_time">

    <button type="submit">Set Goal</button>
</form>
