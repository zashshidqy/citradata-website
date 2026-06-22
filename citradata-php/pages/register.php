<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

// Sudah login → redirect ke dashboard
if (!empty($_SESSION['user_id'])) {
    redirect(url('pages/dashboard.php'));
}

$flashError   = $_SESSION['register_error']   ?? null;
$flashSuccess = $_SESSION['register_success'] ?? null;
$oldInput     = $_SESSION['register_old']     ?? [];
unset($_SESSION['register_error'], $_SESSION['register_success'], $_SESSION['register_old']);

$old = fn(string $k) => htmlspecialchars($oldInput[$k] ?? '', ENT_QUOTES);

$pageTitle = 'Create Account – Citradata';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';
?>
<body class="bg-slate-50 text-slate-800 antialiased flex flex-col min-h-screen">
<?php require __DIR__ . '/../includes/nav.php'; ?>

<main class="flex-grow flex items-center justify-center py-16 px-4">
    <div class="w-full max-w-md">

        <!-- Logo & heading -->
        <div class="text-center mb-8">
            <a href="<?php echo url('index.php'); ?>">
                <img src="<?php echo asset('assets/images/citradata-logo.png'); ?>" alt="Citradata" class="h-10 object-contain mx-auto mb-4">
            </a>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create an Account</h1>
            <p class="text-slate-500 text-sm mt-1">Register to access Citradata construction data.</p>
        </div>

        <?php if ($flashError): ?>
        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 text-red-800 px-5 py-4 flex items-start gap-3 text-sm font-medium">
            <i class="fas fa-circle-exclamation mt-0.5 shrink-0"></i>
            <span><?php echo e($flashError); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($flashSuccess): ?>
        <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 text-green-800 px-5 py-4 flex items-start gap-3 text-sm font-medium">
            <i class="fas fa-circle-check mt-0.5 shrink-0"></i>
            <span><?php echo e($flashSuccess); ?></span>
        </div>
        <?php endif; ?>

        <!-- Register Form -->
        <div class="bg-white rounded-[1.5rem] p-8 shadow-sm border border-slate-100">
            <form method="POST" action="<?php echo url('includes/register_handler.php'); ?>" class="space-y-5" novalidate>

                <!-- Full Name -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">
                        Full Name <span class="text-brandRed">*</span>
                    </label>
                    <input type="text" name="name" required autocomplete="name"
                           value="<?php echo $old('name'); ?>"
                           placeholder="Your full name"
                           class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">
                        Email <span class="text-brandRed">*</span>
                    </label>
                    <input type="email" name="email" required autocomplete="email"
                           value="<?php echo $old('email'); ?>"
                           placeholder="you@example.com"
                           class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Company (optional) -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">Company <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" name="company"
                           value="<?php echo $old('company'); ?>"
                           placeholder="PT Your Company"
                           class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">
                        Password <span class="text-brandRed">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="pw1" required autocomplete="new-password"
                               placeholder="Min. 8 characters"
                               class="w-full h-12 px-4 pr-11 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                        <button type="button" onclick="togglePw('pw1','eye1')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye" id="eye1"></i>
                        </button>
                    </div>
                    <p class="text-[11px] text-slate-400 ml-1">Minimal 8 karakter.</p>
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-slate-700 ml-1">
                        Confirm Password <span class="text-brandRed">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password_confirm" id="pw2" required autocomplete="new-password"
                               placeholder="Repeat your password"
                               class="w-full h-12 px-4 pr-11 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                        <button type="button" onclick="togglePw('pw2','eye2')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <i class="fas fa-eye" id="eye2"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms notice -->
                <p class="text-[11px] text-slate-400 leading-relaxed">
                    Dengan mendaftar, akun Anda akan dibuat dengan status <strong class="text-slate-600">Member</strong>.
                    Akses penuh ke data proyek memerlukan aktivasi oleh tim Citradata.
                </p>

                <button type="submit"
                        class="w-full h-12 bg-brandBlue text-white text-sm font-bold rounded-xl shadow-md hover:bg-blue-800 transition-all duration-300 flex items-center justify-center gap-2 group">
                    Create Account
                    <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>
        </div>

        <p class="text-center text-slate-500 text-sm mt-6">
            Already have an account?
            <a href="<?php echo url('pages/login.php'); ?>" class="text-brandBlue hover:underline font-semibold">Sign In</a>
        </p>

    </div>
</main>

<?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>
<?php require __DIR__ . '/../includes/scripts.php'; ?>

<script>
function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
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
