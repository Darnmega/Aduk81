<?php 
require_once("../includes/layout.php");


$grade_levels = getGradeLevels($conn, $center_no, ' NOT IN ');
?>
<head>
    <title>Aduk8 | <?= htmlspecialchars($schoolInformation['school_name'])?> New Block Registration</title>
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
                <h2>Block Registration</h2>
                
                <div class="progress-indicator" id="progressIndicator">
                </div>
                <div id="responseMessage"></div>
                
                <form method="post" id="subjectSelectionForm">
                    <section class="active" id="subjectSelectionSection">
                        <h4><?= htmlspecialchars($schoolInformation['school_name'])?>'s New Block Registration</h4>
                    <div class="mult-display">
                        <div class="col-row">
                            <label for="block_name" class="required-field">Block Name</label>
                            <input type="text" name="block_name" id="block_name" required Placeholder="Input the name of the block">
                        </div>
                        <div class="col-row">
                            <label for="use" class="required-field">Block Use</label>
                            <select name="use" id="use" required>
                                <option disabled selected >Select Block Use</option>
                                <option value="Administration">Administration</option>
                                <option value="Teaching">Teaching</option>
                                <option value="Labs">Labs</option>
                                <option value="Others">Others</option>
                            </select> 
                        </div>
                        <div class="col-row">
                            <label for="no_rooms" class="required-field">No Of Rooms On Block</label>
                            <input type="number" name="no_rooms" id="no_rooms" oninput="generateRoomRows()"required Placeholder="Input the number Of rooms On this block">
                        </div>
                    </div>
                    <div class="table-container">
                        <table id="subjectsTable">
                            <thead>
                                <tr>
                                    <th>Room Name/ Number</th>
                                    <th>Room Use</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="2" class="no-data">Please Input the number of rooms on this block</td>
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

function generateRoomRows() {
    const noRooms = parseInt(document.getElementById('no_rooms').value) || 0;
    const tableBody = document.querySelector('#subjectsTable tbody');
    
    // Clear existing rows (except the "no data" row if it exists)
    tableBody.innerHTML = '';
    
    // If no rooms specified, show the placeholder message
    if (noRooms <= 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="2" class="no-data">Please Input the number of rooms on this block</td>
            </tr>
        `;
        return;
    }
    
    // Generate rows for each room
    for (let i = 1; i <= noRooms; i++) {
        const row = document.createElement('tr');
        
        // Room Name/Number cell
        const nameCell = document.createElement('td');
        const nameInput = document.createElement('input');
        nameInput.type = 'text';
        nameInput.name = `room_name_${i}`;
        nameInput.required = true;
        nameInput.placeholder = `Room ${i} Name/Number`;
        nameCell.appendChild(nameInput);
        
        // Room Use cell
        const useCell = document.createElement('td');
        const useSelect = document.createElement('select');
        useSelect.name = `room_use_${i}`;
        useSelect.required = true;
        
        // Add options to the dropdown
        const roomUses = [
            'Classroom',
            'Office',
            'Laboratory',
            'Library',
            'Staff Room',
            'Storage',
            'Restroom',
            'Other'
        ];
        
        // Add default option
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Select Room Use';
        defaultOption.disabled = true;
        defaultOption.selected = true;
        useSelect.appendChild(defaultOption);
        
        // Add other options
        roomUses.forEach(use => {
            const option = document.createElement('option');
            option.value = use;
            option.textContent = use;
            useSelect.appendChild(option);
        });
        
        useCell.appendChild(useSelect);
        
        // Append cells to row
        row.appendChild(nameCell);
        row.appendChild(useCell);
        
        // Append row to table
        tableBody.appendChild(row);
    }
}
 // Form submission handler
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('subjectSelectionForm');
    const responseMessage = document.getElementById('responseMessage');
    
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset previous errors
        document.querySelectorAll('.error-text').forEach(el => {
            el.style.display = 'none';
        });
        responseMessage.style.display = 'none';
        
        // Validate main form fields
        const blockName = document.getElementById('block_name').value.trim();
        const blockUse = document.getElementById('use').value.trim();
        const noRooms = parseInt(document.getElementById('no_rooms').value) || 0;
        
        let isValid = true;
        
        if (!blockName) {
            showMessage('Block name is required', 'error');
            isValid = false;
        }
        
        if (!blockUse) {
            showMessage('Block use is required', 'error');
            isValid = false;
        }
        
        if (noRooms <= 0) {
            showMessage('Number of rooms must be at least 1', 'error');
            isValid = false;
        }
        
        // Validate room entries
        const rooms = [];
        let roomErrors = false;
        
        for (let i = 1; i <= noRooms; i++) {
            const roomName = document.querySelector(`input[name="room_name_${i}"]`)?.value.trim();
            const roomUse = document.querySelector(`select[name="room_use_${i}"]`)?.value;
            
            if (!roomName || !roomUse) {
                roomErrors = true;
                break;
            }
            
            rooms.push({
                name: roomName,
                use: roomUse
            });
        }
        
        if (roomErrors) {
            showMessage('Please fill all room information completely', 'error');
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
                    action: 'registerBlock',
                    blockName: blockName,
                    blockUse: blockUse,
                    no_rooms: noRooms,
                    rooms: rooms,
                })
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.status === 'success') {
                showMessage(result.message, 'success');
                setTimeout(() => {
                    window.location.href = result.url || 'blocks.php';
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
