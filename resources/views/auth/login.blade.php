<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NSBM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Add Nunito font from Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary: #F8F9FA;
            --color-primary-light: #FFFFFF;
            --color-secondary: #f78fa7;
            --color-secondary-light: #fce4e9; /* Light pink for input fields */
            --color-secondary-dark: #c57285;
            --color-accent: #8B0000;
            --color-border: #ffccd5;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--color-primary);
        }

        .login-card {
            background-color: var(--color-primary-light);
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--color-border);
        }

        .btn-login {
            background-color: var(--color-secondary);
            transition: all 0.2s;
        }

        .btn-login:hover {
            background-color: var(--color-secondary-dark);
        }

        .input-field {
            background-color: var(--color-secondary-light);
            border: 1px solid var(--color-border);
        }

        .input-field:focus {
            border-color: var(--color-secondary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(247, 143, 167, 0.2);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full login-card p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold" style="color: var(--color-secondary);">Welcome</h1>
            <p class="text-gray-600">Nursing Service Bed Management</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-medium mb-2 text-sm">Your Name</label>
                <input type="text" name="username" id="username" class="w-full px-4 py-2 rounded-md input-field focus:outline-none" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-medium mb-2 text-sm">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 rounded-md input-field focus:outline-none" required>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5" aria-label="Toggle password visibility">
                        <i class="fas fa-eye text-gray-500 hover:text-gray-700" id="passwordIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full btn-login text-white py-2 px-4 rounded-md transition duration-200 font-semibold">
                Login
            </button>
        </form>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const passwordIcon = document.querySelector('#passwordIcon');

        togglePassword.addEventListener('click', function() {
            // Toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle the eye / eye-slash icon
            passwordIcon.classList.toggle('fa-eye');
            passwordIcon.classList.toggle('fa-eye-slash');
        });
    });
</script>
</html>
