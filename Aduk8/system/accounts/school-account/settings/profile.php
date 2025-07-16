<?php require_once("../includes/layout.php");

$schoolContacts =getSchoolContacts($conn, $center_no);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?>'s Profile</title>
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
                <h2><?= htmlspecialchars($schoolInformation['school_name'])?>'s Profile </h2>
                <div class="notes">
                    <span>Note: To save any changes use the update button</span>
                </div>
                <div class="profile-image-container">
                    <div class="profile-image-wrap" title="Click to change profile picture">
                        <img id="profile-image-preview" src="<?= $profile_pic_url . ($schoolInformation['school_emblem'] ?? 'default.png'); ?>" alt="User Profile">
                        <input type="file" accept="image/*" name="profile-image" id="profile-image" onchange="previewImage(this)">
                    </div>
                </div>

                <div id="responseMessage"></div>
                
                <form method="post" id="registrationForm" enctype="multipart/form-data">              
                <section class="active" id="section1">
                        <h4>School Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="name" class="required-field">School Name</label>
                                <input type="text" name="name" id="name" value="<?= htmlspecialchars($schoolInformation['school_name']??'');?>" required>
                            </div>
                            <div class="col-row">
                                <label for="classification" class="required-field">School Classification</label>
                                <select name="classification" id="classification" required>
                                    <option disabled selected value="">Select a school classification</option>
                                    <?php foreach ($schoolClassificationOptions as $schoolClassification): ?>
                                        <option value="<?= htmlspecialchars($schoolClassification); ?>"
                                            <?= (isset($schoolInformation['school_classification']) && $schoolInformation['school_classification'] === $schoolClassification) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($schoolClassification); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </select>
                            </div>
                        </div>
                        <label for="center_no" class="required-field">School Center No</label>
                        <input type="text" name="center_no" id="center_no" value="<?= htmlspecialchars($schoolInformation['center_no']??'');?>" required>
                                               
                        <label for="est_date" class="required-field">Established Date</label>
                        <input type="date" name="est_date" id="est_date" value="<?= htmlspecialchars($schoolInformation['est_date']??'');?>" required>

                        <label for="motto" class="required-field">School Motto</label>
                        <textarea name="motto" id="motto" required><?= htmlspecialchars($schoolInformation['school_motto']??'');?></textarea>
                        
                        <h4>Contact Information</h4>
                        <?php foreach ($schoolContacts as $contact) { ?>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="phone" class="required-field">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($contact['phone']??'');?>" required>
                            </div>
                            <div class="col-row">
                                <label for="email"class="required-field" >School Email</label>
                                <input type="email" name="email" id="email" required value="<?= htmlspecialchars($contact['email_address']??'');?>">
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="physical"class="required-field" >Physical Address</label> 
                                <textarea name="physical" id="physical" required><?= htmlspecialchars($contact['physical_address']??'');?></textarea> 
                            </div>
                            <div class="col-row">
                                <label for="postal" class="required-field">Postal Address</label>
                                <textarea name="postal" id="postal" required ><?= htmlspecialchars($contact['postal_address']??'');?></textarea>
                            </div>
                        </div>
                        
                        <label for="location" class="required-field">School Location</label>
                        <input type="text" name="location" id="location" required value="<?= htmlspecialchars($contact['location']??'');?>">

                        <label for="website" >School Website</label>
                        <input type="text" name="website" id="website" value="<?= htmlspecialchars($contact['website']??'');?>">

                        <div class="mult-display">
                            <div class="col-row">
                                <label for="_facebook"><i class="fas fa-facebook"></i> Facebook Link</label>
                                <input type="link" name="_facebook" id="_facebook" value="<?= htmlspecialchars($contact['_facebook']??'');?>" required>
                            </div>
                            <div class="col-row">
                                <label for="_instagram"><i class="fas fa-instagram"></i> Instagram Link</label>
                                <input type="link" name="_instagram" id="_instagram" value="<?= htmlspecialchars($contact['_instagram']??'');?>">
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="_facebook">Tiktok Link</label>
                                <input type="link" name="_facebook" id="_facebook" value="<?= htmlspecialchars($contact['_facebook']??'');?>" required>
                            </div>
                            <div class="col-row">
                                <label for="_whatsapp_channel"><i class="fas fa-whatsapp"></i> WhatsApp Channel</label>
                                <input type="link" name="_whatsapp_channel" id="_whatsapp_channel" value="<?= htmlspecialchars($contact['_whatsapp_channel']??'');?>">
                            </div>
                            <div class="col-row">
                                <label for="_whatsapp_no"><i class="fas fa-whatsapp"></i> Whatsapp Number</label>
                                <input type="text" name="_whatsapp_no" id="_whatsapp_no" value="<?= htmlspecialchars($contact['_whatsapp_no']??'');?>">
                            </div>
                        </div>
<?php } ?>
<a href="#">change login credentials</a>
                        <div class="buttons-container">        
                            <button type="button" onclick="handleCancelClick()" class="btn-back"> <i class="fa fa-times"></i> Close</button>
                            <button type="submit" id="submitBtn"><i class="fa fa-check"></i> Update</button>
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
            if (confirm('Are you sure you want to exit this page? Any and all unsaved changes will be lost.')) {
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