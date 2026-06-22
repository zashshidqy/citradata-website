<?php
/**
 * Partial: <head> bersama untuk semua halaman.
 * Variabel yang bisa di-set sebelum include file ini:
 * - $pageTitle (string) : judul halaman, default "Citradata Project Information Services"
 * - $extraHeadStyle (string) : tambahan <style> khusus halaman (opsional)
 */

if (!isset($pageTitle)) {
    $pageTitle = 'Citradata Project Information Services';
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    <?php if (!empty($useSwiper)): ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>" />

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brandBlue: '#0057a8',
                        brandRed: '#dc2626',
                        background: '#ffffff',
                        foreground: '#020817',
                        muted: '#f1f5f9',
                        mutedForeground: '#64748b',
                        border: '#e2e8f0',
                    }
                }
            }
        }
    </script>
    <style>
        .bg-grid-slate-100 {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32' width='32' height='32' fill='none' stroke='rgb(241 245 249 / 0.8)'%3e%3cpath d='M0 .5H31.5V32'/%3e%3c/svg%3e");
        }
        .mask-radial {
            mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%);
            -webkit-mask-image: radial-gradient(ellipse at center, black 40%, transparent 80%);
        }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        <?php if (!empty($useSwiper)): ?>
        .swiper-pagination-bullet {
            background-color: #cbd5e1;
            opacity: 0.8;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }
        .swiper-pagination-bullet-active {
            background-color: #0057a8 !important;
            width: 24px;
            border-radius: 99px;
            opacity: 1;
        }
        .swiper-pagination {
            position: absolute;
            bottom: 12px !important;
        }
        <?php endif; ?>
    </style>
    <?php if (!empty($extraHeadStyle)) echo $extraHeadStyle; ?>
</head>
