<?php 
$students = new Students();
$db = DB::getInstance();
$results = $db->get_all("students_details", "Name")->results();

if (Input::exists("post")):
    $year = Input::get('year');
    $department = Input::get('department');
    $sem = Input::get('sem');
    $exam = Input::get('exam');
    $subject = Input::get('subject');
   
    
?>
 <link rel="stylesheet" href="./css/addmarks.css">
    <table>
        <thead>
            <tr>
                <th>Joining Year</th>
                <th>Select Department</th>
                <th>Select Semester</th>
                <th>Exam Type</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= $year; ?></td>
                <td><?= $department; ?></td>
                <td><?= $sem; ?></td>
                <td><?= $exam; ?></td>
                <td><?= $subject; ?></td>
            </tr>
        </tbody>
    </table>
    <table>
        <thead>
            <tr>
                <th>Register Number</th>
                <th>Student Name</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody id="studentMarks">
            <tr>
                <td>A32476298</td>
                <td>Student 1</td>
                <td><input type="text" name="marks[]" value="80" width="60px"></td>
            </tr>
        </tbody>
    </table>
    <div class="button-container">
        <button type="submit">Submit</button>
        <button type="button" onclick="window.history.back();">Back</button>
    </div>

<?php
else:
    // Redirect::to ("./admin.php?admin=addinternalmark");
    echo "Error";
endif;
?>