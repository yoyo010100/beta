<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BETA Admin — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sun: '#F2A900',
                        ocean: '#003087',
                        ink: '#071321',
                        charcoal: '#1a202c',
                        paper: '#f4f7f6',
                        muted: '#718096',
                        line: '#e2e8f0',
                    },
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                        display: ['Manrope', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: "DM Sans", sans-serif; background: var(--paper); color: var(--ink); }
        h1, h2, h3, h4 { font-family: "Manrope", sans-serif; }
        a { text-decoration: none; color: inherit; }
        button, select, input { font: inherit; outline: none; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
        <!-- Brand Header -->
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 font-display font-extrabold text-3xl tracking-wide">
                <span class="relative w-10 h-10 -skew-x-6 rotate-45 border-2 border-sun">
                    <span class="absolute left-[6px] top-[2px] w-[2px] h-[14px] bg-sun"></span>
                </span>
                BETA
            </a>
            <p class="mt-3 text-muted text-sm">Executive Administration Portal</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white rounded-xl border border-line shadow-sm p-8">
            <header class="mb-8 text-center">
                <h1 class="font-display font-bold text-2xl text-ink">Welcome Back</h1>
                <p class="mt-2 text-muted text-sm">Sign in to access the admin dashboard</p>
            </header>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg" role="alert">
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5" novalidate>
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-charcoal mb-1.5">Email Address</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full px-4 py-3 border border-line rounded-lg text-charcoal placeholder-muted transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-sun focus:border-transparent"
                        placeholder="admin@beta.example"
                    >
                    @error('email')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-charcoal mb-1.5">Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            autocomplete="current-password"
                            class="w-full px-4 py-3 pr-12 border border-line rounded-lg text-charcoal placeholder-muted transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-sun focus:border-transparent"
                            placeholder="••••••••"
                        >
                        <button 
                            type="button" 
                            id="togglePassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-muted hover:text-ink transition-colors"
                            aria-label="Toggle password visibility"
                        >
                            <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            id="remember"
                            class="w-4 h-4 rounded border-line text-sun focus:ring-2 focus:ring-sun focus:ring-offset-2 transition-colors"
                        >
                        <span class="text-sm text-charcoal">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-medium text-ocean hover:text-ink hover:underline transition-colors">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full py-3 px-4 bg-ocean text-white font-semibold rounded-lg hover:bg-ink hover:text-sun transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-sun focus:ring-offset-2"
                >
                    Sign In
                </button>
            </form>

            <!-- Footer -->
            <footer class="mt-8 text-center">
                <p class="text-sm text-muted">
                    Don't have an account? 
                    <a href="{{ route('home') }}" class="font-medium text-ocean hover:text-ink hover:underline">Return to site</a>
                </p>
                <p class="mt-3 text-xs text-muted">BETA Industrial &copy; {{ date('Y') }}</p>
            </footer>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            toggleBtn.addEventListener('click', function() {
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';
                eyeOpen.classList.toggle('hidden', isPassword);
                eyeClosed.classList.toggle('hidden', !isPassword);
            });

            // Auto-focus email field
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>