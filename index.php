<?php
/**
 * PintSocial – Main Page (Login + Sign Up)
 */
require_once __DIR__ . '/config.php';

// Already logged in → go to dashboard
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$csrf     = csrf_token();
$activeTab = $_GET['tab'] ?? 'login';          // 'login' | 'signup'
$error    = htmlspecialchars($_GET['error']   ?? '', ENT_QUOTES, 'UTF-8');
$success  = htmlspecialchars($_GET['success'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PintSocial – Log in or Sign up</title>
  <link rel="stylesheet" href="login.css" />
</head>
<body>
  <div class="split-layout">

    <!-- ── Left: Hero ── -->
    <div class="hero">
      <svg class="hero-bird" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 10 L12 110 Q12 116 20 116 L80 116 Q88 116 88 110 L82 10 Z"
              fill="rgba(255,255,255,0.18)" stroke="white" stroke-width="4" stroke-linejoin="round"/>
        <ellipse cx="50" cy="10" rx="32" ry="10" fill="white" opacity="0.9"/>
        <ellipse cx="35" cy="7"  rx="10" ry="8"  fill="white"/>
        <ellipse cx="62" cy="6"  rx="9"  ry="7"  fill="white"/>
        <path d="M22 32 L18.5 108 Q18.5 112 24 112 L76 112 Q81.5 112 81.5 108 L78 32 Z"
              fill="rgba(255,200,60,0.45)"/>
        <circle cx="40" cy="75" r="3"   fill="rgba(255,255,255,0.5)"/>
        <circle cx="58" cy="60" r="2"   fill="rgba(255,255,255,0.5)"/>
        <circle cx="47" cy="90" r="2.5" fill="rgba(255,255,255,0.5)"/>
        <path d="M82 35 Q100 35 100 55 Q100 75 82 75"
              stroke="white" stroke-width="4" fill="none" stroke-linecap="round"/>
      </svg>
    </div>

    <!-- ── Right: Content ── -->
    <div class="form-panel">
      <div class="form-container">

        <!-- Mobile pint icon -->
        <svg class="mobile-bird" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M18 10 L12 110 Q12 116 20 116 L80 116 Q88 116 88 110 L82 10 Z"
                fill="#fde8dc" stroke="#f2561d" stroke-width="4" stroke-linejoin="round"/>
          <ellipse cx="50" cy="10" rx="32" ry="10" fill="#f2561d" opacity="0.9"/>
          <path d="M22 32 L18.5 108 Q18.5 112 24 112 L76 112 Q81.5 112 81.5 108 L78 32 Z"
                fill="rgba(242,86,29,0.2)"/>
          <path d="M82 35 Q100 35 100 55 Q100 75 82 75"
                stroke="#f2561d" stroke-width="4" fill="none" stroke-linecap="round"/>
        </svg>

        <h1 class="heading">Happening now</h1>
        <h2 class="subheading">Join PintSocial today.</h2>

        <!-- Sign-up CTA buttons -->
        <div class="auth-buttons">
          <button class="btn btn-google" onclick="openModal('signup')">
            <svg class="google-icon" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
            Sign up with Google
          </button>
          <button class="btn btn-apple" onclick="openModal('signup')">
            <svg class="apple-icon" viewBox="0 0 24 24"><path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.7 9.05 7.4c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.39-1.32 2.76-2.54 4zm-2.99-17.28c.06 1.96-1.44 3.53-3.23 3.44-.26-1.76 1.44-3.47 3.23-3.44z" fill="#000"/></svg>
            Sign up with Apple
          </button>

          <div class="divider"><span>or</span></div>

          <button class="btn btn-primary" id="btnSignup" onclick="openModal('signup')">
            Sign up with phone or email
          </button>

          <p class="terms">
            By signing up, you agree to the
            <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>,
            including <a href="#">Cookie Use</a>.
          </p>
        </div>

        <!-- Log in link -->
        <div class="login-section">
          <p class="already">Already have an account?</p>
          <button class="btn btn-outline" id="btnLogin" onclick="openModal('login')">
            Log in
          </button>
        </div>

        <!-- ══════════════════════════════
             MODAL
        ══════════════════════════════ -->
        <div class="modal-overlay" id="modalOverlay">
          <div class="modal" id="loginModal" role="dialog" aria-modal="true">

            <button class="modal-close" id="modalClose" aria-label="Close">&times;</button>

            <!-- Pint icon -->
            <svg class="modal-bird" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M18 10 L12 110 Q12 116 20 116 L80 116 Q88 116 88 110 L82 10 Z"
                    fill="#fde8dc" stroke="#f2561d" stroke-width="4" stroke-linejoin="round"/>
              <ellipse cx="50" cy="10" rx="32" ry="10" fill="#f2561d" opacity="0.9"/>
              <path d="M22 32 L18.5 108 Q18.5 112 24 112 L76 112 Q81.5 112 81.5 108 L78 32 Z"
                    fill="rgba(242,86,29,0.2)"/>
              <path d="M82 35 Q100 35 100 55 Q100 75 82 75"
                    stroke="#f2561d" stroke-width="4" fill="none" stroke-linecap="round"/>
            </svg>

            <!-- Flash messages -->
            <?php if ($error): ?>
              <div class="flash flash-error" id="flashMsg"><?= $error ?></div>
            <?php elseif ($success): ?>
              <div class="flash flash-success" id="flashMsg"><?= $success ?></div>
            <?php endif; ?>

            <!-- ── Tab bar ── -->
            <div class="tab-bar">
              <button class="tab-btn" id="tabLogin"  onclick="switchTab('login')">Log in</button>
              <button class="tab-btn" id="tabSignup" onclick="switchTab('signup')">Sign up</button>
            </div>

            <!-- ════ LOGIN FORM ════ -->
            <div id="panelLogin" class="tab-panel">
              <h2 class="modal-title">Log in to PintSocial</h2>
              <form method="POST" action="login.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

                <div class="field-group">
                  <input type="text" id="username" name="username"
                         class="field" placeholder=" " autocomplete="username" required />
                  <label class="field-label" for="username">Phone, email, or username</label>
                </div>

                <div class="field-group">
                  <input type="password" id="password" name="password"
                         class="field" placeholder=" " autocomplete="current-password" required />
                  <label class="field-label" for="password">Password</label>
                  <button type="button" class="toggle-pw" id="togglePwLogin">Show</button>
                </div>

                <button type="submit" class="btn btn-primary full-width">Log in</button>
              </form>
              <a href="#" class="forgot-link">Forgot password?</a>
              <p class="modal-signup">Don't have an account?
                <a href="#" onclick="switchTab('signup'); return false;">Sign up</a>
              </p>
            </div>

            <!-- ════ SIGNUP FORM ════ -->
            <div id="panelSignup" class="tab-panel" style="display:none">
              <h2 class="modal-title">Create your account</h2>
              <form method="POST" action="register.php" novalidate>
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">

                <div class="field-group">
                  <input type="text" id="reg_username" name="reg_username"
                         class="field" placeholder=" " autocomplete="username" required />
                  <label class="field-label" for="reg_username">Username</label>
                </div>

                <div class="field-group">
                  <input type="email" id="reg_email" name="reg_email"
                         class="field" placeholder=" " autocomplete="email" required />
                  <label class="field-label" for="reg_email">Email</label>
                </div>

                <div class="field-group">
                  <input type="password" id="reg_password" name="reg_password"
                         class="field" placeholder=" " autocomplete="new-password" required />
                  <label class="field-label" for="reg_password">Password (min 8 chars)</label>
                  <button type="button" class="toggle-pw" id="togglePwReg">Show</button>
                </div>

                <div class="field-group">
                  <input type="password" id="reg_confirm" name="reg_confirm"
                         class="field" placeholder=" " autocomplete="new-password" required />
                  <label class="field-label" for="reg_confirm">Confirm password</label>
                </div>

                <p class="terms" style="margin-bottom:12px">
                  By signing up, you agree to the
                  <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>.
                </p>

                <button type="submit" class="btn btn-primary full-width">Sign up</button>
              </form>
              <p class="modal-signup">Already have an account?
                <a href="#" onclick="switchTab('login'); return false;">Log in</a>
              </p>
            </div>

          </div><!-- /.modal -->
        </div><!-- /.modal-overlay -->

      </div><!-- /.form-container -->

      <footer class="footer">
        <a href="#">About</a>
        <a href="#">Help Center</a>
        <a href="#">Terms of Service</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Cookie Policy</a>
        <a href="#">Ads info</a>
        <a href="#">Blog</a>
        <a href="#">Status</a>
        <a href="#">Careers</a>
        <a href="#">Brand Resources</a>
        <a href="#">Advertising</a>
        <a href="#">Marketing</a>
        <span>&copy; 2026 PintSocial, Inc.</span>
      </footer>
    </div><!-- /.form-panel -->
  </div><!-- /.split-layout -->

  <script>
    // Pass PHP state to JS
    const INITIAL_TAB   = <?= json_encode($activeTab) ?>;
    const HAS_FLASH     = <?= json_encode((bool)($error || $success)) ?>;
  </script>
  <script src="login.js"></script>
</body>
</html>
