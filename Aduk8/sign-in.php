<?php require_once 'system/includes/connection.php';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aduk8 | Sign in</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: url('https://source.unsplash.com/random/1920x1080/?abstract') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 2;
            width: 400px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .input-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 30px;
            font-size: 16px;
            color: white;
            outline: none;
            transition: all 0.3s;
        }

        .input-group input:focus {
            background: rgba(255, 255, 255, 0.2);
        }

        .input-group input::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        button {
            padding: 12px 30px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        button[type="submit"] {
            background: linear-gradient(45deg, #0061ff, #60efff);
            color: white;
            flex: 1;
            margin-right: 15px;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 97, 255, 0.3);
        }

        #cancelBtn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        #cancelBtn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        #responseMessage {
            padding: 15px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
            display: none;
        }

        .success {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .error {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .brand-logo {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
            background: linear-gradient(45deg, #0061ff, #60efff);
            -webkit-text-fill-color: transparent;
        }

        @media (max-width: 500px) {
            .login-container {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-logo">ADUK8</div>
        <h2>Sign In</h2>
        
        <form id="loginForm" >
            <div class="input-group">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <div id="responseMessage"></div>
            
            <div class="button-group">
                <button type="submit">Sign in</button>
                <button type="button" id="cancelBtn">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                username: document.querySelector('input[name="username"]').value,
                password: document.querySelector('input[name="password"]').value
            };
            
            // Send data to auth.php
            fetch('system/includes/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                const responseDiv = document.getElementById('responseMessage');
                responseDiv.style.display = 'block';
                
                if (data.status === true) {
                    responseDiv.className = 'success';
                    responseDiv.textContent = 'Login successful! Redirecting...';
                    setTimeout(() => {
                        window.location.href = data.url;
                    }, 1500);
                } else {
                    responseDiv.className = 'error';
                    responseDiv.textContent = data.message || 'Login failed. Please try again.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const responseDiv = document.getElementById('responseMessage');
                responseDiv.style.display = 'block';
                responseDiv.className = 'error';
                responseDiv.textContent = 'An error occurred during login.';
            });
        });

        document.getElementById('cancelBtn').addEventListener('click', function() {
            // Clear form fields
            document.getElementById('loginForm').reset();
            // Hide any visible messages
            document.getElementById('responseMessage').style.display = 'none';
        });
    </script>
</body>
</html>