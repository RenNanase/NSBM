<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="0;url={{ route('login') }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-blue-600">Ward Management System</h1>
        <p class="mt-4 text-gray-600">Redirecting to login page...</p>
        <div class="mt-8">
            <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                Go to Login
            </a>
        </div>
    </div>
</body>
</html>
