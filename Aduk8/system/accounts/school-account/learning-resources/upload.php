<?php 
require_once("../includes/layout.php");

$subjects = getSchoolSubjects($conn, $_SESSION['user_id'],' NOT IN ','1');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduk8 | Learning Resources Upload</title>
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
        .attactchments-container {
            padding: 0px;
            margin:0px;

        }
        .attactchments-container label, i{
            padding: 0px;
            margin:0px;
            color:var(--text-primary);

        }
        .attactchments-container span{
            padding: 0px;
            margin-left: 10px;
            display:none;
            color:var(--text-primary);

        }

        .attactchments-container i:hover{
            color:var(--text-secondary);
            
        }
        .file-upload-label {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 4px;
            background: var(--bg-secondary);
        }

        .file-upload-label:hover {
            background: var(--bg-secondary);
        }

        #file_name {
            margin-left: 8px;
            color:var(--text-primary);
            font-style: italic;
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="content">
                <h2>Upload Learning Resources</h2>
                
                <div class="progress-indicator" id="progressIndicator">
                </div>
                <div id="responseMessage"></div>
                
                <form method="post" id="subjectSelectionForm">
                    <section class="active" id="subjectSelectionSection">
                        <h4>Content Information</h4>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="name" class="required-field">Resource Name</label>
                                <input type="text" name="name" id="name" placeholder="Input The Resource Name" required>
                            </div>
                            <div class="col-row">
                                <label for="classification" class="required-field">Resource Classification</label>
                                <select name="classification" id="classification" required>
                                    <option disabled selected >Please select the Resource classification</option>
                                    <option value="Revision">Revision / Study Materials</option>
                                    <option value="Notes">Notes</option>
                                </select> 
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="name" class="required-field">Resource Topic</label>
                                <input type="text" name="name" id="name" placeholder="Input The Resource Topic" required>
                            </div>
                            <div class="col-row">
                                <label for="classification" class="required-field">Resource Subject</label>
                                <select name="classification" id="classification" required>
                                    <option disabled selected >Please select the Resource's Subject</option>
                                    <option value="All Subjects">All Subjects</option>
                                </select> 
                            </div>
                            <div class="col-row">
                                <label for="classification" class="required-field">Resource For Grade</label>
                                <select name="classification" id="classification" required>
                                    <option disabled selected >Please select the Grade for this Resource</option>
                                    <option value="All Grades">All Grades</option>
                                </select> 
                            </div>
                        </div>
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="content_for" class="required-field">Content For </label>
                                <select name="content_for" id="content_for" required>
                                    <option disabled selected >Content for which schools</option>
                                    <option value="All Schools">Global</option>
                                    <option value="<?= $center_no;?>">Local</option>
                                </select> 
                            </div>
                            <div class="col-row" id="class_list">
                                <label for="class" class="required-field">Class List</label>
                                <select name="class" id="class" required>
                                    <option disabled selected >Content For which Classes</option>
                                    <option value="All Classes">All Classes</option>
                                </select> 
                            </div>
                        </div>
                        <div class="col-row">
                            <label for="description"class="required-field">Resource Description</label>
                            <textarea name="description" id="description" required></textarea>
                        </div>
                        <br>
                        <div class="attactchments-container">
                        <label for="attatchment" class="file-upload-label">
                            <i class="fas fa-paperclip"></i> Upload File
                            <span id="file_name" style="display:none;"></span>
                        </label>
                        <input type="file" style="display:none;" name="attatchment" id="attatchment" accept="*/*">
                        </div>

                        <div class="buttons-container">        
                            <button type="button" class="btn-cancel" onclick="confirmCancel()">Cancel</button>
                            <button type="button" class="btn-primary" onclick="confirmCancel()">Existing Resources</button>
                            <button type="submit" id="submitBtn" class="btn-primary">Upload</button>
                        </div>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('attatchment');
    const fileNameSpan = document.getElementById('file_name');
    
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            // Display the filename for any file type
            fileNameSpan.textContent = this.files[0].name;
            fileNameSpan.style.display = 'inline';
        } else {
            // Hide the span if no file selected
            fileNameSpan.style.display = 'none';
        }
    });
    
    // Make the whole label clickable (except when clicking the filename)
    document.querySelector('label[for="attatchment"]').addEventListener('click', function(e) {
        if (e.target !== fileNameSpan) {
            fileInput.click();
        }
    });
});
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('subjectSelectionForm');
            const responseMessage = document.getElementById('responseMessage');
            const submitBtn = document.getElementById('submitBtn');
            const subjectsError = document.getElementById('subjects-error');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Reset error states
                responseMessage.style.display = 'none';
                subjectsError.style.display = 'none';
                
                // Validate at least one subject is selected
                const checkedBoxes = document.querySelectorAll('input[name="subjects[]"]:checked');
                if (checkedBoxes.length === 0) {
                    subjectsError.style.display = 'block';
                    showMessage('Please select at least one subject.', 'error');
                    return;
                }
                
                // Show loading state
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                
                try {
                    // Get all selected subjects
                    const selectedSubjects = Array.from(checkedBoxes).map(cb => cb.value);
                    
                    // Prepare data for JSON submission
                    const data = {
                        center_no: '<?php echo $_SESSION["user_id"]; ?>',
                        subjects: selectedSubjects,
                        user: 'subject_selection'
                    };
                    
                    const response = await fetch('submit.php', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
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
                    showMessage('An error occurred. Please try again.', 'error');
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
            
            function handleServerResponse(result) {
                responseMessage.style.display = 'block';
                responseMessage.className = result.status === 'success' ? 'success' : 'error';
                responseMessage.textContent = result.message || 
                    (result.status === 'success' ? 'Subject selection successful!' : 'Subject selection failed.');
                
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
                        responseMessage.textContent = result.errors.message;
                    }
                }

                // Redirect on success
                if (result.status === 'success' && result.url) {
                    setTimeout(() => {
                        window.location.href = result.url;
                    }, 500);
                }
            }
            
            function showMessage(message, type) {
                responseMessage.style.display = 'block';
                responseMessage.className = type;
                responseMessage.textContent = message;
            }
        });
        
        function confirmCancel() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '../dashboard.php';
            }
        }
    </script>
</body>
</html>