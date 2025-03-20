<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NSBM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-md shadow-md p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Welcome</h1>
            <p class="text-gray-600">Nursing Service Bed Management</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="username" class="block text-gray-700 mb-2">Your Name</label>
                <input type="text" name="username" id="username" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('username') }}" required autofocus>
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" required>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 px-4 rounded-md transition duration-200">
                Login
            </button>
        </form>
    </div>
</body>
</html>
