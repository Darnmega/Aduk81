<?php require_once("../includes/layout.php"); 

$grade_levels = getGradeLevels($conn, $center_no, ' IN ');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?>'s New Student Registration</title>
    <style>
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
        #password-strength {
            margin-top: 5px;
            font-size: 0.85rem;
        }
        .strength-weak { color: #e74c3c; }
        .strength-medium { color: #f39c12; }
        .strength-strong { color: #27ae60; }
    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="content">
                <h2><?= htmlspecialchars($schoolInformation['school_name'])?>'s New Student Registration</h2>
                
                <div class="progress-indicator" id="progressIndicator"></div>
                <div id="responseMessage"></div>
                
                <form method="post" id="registrationForm" enctype="multipart/form-data">                    <!-- Section 1: School Information -->
                    <section class="active" id="section1">
                        <h4>Student's Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="first_name" class="required-field">First Name</label>
                                <input type="text" name="first_name" id="first_name" placeholder="Input first name" required>
                            </div>
                            <div class="col-row">
                            <label for="middle_name" >Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" placeholder="Input middle name">
                            </div>
                            <div class="col-row">
                            <label for="last_name" class="required-field">Last Name</label>
                            <input type="text" name="last_name" id="last_name" placeholder="Input last name" required>
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="DOB" class="required-field">Date Of Birth</label>
                                <input type="date" name="DOB" id="DOB" required >
                            </div>
                            <div class="col-row">
                                <label for="gender" class="required-field">Gender</label>
                                <select name="gender" id="gender" required>
                                    <option value="" disabled selected>Select your gender</option>
                                    <?php foreach ($genderOptions as $genderOption): ?>
                                        <option value="<?= htmlspecialchars($genderOption); ?>"><?= htmlspecialchars($genderOption); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mult-display">
                            <div class="col-row">
                                <label for="nationality" class="required-field">Nationality</label>
                                <select name="nationality" id="nationality" required>
                                    <option value="">Select your nationality</option>
                                    <?php foreach ($nationalityOptions as $nationalityValue => $nationalityKey): ?>
                                        <option value="<?= htmlspecialchars($nationalityValue); ?>">
                                            <?= htmlspecialchars($nationalityKey); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-row">
                                <label for="id_number" class="required-field">ID / Passport Number</label>
                                <input type="text" name="id_number" id="id_number" placeholder="Input ID / Passport Number" required>
                            </div>
                        </div>
                        
                        <div class="buttons-container">
                            <button type="button" class="btn-cancel"><i class="fa fa-times"></i> Close</button>
                            <button type="button" class="btn-next"><i class="fa fa-arrow-right"></i> Next</button>
                        </div>
                    </section>
                    
                    <!-- Section 2:  students school information-->
                    <section id="section2">
                        <h4>Student's school Information</h4>
                        <span>For Qualification Document upload a document that shows that the student has advanced to the requested grade, acceptance and transfer letters also work</span>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="start_date" class="required-field">Start date</label>
                                <input type="date" name="start_date" id="start_date" required >
                            </div>
                            <div class="col-row">
                                <label for="grade_level" class="required-field">Student's Grade level</label>
                                <select type="tel" name="grade_level" id="grade_level" placeholder="Input students grade level" required>
                                <?php if (isset($grade_levels['message'])): ?>
                                            <option value=""><?= htmlspecialchars($grade_levels['message']); ?></op>
                                    <?php else: ?>
                                        <option disabled selected>Please select the student's grade level</option>
                                        <?php foreach ($grade_levels as $grade_level): ?>
                                            <option value="<?= htmlspecialchars($grade_level["grade_no"]);?>"><?= htmlspecialchars($grade_level["grade_name"]);?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>                          
                                </select>
                            </div>
                            </div>
                            <label for="qualification_doc" class="required-field">Student's Qualification Document</label>
                            <input type="file" name="qualification_doc" id="qualification_doc">

                            <label for="id_doc" class="required-field">Student's ID Certified Documentation</label>
                            <input type="file" name="id_doc" id="id_doc">

                            

                        <div class="buttons-container">        
                            <button type="button" class="btn-back"><i class="fa fa-arrow-left"></i> Back</button>
                            <button type="button" class="btn-next"><i class="fa fa-arrow-right"></i> Next</button>
                        </div>
                    </section>

                                    <!--Section for Contact Information-->
                    <section id="section3">
                        <h4>Contact Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="phone">Phone Number</label>
                                <input type="tel" name="phone" id="phone" placeholder="Input school phone number">
                            </div>
                            <div class="col-row">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" placeholder="Input Email Address">
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="physical">Physical Address</label> 
                                <textarea name="physical" id="physical" placeholder="Input physical address"></textarea> 
                            </div>
                            <div class="col-row">
                                <label for="postal">Postal Address</label>
                                <textarea name="postal" id="postal" placeholder="Input postal address"></textarea>
                            </div>
                        </div>
                        <label for="residence">Place Of Recidence</label>
                        <input type="text" name="residence" id="residence" placeholder=" e.g Lobatse, Tlokweng, Ledumadumane">
                       

                        <div class="buttons-container">        
                            <button type="button" class="btn-back"><i class="fa fa-arrow-left"></i> Back</button>
                            <button type="submit" id="submitBtn"><i class="fa fa-check"></i> Register</button>
                        </div>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
        // Optimized DOM elements caching
        const domElements = {
            sections: document.querySelectorAll('form section'),
            progressIndicator: document.getElementById('progressIndicator'),
            cancelBtn: document.querySelector('.btn-cancel'),
            form: document.getElementById('registrationForm'),
            responseMessage: document.getElementById('responseMessage'),
            submitBtn: document.getElementById('submitBtn')
        };

        // Initialize form
        document.addEventListener('DOMContentLoaded', function() {
            initializeProgressSteps();
            setupEventListeners();
        });

        function initializeProgressSteps() {
            domElements.sections.forEach((section, index) => {
                const step = document.createElement('div');
                step.className = `step${index === 0 ? ' active' : ''}`;
                step.textContent = index + 1;
                step.id = `step${index + 1}`;
                domElements.progressIndicator.appendChild(step);
            });
        }

        function setupEventListeners() {
            // Navigation buttons
            document.querySelectorAll('.btn-next').forEach(btn => {
                btn.addEventListener('click', handleNextClick);
            });
            
            document.querySelectorAll('.btn-back').forEach(btn => {
                btn.addEventListener('click', handleBackClick);
            });
            
            domElements.cancelBtn.addEventListener('click', handleCancelClick);
            
            // Form submission
            domElements.form.addEventListener('submit', handleFormSubmit);
        }

        // Navigation functions
        function navigateToSection(targetIndex) {
            domElements.sections.forEach((section, index) => {
                const isActive = index === targetIndex;
                section.classList.toggle('active', isActive);
                document.getElementById(`step${index + 1}`).classList.toggle('active', isActive);
            });
            window.scrollTo(0, 0);
        }

        function currentIndex() {
            return Array.from(domElements.sections).findIndex(section => section.classList.contains('active'));
        }

        function handleNextClick() {
            const currentIdx = currentIndex();
            const currentSection = domElements.sections[currentIdx];
            
            if (validateSection(currentSection)) {
                const nextIndex = currentIdx + 1;
                if (nextIndex < domElements.sections.length) {
                    navigateToSection(nextIndex);
                }
            }
        }

        function handleBackClick() {
            const prevIndex = currentIndex() - 1;
            if (prevIndex >= 0) {
                navigateToSection(prevIndex);
            }
        }

        function handleCancelClick() {
            if (confirm('Are you sure you want to cancel registration? All entered data will be lost.')) {
                window.location.href = '<?= htmlspecialchars($dashboardFile); ?>';
            }
        }

        // Validation functions
        function validateSection(section) {
            const inputs = section.querySelectorAll('input[required], select[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('input-error');
                } else {
                    input.classList.remove('input-error');
                }
            });
            
            return isValid;
        }

    async function handleFormSubmit(e) {
    e.preventDefault();
    
    // Validate all sections
    let allValid = true;
    const errorFields = [];
    
    // Check required fields
    domElements.sections.forEach(section => {
        const inputs = section.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            if (!input.value.trim()) {
                allValid = false;
                input.classList.add('input-error');
                errorFields.push(input.name);
            }
        });
    });
      
    if (!allValid) {
        domElements.responseMessage.style.display = 'block';
        domElements.responseMessage.className = 'error';
        domElements.responseMessage.textContent = 'Please fill in all required fields correctly.';
        
        // Highlight error fields
        errorFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('input-error');
                const errorElement = document.getElementById(`${fieldName}-error`);
                if (errorElement) {
                    errorElement.style.display = 'block';
                    errorElement.textContent = 'This field is required';
                }
            }
        });
        return;
    }
    
    // Show loading state
    const originalBtnText = domElements.submitBtn.textContent;
    domElements.submitBtn.disabled = true;
    domElements.submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
    
    try {
        const formData = new FormData(domElements.form);
        const idDocInput = document.getElementById('id_doc');
        const qualificationDocInput = document.getElementById('qualification_doc');
        
        // Prepare the data object
        const data = {
            ...Object.fromEntries(formData.entries()),
            action: "registerNewStudent"
        };
        
        // Function to read file as base64
        const readFileAsBase64 = (file) => {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = error => reject(error);
                reader.readAsDataURL(file);
            });
        };
        
        // Process ID document if provided
        if (idDocInput.files.length > 0) {
            const file = idDocInput.files[0];
            if (file.type !== 'application/pdf') {
                throw new Error('Only PDF files are allowed for ID documents');
            }
            data.id_doc = await readFileAsBase64(file);
        }
        
        // Process qualification document if provided
        if (qualificationDocInput.files.length > 0) {
            const file = qualificationDocInput.files[0];
            if (file.type !== 'application/pdf') {
                throw new Error('Only PDF files are allowed for qualification documents');
            }
            data.qualification_doc = await readFileAsBase64(file);
        }
        
        const response = await fetch('<?= htmlspecialchars($postDataFile); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        // Check if response is JSON
        let result;
        try {
            result = await response.json();
            console.log('Response received:', result);
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            throw new Error('Invalid server response');
        }

        // Handle response
        handleServerResponse(result);
        
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage(error.message || 'An error occurred. Please try again.');
    } finally {
        domElements.submitBtn.disabled = false;
        domElements.submitBtn.textContent = originalBtnText;
    }
}
function handleServerResponse(result)
{
    domElements.responseMessage.style.display = 'block';
    domElements.responseMessage.className = result.status === 'success' ? 'success' : 'error';
    domElements.responseMessage.textContent = result.message || 
        (result.status === 'success' ? 'Registration successful!' : 'Registration failed.');
    
    // Handle field-specific errors
    if (result.errors && result.errors.fields) {
        result.errors.fields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('input-error');
                const errorElement = document.getElementById(`${fieldName}-error`);
                if (errorElement && result.errors.message) {
                    errorElement.style.display = 'block';
                    errorElement.textContent = result.errors.message;
                }
            }
        });
        
        if (result.errors.message) {
            domElements.responseMessage.textContent = result.errors.message;
        }
        
        // Navigate to first section with errors
        const firstErrorField = result.errors.fields[0];
        if (firstErrorField) {
            const field = document.querySelector(`[name="${firstErrorField}"]`);
            if (field) {
                const section = field.closest('section');
                if (section) {
                    const sectionIndex = Array.from(domElements.sections).indexOf(section);
                    navigateToSection(sectionIndex);
                }
            }
        }
    }

    // Redirect on success
    if (result.status === 'success' && result.url) {
        setTimeout(() => {
            window.location.href = result.url;
        }, 2000);
    }
}

function showErrorMessage(message) {
    domElements.responseMessage.style.display = 'block';
    domElements.responseMessage.className = 'error';
    domElements.responseMessage.textContent = message;
}
    </script>
</body>
</html>