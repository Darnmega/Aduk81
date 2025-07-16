<?php 
require_once("../includes/layout.php");

$grade_levels = getGradeLevels($conn, $center_no, ' IN ');
?>
<head>
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?>'s Class Registration</title>
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
                <h2>Class Registration</h2>
                
                <div class="progress-indicator" id="progressIndicator"></div>
                <div id="responseMessage"></div>
                
                <form method="post" id="classRegistrationForm">
                    <section class="active" id="classRegistrationSection">
                        <h4><?= htmlspecialchars($schoolInformation['school_name'])?>'s New Class Registration</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="start_year" class="required-field">Start Year</label>
                                <select name="start_year" id="start_year" required>
                                    <option value="" disabled selected>Select Year</option>
                                </select>
                            </div>
                            <div class="col-row">
                                <label for="grade" class="required-field">Grade</label>
                                <select name="grade" id="grade" required>
                                    <?php if (isset($grade_levels['message'])): ?>
                                        <option value=""><?= htmlspecialchars($grade_levels['message']); ?></option>
                                    <?php else: ?>
                                        <option disabled selected>Please select the student's grade level</option>
                                        <?php foreach ($grade_levels as $grade_level): ?>
                                            <option value="<?= htmlspecialchars($grade_level["grade_no"]); ?>"><?= htmlspecialchars($grade_level["grade_name"]); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                          
                                </select>
                            </div>
                            <div class="col-row">
                                <label for="graduation_year" class="required-field">Graduation Year</label>
                                <select name="graduation_year" id="graduation_year" required>
                                    <option value="" disabled selected>Select Year</option>
                                </select>                       
                            </div>
                        </div>
                        <br>
                        <div class="table-container">
                            <table id="classesTable">
                                <thead>
                                    <tr>
                                        <th class="required-field">Register</th>
                                        <th class="required-field">Class</th>
                                        <th class="required-field">Home Room</th>
                                        <th class="required-field">Home Teacher</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $classes = getClassRange($conn, $center_no, ' IN ');
                                    if (isset($classes['message'])): 
                                        echo '<tr><td colspan="4" class="no-data">'.htmlspecialchars($classes["message"]).'</td></tr>'; 
                                    else: 
                                        foreach ($classes as $class): 
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="selected_classes[]" class="class-checkbox" value="<?= htmlspecialchars($class["range_no"]); ?>">
                                                </td>
                                                <td><?= htmlspecialchars($class["name"]); ?></td>
                                                <td>
                                                    <select name="home_room[<?= htmlspecialchars($class["range_no"]); ?>]" class="home-room-select" required>
                                                        <?php
                                                        $rooms = getSchoolRooms($conn, $center_no, ' IN ', 'Classroom','Vacant');
                                                        if (isset($rooms['message'])): ?>
                                                            <option disabled selected><?= htmlspecialchars($rooms['message']); ?></option>
                                                        <?php else: ?>
                                                            <option disabled selected>Select Class Home Room</option>
                                                            <?php foreach ($rooms as $room): ?>
                                                                <option value="<?= htmlspecialchars($room["room_no"]); ?>"><?= htmlspecialchars($room["room_name"]); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="home_teacher[<?= htmlspecialchars($class["range_no"]); ?>]" class="home-teacher-select" required>
                                                        <?php
                                                        $staffs = getSchoolStaff($conn, $center_no,  'Teacher');
                                                        if (isset($staffs['message'])): ?>
                                                            <option disabled selected><?= htmlspecialchars($staffs['message']); ?></option>
                                                        <?php else: ?>
                                                            <option disabled selected>Select home room teacher</option>
                                                            <?php foreach ($staffs as $staff): ?>
                                                                <option value="<?= htmlspecialchars($staff["user_id"]); ?>"><?= htmlspecialchars($staff["first_name"]." ".$staff["last_name"]); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </td>
                                            </tr>
                                    <?php 
                                        endforeach;
                                    endif; 
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="buttons-container">        
                            <button type="button" class="btn-cancel" onclick="confirmCancel()">Cancel</button>
                            <button type="submit" id="submitBtn" class="btn-primary">Submit</button>
                        </div>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
    // Populate year dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        // Populate both year dropdowns
        const populateYears = (selectId) => {
            const select = document.getElementById(selectId);
            const currentYear = new Date().getFullYear();
            
            // Clear existing options except the first one
            while (select.options.length > 1) {
                select.remove(1);
            }
            
            // Add years from 2000 to current year + 10
            for (let year = 2000; year <= currentYear + 10; year++) {
                const option = new Option(year, year);
                select.add(option);
            }
            
            // Set default value
            if (selectId === 'start_year') {
                select.value = currentYear;
            } else if (selectId === 'graduation_year') {
                select.value = currentYear + (12 - parseInt(document.getElementById('grade').value || 0));
            }
        };
        
        // Populate both dropdowns initially
        populateYears('start_year');
        populateYears('graduation_year');
        
        // Update graduation year when grade or start year changes
        document.getElementById('grade').addEventListener('change', function() {
            updateGraduationYear();
        });
        
        document.getElementById('start_year').addEventListener('change', function() {
            updateGraduationYear();
        });
        
        function updateGraduationYear() {
            const startYear = parseInt(document.getElementById('start_year').value) || new Date().getFullYear();
            const grade = parseInt(document.getElementById('grade').value) || 0;
            const yearsToGraduate = 12 - grade; // Assuming K-12 system
            
            const graduationYear = startYear + yearsToGraduate;
            document.getElementById('graduation_year').value = graduationYear;
        }

        // Rest of your existing form submission handler code...
        const form = document.getElementById('classRegistrationForm');
        const responseMessage = document.getElementById('responseMessage');
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Reset previous errors
            responseMessage.style.display = 'none';
            document.querySelectorAll('.error-text').forEach(el => {
                el.style.display = 'none';
            });
            
            // Validate form
            const startYear = document.getElementById('start_year').value;
            const grade = document.getElementById('grade').value;
            const graduationYear = document.getElementById('graduation_year').value;
            const selectedClasses = document.querySelectorAll('.class-checkbox:checked');
            
            if (!startYear || !grade || !graduationYear) {
                showMessage('Please fill all required fields', 'error');
                return;
            }
            
            if (selectedClasses.length === 0) {
                showMessage('Please select at least one class', 'error');
                return;
            }
            
            // Validate all selected classes have room and teacher selected
            let isValid = true;
            selectedClasses.forEach(checkbox => {
                const classId = checkbox.value;
                const roomSelect = document.querySelector(`select[name="home_room[${classId}]"]`);
                const teacherSelect = document.querySelector(`select[name="home_teacher[${classId}]"]`);
                
                if (!roomSelect.value || !teacherSelect.value) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                showMessage('Please select both home room and teacher for all selected classes', 'error');
                return;
            }
            
            // Prepare data for submission
            const submitBtn = document.getElementById('submitBtn');
            const originalBtnText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
            
            try {
                // Collect form data
                const formData = {
                    action: 'registeClasses',
                    start_year: startYear,
                    grade: grade,
                    graduation_year: graduationYear,
                    classes: []
                };
                
                // Add selected classes with their room and teacher
                selectedClasses.forEach(checkbox => {
                    const classId = checkbox.value;
                    formData.classes.push({
                        class_id: classId,
                        home_room: document.querySelector(`select[name="home_room[${classId}]"]`).value,
                        home_teacher: document.querySelector(`select[name="home_teacher[${classId}]"]`).value
                    });
                });
                
                const response = await fetch('<?= htmlspecialchars($postDataFile); ?>', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(formData)
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    showMessage(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.url || 'classes.php';
                    }, 1500);
                } else {
                    showMessage(result.message || 'Registration failed', 'error');
                    
                    if (result.errors) {
                        for (const [field, message] of Object.entries(result.errors)) {
                            const errorEl = document.getElementById(`${field}_error`);
                            if (errorEl) {
                                errorEl.textContent = message;
                                errorEl.style.display = 'block';
                            }
                        }
                    }
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
            window.location.href = '<?= htmlspecialchars($dashboardFile); ?>';
        }
    }
</script>
</body>