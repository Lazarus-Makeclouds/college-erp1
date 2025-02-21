<style>
    body {
        font-family: 'Times New Roman', Times, serif, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
    }

    #header {
        background-color: #007BFF;
        color: white;
        padding: 15px 20px;
        text-align: center;
    }

    .container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    select {
        width: 100%;
        padding: 10px;
        font-size: 14px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background-color: #ffffff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    button {
        width: 100%;
        padding: 10px 15px;
        color: white;
        background-color: #007BFF;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #0056b3;
    }
</style>

<body>
    <header id="header">
        <h1>Question Bank</h1>
    </header>
    <main class="container">
        <div class="form-group">
            <label for="year">Joining Year:</label>
            <select id="year" name="year" required>
                <option value="">--Select Year--</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
            </select>
        </div>
        <div class="form-group">
            <label for="department">Select Department:</label>
            <select id="department" name="department" required>
                <option value="">--Select Department--</option>
                <option value="Computer Science">Computer Science</option>
                <option value="BCA">BCA</option>
                <option value="BBA">BBA</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" onclick="submitForm()">Submit</button>
        </div>
    </main>
    <div class="grid">
        <div class="card">
            <div class="row"><span class="label">Subject:</span>Data Structures</div>
            <div class="row"><span class="label">Subject Code:</span>CS101</div>
            <button onclick="downloadFile('cs101.pdf')">Download</button>
        </div>
        <div class="card">
            <div class="row"><span class="label">Subject:</span>Microprocessors</div>
            <div class="row"><span class="label">Subject Code:</span>CA201</div>
            <button onclick="downloadFile('ca201.pdf')">Download</button>
        </div>
        <div class="card">
            <div class="row"><span class="label">Subject:</span>Thermodynamics</div>
            <div class="row"><span class="label">Subject Code:</span>BA301</div>
            <button onclick="downloadFile('ba301.pdf')">Download</button>
        </div>
    </div>

    <script>
        function downloadFile(filename) {
            alert('Downloading ' + filename);

        }

        function submitForm() {
            var year = document.getElementById('year').value;
            var department = document.getElementById('department').value;

            if (year && department) {
                alert('Form Submitted!\nYear: ' + year + '\nDepartment: ' + department);
            } else {
                alert('Please select both Year and Department.');
            }
        }
    </script>
</body>

</html>