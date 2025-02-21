<?php
class Students{

    private $_db;

    public function __construct(){
        $this->_db = DB::getInstance();
    }

    public function get_batch_id($year, $department){
        $results = $this->_db->query("SELECT `id` from `batch` WHERE `Year_of_joining`= ? AND `Department` = ?;",[$year, $department])->first()->id;
        return $results;
    }

    public function get_subjects($year, $department, $semester){
        $batch_id = $this->get_batch_id($year, $department);
        $results = $this->_db->query("SELECT * FROM `batch_subjects` WHERE `batch_id` = ? AND `semester` = ?;",[$batch_id, $semester])->results();
        return $results;
    }

    public function generate_students($year, $department){
        $students = $this->_db->query("SELECT * FROM `students_details` WHERE `Department` = ? and YEAR(`Joining_year`) = ?;",[$department, $year])->results();
        return $students;
    }


    public function generate_students_with_subjects($year, $department) {
        $students = $this->_db->query("SELECT `students_details.id`, `students_details.Register_number`, `students_details.Name`, `batch.Subject`FROM 
        `students_details`JOIN `batch` ON `students_details.batchid` = `batch.batchid` WHERE `students_details.Department` = ? 
        AND `students_details.Joining_year` = ?;", [$department, $year])->results();
        return $students;
    }
    
    public function get_subject_id($batch_id, $sem){
        $results = $this->_db->query("SELECT `id`, `subject` FROM `batch_subjects` WHERE `batch_id` = ? and `semester` = ?;", [$batch_id, $sem])->results();
        return $results;
    }
    
    // public function get_student_subject($batch_id, $subject_id, $student_id, ){
    //     $results = $this->_db->query("SELECT * FROM `internal_marks` WHERE `batch_id` = ? and `semester` = ? and `student_id` = ?;", [$batch_id, $sem, $student_id])->results();
    //     return $results;
    // }

    public function get_marks($batch_id, $subject_id, $student_id, $internal = null) {
        // Prepare the SQL query based on whether we need internal marks or semester marks
        if (!empty($internal)) {
            // Query for internal marks
            $results = $this->_db->query(
                "SELECT * FROM `internal_marks` WHERE `batch_id` = ? AND `subject_id` = ? AND `student_id` = ? AND `exam` = ?", 
                [$batch_id, $subject_id, $student_id, $internal]
            )->results();
        } else {
            // Query for semester marks
            $results = $this->_db->query(
                "SELECT * FROM `semester_marks` WHERE `batch_id` = ? AND `subject_id` = ? AND `student_id` = ?", 
                [$batch_id, $subject_id, $student_id]
            )->results();
        }
    
        // Check if any results were returned
        if (empty($results)) {
            return null; // Return null if no marks found
        }
    
        return $results; // Return the results
    }
    public function get_unique_exams($batch_id, $subject_id, $student_id) {
       
        $internalExams = $this->_db->query("SELECT DISTINCT(`exam`) FROM `internal_marks` WHERE `batch_id` = ? AND `subject_id` = ? AND `student_id` = ?", [$batch_id, $subject_id, $student_id])->results();
        $semesterExams = $this->_db->query("SELECT DISTINCT(`exam`) FROM `semester_marks` WHERE `batch_id` = ? AND `subject_id` = ? AND `student_id` = ?", [$batch_id, $subject_id, $student_id])->results();
        $allExams = array_merge($internalExams, $semesterExams);
        $uniqueExams = [];
        foreach ($allExams as $exam) {
            if (!in_array($exam->exam, $uniqueExams)) {
                $uniqueExams[] = $exam->exam;
            }
        }
    
        return $uniqueExams;
    }

    public function get_subjectmark($sub_id){
        $results = $this->_db->get("batch_subjects",["id","=",$sub_id])->first();
        return $results->subject;
    }
    public function get_attendance($student_id, $year, $month) {
        $query = "SELECT * FROM attendance WHERE student_id = ? AND YEAR(date) = ? AND MONTH(date) = ?";
        $results = $this->_db->query($query, [$student_id, $year, $month])->results();
        
        // Debugging output
        error_log("Query: $query, Params: [$student_id, $year, $month], Results: " . print_r($results, true));
        
        return $results;
    }
    
    function getYearFromDate($date) {
        // Create a DateTime object from the provided date
        $dateTime = new DateTime($date);
        
        // Extract the year from the DateTime object
        $year = $dateTime->format('Y');
        
        return $year;
    }
    
    public function get_number_of_years($year, $department) {
        $db = DB::getInstance();
        return $db->query(
            "SELECT Number_of_years FROM batch WHERE Year_of_joining = ? AND Department = ?",
            [$year, $department]
        )->first()->Number_of_years;
    }
            public function get_subject_ids($batch_id, $sem) {
            // Query the subject_allocation table based on batch_id (year + department) and semester
            $year = $batch_id['year'];  // Assuming batch_id contains year information
            $department = $batch_id['department'];  // Assuming batch_id contains department information
    
            $query = "SELECT id, Subject, Subject_id FROM subject_allocation 
                      WHERE Year = ? AND Department = ? AND Sem = ?";
    
            // Prepare and execute the query with the relevant parameters
            $subjects = $this->_db->query($query, [$year, $department, $sem])->results();
    
            return $subjects;  // Return the result (list of subjects)
        }
    
        
    
    
    
    
}