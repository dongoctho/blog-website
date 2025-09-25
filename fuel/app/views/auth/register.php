<?php
use Fuel\Core\Form;
use Fuel\Core\Input;
use Fuel\Core\Uri;
?>

<div class="register-container">
    <div class="register-wrapper">
        <!-- Left side - Welcome section với animation -->
        <div class="register-welcome">
            <div class="welcome-content">
                <div class="logo-section">
                    <i class="bi bi-person-plus-fill logo-icon"></i>
                    <h2 class="welcome-title">Tham gia cùng chúng tôi!</h2>
                    <p class="welcome-subtitle">Tạo tài khoản để bắt đầu hành trình viết blog của bạn</p>
                </div>
                
                <div class="feature-list">
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Tạo blog cá nhân miễn phí</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Giao diện thân thiện, dễ sử dụng</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Chia sẻ bài viết với cộng đồng</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Quản lý nội dung dễ dàng</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>Bảo mật thông tin cao cấp</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right side - Register form -->
        <div class="register-form-section">
            <div class="register-form-container">
                <!-- Header -->
                <div class="register-header">
                    <h3 class="register-title">Đăng ký tài khoản</h3>
                    <p class="register-subtitle">Điền thông tin để tạo tài khoản mới</p>
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

                <!-- Register Form -->
                <?php echo Form::open(array('class' => 'register-form', 'id' => 'registerForm')); ?>
                    <?php echo Form::csrf(); ?>
                    
                    <!-- Full Name field -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="bi bi-person"></i>
                            Họ và tên
                        </label>
                        <div class="input-wrapper">
                            <?php echo Form::input('username', 
                                Input::post('username'), 
                                array(
                                    'class' => 'form-control form-control-custom',
                                    'id' => 'username',
                                    'placeholder' => 'Nhập họ và tên đầy đủ',
                                    'required' => true,
                                    'autocomplete' => 'name'
                                )
                            ); ?>
                            <i class="input-icon bi bi-person"></i>
                        </div>
                    </div>

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
                                    'placeholder' => 'Nhập địa chỉ email',
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
                                    'placeholder' => 'Tạo mật khẩu (tối thiểu 6 ký tự)',
                                    'required' => true,
                                    'autocomplete' => 'new-password'
                                )
                            ); ?>
                            <i class="input-icon bi bi-lock"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="passwordToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password field -->
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            <i class="bi bi-lock-fill"></i>
                            Xác nhận mật khẩu
                        </label>
                        <div class="input-wrapper">
                            <?php echo Form::password('confirm_password', '', 
                                array(
                                    'class' => 'form-control form-control-custom',
                                    'id' => 'confirm_password',
                                    'placeholder' => 'Nhập lại mật khẩu',
                                    'required' => true,
                                    'autocomplete' => 'new-password'
                                )
                            ); ?>
                            <i class="input-icon bi bi-lock-fill"></i>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="bi bi-eye" id="confirmPasswordToggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Terms and Privacy -->
                    <div class="form-group">
                        <div class="terms-checkbox">
                            <?php echo Form::checkbox('agree_terms', '1', false, 
                                array(
                                    'id' => 'agree_terms',
                                    'class' => 'form-check-input',
                                    'required' => true
                                )
                            ); ?>
                            <label for="agree_terms" class="form-check-label">
                                Tôi đồng ý với 
                                <a href="#" class="terms-link">Điều khoản dịch vụ</a> 
                                và 
                                <a href="#" class="privacy-link">Chính sách bảo mật</a>
                            </label>
                        </div>
                    </div>

                    <!-- Register button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-register" id="registerBtn">
                            <span class="btn-text">
                                <i class="bi bi-person-plus"></i>
                                Tạo tài khoản
                            </span>
                            <span class="btn-loading" style="display: none;">
                                <i class="bi bi-arrow-clockwise spin"></i>
                                Đang xử lý...
                            </span>
                        </button>
                    </div>

                    <!-- Social register buttons -->
                    <div class="login-divider">
                        <span>Hoặc đăng ký với</span>
                    </div>

                    <div class="social-register">
                        <button type="button" class="btn btn-social btn-google">
                            <i class="bi bi-google"></i>
                            Đăng ký với Google
                        </button>
                        <button type="button" class="btn btn-social btn-facebook">
                            <i class="bi bi-facebook"></i>
                            Đăng ký với Facebook
                        </button>
                    </div>

                    <!-- Login link -->
                    <div class="login-link">
                        <p>Đã có tài khoản? <a href="<?php echo Uri::create('auth/login'); ?>">Đăng nhập ngay</a></p>
                    </div>

                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS styles cho trang register -->
<style>
    /* Kế thừa styling từ login page và custom cho register */
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

    /* Register container */
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .register-wrapper {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 1100px;
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 700px;
    }

    /* Welcome section cho register */
    .register-welcome {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        color: white;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .register-welcome::before {
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

    .welcome-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .feature-list {
        margin: 40px 0;
    }

    .feature-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 1rem;
    }

    .feature-item i {
        color: #2ecc71;
        margin-right: 15px;
        font-size: 1.1rem;
    }

    /* Stats section */
    .stats-section {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 40px;
        text-align: center;
    }

    .stat-item {
        padding: 15px 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Register form section */
    .register-form-section {
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        overflow-y: auto;
    }

    .register-form-container {
        max-width: 400px;
        margin: 0 auto;
        width: 100%;
    }

    .register-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .register-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .register-subtitle {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 0;
    }

    /* Form styling - tương tự login nhưng có một số custom */
    .form-group {
        margin-bottom: 20px;
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

    /* Password strength indicator */
    .password-strength {
        margin-top: 8px;
    }

    .strength-bar {
        height: 4px;
        background: #e1e8ed;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 5px;
    }

    .strength-text {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Terms checkbox */
    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .form-check-input {
        width: 18px;
        height: 18px;
        accent-color: #3498db;
        margin-top: 2px;
    }

    .form-check-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
        cursor: pointer;
        line-height: 1.4;
    }

    .terms-link, .privacy-link {
        color: #3498db;
        text-decoration: none;
    }

    .terms-link:hover, .privacy-link:hover {
        color: #2980b9;
        text-decoration: underline;
    }

    /* Register button */
    .btn-register {
        width: 100%;
        background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
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

    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(46, 204, 113, 0.3);
    }

    .btn-register:active {
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
        margin: 25px 0;
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

    /* Social register */
    .social-register {
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

    /* Login link */
    .login-link {
        text-align: center;
    }

    .login-link p {
        color: #6c757d;
        margin-bottom: 0;
    }

    .login-link a {
        color: #3498db;
        text-decoration: none;
        font-weight: 600;
    }

    .login-link a:hover {
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
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .alert-custom i {
        margin-top: 2px;
    }

    /* Field validation states */
    .field-valid {
        border-color: #2ecc71 !important;
    }

    .field-invalid {
        border-color: #e74c3c !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .register-wrapper {
            grid-template-columns: 1fr;
            max-width: 500px;
            min-height: auto;
        }

        .register-welcome {
            padding: 40px 20px;
            text-align: center;
        }

        .register-form-section {
            padding: 40px 20px;
        }

        .welcome-title {
            font-size: 1.5rem;
        }

        .register-title {
            font-size: 1.5rem;
        }

        .stats-section {
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .stat-number {
            font-size: 1.2rem;
        }

        .stat-label {
            font-size: 0.8rem;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .social-register {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .register-container {
            padding: 10px;
        }

        .register-welcome {
            padding: 30px 15px;
        }

        .register-form-section {
            padding: 30px 15px;
        }

        .stats-section {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
</style>

<!-- JavaScript for register functionality -->
<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const toggleIcon = document.getElementById(fieldId + 'ToggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.className = 'bi bi-eye-slash';
        } else {
            passwordField.type = 'password';
            toggleIcon.className = 'bi bi-eye';
        }
    }

    // Password strength checker
    function checkPasswordStrength(password) {
        let strength = 0;
        let feedback = 'Rất yếu';
        
        // Kiểm tra độ dài
        if (password.length >= 8) strength += 1;
        
        // Kiểm tra chữ hoa
        if (/[A-Z]/.test(password)) strength += 1;
        
        // Kiểm tra chữ thường
        if (/[a-z]/.test(password)) strength += 1;
        
        // Kiểm tra số
        if (/[0-9]/.test(password)) strength += 1;
        
        // Kiểm tra ký tự đặc biệt
        if (/[^A-Za-z0-9]/.test(password)) strength += 1;

        const strengthBar = document.getElementById('passwordStrength');
        const strengthText = document.getElementById('strengthText');
        
        // Xóa tất cả class cũ
        strengthBar.className = 'password-strength';
        
        switch (strength) {
            case 0:
            case 1:
                strengthBar.classList.add('strength-weak');
                feedback = 'Rất yếu';
                break;
            case 2:
                strengthBar.classList.add('strength-fair');
                feedback = 'Yếu';
                break;
            case 3:
                strengthBar.classList.add('strength-good');
                feedback = 'Trung bình';
                break;
            case 4:
            case 5:
                strengthBar.classList.add('strength-strong');
                feedback = 'Mạnh';
                break;
        }
        
        strengthText.textContent = 'Độ mạnh: ' + feedback;
    }

    // Form submission handling
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('registerBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');
        
        // Validation check trước khi submit
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
        
        // Show loading state
        btnText.style.display = 'none';
        btnLoading.style.display = 'block';
        submitBtn.disabled = true;
    });

    // Real-time validation
    document.addEventListener('DOMContentLoaded', function() {
        const fullNameField = document.getElementById('full_name');
        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');
        const confirmPasswordField = document.getElementById('confirm_password');
        const agreeTermsField = document.getElementById('agree_terms');

        // Full name validation
        fullNameField.addEventListener('blur', function() {
            validateFullName(this);
        });

        // Email validation
        emailField.addEventListener('blur', function() {
            validateEmail(this);
        });

        // Password validation và strength check
        passwordField.addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });

        passwordField.addEventListener('blur', function() {
            validatePassword(this);
        });

        // Confirm password validation
        confirmPasswordField.addEventListener('blur', function() {
            validateConfirmPassword(this);
        });

        // Auto-focus vào full name field
        fullNameField.focus();
    });

    // Validation functions
    function validateFullName(field) {
        const name = field.value.trim();
        
        if (name.length < 2) {
            setFieldInvalid(field, 'Họ tên phải có ít nhất 2 ký tự');
            return false;
        } else if (name.length > 50) {
            setFieldInvalid(field, 'Họ tên không được quá 50 ký tự');
            return false;
        } else {
            setFieldValid(field);
            return true;
        }
    }

    function validateEmail(field) {
        const email = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (!email) {
            setFieldInvalid(field, 'Email không được để trống');
            return false;
        } else if (!emailRegex.test(email)) {
            setFieldInvalid(field, 'Email không hợp lệ');
            return false;
        } else {
            setFieldValid(field);
            return true;
        }
    }

    function validatePassword(field) {
        const password = field.value;
        
        if (password.length < 6) {
            setFieldInvalid(field, 'Mật khẩu phải có ít nhất 6 ký tự');
            return false;
        } else {
            setFieldValid(field);
            return true;
        }
    }

    function validateConfirmPassword(field) {
        const password = document.getElementById('password').value;
        const confirmPassword = field.value;
        
        if (confirmPassword !== password) {
            setFieldInvalid(field, 'Mật khẩu xác nhận không khớp');
            return false;
        } else {
            setFieldValid(field);
            return true;
        }
    }

    function validateForm() {
        const fullName = document.getElementById('full_name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const agreeTerms = document.getElementById('agree_terms');

        let isValid = true;

        // Validate từng field
        if (!validateFullName(fullName)) isValid = false;
        if (!validateEmail(email)) isValid = false;
        if (!validatePassword(password)) isValid = false;
        if (!validateConfirmPassword(confirmPassword)) isValid = false;

        // Kiểm tra agree terms
        if (!agreeTerms.checked) {
            showFieldError(agreeTerms, 'Bạn phải đồng ý với điều khoản dịch vụ');
            isValid = false;
        } else {
            hideFieldError(agreeTerms);
        }

        return isValid;
    }

    function setFieldValid(field) {
        field.classList.remove('field-invalid');
        field.classList.add('field-valid');
        hideFieldError(field);
    }

    function setFieldInvalid(field, message) {
        field.classList.remove('field-valid');
        field.classList.add('field-invalid');
        showFieldError(field, message);
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
        const registerWrapper = document.querySelector('.register-wrapper');
        registerWrapper.style.opacity = '0';
        registerWrapper.style.transform = 'translateY(20px)';
        
        setTimeout(function() {
            registerWrapper.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            registerWrapper.style.opacity = '1';
            registerWrapper.style.transform = 'translateY(0)';
        }, 100);
    });

    // Social registration handlers (placeholder)
    document.querySelector('.btn-google').addEventListener('click', function() {
        alert('Tính năng đăng ký với Google sẽ được triển khai sớm!');
    });

    document.querySelector('.btn-facebook').addEventListener('click', function() {
        alert('Tính năng đăng ký với Facebook sẽ được triển khai sớm!');
    });
</script>
