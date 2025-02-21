<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 80%;
        margin: 20px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .controls {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        text-align: center;
        margin-top: 20px;
    }

    .calendar .day {
        padding: 10px;
        background-color: #fff;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
    }

    .calendar .day:hover {
        background-color: #f1f1f1;
    }

    .calendar .day[data-status="W"] {
        background-color: #4CAF50;
        color: white;
    }

    .calendar .day[data-status="L"] {
        background-color: #FFEB3B;
        color: black;
    }

    .calendar .day[data-status="A"] {
        background-color: #F44336;
        color: white;
    }

    .calendar .header {
        font-weight: bold;
        background-color: #f1f1f1;
    }

    .summary {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
        font-weight: bold;
    }

    .summary div {
        padding: 10px;
        background-color: #f1f1f1;
        border-radius: 4px;
    }

    .submit-btn {
        display: block;
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background-color: #45a049;
    }
</style>

<body>

    <div class="container">
        <h1>Attendance Tracker</h1>

        <div class="controls">
            <div>
                <label for="month-selector">Month: </label>
                <select id="month-selector">
                    <option value="0">January</option>
                    <option value="1">February</option>
                    <option value="2">March</option>
                    <option value="3">April</option>
                    <option value="4">May</option>
                    <option value="5">June</option>
                    <option value="6">July</option>
                    <option value="7">August</option>
                    <option value="8">September</option>
                    <option value="9">October</option>
                    <option value="10">November</option>
                    <option value="11">December</option>
                </select>
            </div>
            <div>
                <label for="year-selector">Year: </label>
                <input type="number" id="year-selector" value="2025" min="2020" max="2100">
            </div>
            <button onclick="generateCalendar()">Generate Calendar</button>
        </div>

        <h2 id="month-year-header" style="text-align: center;"></h2>

        <div class="calendar" id="calendar">
            <div class="header">Sun</div>
            <div class="header">Mon</div>
            <div class="header">Tue</div>
            <div class="header">Wed</div>
            <div class="header">Thu</div>
            <div class="header">Fri</div>
            <div class="header">Sat</div>
        </div>

        <div class="summary">
            <div>Working Days: <span id="working-days">0</span></div>
            <div>Leave Days: <span id="leave-days">0</span></div>
            <div>Absent Days: <span id="absent-days">0</span></div>
        </div>

        <button class="submit-btn" onclick="submitAttendance()">Submit</button>
    </div>

    <script>
        // Initial values for current month and year
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();

        // Generate the calendar based on selected month and year
        function generateCalendar() {
            currentMonth = document.getElementById('month-selector').value;
            currentYear = document.getElementById('year-selector').value;

            const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
            const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();

            // Update the month and year header
            const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            document.getElementById('month-year-header').textContent = `${monthNames[currentMonth]} ${currentYear}`;

            const calendarContainer = document.getElementById('calendar');

            // Clear the calendar before generating new one
            calendarContainer.innerHTML = `
            <div class="header">Sun</div>
            <div class="header">Mon</div>
            <div class="header">Tue</div>
            <div class="header">Wed</div>
            <div class="header">Thu</div>
            <div class="header">Fri</div>
            <div class="header">Sat</div>
        `;

            let dayCount = 1;

            // Add empty days before the first day of the month
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDay = document.createElement('div');
                emptyDay.classList.add('day');
                calendarContainer.appendChild(emptyDay);
            }

            // Add days of the month
            for (let i = firstDayOfMonth; i < firstDayOfMonth + daysInMonth; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.classList.add('day');
                dayDiv.textContent = dayCount;
                dayDiv.dataset.day = dayCount;
                dayDiv.dataset.status = '';
                dayDiv.onclick = () => toggleAttendanceStatus(dayDiv);
                calendarContainer.appendChild(dayDiv);
                dayCount++;
            }

            updateSummary();
        }

        // Toggle attendance status for the clicked day
        function toggleAttendanceStatus(dayDiv) {
            let status = dayDiv.dataset.status;
            if (status === 'W') {
                status = 'L';
            } else if (status === 'L') {
                status = 'A';
            } else {
                status = 'W';
            }
            dayDiv.dataset.status = status;
            dayDiv.classList.remove('W', 'L', 'A');
            dayDiv.classList.add(status);
            updateSummary();
        }

        // Update the attendance summary
        function updateSummary() {
            let workingDays = 0;
            let leaveDays = 0;
            let absentDays = 0;

            const days = document.querySelectorAll('.day');
            days.forEach(day => {
                const status = day.dataset.status;
                if (status === 'W') {
                    workingDays++;
                } else if (status === 'L') {
                    leaveDays++;
                } else if (status === 'A') {
                    absentDays++;
                }
            });

            document.getElementById('working-days').textContent = workingDays;
            document.getElementById('leave-days').textContent = leaveDays;
            document.getElementById('absent-days').textContent = absentDays;
        }

        // Submit the attendance and show the result
        function submitAttendance() {
            const workingDays = document.getElementById('working-days').textContent;
            const leaveDays = document.getElementById('leave-days').textContent;
            const absentDays = document.getElementById('absent-days').textContent;

            alert(`Attendance Submitted!\nWorking Days: ${workingDays}\nLeave Days: ${leaveDays}\nAbsent Days: ${absentDays}`);
        }

        // Generate the calendar for the current month on page load
        window.onload = generateCalendar;
    </script>

</body>

</html>