<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Asset Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

        :root {
            --primary-color: #5a9bd8;
            --primary-dark: #a7c9f1;
            --secondary-color: #6c757d;
            --text-color: #2d3436;
            --background-color: #f8f9fa;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--background-color);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Background Animation */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
            background: 
                radial-gradient(circle at 80% 20%, var(--primary-color) 0%, transparent 25%),
                radial-gradient(circle at 20% 80%, var(--primary-dark) 0%, transparent 25%);
            animation: gradientAnimation 15s ease infinite alternate;
        }

        @keyframes gradientAnimation {
            0% {
                transform: scale(1) rotate(0deg);
            }
            100% {
                transform: scale(1.2) rotate(5deg);
            }
        }

        .login-container {
            width: 100%;
            max-width: 1000px;
            margin: 2rem auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .row {
            min-height: 600px;
        }

        /* Left Side - Illustration */
        .illustration-side {
            background: var(--primary-color);
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .illustration-side::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%) -50px 0,
                linear-gradient(-45deg, rgba(255,255,255,0.1) 25%, transparent 25%) -50px 0;
            background-size: 100px 100px;
            animation: patternMove 10s linear infinite;
        }

        @keyframes patternMove {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 100px 100px;
            }
        }

        .brand-title {
            font-size: 2.5rem;
            font-weight: 600;
            color: #000;
            margin-bottom: 1rem;
            position: relative;
            z-index: 1;
        }

        .brand-description {
            color: rgba(0, 0, 0, 0.8);
            font-size: 1.1rem;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        /* Right Side - Login Form */
        .form-side {
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 2rem;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating > label {
            padding-left: 1rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            height: 3.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(139, 162, 231, 0.25);
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            padding: 1rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 162, 231, 0.25);
        }

        .form-error {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            padding-left: 0.5rem;
        }

        .alert {
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: none;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            height: 3rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group .form-control {
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-left: none;
            padding: 0.75rem;
        }

        .password-toggle {
            color: var(--secondary-color);
            width: 1.25rem;
            height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(139, 162, 231, 0.25);
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: var(--primary-color);
        }

        .form-error {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .illustration-side {
                padding: 2rem;
                min-height: 200px;
            }

            .brand-title {
                font-size: 2rem;
            }

            .brand-description {
                font-size: 1rem;
            }

            .form-side {
                padding: 2rem;
            }

            .login-container {
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Background Animation -->
    <div class="bg-animation"></div>

    <div class="container">
        <div class="login-container">
            <div class="row g-0">
                <!-- Left Side - Illustration -->
                <div class="col-lg-6 illustration-side">
                    <div class="text-center">
                        <h1 class="brand-title">Asset Management</h1>
                        <p class="brand-description">
                            Sistem manajemen aset yang memudahkan pengelolaan dan pemantauan aset secara efisien dan terstruktur.
                        </p>
                        <!-- Additional animation or illustration can be added here -->
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="col-lg-6 form-side">
                    <div>
                        <h2 class="login-title">Login</h2>

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <div>{{ session('error') }}</div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" 
                                       id="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="Masukkan alamat email"
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           id="password" 
                                           placeholder="Masukkan password"
                                           required>
                                    <span class="input-group-text">
                                        <i class="fas fa-eye password-toggle" 
                                           onclick="togglePassword()"
                                           style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="form-error">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-login text-dark">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
            
            // Memastikan focus tetap pada input
            passwordInput.focus();
        }        // Optional: Add input focus animation
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('focused');
            });
            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentElement.classList.remove('focused');
                }
            });
        });
    </script>
</body>
</html>
