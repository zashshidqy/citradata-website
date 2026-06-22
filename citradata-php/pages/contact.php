<?php
require_once __DIR__ . '/../includes/functions.php';

session_start();

$pageTitle = 'Contact Us - Citradata Project Information Services';
$useSwiper = false;
require __DIR__ . '/../includes/head.php';

// Ambil flash message hasil submit form (sukses/error) dari session, lalu hapus.
$flashSuccess = $_SESSION['contact_success'] ?? null;
$flashError = $_SESSION['contact_error'] ?? null;
$oldInput = $_SESSION['contact_old_input'] ?? [];
unset($_SESSION['contact_success'], $_SESSION['contact_error'], $_SESSION['contact_old_input']);

$old = function (string $key) use ($oldInput) {
    return htmlspecialchars($oldInput[$key] ?? '', ENT_QUOTES);
};
?>
<body class="bg-slate-50 text-foreground antialiased flex flex-col min-h-screen selection:bg-brandBlue/20 selection:text-brandBlue">

    <?php require __DIR__ . '/../includes/nav.php'; ?>

    <main class="flex-grow relative overflow-hidden">

        <?php require __DIR__ . '/../includes/hero.php'; ?>

        <div class="absolute inset-0 z-0 pointer-events-none flex justify-center mt-[400px]">
            <div class="absolute inset-0 bg-grid-slate-100 mask-radial"></div>
        </div>

        <section class="py-16 md:py-24 relative z-10">
            <div class="max-w-6xl mx-auto px-4 md:px-6">

                <?php if ($flashSuccess): ?>
                <div class="mb-8 rounded-2xl border border-green-200 bg-green-50 text-green-800 px-6 py-4 flex items-start gap-3">
                    <i class="fas fa-circle-check text-lg mt-0.5"></i>
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($flashSuccess); ?></p>
                </div>
                <?php endif; ?>

                <?php if ($flashError): ?>
                <div class="mb-8 rounded-2xl border border-red-200 bg-red-50 text-red-800 px-6 py-4 flex items-start gap-3">
                    <i class="fas fa-circle-exclamation text-lg mt-0.5"></i>
                    <p class="text-sm font-medium"><?php echo htmlspecialchars($flashError); ?></p>
                </div>
                <?php endif; ?>

                <div class="grid lg:grid-cols-5 gap-10 lg:gap-16 items-center">

                    <div class="lg:col-span-2 relative h-full flex flex-col justify-center gap-0">
                        <!-- Card biru dengan teks -->
                        <div class="bg-brandBlue text-white rounded-t-[2rem] rounded-b-none px-8 pt-10 pb-8 shadow-xl relative overflow-hidden">
                            <!-- Pill merah di atas -->
                            <div class="absolute -top-4 left-1/2 -translate-x-1/2 w-28 h-8 bg-brandRed rounded-full shadow-md"></div>

                            <div class="relative z-10 mt-4 text-center">
                                <h2 class="text-xl md:text-2xl font-bold leading-snug tracking-tight">
                                    We are ready and waiting to help you secure new opportunities by connecting you with key construction players to grow your business
                                </h2>
                            </div>
                        </div>

                        <!-- Gambar contact di bawah card biru -->
                        <div class="rounded-b-[2rem] overflow-hidden shadow-xl">
                            <img
                                src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?w=800&q=80"
                                alt="Contact Us"
                                class="w-full h-56 object-cover object-center"
                            >
                        </div>
                    </div>

                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative">

                            <div class="mb-10">
                                <h3 class="text-2xl font-bold text-slate-900 tracking-tight mb-3">Get in Touch</h3>
                                <p class="text-slate-500 font-medium leading-relaxed">
                                    Please contact us if you require further assistance or would like to learn more about our solutions.
                                </p>
                            </div>

                            <form action="<?php echo url('includes/contact_handler.php'); ?>" method="POST" class="space-y-5">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-700 ml-1">Name <span class="text-brandRed">*</span></label>
                                        <input type="text" name="name" required placeholder="Your Name" value="<?php echo $old('name'); ?>" class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-700 ml-1">Company</label>
                                        <input type="text" name="company" placeholder="Your Company Ltd" value="<?php echo $old('company'); ?>" class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                                    </div>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-700 ml-1">Email <span class="text-brandRed">*</span></label>
                                        <input type="email" name="email" required placeholder="citradata@example.com" value="<?php echo $old('email'); ?>" class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-semibold text-slate-700 ml-1">Mobile</label>
                                        <input type="tel" name="mobile" placeholder="" value="<?php echo $old('mobile'); ?>" class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-slate-700 ml-1">Subject</label>
                                    <input type="text" name="subject" placeholder="How can we help you?" value="<?php echo $old('subject'); ?>" class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400">
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-slate-700 ml-1">Message</label>
                                    <textarea name="message" rows="4" placeholder="Tell us more about your needs..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all text-slate-800 placeholder:text-slate-400 resize-none"><?php echo $old('message'); ?></textarea>
                                </div>

                                <div class="pt-6">
                                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-slate-900 text-white text-sm font-bold rounded-xl shadow-md hover:bg-brandBlue hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 group">
                                        Submit
                                        <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <?php $footerVariant = 'light'; require __DIR__ . '/../includes/footer.php'; ?>

    <?php require __DIR__ . '/../includes/scripts.php'; ?>
</body>
</html>
