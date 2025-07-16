<?php require_once("../includes/layout.php"); 
if(!isset($_GET['usr_id']) && empty($_GET['usr_id'])){
    kickout('staff-list.php', 'Please Select a Staff Member');
}
$user = $_GET['usr_id'];
$table = ' school_staff ';
$field1 = ' staff_id ';
$field2 = ' center_no ';
authenticateUserRelation($conn, $user, $center_no, $table,$field1, $field2);
$get_user_info = $conn->prepare("SELECT * FROM user_information  WHERE user_id = ?");
$get_user_info->bind_param("s", $user);
$get_user_info->execute();
$get_user_info_results = $get_user_info->get_result();
$user_info = $get_user_info_results->fetch_assoc();

$get_user_contacts = $conn->prepare("SELECT * FROM user_contacts  WHERE user_id = ?");
$get_user_contacts->bind_param("s", $user);
$get_user_contacts->execute();
$get_user_contacts_results = $get_user_contacts->get_result();
$user_contacts = $get_user_contacts_results->fetch_assoc();

$get_user_employment_status = $conn->prepare("SELECT * FROM school_staff  WHERE staff_id = ?");
$get_user_employment_status->bind_param("s", $user);
$get_user_employment_status->execute();
$get_user_employment_status_results = $get_user_employment_status->get_result();
$user_employment_status = $get_user_employment_status_results->fetch_assoc();

$get_user_credentials = $conn->prepare("SELECT * FROM user_credentials  WHERE user_id = ?");
$get_user_credentials->bind_param("s", $user);
$get_user_credentials->execute();
$get_user_credentials_results = $get_user_credentials->get_result();
$user_credentials = $get_user_credentials_results->fetch_assoc();

if($user_credentials['role' == 'Teacher']){
    $get_teacher_subjects = $conn->prepare("SELECT * FROM user_credentials  WHERE user_id = ?");
    $get_teacher_subjects->bind_param("s", $user);
    $get_teacher_subjects->execute();
    $get_teacher_subjects_results = $get_teacher_subjects->get_result();
    $teacher_subjects = $get_teacher_subjects_results->fetch_assoc();
}

$nationalities = array(
    "Afghanistan", "Albania", "Algeria", "Andorra", "Angola", 
    "Antigua and Barbuda", "Argentina", "Armenia", "Australia", "Austria", 
    "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", 
    "Belarus", "Belgium", "Belize", "Benin", "Bhutan", 
    "Bolivia", "Bosnia and Herzegovina", "Botswana", "Brazil", "Brunei", 
    "Bulgaria", "Burkina Faso", "Burundi", "Côte d'Ivoire", "Cabo Verde", 
    "Cambodia", "Cameroon", "Canada", "Central African Republic", "Chad", 
    "Chile", "China", "Colombia", "Comoros", "Congo (Congo-Brazzaville)", 
    "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czechia (Czech Republic)", 
    "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", 
    "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", 
    "Eswatini (fmr. Swaziland)", "Ethiopia", "Fiji", "Finland", "France", 
    "Gabon", "Gambia", "Georgia", "Germany", "Ghana", 
    "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", 
    "Guyana", "Haiti", "Honduras", "Hungary", "Iceland", 
    "India", "Indonesia", "Iran", "Iraq", "Ireland", 
    "Israel", "Italy", "Jamaica", "Japan", "Jordan", 
    "Kazakhstan", "Kenya", "Kiribati", "Kuwait", "Kyrgyzstan", 
    "Laos", "Latvia", "Lebanon", "Lesotho", "Liberia", 
    "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Madagascar", 
    "Malawi", "Malaysia", "Maldives", "Mali", "Malta", 
    "Marshall Islands", "Mauritania", "Mauritius", "Mexico", "Micronesia", 
    "Moldova", "Monaco", "Mongolia", "Montenegro", "Morocco", 
    "Mozambique", "Myanmar (formerly Burma)", "Namibia", "Nauru", "Nepal", 
    "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", 
    "North Korea", "North Macedonia", "Norway", "Oman", "Pakistan", 
    "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", 
    "Philippines", "Poland", "Portugal", "Qatar", "Romania", 
    "Russia", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", 
    "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", 
    "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", 
    "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Korea", 
    "South Sudan", "Spain", "Sri Lanka", "Sudan", "Suriname", 
    "Sweden", "Switzerland", "Syria", "Taiwan", "Tajikistan", 
    "Tanzania", "Thailand", "Timor-Leste", "Togo", "Tonga", 
    "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", 
    "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", 
    "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", 
    "Vietnam", "Yemen", "Zambia", "Zimbabwe"
);
$employemntStatus=  array(
    'Active',
    'Suspended',
    'Terminated'
);
$marital_status = array('Single', 'Married', 'Divorced', 'Widowed');


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Aduk8 | Staff Registration</title>
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
           
                <h2>Staff Registration</h2>
                <div id="responseMessage"></div>
                <div class="notes">
                    <span>Note: To save any changes use the update button</span>
                </div>
                <div class="profile-image-container">
                    <div class="profile-image-wrap" title="Click to change profile picture">
                        <img id="profile-image-preview" src="<?= $profile_pic_url . ($user_info['profile_img'] ?? 'default.png'); ?>" alt="User Profile">
                        <input type="file" accept="image/*" name="profile-image" id="profile-image" onchange="previewImage(this)">
                    </div>
                </div>
                <form method="post" id="registrationForm">
                    <!-- Section 1: School Information -->
                    <section class="active" id="section1">
                        <h4>Staff Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="first_name" class="required-field">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user_info['first_name']);?>" required>
                            </div>
                            <div class="col-row">
                            <label for="middle_name" >Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value="<?= htmlspecialchars($user_info['middle_name']);?>" >
                            </div>
                            <div class="col-row">
                            <label for="last_name" class="required-field">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($user_info['last_name']);?>" required>
                            </div>
                        </div>
                                               
                        <label for="DOB" class="required-field">Date Of Birth</label>
                        <input type="date" name="DOB" id="DOB" required value="<?= htmlspecialchars($user_info['date_of_birth']);?>">
                        <div class="mult-display">
                            <div class="col-row">
                            <label for="nationality" class="required-field">Nationality</label>
                            <select name="nationality" id="nationality" required>
                                <option value="">Select your nationality</option>
                                <?php foreach ($nationalities as $nationality): ?>
                                    <option value="<?= htmlspecialchars($nationality); ?>"
                                        <?= (isset($user_info['nationality']) && $user_info['nationality'] === $nationality) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($nationality); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            </div>
                            <div class="col-row">
                                <label for="id_number" class="required-field">ID / Passport Number</label>
                                <input type="text" name="id_number" id="id_number" value="<?= htmlspecialchars($user_info['id_number']);?>" required>
                            </div>
                        </div>
                        <label for="marital" class="required-field">Marital Status</label>
                        <select name="marital" id="marital" required>
                            <option disabled selected >Select your marital Status</option>
                            <?php foreach ($marital_status as $marital_state): ?>
                                    <option value="<?= htmlspecialchars($marital_state); ?>"
                                        <?= (isset($user_info['marital_status']) && $user_info['marital_status'] === $marital_state) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($marital_state); ?>
                                    </option>
                                <?php endforeach; ?>
                        </select> 
                        <h4>Contact Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="phone" class="required-field">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($user_contacts['phone']);?>" required>
                            </div>
                            <div class="col-row">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user_contacts['email']);?>" >
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="physical">Physical Address</label> 
                                <textarea name="physical" id="physical" value="<?= htmlspecialchars($user_contacts['physical']);?>"><?= htmlspecialchars($user_contacts['physical']);?></textarea> 
                            </div>
                            <div class="col-row">
                                <label for="postal" class="required-field">Postal Address</label>
                                <textarea name="postal" id="postal" required value="<?= htmlspecialchars($user_contacts['postal']);?>"><?= htmlspecialchars($user_contacts['postal']);?></textarea>
                            </div>
                        </div>
                        <label for="residence" class="required-field">Place Of Recidence</label>
                        <input type="text" name="residence" id="residence" value="<?= htmlspecialchars($user_contacts['place_of_residence']);?>" required>
                       
                        <h4>Employment Information</h4>
                        <div class="col-row">
                                <label for="position" class="required-field">Employment Status</label>
                                <select name="position" id="position" required>
                                    <option disabled selected >Select current Employment Status</option>
                                    <?php foreach ($employemntStatus as $employemntState): ?>
                                    <option value="<?= htmlspecialchars($employemntState); ?>"
                                        <?= (isset($user_info['nationality']) && $user_info['nationality'] === $nationality) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($employemntState); ?>
                                    </option>
                                <?php endforeach; ?>
                                </select> 
                            </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="occupation" class="required-field">Occupation</label>
                                <select name="occupation" id="occupation" onchange="displaySubjects()" required>
                                    <option disabled selected>Select your Occupation</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Principal">Principal</option>
                                    <option value="Vice Principal">Vice Principal</option>
                                    <option value="Head Of House">Head Of House</option>
                                </select>
                            </div>
                            <div class="col-row">
                                <label for="position" class="required-field">Position</label>
                                <select name="position" id="position" required>
                                    <option disabled selected >Select your Occupational position</option>
                                    <option value="Junior">Junior</option>
                                    <option value="Senior">Senior</option>
                                    <option value="N/A">N/A</option>
                                </select> 
                            </div>
                        </div>

                       
                        <span>Qualification Files</span>
                        
                        <Span>Identification Files</Span>
                        <div class="mult-display">
                            <div class="col-row">
                               
                                <label for="qualification_doc" class="required-field">Qualification Documents(PDF)</label> 
                                <input type="file" name="qualification_doc" id="qualification_doc" accept="application/pdf" required>
                                <p>Current Files</p>
                                <a href=""><?= htmlspecialchars($user_info['id_document']);?></a>
                            </div>
                            <div class="col-row">
                                <label for="id_doc" class="required-field">ID / Passport Copy(PDF)</label>
                                <input type="file" name="id_doc" id="id_doc" accept="application/pdf" required>
                           <p>Current Files</p>
                                <a href=""><?= htmlspecialchars($user_info['id_document']);?></a>
                            </div>
                        </div>
                        <?php
                        if (isset($teacher_subjects['subject_no'])) {
                        ?>
                        <div id="subjects" class = "active">
                            <label for="subject" class="required-field">Main Subject</label>
                            <select name="subject" id="subject" required>
                                    <option value="">Select your main teaching subject</option>
                                    <?php $stf_subjects = getSchoolSubjects($conn, $center_no,' IN ','1');
                                        foreach ($stf_subjects as $stf_subject): ?>
                                 <option value="<?= htmlspecialchars($stf_subject); ?>"
                                        <?= (isset($user_info['nationality']) && $user_info['nationality'] === $stf_subject) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($stf_subject); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                        </div>
                        <?php }
                        ?>
                        <label for="empl_date" class="required-field">Employment Date</label>
                        <input type="date" name="empl_date" id="empl_date"  value="<?= htmlspecialchars($user_employment_status['start_date']);?>"required>   
                        
                        <div class="buttons-container">        
                            <button type="button" class="btn-back">Back</button>
                            <button type="submit" id="submitBtn">Submit</button>
                        </div>

                    </section>
                    
                    <!-- Section 4: Login Credentials -->
                    <section id="section4">
                        <h4>Login Credentials</h4>
                        <label for="username" class="required-field">Username</label>
                        <input type="text" name="username" id="username" placeholder="Input your account username" required>
                        <div class="error-message" id="username-error"></div>
                        
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="pass" class="required-field">Password</label>
                                <input type="password" name="pass" id="pass" placeholder="Input your password" required>
                                <div id="password-strength"></div>
                                <div class="error-message" id="pass-error"></div>
                            </div>
                            <div class="col-row">
                                <label for="conf_pass" class="required-field">Confirm Password</label>
                                <input type="password" name="conf_pass" id="conf_pass" placeholder="Confirm password" required>
                                <div class="error-message" id="conf-pass-error"></div>
                            </div>
                        </div>

                        <label for="hint" class="required-field">Password Hint</label>
                        <textarea name="hint" id="hint" required placeholder="Input password hint"></textarea>

                        <div class="buttons-container">        
                            <button type="button" class="btn-back">Back</button>
                            <button type="submit" id="submitBtn">Submit</button>
                        </div>


                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
       function displaySubjects() {
            var subjectsDiv = document.getElementById('subjects');
            var subjectSelect = document.getElementById('subject');
            var occupationSelect = document.getElementById('occupation');
            
            // Get the selected value, not the element itself
            var selectedOccupation = occupationSelect.value;
            
            if (selectedOccupation === 'Teacher') {
                subjectsDiv.style.display = 'block';
                subjectSelect.required = true;
            } else {
                subjectsDiv.style.display = 'none';
                subjectSelect.required = false;
                subjectSelect.value = ''; // Clear the selection when hidden
            }
        }
        // Optimized DOM elements caching
        const domElements = {
            sections: document.querySelectorAll('form section'),
            progressIndicator: document.getElementById('progressIndicator'),
            cancelBtn: document.querySelector('.btn-cancel'),
            form: document.getElementById('registrationForm'),
            responseMessage: document.getElementById('responseMessage'),
            passwordField: document.getElementById('pass'),
            confirmPasswordField: document.getElementById('conf_pass'),
            passwordError: document.getElementById('pass-error'),
            confirmPasswordError: document.getElementById('conf-pass-error'),
            passwordStrength: document.getElementById('password-strength'),
            usernameField: document.getElementById('username'),
            usernameError: document.getElementById('username-error'),
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
            
            // Password validation
            domElements.passwordField.addEventListener('input', handlePasswordInput);
            domElements.confirmPasswordField.addEventListener('input', validatePasswordMatch);
            
            // Username validation
            domElements.usernameField.addEventListener('input', validateUsername);
            
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
            if (confirm('Are you sure you want to exit? All entered data will be lost.')) {
                window.location.href = 'staff-list.php';
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

        function handlePasswordInput() {
            const password = domElements.passwordField.value;
            const strength = calculatePasswordStrength(password);
            
            if (password.length > 0) {
                domElements.passwordStrength.textContent = `Strength: ${strength.text}`;
                domElements.passwordStrength.className = `strength-${strength.level}`;
            } else {
                domElements.passwordStrength.textContent = '';
            }
            
            validatePassword();
            validatePasswordMatch();
        }

        function calculatePasswordStrength(password) {
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            
            // Character variety
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            // Determine strength level
            if (strength <= 3) return { level: 'weak', text: 'Weak' };
            if (strength <= 5) return { level: 'medium', text: 'Medium' };
            return { level: 'strong', text: 'Strong' };
        }

        function validatePassword() {
            const password = domElements.passwordField.value;
            
            if (password.length === 0) {
                domElements.passwordError.style.display = 'none';
                return false;
            }
            
            if (password.length < 8) {
                domElements.passwordError.textContent = 'Password must be at least 8 characters';
                domElements.passwordError.style.display = 'block';
                domElements.passwordField.classList.add('input-error');
                return false;
            }
            
            if (!/[A-Z]/.test(password)) {
                domElements.passwordError.textContent = 'Password must contain at least one uppercase letter';
                domElements.passwordError.style.display = 'block';
                domElements.passwordField.classList.add('input-error');
                return false;
            }
            
            if (!/[a-z]/.test(password)) {
                domElements.passwordError.textContent = 'Password must contain at least one lowercase letter';
                domElements.passwordError.style.display = 'block';
                domElements.passwordField.classList.add('input-error');
                return false;
            }
            
            if (!/[^A-Za-z0-9]/.test(password)) {
                domElements.passwordError.textContent = 'Password must contain at least one special character';
                domElements.passwordError.style.display = 'block';
                domElements.passwordField.classList.add('input-error');
                return false;
            }
            
            domElements.passwordError.style.display = 'none';
            domElements.passwordField.classList.remove('input-error');
            return true;
        }

        function validatePasswordMatch() {
            const password = domElements.passwordField.value;
            const confirmPassword = domElements.confirmPasswordField.value;
            
            if (confirmPassword.length === 0) {
                domElements.confirmPasswordError.style.display = 'none';
                return false;
            }
            
            if (password !== confirmPassword) {
                domElements.confirmPasswordError.textContent = 'Passwords do not match';
                domElements.confirmPasswordError.style.display = 'block';
                domElements.confirmPasswordField.classList.add('input-error');
                return false;
            }
            
            domElements.confirmPasswordError.style.display = 'none';
            domElements.confirmPasswordField.classList.remove('input-error');
            return true;
        }

        function validateUsername() {
            const username = domElements.usernameField.value.trim();
            
            if (username.length === 0) {
                domElements.usernameError.style.display = 'none';
                domElements.usernameField.classList.remove('input-error');
                return false;
            }
            
            if (username.length < 4) {
                domElements.usernameError.textContent = 'Username must be at least 4 characters';
                domElements.usernameError.style.display = 'block';
                domElements.usernameField.classList.add('input-error');
                return false;
            }
            
            if (!/^[a-zA-Z0-9_\-]+$/.test(username)) {
                domElements.usernameError.textContent = 'Username can only contain letters, numbers, underscores and hyphens';
                domElements.usernameError.style.display = 'block';
                domElements.usernameField.classList.add('input-error');
                return false;
            }
            
            domElements.usernameError.style.display = 'none';
            domElements.usernameField.classList.remove('input-error');
            return true;
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

    // Validate username
    if (!validateUsername()) {
        allValid = false;
        errorFields.push('username');
        navigateToSection(2);
    }
    
    // Validate passwords
    if (!validatePassword() || !validatePasswordMatch()) {
        allValid = false;
        errorFields.push('pass', 'conf_pass');
        navigateToSection(2);
    }
          
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
            user: "staff"
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
        
        const response = await fetch('submit.php', {
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