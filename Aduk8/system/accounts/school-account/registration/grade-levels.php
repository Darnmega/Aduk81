<?php 
require_once("../includes/layout.php");


$grade_levels = getGradeLevels($conn, $center_no, ' NOT IN ');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?>'s Grade Level Selection</title>
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
                <h2><?= htmlspecialchars($schoolInformation['school_name'])?>'s Grade Level Selection</h2>
                
                <div class="progress-indicator" id="progressIndicator">
                </div>
                <div id="responseMessage"></div>
                
                <form method="post" id="subjectSelectionForm">
                    <section class="active" id="subjectSelectionSection">
                        <h4>Available Grade Levels</h4>
                        <div class="table-container">
                            <table id="subjectsTable">
                                <thead>
                                    <tr>
                                        <th width="50px">Select</th>
                                        <th>Name</th>
                                        <th>Level</th>
                                        <th>Description</th>    
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($grade_levels['message'])): ?>
                                        <tr>
                                            <td colspan="5" class="no-data"><?= htmlspecialchars($grade_levels['message']); ?></td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($grade_levels as $grade_level): ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" 
                                                           name="grade_level[]" 
                                                           id="grade_level_<?= htmlspecialchars($grade_level["grade_no"]); ?>" 
                                                           value="<?= htmlspecialchars($grade_level["grade_no"]); ?>" 
                                                           class="subject-checkbox">
                                                </td>
                                                <td><?= htmlspecialchars($grade_level["grade_name"]); ?></td>
                                                <td><?= htmlspecialchars($grade_level["grade_level"]); ?></td>
                                                <td><?= htmlspecialchars($grade_level["description"]); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="subjects-error" class="error-text">Please select at least one grade level</div>
                        <div class="buttons-container">        
                            <button type="button" class="btn-cancel" onclick="confirmCancel()"><i class="fa fa-times"></i> Close</button>
                            <button type="submit" id="submitBtn" class="btn-primary"><i class="fa fa-check"></i> Register</button>
                        </div>
                    </section>
                </form>
            </div>
        </main>
    </div>

    <script>
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
                const checkedBoxes = document.querySelectorAll('input[name="grade_level[]"]:checked');
                if (checkedBoxes.length === 0) {
                    subjectsError.style.display = 'block';
                    showMessage('Please select at least one grade_level.', 'error');
                    return;
                }
                
                // Show loading state
                const originalBtnText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                
                try {
                    // Get all selected subjects
                    const selectedgradelevel = Array.from(checkedBoxes).map(cb => cb.value);
                    
                    // Prepare data for JSON submission
                    const data = {
                        center_no: '<?= $_SESSION["user_id"]; ?>',
                        grade_level: selectedgradelevel,
                        action: 'registerGradeLevel'
                    };
                    
                    const response = await fetch('<?= htmlspecialchars($postDataFile); ?>', {
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
                    (result.status === 'success' ? 'Grade level selection successful!' : 'Grade level selection failed.');
                
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
                window.location.href = '<?= htmlspecialchars($dashboardFile); ?>';
            }
        }
    </script>
</body>
</html>