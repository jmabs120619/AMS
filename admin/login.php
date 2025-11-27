
<?php
include 'loading_screen.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .login {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('assets/background.png') no-repeat center center/cover;
        }

        .login-form {
            width: 400px;
            background: rgba(255, 255, 255, 0.6);
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            backdrop-filter: blur(20px);
            padding: 30px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }

        .login-form h3 {
            text-align: center;
            color: black;
            margin-bottom: 20px;
        }

        .input-box {
            position: relative;
            margin: 20px 0;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            font-size: 0.9em;
            color: black;
            pointer-events: none;
            transition: 0.3s;
        }

        .input-box input {
            width: 100%;
            height: 45px;
            border: none;
            border-bottom: 1px solid black;
            background: transparent;
            outline: none;
            font-size: 1em;
            padding: 0 10px;
            color: black;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -10px;
            font-size: 0.8em;
        }

        .btn {
            width: 100%;
            height: 45px;
            background: black;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 1em;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn:hover {
            background: #333;
        }

        .text-center a {
            color: black;
            text-decoration: none;
            font-size: 0.9em;
        }

        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login">
        <div class="login-form">
            <h3>Admin Login</h3>
            <form method="POST" action="authenticate.php">
                <div class="input-box">
                    <input type="text" name="username" id="username" required>
                    <label for="username">Username</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
            <div class="text-center mt-3">
                <div class="text-center mt-3">
                    <a href="/dpwh/">Back to Homepage</a>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>