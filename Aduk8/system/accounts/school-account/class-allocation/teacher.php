<?php 
require_once("../includes/layout.php");

$gradeLevels = getGradesForExistingClasses($conn, $center_no);
$activeClasses = getActiveClasses($conn, $center_no);
$schoolTeachers = getSchoolStaff($conn, $center_no,' Teacher ');
if(isset($gradeLevels['message'])) {
    kickout($registerGradeLevelFile, 'No grade levels found, please register a grade level first');
}
if(isset($activeClasses['message'])) {
    kickout($registerClassFile, 'No active classes found, please register a class first');
}
if(isset($schoolTeachers['message'])) {
    kickout($registerStaffFile, 'No active teachers found, please register a teacher first');
}
?>
<head>
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?>'s Teacher Class Allocation</title>
    <style>
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px;
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #ddd; 
        }
        th { 
            background-color: var(--hover-light); 
            position: sticky;
            top: 0;
        }
        tr:hover { 
            background-color: var(--hover-light); 
        }
        .no-data { 
            text-align: center; 
            padding: 20px; 
            color: #666; 
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: none;
        }
        .input-error {
            border-color: #e74c3c !important;
        }
        #responseMessage {
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
            display: none;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .subject-checkbox {
            transform: scale(1.3);
            margin: 0;
        }
        .input-error {
            border: 1px solid #f44336 !important;
        }
        .error-text {
            color: #f44336;
            font-size: 0.8em;
            display: none;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="content">
                <h2><?= htmlspecialchars($schoolInformation['school_name'])?> Teacher Class Allocation</h2>
                
                <div class="progress-indicator" id="progressIndicator"></div>
                <div id="responseMessage"></div>
                
                <form method="post" id="classRegistrationForm">
                    <section class="active" id="classRegistrationSection">
                        <h4>Teacher and Class Allocation Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="grade"class="required-field" >Class Grade</label>
                                <select name="grade" id="grade">
                                    <?php if (isset($gradeLevels['message'])): ?>
                                        <option value="" disabled selected><?= htmlspecialchars($gradeLevels['message']); ?></option>
                                    <?php else: ?>
                                        <option value="" disabled selected>Please select the student's grade level</option>
                                        <?php foreach ($gradeLevels as $gradeLevel): ?>
                                            <option value="<?= htmlspecialchars($gradeLevel["grade_no"]); ?>"><?= htmlspecialchars($gradeLevel["grade_name"]); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                          
                                </select>
                            </div>
                            <div class="col-row">
                                <label for="class" class="required-field">Class</label>
                                <select name="class" id="class" required>
                                    <?php if (isset($activeClasses['message'])): ?>
                                        <option value="" disabled selected><?= htmlspecialchars($activeClasses['message']); ?></option>
                                    <?php endif; ?>  
                                </select>                       
                            </div>
                            <div class="col-row">
                                <label for="teacher" class="required-field">Teacher</label>
                                <select name="teacher" id="teacher" required>
                                <?php if (isset($schoolTeachers['message'])): ?>
                                        <option value="" disabled selected><?= htmlspecialchars($schoolTeachers['message']); ?></option>
                                    <?php else: ?>
                                        <option value="" disabled selected>Please select a teacher</option>
                                        <?php foreach ($schoolTeachers as $teacherInformation): ?>
                                            <option value="<?= htmlspecialchars($teacherInformation["user_id"]); ?>"><?= htmlspecialchars($teacherInformation["prefix"].' '.$teacherInformation["first_name"][0].'. '.$teacherInformation["last_name"]); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>  
                                </select>                       
                            </div>
                        </div>
                        <br>
                        <div class="table-container">
                            <table id="classesTable">
                                <thead>
                                    <tr>
                                        <th class="required-field">Allocate</th>
                                        <th class="required-field">subject Code</th>
                                        <th class="required-field">Subject Name</th>
                                        <th class="required-field">Classification</th>
                                        <th class="required-field">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $students = getStudentsNotInClasses($conn, $center_no);
                                    if (isset($students['message'])): 
                                        echo '<tr><td colspan="5" class="no-data">'.htmlspecialchars($students["message"]).'</td></tr>'; 
                                    else: 
                                        foreach ($students as $student): 
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_classes[]" class="class-checkbox" value="<?= htmlspecialchars($student["user_id"]); ?>">
                                                </td>
                                                <td><?= htmlspecialchars($student["first_name"].' '.$student["middle_name"].' '.$student["last_name"]); ?></td>
                                                <td><?= htmlspecialchars($student["date_of_birth"]); ?></td>
                                                <td><?= htmlspecialchars($student["gender"]?? ''); ?></td>
                                                <td><?= htmlspecialchars($student["nationality"]); ?></td>
                                            </tr>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="buttons-container">        
                            <button type="button" class="btn-cancel" onclick="confirmCancel()"><i class="fa fa-times"></i> Close</button>
                            <button type="submit" id="submitBtn" class="btn-primary"><i class="fa fa-check" ></i> Allocate</button>
                        </div>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const gradeSelect = document.getElementById('grade');
    const classSelect = document.getElementById('class');
    const studentsTable = document.getElementById('classesTable').getElementsByTagName('tbody')[0];
    const form = document.getElementById('classRegistrationForm');
    const responseMessage = document.getElementById('responseMessage');
    
    // Function to fetch and populate classes
    function fetchClasses() {
        const grade = gradeSelect.value;
        
        if (!grade) {
            classSelect.innerHTML = '<option disabled selected>First select Grade</option>';
            return;
        }
        
        // Show loading state
        classSelect.disabled = true;
        classSelect.innerHTML = '<option disabled selected>Loading classes...</option>';
        
        // Fetch classes via AJAX
        fetch('<?= htmlspecialchars($$getDataFile); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `grade=${encodeURIComponent(grade)}&action=GetClassUsingClassGrade`
        })
        .then(response => response.json())
        .then(data => {
            classSelect.innerHTML = '';
            
            if (data.message) {
                classSelect.innerHTML = `<option disabled selected>${data.message}</option>`;
            } else {
                classSelect.innerHTML = '<option disabled selected>Select a class</option>';
                data.forEach(classItem => {
                    const option = document.createElement('option');
                    option.value = classItem.class_id;
                    option.textContent = classItem.name;
                    classSelect.appendChild(option);
                });
            }
            
            classSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error fetching classes:', error);
            classSelect.innerHTML = '<option disabled selected>Error loading classes</option>';
            classSelect.disabled = false;
        });
    }
    
    // Function to fetch and populate students
    function fetchStudents() {
        const grade = gradeSelect.value;
        
        if (!grade) {
            studentsTable.innerHTML = '<tr><td colspan="5" class="no-data">First select a grade</td></tr>';
            return;
        }
        
        // Show loading state
        studentsTable.innerHTML = '<tr><td colspan="5" class="no-data">Loading students...</td></tr>';
        
        // Fetch students via AJAX
        fetch('includes/fetch_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `grade=${encodeURIComponent(grade)}&action=GetStudentsUsingStartingGrade`
        })
        .then(response => response.json())
        .then(data => {
            studentsTable.innerHTML = '';
            
            if (data.message) {
                studentsTable.innerHTML = `<tr><td colspan="5" class="no-data">${data.message}</td></tr>`;
            } else {
                data.forEach(student => {
                    const row = document.createElement('tr');
                    
                    row.innerHTML = `
                        <td>
                            <input type="checkbox" name="selected_students[]" class="class-checkbox" value="${student.user_id}">
                        </td>
                        <td>${student.first_name} ${student.middle_name} ${student.last_name}</td>
                        <td>${student.date_of_birth}</td>
                        <td>${student.gender || ''}</td>
                        <td>${student.nationality}</td>
                    `;
                    
                    studentsTable.appendChild(row);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            studentsTable.innerHTML = '<tr><td colspan="5" class="no-data">Error loading students</td></tr>';
        });
    }
    
    // Add event listeners
    gradeSelect.addEventListener('change', function() {
        fetchClasses();
        fetchStudents();
    });
    
    // Rest of your form submission handler remains the same
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset previous errors
        responseMessage.style.display = 'none';
        
        // Validate form
        const selectedClass = classSelect.value;
        const selectedStudents = document.querySelectorAll('.class-checkbox:checked');
        
        if (!selectedClass) {
            showMessage('Please select a class', 'error');
            return;
        }
        
        if (selectedStudents.length === 0) {
            showMessage('Please select at least one student', 'error');
            return;
        }
        
        // Prepare data for submission
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
        
        try {
            // Collect student IDs
            const studentIds = Array.from(selectedStudents).map(checkbox => checkbox.value);
            
            // Prepare form data
            const formData = new FormData();
            formData.append('action', 'AllocateStudentsToClass');
            formData.append('class_id', selectedClass);
            formData.append('students', JSON.stringify(studentIds));
            formData.append('center_no', '<?= $center_no; ?>');
            
            const response = await fetch('includes/submit_data.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                setTimeout(() => {
                    window.location.href = result.url || 'class_allocation.php';
                }, 1500);
            } else {
                showMessage(result.message || 'Allocation failed', 'error');
            }
        } catch (error) {
            console.error('Submission error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
    
    function showMessage(message, type) {
        responseMessage.textContent = message;
        responseMessage.className = type;
        responseMessage.style.display = 'block';
    }
});

function confirmCancel() {
    if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
        window.location.href = '../dashboard.php';
    }
}
</script>
</body>