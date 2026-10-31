<div>
     <div>
        

<title>تسجيل الدخول - العقارية العالمية</title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Tajawal', sans-serif;
  }

  :root {
    --primary: #1a3a5c;
    --primary-light: #2c5282;
    --accent: #c9a961;
    --accent-light: #e0c584;
    --bg-light: #f8f9fb;
    --text-dark: #1a202c;
    --text-muted: #718096;
    --border: #e2e8f0;
    --white: #ffffff;
    --shadow-lg: 0 25px 50px -12px rgba(26, 58, 92, 0.15);
    --shadow-md: 0 10px 30px -5px rgba(26, 58, 92, 0.1);
  }

  body {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    position: relative;
    overflow-x: hidden;
  }

  /* عناصر خلفية زخرفية */
  body::before {
    content: '';
    position: fixed;
    top: -50%;
    right: -20%;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(201, 169, 97, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    z-index: 0;
  }

  body::after {
    content: '';
    position: fixed;
    bottom: -40%;
    left: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(26, 58, 92, 0.08) 0%, transparent 70%);
    border-radius: 50%;
    z-index: 0;
  }

  .login-container {
    width: 100%;
    max-width: 1100px;
    background: var(--white);
    border-radius: 28px;
    overflow: hidden;
    display: grid;
    grid-template-columns: 1.1fr 1fr;
    box-shadow: var(--shadow-lg);
    position: relative;
    z-index: 1;
    animation: fadeIn 0.8s ease-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* ===== القسم الأيسر - صورة المؤسسة ===== */
  .brand-side {
    position: relative;
    background: linear-gradient(160deg, #1a3a5c 0%, #2c5282 60%, #1a3a5c 100%);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 50px 45px;
    overflow: hidden;
    color: var(--white);
  }

  /* نمط معماري خلفي */
  .brand-side::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: 
      linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    z-index: 0;
  }

  .brand-side::after {
    content: '';
    position: absolute;
    bottom: -100px;
    left: -100px;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(201, 169, 97, 0.25) 0%, transparent 65%);
    border-radius: 50%;
  }

  .brand-top {
    position: relative;
    z-index: 2;
  }

  .logo-box {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 45px;
  }

  .logo-icon {
    width: 58px;
    height: 58px;
    background: linear-gradient(135deg, var(--accent), var(--accent-light));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: var(--primary);
    box-shadow: 0 8px 20px rgba(201, 169, 97, 0.4);
  }

  .logo-text h1 {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.5px;
  }

  .logo-text span {
    font-size: 12px;
    font-weight: 400;
    color: var(--accent-light);
    letter-spacing: 2px;
  }

  .brand-title {
    font-size: 40px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 18px;
    letter-spacing: -1px;
  }

  .brand-title span {
    color: var(--accent);
    display: block;
  }

  .brand-subtitle {
    font-size: 15px;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.75);
    max-width: 380px;
    font-weight: 300;
  }

  /* بطاقة المعاينة / الصورة */
  .property-preview {
    position: relative;
    z-index: 2;
    margin-top: 30px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    transition: transform 0.4s ease;
  }

  .property-preview:hover {
    transform: translateY(-5px);
  }

  .property-preview img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
  }

  .property-preview .overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(26,58,92,0.85) 0%, transparent 60%);
    display: flex;
    align-items: flex-end;
    padding: 20px;
  }

  .property-preview .overlay p {
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .property-preview .overlay i {
    color: var(--accent);
  }

  .brand-footer {
    position: relative;
    z-index: 2;
    display: flex;
    gap: 30px;
    margin-top: 35px;
    padding-top: 25px;
    border-top: 1px solid rgba(255,255,255,0.1);
  }

  .stat-item h3 {
    font-size: 24px;
    font-weight: 800;
    color: var(--accent);
  }

  .stat-item p {
    font-size: 12px;
    color: rgba(255,255,255,0.6);
    margin-top: 2px;
  }

  /* ===== القسم الأيمن - النموذج ===== */
  .form-side {
    padding: 55px 50px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background: var(--white);
    position: relative;
  }

  .form-header {
    margin-bottom: 38px;
  }

  .form-header .welcome {
    display: inline-block;
    background: linear-gradient(135deg, rgba(201, 169, 97, 0.15), rgba(201, 169, 97, 0.08));
    color: #a07d2e;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 16px;
    border: 1px solid rgba(201, 169, 97, 0.2);
  }

  .form-header h2 {
    font-size: 30px;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 10px;
    letter-spacing: -0.5px;
  }

  .form-header p {
    font-size: 14px;
    color: var(--text-muted);
    line-height: 1.6;
  }

  .form-group {
    margin-bottom: 22px;
    position: relative;
  }

  .form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
  }

  .input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .input-wrapper i {
    position: absolute;
    right: 18px;
    color: var(--text-muted);
    font-size: 16px;
    transition: color 0.3s;
    pointer-events: none;
  }

  .input-wrapper input {
    width: 100%;
    padding: 15px 48px 15px 18px;
    border: 2px solid var(--border);
    border-radius: 14px;
    font-size: 14px;
    font-family: 'Tajawal', sans-serif;
    color: var(--text-dark);
    background: var(--bg-light);
    transition: all 0.3s ease;
    outline: none;
  }

  .input-wrapper input:focus {
    border-color: var(--primary-light);
    background: var(--white);
    box-shadow: 0 0 0 4px rgba(44, 82, 130, 0.08);
  }

  .input-wrapper input:focus + i,
  .input-wrapper input:not(:placeholder-shown) + i {
    color: var(--primary-light);
  }

  .input-wrapper .toggle-pass {
    position: absolute;
    left: 16px;
    right: auto;
    cursor: pointer;
    color: var(--text-muted);
    font-size: 16px;
    pointer-events: all;
    transition: color 0.3s;
  }

  .input-wrapper .toggle-pass:hover {
    color: var(--primary);
  }

  .form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 28px;
    font-size: 13px;
  }

  .remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: var(--text-muted);
  }

  .remember-me input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: var(--primary);
    cursor: pointer;
  }

  .forgot-link {
    color: var(--primary-light);
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
  }

  .forgot-link:hover {
    color: var(--accent);
  }

  .btn-login {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: var(--white);
    border: none;
    border-radius: 14px;
    font-size: 15px;
    font-weight: 700;
    font-family: 'Tajawal', sans-serif;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px -5px rgba(26, 58, 92, 0.4);
    position: relative;
    overflow: hidden;
  }

  .btn-login::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-light) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
  }

  .btn-login:hover::before {
    opacity: 1;
  }

  .btn-login span {
    position: relative;
    z-index: 1;
  }

  .btn-login i {
    position: relative;
    z-index: 1;
    transition: transform 0.3s;
  }

  .btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px -5px rgba(201, 169, 97, 0.5);
  }

  .btn-login:hover i {
    transform: translateX(-4px);
  }

  .btn-login:hover span {
    color: var(--primary);
  }

  .divider {
    display: flex;
    align-items: center;
    gap: 15px;
    margin: 28px 0;
    color: var(--text-muted);
    font-size: 12px;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
  }

  .social-login {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
  }

  .social-btn {
    padding: 13px;
    border: 2px solid var(--border);
    border-radius: 12px;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 600;
    font-family: 'Tajawal', sans-serif;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.3s;
  }

  .social-btn:hover {
    border-color: var(--primary-light);
    background: var(--bg-light);
    transform: translateY(-2px);
  }

  .social-btn i {
    font-size: 16px;
  }

  .social-btn .fa-google { color: #db4437; }
  .social-btn .fa-apple { color: #000; }

  .signup-text {
    text-align: center;
    margin-top: 28px;
    font-size: 13px;
    color: var(--text-muted);
  }

  .signup-text a {
    color: var(--primary-light);
    font-weight: 700;
    text-decoration: none;
    transition: color 0.3s;
  }

  .signup-text a:hover {
    color: var(--accent);
  }

  /* ===== Responsive ===== */
  @media (max-width: 968px) {
    .login-container {
      grid-template-columns: 1fr;
      max-width: 500px;
    }

    .brand-side {
      padding: 40px 35px;
      min-height: auto;
    }

    .brand-title {
      font-size: 30px;
    }

    .property-preview {
      display: none;
    }

    .brand-footer {
      margin-top: 25px;
    }

    .form-side {
      padding: 40px 35px;
    }
  }

  @media (max-width: 480px) {
    body { padding: 12px; }
    
    .login-container { border-radius: 20px; }
    
    .brand-side { padding: 30px 24px; }
    .form-side { padding: 30px 24px; }

    .brand-title { font-size: 26px; }
    .form-header h2 { font-size: 24px; }
    
    .brand-footer { gap: 20px; }
    .stat-item h3 { font-size: 20px; }

    .logo-icon { width: 48px; height: 48px; font-size: 22px; }
    .logo-text h1 { font-size: 18px; }
  }

  /* تحسين الحقول عند التعبئة */
  .input-wrapper input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 1000px var(--bg-light) inset;
    -webkit-text-fill-color: var(--text-dark);
  }
</style>


<div class="login-container">
  
  <!-- الجانب الأيسر - العلامة التجارية -->
  <div class="brand-side">
    <div class="brand-top">
      <div class="logo-box">
        <div class="logo-icon">
          <i class="fas fa-building"></i>
        </div>
        <div class="logo-text">
          <h1>{{ $organization['name'] ?? 'غير موجود' }}</h1>
          <span>GLOBAL REALTY</span>
        </div>
      </div>

      <h2 class="brand-title">
        استثمر في مستقبلك
        <span>{{ $organization['display_name'] }}</span>
      </h2>

      <p class="brand-subtitle">
        منصة متكاملة لإدارة العقارات والاستثمار العقاري بأحدث التقنيات العالمية，
        حيث تلتقي الفخامة بالاحترافية.
      </p>
    </div>


    <!-- معاينة عقار -->
    <div class="property-preview">
@if(!empty($organization['logo']))
            <img
                src="{{ $organization['logo'] }}"
                alt="{{ $organization['display_name'] }}"
                style="width:58px;height:58px;object-fit:contain;border-radius:12px;"
            >
        @else
            <i class="fas fa-building"></i>
        @endif      <div class="overlay">
        <p><i class="fas fa-map-marker-alt"></i> مشاريع حصرية في أرقى المواقع</p>
      </div>
    </div>

    <div class="brand-footer">
      <div class="stat-item">
        <h3>+2500</h3>
        <p>عقار مسجل</p>
      </div>
      <div class="stat-item">
        <h3>+120</h3>
        <p>مدينة عالمية</p>
      </div>
      <div class="stat-item">
        <h3>98%</h3>
        <p>رضا العملاء</p>
      </div>
    </div>
  </div>

  <!-- الجانب الأيمن - النموذج -->
  <div class="form-side">
    <div class="form-header">
      <span class="welcome"><i class="fas fa-star" style="margin-left:6px;"></i> مرحبًا بعودتك</span>
      <h2>تسجيل الدخول</h2>
      <p>أدخل بياناتك للوصول إلى لوحة التحكم الخاصة بك</p>
    </div>

  <form wire:submit="login">

    {{-- البريد الإلكتروني --}}
    <div class="form-group">
        <label for="email">البريد الإلكتروني</label>

        <div class="input-wrapper">
            <input
                type="email"
                id="email"
                wire:model="email"
                placeholder="name@company.com"
                autocomplete="username"
            >
            <i class="fas fa-envelope"></i>
        </div>

        @error('email')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- كلمة المرور --}}
    <div class="form-group">
        <label for="password">كلمة المرور</label>

        <div class="input-wrapper">
            <input
                type="password"
                id="password"
                wire:model="password"
                placeholder="أدخل كلمة المرور"
                autocomplete="current-password"
            >
            <i class="fas fa-lock"></i>
        </div>

        @error('password')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    {{-- تذكرني --}}
    <div class="form-options">
        <label class="remember-me">
            <input type="checkbox" wire:model="remember">
            تذكرني
        </label>
    </div>

    {{-- زر الدخول --}}
    <button
        type="submit"
        class="btn-login"
        wire:loading.attr="disabled"
        wire:target="login"
    >
        <span wire:loading.remove wire:target="login">
            تسجيل الدخول
        </span>

        <span wire:loading wire:target="login">
            جارٍ التحقق...
        </span>
    </button>

</form>

    <div class="divider">أو تابع باستخدام</div>

    <div class="social-login">
      <button class="social-btn" type="button">
        <i class="fab fa-google"></i> Google
      </button>
      <button class="social-btn" type="button">
        <i class="fab fa-apple"></i> Apple
      </button>
    </div>

    <p class="signup-text">
      ليس لديك حساب؟ <a href="#">أنشئ حسابًا جديدًا</a>
    </p>
  </div>

</div>

<script>
  // إظهار/إخفاء كلمة المرور
  const togglePass = document.getElementById('togglePass');
  const passwordInput = document.getElementById('password');

  togglePass.addEventListener('click', () => {
    const type = passwordInput.type === 'password' ? 'text' : 'password';
    passwordInput.type = type;
    togglePass.classList.toggle('fa-eye');
    togglePass.classList.toggle('fa-eye-slash');
  });

  // معالجة إرسال النموذج
  document.getElementById('loginForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('.btn-login');
    const originalHTML = btn.innerHTML;
    
    btn.innerHTML = '<span>جارٍ التحقق...</span> <i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    setTimeout(() => {
      btn.innerHTML = '<span>تم بنجاح!</span> <i class="fas fa-check"></i>';
      setTimeout(() => {
        btn.innerHTML = originalHTML;
        btn.disabled = false;
      }, 1500);
    }, 1500);
  });
</script>


     </div>


</div>
