<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | FSKTM Task Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-xl flex flex-col md:flex-row overflow-hidden max-w-4xl w-full">
        
        <!-- Left: Welcome Section -->
        <div class="md:w-1/2 bg-blue-600 text-white flex flex-col items-center justify-center p-10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white-800">FSKTM</h1> <br>
                <h3 class="text-sm text-white-500 tracking-widest">TASK MANAGEMENT SYSTEM</h3>
            </div>
           <br> <p class="text-center mb-6 text-sm">Login to access your dashboard and manage academic tasks effectively.</p>
        </div>

        <!-- Right: Login Form Section -->
        <div class="md:w-1/2 flex items-center justify-center">
            <div class="w-full p-8 md:p-10"> 
                <h3 class="text-2xl font-semibold text-gray-700 mb-6 text-center">Sign In to Your Account</h3>

                @if (session('error'))
                    <div class="bg-red-100 text-red-700 px-4 py-3 mb-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm text-gray-600 mb-1">Email</label>
                        <input type="email" id="email" name="email" required autofocus
                            class="w-full px-4 py-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <div>
                        <label for="password" class="block text-sm text-gray-600 mb-1">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 border rounded-lg text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center text-gray-600">
                            <input type="checkbox" name="remember" class="mr-2"> Remember me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">Forgot?</a>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                        Sign In
                    </button>
                </form>

                <p class="mt-6 text-sm text-center text-gray-600">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline">Register</a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
