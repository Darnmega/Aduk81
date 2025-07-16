<?php require_once("../includes/layout.php"); 



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Aduk8 | Profile Information</title>
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

        .notes{
            font-size: 0.85rem;

        }
        .notes span{
            text-rendering: geometricPrecision;
        }



    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="content">
                <h2>Profile Information</h2>
                <div class="notes">
                    <span>Note: To save any changes use the update button</span>
                </div>
                <div class="profile-image-container">
                    <div class="profile-image-wrap" title="Click to change profile picture">
                        <img id="profile-image-preview" src="<?= $profile_pic_url . ($user_info['profile_img'] ?? 'default.png'); ?>" alt="User Profile">
                        <input type="file" accept="image/*" name="profile-image" id="profile-image" onchange="previewImage(this)">
                    </div>
                </div>

                <div id="responseMessage"></div>
                
                <form method="post" id="registrationForm" enctype="multipart/form-data">              
                    <section class="active" id="section1">
                        <h4>Personal Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="first_name" class="required-field">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($user_info['first_name']);?>" required>
                            </div>
                            <div class="col-row">
                            <label for="middle_name" >Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" value = "<?= htmlspecialchars($user_info['middle_name']);?>">
                            </div>
                            <div class="col-row">
                            <label for="last_name" class="required-field">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value = "<?= htmlspecialchars($user_info['last_name']);?>"required>
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="gender" class="required-field">Gender</label>
                                    <select name="gender" id="gender" required>
                                        <option disabled selected>Select your gender</option>
                                        <?php foreach ($genders as $gender): ?>
                                        <option value="<?= htmlspecialchars($gender); ?>"
                                            <?= (isset($user_info['gender']) && $user_info['gender'] === $gender) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($gender); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>

                            </div>
                            <div class="col-row">
                                <label for="DOB" class="required-field">Date Of Birth</label>
                                <input type="date" name="DOB" id="DOB" value = "<?= htmlspecialchars($user_info['date_of_birth']);?>"required >
                            </div>
                        </div>
                                               
                        
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
                                <label for="id_number" class="required-field">Birth Certificate/ ID / Passport Number</label>
                                <input type="text" name="id_number" id="id_number" value = "<?= htmlspecialchars($user_info['id_number']);?>" required>
                            </div>
                        </div>
                        
                        <h4>Contact Information</h4>
                        <?php $contacts =  getContacts($conn, $UserID);
                        foreach ($contacts as $contact) {
                            # code...
                        ?>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="phone">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value = "<?= htmlspecialchars($contact['phone']);?>" >
                            </div>
                            <div class="col-row">
                                <label for="email">Email Address</label>
                                <input type="email" name="email" id="email" value = "<?= htmlspecialchars($contact['email']);?>" >
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="physical">Physical Address</label> 
                                <textarea name="physical" id="physical" ><?= htmlspecialchars($contact['physical']);?> </textarea> 
                            </div>
                            <div class="col-row">
                                <label for="postal">Postal Address</label>
                                <textarea name="postal" id="postal"><?= htmlspecialchars($contact['postal']);?></textarea>
                            </div>
                        </div>
                        <label for="residence">Place Of Recidence</label>
                        <input type="text" name="residence" id="residence" value = "<?= htmlspecialchars($contact['place_of_residence']);?>" >
                       
<?php } ?>
<br>
<a href="#">change login credentials</a>
                        <div class="buttons-container">        
                            <button type="button" class="btn-back">Back</button>
                            <button type="submit" id="submitBtn">Update</button>
                        </div>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                document.getElementById('profile-image-preview').src = e.target.result;
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
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
                window.location.href = '../dashboard.php';
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
            user: "student"
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