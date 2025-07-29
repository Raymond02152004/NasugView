<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <title>NasugView</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Montserrat', sans-serif;
    }
    body {
      background: linear-gradient(to right, #e0f2e9, #c5e1c5);
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .container {
      background-color: #ffffff;
      border-radius: 30px;
      box-shadow: 0 8px 25px rgba(0, 80, 0, 0.2);
      position: relative;
      overflow: hidden;
      width: 768px;
      max-width: 100%;
      min-height: 480px;
    }
    .corner-logo {
      position: absolute;
      top: 20px;
      width: 100px;
      height: auto;
      transition: all 0.6s ease-in-out;
      z-index: 1001;
    }
    .logo-left { left: 30px; opacity: 1; }
    .logo-right { right: 30px; opacity: 0; }
    .container.active .logo-left { opacity: 0; }
    .container.active .logo-right { opacity: 1; }
    .container button {
      background-color: #0b3d0b;
      color: #fff;
      font-size: 13px;
      padding: 10px 45px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      text-transform: uppercase;
      margin-top: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
    .container button:hover { background-color: #145a14; }
    .toggle-panel button.hidden {
      background-color: transparent;
      border: 2px solid #ffffff;
      color: #ffffff;
      font-weight: 600;
      padding: 10px 45px;
      text-transform: uppercase;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .toggle-panel button.hidden:hover {
      background-color: #ffffff;
      color: #1b5e20;
    }
    .form-container {
      position: absolute;
      top: 0;
      height: 100%;
      transition: all 0.6s ease-in-out;
    }
    .sign-in { left: 0; width: 50%; z-index: 2; }
    .container.active .sign-in { transform: translateX(100%); }
    .sign-up {
      left: 0;
      width: 50%;
      opacity: 0;
      z-index: 1;
    }
    .container.active .sign-up {
      transform: translateX(100%);
      opacity: 1;
      z-index: 5;
      animation: move 0.6s;
    }
    @keyframes move {
      0%, 49.99% { opacity: 0; z-index: 1; }
      50%, 100% { opacity: 1; z-index: 5; }
    }
    .toggle-container {
      position: absolute;
      top: 0;
      left: 50%;
      width: 50%;
      height: 100%;
      overflow: hidden;
      transition: all 0.6s ease-in-out;
      border-radius: 150px 0 0 100px;
      z-index: 1000;
    }
    .container.active .toggle-container {
      transform: translateX(-100%);
      border-radius: 0 150px 100px 0;
    }
    .toggle {
      background: linear-gradient(to right, #2e7d32, #1b5e20);
      height: 100%;
      color: #fff;
      position: relative;
      left: -100%;
      width: 200%;
      transform: translateX(0);
      transition: all 0.6s ease-in-out;
    }
    .container.active .toggle { transform: translateX(50%); }
    .toggle-panel {
      position: absolute;
      width: 50%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding: 0 30px;
      transition: all 0.6s ease-in-out;
    }
    .toggle-left { transform: translateX(-200%); }
    .container.active .toggle-left { transform: translateX(0); }
    .toggle-right { right: 0; transform: translateX(0); }
    .container.active .toggle-right { transform: translateX(200%); }
    form {
      background-color: #f9fff9;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100%;
      padding: 0 40px;
    }
    input {
      background-color: #e8f5e9;
      border: none;
      margin: 8px 0;
      padding: 10px 15px;
      font-size: 13px;
      border-radius: 8px;
      width: 100%;
      outline: none;
      color: #1b3c1b;
    }
    .social-icons {
      margin: 15px 0;
      display: flex;
      gap: 10px;
    }
    .social-icons a {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 40px;
      height: 40px;
      background: #ffffff;
      border: 1px solid #cccccc;
      border-radius: 50%;
      color: #1b3c1b;
      font-size: 16px;
      transition: background 0.3s ease, transform 0.3s ease;
    }
    .social-icons a:hover {
      background: #e0f2e9;
      transform: scale(1.1);
    }
    a {
      color: #1b3c1b;
      font-size: 12px;
      text-decoration: none;
      margin-top: 10px;
    }
    h1 {
      color: #1b3c1b;
      margin-bottom: 10px;
    }
    .toggle-panel h1 {
      color: #ffffff;
    }
  </style>
</head>
<body>

<div class="container" id="container">
  <!-- Logos -->
  <img src="{{ asset('img/nasugview-logo.png') }}" alt="Logo Left" class="corner-logo logo-left" />
  <img src="{{ asset('img/nasugview-logo.png') }}" alt="Logo Right" class="corner-logo logo-right" />

  <!-- Sign Up Form -->
  <div class="form-container sign-up">
    <form action="/register-submit" method="POST">
      @csrf
      <h1>Create Account</h1>
      <input type="text" name="username" placeholder="Username" required />
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <input type="hidden" name="role" value="consumer" />
      <div class="social-icons">
        <a href="/auth/google"><i class="fab fa-google"></i></a>
        <a href="/auth/facebook"><i class="fab fa-facebook-f"></i></a>
      </div>
      <button type="submit">Sign Up</button>
    </form>
  </div>

  <!-- Sign In Form -->
  <div class="form-container sign-in">
    <form action="/login-submit" method="POST">
      @csrf
      <h1>Sign In</h1>
      <input type="email" name="email" placeholder="Email" required />
      <input type="password" name="password" placeholder="Password" required />
      <a href="/forgot-password">Forgot your password?</a>
      <div class="social-icons">
        <a href="/auth/google"><i class="fab fa-google"></i></a>
        <a href="/auth/facebook"><i class="fab fa-facebook-f"></i></a>
      </div>
      <button type="submit">Sign In</button>
    </form>
  </div>

  <!-- Toggle UI -->
  <div class="toggle-container">
    <div class="toggle">
      <div class="toggle-panel toggle-left">
        <h1>Welcome Back!</h1>
        <p>Enter your personal details to use all of site features</p>
        <button class="hidden" id="login">Sign In</button>
      </div>
      <div class="toggle-panel toggle-right">
        <h1>Hello, Friend!</h1>
        <p>Register with your personal details to use all of site features</p>
        <button class="hidden" id="register">Sign Up</button>
      </div>
    </div>
  </div>
</div>

<!-- ✅ Success Modal -->
@if(session('registered'))
  <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="successModalLabel">Success</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          {{ session('registered') }}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
        </div>
      </div>
    </div>
  </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const container = document.getElementById('container');
  const registerBtn = document.getElementById('register');
  const loginBtn = document.getElementById('login');

  registerBtn.addEventListener('click', () => {
    container.classList.add("active");
  });

  loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
  });

  @if(session('registered'))
    new bootstrap.Modal(document.getElementById('successModal')).show();
  @endif
</script>

</body>
</html>
