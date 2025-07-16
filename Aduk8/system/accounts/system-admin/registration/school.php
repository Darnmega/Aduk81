<?php require_once("../includes/layout.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Aduk8 | School Registration</title>
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
                <h2>School Registration</h2>
                
                <div class="progress-indicator" id="progressIndicator"></div>
                <div id="responseMessage"></div>
                
                <form method="post" id="registrationForm">
                    <!-- Section 1: School Information -->
                    <section class="active" id="section1">
                        <h4>School Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="name" class="required-field">School Name</label>
                                <input type="text" name="name" id="name" placeholder="Input school name" required>
                            </div>
                            <div class="col-row">
                                <label for="classification" class="required-field">School Classification</label>
                                <select name="classification" id="classification" required>
                                    <option disabled selected value="">Select a classification</option>
                                    <option value="Primary School">Primary School</option>
                                    <option value="Junior Secondary School">Junior Secondary School</option>
                                    <option value="Senior Secondary School">Senior Secondary School</option>
                                    <option value="International School">International School</option>
                                </select>
                            </div>
                        </div>
                        <label for="center_no" class="required-field">School Center No</label>
                        <input type="text" name="center_no" id="center_no" placeholder="Input school center no" required>
                                               
                        <label for="est_date" class="required-field">Established Date</label>
                        <input type="date" name="est_date" id="est_date" required>

                        <label for="motto" class="required-field">School Motto</label>
                        <textarea name="motto" id="motto" required placeholder="Input school motto"></textarea>
                        
                        <div class="buttons-container">
                            <button type="button" class="btn-cancel">Cancel</button>
                            <button type="button" class="btn-next">Next</button>
                        </div>
                    </section>
                    
                    <!-- Section 2: Contact Information -->
                    <section id="section2">
                        <h4>Contact Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="phone" class="required-field">Phone Number</label>
                                <input type="tel" name="phone" id="phone" placeholder="Input school phone number" required>
                            </div>
                            <div class="col-row">
                                <label for="email">School Email</label>
                                <input type="email" name="email" id="email" placeholder="Input school email">
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="physical">Physical Address</label> 
                                <textarea name="physical" id="physical" placeholder="Input physical address"></textarea> 
                            </div>
                            <div class="col-row">
                                <label for="postal" class="required-field">Postal Address</label>
                                <textarea name="postal" id="postal" required placeholder="Input complete school address"></textarea>
                            </div>
                        </div>

                        <label for="website">School Website</label>
                        <input type="text" name="website" id="website" placeholder="Input website URL">

                        <label for="location">School Location</label>
                        <input type="text" name="location" id="location" placeholder="e.g. Lobatse, Ledumadumane, Manyana">

                        <div class="buttons-container">        
                            <button type="button" class="btn-back">Back</button>
                            <button type="button" class="btn-next">Next</button>
                        </div>
                    </section>
                    
                    <!-- Section 3: Login Credentials -->
                    <section id="section3">
                        <h4>Login Credentials</h4>
                        <label for="username" class="required-field">Username</label>
                        <input type="text" name="username" id="username" placeholder="Input your school account username" required>
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
        const data = {
            ...Object.fromEntries(formData.entries()),
            action: "registerNewschool"
        };
        
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
        showErrorMessage('An error occurred. Please try again.');
    } finally {
        domElements.submitBtn.disabled = false;
        domElements.submitBtn.textContent = originalBtnText;
    }
}

function handleServerResponse(result) {
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
        }, 1000);
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