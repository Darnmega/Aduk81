<?php 
require_once("../includes/layout.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?>'s House Registration</title>
    <style>
        /* Main Table Styles */
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
        
        /* Empty State */
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        
        /* Messages and Errors */
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
        
        /* Container Styles */
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        /* Form Elements */
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
        

        /* Spinner */
        .spinner {
            display: inline-block;
            width: 1em;
            height: 1em;
            border: 2px solid rgba(0,0,0,.1);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <div class="content">
                <h2><?= htmlspecialchars($schoolInformation['school_name'])?>'s House Registration</h2>
                
                <div id="responseMessage"></div>
                
                <form id="houseRegistrationForm">
                    <section class="active" id="houseRegistrationSection">
                        <h4>House Information</h4>
                        
                        <div class="mult-display">
                            <div class="col-row">
                                <label for="house_name" class="required-field">House Name</label>
                                <input type="text" id="house_name" required placeholder="Enter house name">
                                <div class="error-text" id="house_name_error"></div>
                            </div>
                            
                            <div class="col-row">
                                <label for="house_color" class="required-field">House Color</label>
                                <input type="text" id="house_color" required placeholder="Enter house color">
                                <div class="error-text" id="house_color_error"></div>
                            </div>
                            
                            <div class="col-row">
                                <label for="class_range" class="required-field">Number of Classes</label>
                                <input type="number" id="class_range" min="1" required 
                                       placeholder="Enter number of classes" oninput="generateClassRows()">
                                <div class="error-text" id="class_range_error"></div>
                            </div>
                        </div>
                        
                        <div class="col-row">
                            <label for="motto" class="required-field">House Motto</label>
                            <textarea id="motto" required></textarea>
                            <div class="error-text" id="motto_error"></div>
                        </div>
                        
                        <br>
                        
                        <div class="table-container">
                            <table id="classesTable">
                                <thead>
                                    <tr>
                                        <th>Class Name</th>
                                        <th>Class Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="2" class="no-data">Please enter the number of classes first</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
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
        // Generate dynamic class rows based on number input
        function generateClassRows() {
            const numClasses = parseInt(document.getElementById('class_range').value) || 0;
            const tableBody = document.querySelector('#classesTable tbody');
            
            tableBody.innerHTML = '';
            
            if (numClasses <= 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="2" class="no-data">Please enter the number of classes first</td>
                    </tr>
                `;
                return;
            }
            
            for (let i = 1; i <= numClasses; i++) {
                const row = document.createElement('tr');
                
                // Class Name cell
                const nameCell = document.createElement('td');
                const nameInput = document.createElement('input');
                nameInput.type = 'text';
                nameInput.id = `class_name_${i}`;
                nameInput.required = true;
                nameInput.placeholder = 'A, B, C, etc';
                nameCell.appendChild(nameInput);
                
                // Class Type cell
                const typeCell = document.createElement('td');
                const typeSelect = document.createElement('select');
                typeSelect.id = `class_type_${i}`;
                typeSelect.required = true;
                
                // Add options
                const classTypes = ['Upper Class', 'Middle Class', 'Lower Class'];
                
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select Class Type';
                defaultOption.disabled = true;
                defaultOption.selected = true;
                typeSelect.appendChild(defaultOption);
                
                classTypes.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.textContent = type;
                    typeSelect.appendChild(option);
                });
                
                typeCell.appendChild(typeSelect);
                
                row.appendChild(nameCell);
                row.appendChild(typeCell);
                tableBody.appendChild(row);
            }
        }
        
        // Form submission handler
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('houseRegistrationForm');
            const responseMessage = document.getElementById('responseMessage');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Reset previous errors
                document.querySelectorAll('.error-text').forEach(el => {
                    el.style.display = 'none';
                });
                responseMessage.style.display = 'none';
                
                // Validate main form fields
                const houseName = document.getElementById('house_name').value.trim();
                const houseColor = document.getElementById('house_color').value.trim();
                const motto = document.getElementById('motto').value.trim();
                const classRange = parseInt(document.getElementById('class_range').value) || 0;
                
                let isValid = true;
                
                if (!houseName) {
                    document.getElementById('house_name_error').textContent = 'House name is required';
                    document.getElementById('house_name_error').style.display = 'block';
                    isValid = false;
                }
                
                if (!houseColor) {
                    document.getElementById('house_color_error').textContent = 'House color is required';
                    document.getElementById('house_color_error').style.display = 'block';
                    isValid = false;
                }
                
                if (!motto) {
                    document.getElementById('motto_error').textContent = 'House motto is required';
                    document.getElementById('motto_error').style.display = 'block';
                    isValid = false;
                }
                
                if (classRange <= 0) {
                    document.getElementById('class_range_error').textContent = 'Please enter a valid number of classes';
                    document.getElementById('class_range_error').style.display = 'block';
                    isValid = false;
                }
                
                // Validate class entries
                const classes = [];
                let classErrors = false;
                
                for (let i = 1; i <= classRange; i++) {
                    const className = document.getElementById(`class_name_${i}`)?.value.trim();
                    const classType = document.getElementById(`class_type_${i}`)?.value;
                    
                    if (!className || !classType) {
                        classErrors = true;
                        break;
                    }
                    
                    classes.push({
                        name: className,
                        type: classType
                    });
                }
                
                if (classErrors) {
                    showMessage('Please fill all class information completely', 'error');
                    isValid = false;
                }
                
                if (!isValid) return;
                
                // Prepare data for submission
                const submitBtn = document.getElementById('submitBtn');
                const originalBtnText = submitBtn.innerHTML;
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner"></span> Processing...';
                
                try {
                    const response = await fetch('<?= htmlspecialchars($postDataFile); ?>', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            house_name: houseName,
                            house_color: houseColor,
                            motto: motto,
                            classes: classes,
                            action:'registerHouse'
                        })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const result = await response.json();
                    
                    if (result.status === 'success') {
                        showMessage(result.message, 'success');
                        setTimeout(() => {
                            window.location.href = result.url || 'house.php';
                        }, 1500);
                    } else {
                        showMessage(result.message || 'Registration failed', 'error');
                        
                        // Show field-specific errors if available
                        if (result.errors && result.errors.fields) {
                            result.errors.fields.forEach(field => {
                                const errorEl = document.getElementById(`${field}_error`);
                                if (errorEl) {
                                    errorEl.textContent = result.errors.message || 'Invalid value';
                                    errorEl.style.display = 'block';
                                }
                            });
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
        });
        
        // Helper functions
        function showMessage(message, type) {
            const responseMessage = document.getElementById('responseMessage');
            responseMessage.textContent = message;
            responseMessage.className = type;
            responseMessage.style.display = 'block';
        }
        
        function confirmCancel() {
            if (confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
                window.location.href = '<?= htmlspecialchars($dashboardFile); ?>';
            }
        }
    </script>
</body>
</html>