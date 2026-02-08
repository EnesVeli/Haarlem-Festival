<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Haarlem Festival</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        .hidden { display: none; }
    </style>
</head>
<body>

    <h1>Register</h1>

    <div id="message-container"></div>

    <form id="register-form">
        <div>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" minlength="8" required>
        </div>

        <button type="submit" id="submit-btn">Register</button>
        <span id="loading-spinner" class="hidden">Loading...</span>
    </form>

    <script>
        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-btn');
            const spinner = document.getElementById('loading-spinner');
            const messageContainer = document.getElementById('message-container');

            messageContainer.innerHTML = '';
            messageContainer.className = '';
            submitBtn.disabled = true;
            spinner.classList.remove('hidden');

            const formData = {
                name: document.getElementById('name').value, 
                email: document.getElementById('email').value,
                password: document.getElementById('password').value
            };

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    messageContainer.innerHTML = sanitizeHTML(result.message);
                    messageContainer.className = 'success';
                    e.target.reset();
                } else {
                    messageContainer.innerHTML = sanitizeHTML(result.message);
                    messageContainer.className = 'error';
                }

            } catch (error) {
                messageContainer.innerHTML = 'An unexpected error occurred.';
                messageContainer.className = 'error';
            } finally {
                submitBtn.disabled = false;
                spinner.classList.add('hidden');
            }
        });

        function sanitizeHTML(str) {
            const temp = document.createElement('div');
            temp.textContent = str;
            return temp.innerHTML;
        }
    </script>
</body>
</html>