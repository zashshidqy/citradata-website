<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Sudah login → redirect
if (!empty($_SESSION['user_id'])) {
    redirect(url('pages/dashboard.php'));
}

$isTrial    = isset($_GET['trial']) && $_GET['trial'] == '1';
$redirectTo = $_GET['redirect'] ?? url('pages/dashboard.php');

$flashError   = $_SESSION['login_error']   ?? null;
$flashSuccess = $_SESSION['login_success'] ?? null;
unset($_SESSION['login_error'], $_SESSION['login_success']);

$pageTitle = ($isTrial ? 'Free Trial – Find Projects' : 'Sign In') . ' – Citradata';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?php echo url('index.php'); ?>">
                <img src="<?php echo asset('assets/images/citradata-logo.png'); ?>" alt="Citradata" class="h-10 object-contain mx-auto mb-4">
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">
                <?php echo $isTrial ? 'Start Free Trial' : 'Welcome Back'; ?>
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                <?php echo $isTrial ? 'Access construction project data — no payment required.' : 'Sign in to your Citradata account.'; ?>
            </p>
        </div>

        <?php if ($flashError): ?>
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 text-red-800 px-5 py-4 flex items-center gap-3 text-sm font-medium">
            <i class="fas fa-circle-exclamation"></i> <?php echo e($flashError); ?>
        </div>
        <?php endif; ?>

        <?php if ($flashSuccess): ?>
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 text-green-800 px-5 py-4 flex items-center gap-3 text-sm font-medium">
            <i class="fas fa-circle-check"></i> <?php echo e($flashSuccess); ?>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div class="bg-white rounded-[1.5rem] p-8 shadow-sm border border-slate-100">
            <form method="POST" action="<?php echo url('includes/login_handler.php'); ?>" class="space-y-5">
                <input type="hidden" name="redirect" value="<?php echo e($redirectTo); ?>">

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">Email</label>
                    <input type="email" name="email" required autocomplete="email"
                           placeholder="you@example.com"
                           class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password-field" required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full h-12 px-4 pr-11 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                        <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye" id="pw-eye-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full h-12 bg-brandBlue text-white text-sm font-bold rounded-xl shadow-md hover:bg-blue-800 transition-all duration-300 flex items-center justify-center gap-2 group">
                    <?php echo $isTrial ? 'Start Free Trial' : 'Sign In'; ?>
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-6">
            Don't have an account?
            <a href="<?php echo url('pages/register.php'); ?>" class="text-brandBlue hover:underline font-semibold">Sign Up</a>
        </p>
        <p class="text-center text-slate-400 text-xs mt-2">
            Need help?
            <a href="<?php echo url('pages/contact.php'); ?>" class="text-brandBlue hover:underline font-medium">Contact us</a>
        </p>

    </div>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>

<script>
function togglePassword() {
    const f = document.getElementById('password-field');
    const i = document.getElementById('pw-eye-icon');
    if (f.type === 'password') {
        f.type = 'text';
        i.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        f.type = 'password';
        i.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
