<?php
/**
 * Admin shared head partial.
 * $adminTitle must be set before including this file.
 */
if (!isset($adminTitle)) $adminTitle = 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($adminTitle); ?> – Citradata Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter','sans-serif'] },
                colors: {
                    brandBlue: '#0057a8',
                    brandRed:  '#dc2626',
                }
            }
        }
    }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex">
