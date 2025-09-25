<?php
use Fuel\Core\Form;
use Fuel\Core\Input;
use Fuel\Core\Uri;
?>

<div class="login-container">
    <div class="login-wrapper">
        <!-- Left side - Welcome section với animation -->
        <div class="login-welcome">
            <div class="welcome-content">
                <div class="logo-section">
                    <i class="bi bi-shield-lock-fill logo-icon"></i>
                    <h2 class="welcome-title">Chào mừng trở lại!</h2>
                </div>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Quản lý blog dễ dàng</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Giao diện thân thiện</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Bảo mật cao cấp</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Hỗ trợ đa thiết bị</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Login form -->
        <div class="login-form-section">
            <div class="login-form-container">
                <!-- Header -->
                <div class="login-header">
                    <h3 class="login-title">Đăng nhập</h3>
                    <p class="login-subtitle">Vui lòng nhập thông tin đăng nhập của bạn</p>
                </div>

                <!-- Alert messages -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-custom">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="alert alert-danger alert-custom">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <?php echo Form::open(array('class' => 'login-form', 'id' => 'loginForm')); ?>
                    <?php echo Form::csrf(); ?>
                    
                    <!-- Email field -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i>
                            Email
                        </label>
                        <div class="input-wrapper">
                            <?php echo Form::input('email', 
                                Input::post('email'), 
                                array(
                                    'class' => 'form-control form-control-custom',
                                    'id' => 'email',
                                    'placeholder' => 'Nhập địa chỉ email của bạn',
                                    'required' => true,
                                    'autocomplete' => 'email'
                                )
                            ); ?>
                            <i class="input-icon bi bi-envelope"></i>
                        </div>
                    </div>

                    <!-- Password field -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i>
                            Mật khẩu
                        </label>
                        <div class="input-wrapper">
                            <?php echo Form::password('password', '', 
                                array(
                                    'class' => 'form-control form-control-custom',
                                    'id' => 'password',
                                    'placeholder' => 'Nhập mật khẩu của bạn',
                                    'required' => true,
                                    'autocomplete' => 'current-password'
                                )
                            ); ?>
                            <i class="input-icon bi bi-lock"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me & Forgot password -->
                    <div class="form-options">
                        <div class="remember-me">
                            <?php echo Form::checkbox('remember_me', '1', false, 
                                array(
                                    'id' => 'remember_me',
                                    'class' => 'form-check-input'
                                )
                            ); ?>
                            <label for="remember_me" class="form-check-label">
                                Ghi nhớ đăng nhập
                            </label>
                        </div>
                        <a href="#" class="forgot-password">Quên mật khẩu?</a>
                    </div>

                    <!-- Login button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-login" id="loginBtn">
                            <span class="btn-text">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Đăng nhập
                            </span>
                            <span class="btn-loading" style="display: none;">
                                <i class="bi bi-arrow-clockwise spin"></i>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>

                    <!-- Social login buttons -->
                    <div class="social-login">
                        <a href="<?php echo Uri::create('auth/google'); ?>" class="btn btn-social btn-google">
                            <i class="bi bi-google"></i>
                            Đăng nhập với Google
                        </a>
                    </div>

                    <!-- Register link -->
                    <div class="register-link">
                        <p>Chưa có tài khoản? <a href="<?php echo Uri::create('auth/register'); ?>">Đăng ký ngay</a></p>
                    </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS styles cho trang login -->
<style>
    /* Reset page styles for login */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-container {
        margin: 0;
        padding: 0;
        max-width: none;
    }

    .page-header {
        display: none;
    }

    /* Login container */
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1000px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 600px;
    }

    /* Welcome section */
    .login-welcome {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .login-welcome::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        animation: float 20s infinite linear;
    }

    @keyframes float {
        0% { transform: translateX(0) translateY(0); }
        100% { transform: translateX(-100px) translateY(-100px); }
    }

    .welcome-content {
        position: relative;
        z-index: 2;
    }

    .logo-section {
        text-align: center;
        margin-bottom: 40px;
    }

    .logo-icon {
        font-size: 3rem;
        margin-bottom: 20px;
        display: block;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .feature-list {
        margin: 40px 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 1.1rem;
    }

    .feature-item i {
        color: #2ecc71;
        margin-right: 15px;
        font-size: 1.2rem;
    }

    .welcome-footer {
        margin-top: 40px;
        text-align: center;
    }

    /* Login form section */
    .login-form-section {
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-form-container {
        max-width: 400px;
        margin: 0 auto;
        width: 100%;
    }

    .login-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .login-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .login-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* Demo accounts info */
    .demo-accounts {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 25px;
        border-left: 4px solid #3498db;
    }

    .demo-title {
        font-weight: 600;
        color: #3498db;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .demo-account {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    /* Form styling */
    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .form-control-custom {
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        padding: 15px 45px 15px 45px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control-custom:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        background: white;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        font-size: 1.1rem;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #6c757d;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 5px;
        border-radius: 5px;
        transition: color 0.3s ease;
    }

    .password-toggle:hover {
        color: #3498db;
    }

    /* Form options */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        accent-color: #3498db;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
        cursor: pointer;
    }

    .forgot-password {
        color: #3498db;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #2980b9;
        text-decoration: underline;
    }

    /* Login button */
    .btn-login {
        width: 100%;
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        border: none;
        border-radius: 12px;
        padding: 15px;
        font-size: 1.1rem;
        font-weight: 600;
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(52, 152, 219, 0.3);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .btn-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Divider */
    .login-divider {
        text-align: center;
        margin: 30px 0;
        position: relative;
    }

    .login-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #e1e8ed;
    }

    .login-divider span {
        background: white;
        padding: 0 20px;
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Social login */
    .social-login {
        display: grid;
        gap: 12px;
        margin-bottom: 25px;
    }

    .btn-social {
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        padding: 12px;
        background: white;
        color: #6c757d;
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .btn-google:hover {
        border-color: #db4437;
        color: #db4437;
        background: #fef7f7;
    }

    .btn-facebook:hover {
        border-color: #4267b2;
        color: #4267b2;
        background: #f0f4ff;
    }

    /* Register link */
    .register-link {
        text-align: center;
    }

    .register-link p {
        color: #6c757d;
        margin-bottom: 0;
    }

    .register-link a {
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
    }

    .register-link a:hover {
        color: #2980b9;
        text-decoration: underline;
    }

    /* Alert custom */
    .alert-custom {
        border-radius: 12px;
        border: none;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 25px;
    }

    .alert-custom i {
        margin-top: 2px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .login-wrapper {
            grid-template-columns: 1fr;
            max-width: 500px;
        }

        .login-welcome {
            padding: 40px 20px;
            text-align: center;
        }

        .login-form-section {
            padding: 40px 20px;
        }

        .welcome-title {
            font-size: 1.5rem;
        }

        .login-title {
            font-size: 1.5rem;
        }

        .form-options {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .social-login {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .login-container {
            padding: 10px;
        }

        .login-welcome {
            padding: 30px 15px;
        }

        .login-form-section {
            padding: 30px 15px;
        }
    }
</style>

<!-- JavaScript for login functionality -->
<script>
    // Toggle password visibility
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('passwordToggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.className = 'bi bi-eye-slash';
        } else {
            passwordField.type = 'password';
            toggleIcon.className = 'bi bi-eye';
        }
    }

    // Form submission handling
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('loginBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        // Show loading state
        btnText.style.display = 'none';
        btnLoading.style.display = 'block';
        submitBtn.disabled = true;
        
        // Simulate loading time (remove in production)
        setTimeout(function() {
            // Form will submit naturally after this delay
        }, 500);
    });

    // Auto-focus on email field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('email').focus();
    });

    // Enhanced form validation
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');

    emailField.addEventListener('blur', function() {
        validateEmail(this);
    });

    passwordField.addEventListener('blur', function() {
        validatePassword(this);
    });

    function validateEmail(field) {
        const email = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            field.style.borderColor = '#e74c3c';
            showFieldError(field, 'Email không hợp lệ');
        } else {
            field.style.borderColor = '#e1e8ed';
            hideFieldError(field);
        }
    }

    function validatePassword(field) {
        const password = field.value;
        
        if (password && password.length < 6) {
            field.style.borderColor = '#e74c3c';
            showFieldError(field, 'Mật khẩu phải có ít nhất 6 ký tự');
        } else {
            field.style.borderColor = '#e1e8ed';
            hideFieldError(field);
        }
    }

    function showFieldError(field, message) {
        hideFieldError(field); // Remove existing error
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.style.cssText = 'color: #e74c3c; font-size: 0.875rem; margin-top: 5px;';
        errorDiv.textContent = message;
        
        field.parentNode.parentNode.appendChild(errorDiv);
    }

    function hideFieldError(field) {
        const errorDiv = field.parentNode.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Add smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        const loginWrapper = document.querySelector('.login-wrapper');
        loginWrapper.style.opacity = '0';
        loginWrapper.style.transform = 'translateY(20px)';
        
        setTimeout(function() {
            loginWrapper.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            loginWrapper.style.opacity = '1';
            loginWrapper.style.transform = 'translateY(0)';
        }, 100);
    });
</script>
