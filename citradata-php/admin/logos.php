<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin(); // sudah include session_start() di dalamnya

$pdo        = getDbConnection();
$adminTitle = 'Client & Collaboration Logos';
$activePage = 'logos';

// Helper: filesystem path ke project root (via DOCUMENT_ROOT, bukan __DIR__)
function projectFsRoot(): string {
    return rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . '/' . ltrim(baseUrl(), '/');
}

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

// ── DELETE ──────────────────────────────────────────────────────────────────
if ($action === 'delete' && isset($_GET['id'])) {
    $row = $pdo->prepare('SELECT logo_path FROM client_logos WHERE id=:id');
    $row->execute([':id' => (int)$_GET['id']]);
    $row = $row->fetch();
    if ($row && str_starts_with($row['logo_path'], 'assets/logos/')) {
        $fullPath = dirname(__DIR__) . '/' . $row['logo_path'];
        if (file_exists($fullPath)) @unlink($fullPath);
    }
    $pdo->prepare('DELETE FROM client_logos WHERE id=:id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/logos.php?deleted=1'));
    exit;
}

// ── TOGGLE ACTIVE ────────────────────────────────────────────────────────────
if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE client_logos SET is_active = NOT is_active WHERE id=:id')
        ->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/logos.php'));
    exit;
}

// ── CREATE / UPDATE ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'update'])) {
    $id          = (int)($_POST['id']               ?? 0);
    $name        = trim($_POST['name']              ?? '');
    $website_url = trim($_POST['website_url']       ?? '');
    $type        = ($_POST['type'] ?? '') === 'collaboration' ? 'collaboration' : 'client';
    $is_active   = isset($_POST['is_active'])        ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order']        ?? 0);
    $logo_path   = trim($_POST['logo_path_existing'] ?? '');
    $upload_error = '';

    // Handle file upload — gunakan __DIR__ naik satu level (selalu benar di semua env)
    if (!empty($_FILES['logo_file']['name'])) {
        $fileError = $_FILES['logo_file']['error'];

        if ($fileError !== UPLOAD_ERR_OK) {
            $upload_error = 'Upload error code: ' . $fileError;
        } else {
            // Path fisik: dari script ini (admin/) naik ke root project
            $uploadDir = dirname(__DIR__) . '/assets/logos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext     = strtolower(pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'];

            if (!in_array($ext, $allowed)) {
                $upload_error = 'Format file tidak didukung: ' . $ext;
            } else {
                $filename = 'logo_' . uniqid() . '.' . $ext;
                $destPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['logo_file']['tmp_name'], $destPath)) {
                    // Hapus file lama kalau ada
                    if ($logo_path && str_starts_with($logo_path, 'assets/logos/')) {
                        $oldFile = dirname(__DIR__) . '/' . $logo_path;
                        if (file_exists($oldFile)) @unlink($oldFile);
                    }
                    $logo_path = 'assets/logos/' . $filename;
                } else {
                    $upload_error = 'Gagal memindahkan file ke: ' . $destPath;
                }
            }
        }
    }

    if ($name !== '' && $logo_path !== '') {
        if ($action === 'create') {
            $pdo->prepare('INSERT INTO client_logos (name,logo_path,website_url,type,is_active,sort_order) VALUES(:n,:l,:w,:t,:a,:o)')
                ->execute([':n'=>$name,':l'=>$logo_path,':w'=>$website_url,':t'=>$type,':a'=>$is_active,':o'=>$sort_order]);
        } else {
            $pdo->prepare('UPDATE client_logos SET name=:n,logo_path=:l,website_url=:w,type=:t,is_active=:a,sort_order=:o WHERE id=:id')
                ->execute([':n'=>$name,':l'=>$logo_path,':w'=>$website_url,':t'=>$type,':a'=>$is_active,':o'=>$sort_order,':id'=>$id]);
        }
        header('Location: ' . url('admin/logos.php?saved=1'));
        exit;
    }

    // Kalau sampai sini berarti ada error — simpan ke session untuk ditampilkan
    if ($upload_error) {
        $_SESSION['logo_upload_error'] = $upload_error . ' | uploadDir: ' . (dirname(__DIR__) . '/assets/logos/') . ' | logo_path: ' . $logo_path;
    } elseif ($name === '') {
        $_SESSION['logo_upload_error'] = 'Name wajib diisi.';
    } elseif ($logo_path === '') {
        $_SESSION['logo_upload_error'] = 'Logo image wajib diupload.';
    }
}

// ── FETCH FOR EDIT ───────────────────────────────────────────────────────────
$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM client_logos WHERE id=:id');
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$items = $pdo->query('SELECT * FROM client_logos ORDER BY type ASC, sort_order ASC')->fetchAll();

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>

<div class="flex-grow p-8 max-w-5xl">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Logos</h1>
        <a href="<?php echo url('admin/logos.php?new=1'); ?>"
           class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors">
            + Add Logo
        </a>
    </div>

    <?php if (isset($_GET['saved'])): ?>
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fas fa-circle-check"></i> Logo berhasil disimpan dan siap tampil.
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fas fa-trash"></i> Logo dihapus.
    </div>
    <?php endif; ?>
    <?php
    $uploadErrMsg = $_SESSION['logo_upload_error'] ?? null;
    unset($_SESSION['logo_upload_error']);
    if ($uploadErrMsg): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
        <i class="fas fa-circle-exclamation mr-2"></i><?php echo e($uploadErrMsg); ?>
    </div>
    <?php endif; ?>
    <!-- ── FORM ADD / EDIT ── -->
    <?php if (isset($_GET['new']) || $editItem): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <h2 class="font-semibold text-slate-800 mb-6 text-lg">
            <?php echo $editItem ? 'Edit Logo' : 'Add New Logo'; ?>
        </h2>

        <form method="POST" action="<?php echo url('admin/logos.php'); ?>"
              enctype="multipart/form-data" class="space-y-5">

            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?>
            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
            <input type="hidden" name="logo_path_existing" value="<?php echo e($editItem['logo_path']); ?>">
            <?php else: ?>
            <input type="hidden" name="logo_path_existing" value="">
            <?php endif; ?>

            <div class="grid sm:grid-cols-2 gap-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Name *</label>
                    <input type="text" name="name" required
                           value="<?php echo e($editItem['name'] ?? ''); ?>"
                           placeholder="e.g. Pacific Paint"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
                <!-- Type -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Type</label>
                    <select name="type"
                            class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                        <option value="client"        <?php echo ($editItem['type'] ?? 'client') === 'client'        ? 'selected' : ''; ?>>Valuable Client</option>
                        <option value="collaboration" <?php echo ($editItem['type'] ?? 'client') === 'collaboration' ? 'selected' : ''; ?>>Collaboration</option>
                    </select>
                </div>
            </div>

            <!-- Website URL -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Website URL <span class="font-normal text-slate-400">(saat logo diklik)</span></label>
                <input type="url" name="website_url"
                       value="<?php echo e($editItem['website_url'] ?? ''); ?>"
                       placeholder="https://www.example.com"
                       class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
            </div>

            <!-- Logo Upload with live preview -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Logo Image *</label>

                <!-- Drop zone -->
                <label id="drop-zone"
                       class="flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-300 rounded-2xl p-6 cursor-pointer hover:border-brandBlue hover:bg-blue-50/30 transition-all duration-200 bg-slate-50">

                    <!-- Preview area -->
                    <div id="preview-wrap" class="mb-4 <?php echo ($editItem && !empty($editItem['logo_path'])) ? '' : 'hidden'; ?>">
                        <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-center" style="min-width:120px; min-height:64px;">
                            <img id="logo-preview"
                                 src="<?php echo ($editItem && !empty($editItem['logo_path'])) ? asset($editItem['logo_path']) : ''; ?>"
                                 alt="Preview"
                                 class="max-h-14 max-w-[180px] object-contain">
                        </div>
                        <p id="preview-label" class="text-[11px] text-slate-400 text-center mt-2">
                            <?php echo ($editItem && !empty($editItem['logo_path'])) ? e($editItem['logo_path']) : ''; ?>
                        </p>
                    </div>

                    <!-- Upload icon & text (hidden once preview shows) -->
                    <div id="upload-hint" class="flex flex-col items-center <?php echo ($editItem && !empty($editItem['logo_path'])) ? 'hidden' : ''; ?>">
                        <i class="fas fa-cloud-arrow-up text-3xl text-slate-300 mb-2"></i>
                        <p class="text-sm font-semibold text-slate-600">Klik atau drag file ke sini</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, SVG, WebP — maks. 2 MB</p>
                    </div>

                    <input type="file" name="logo_file" id="logo-file-input"
                           accept="image/png,image/jpeg,image/gif,image/svg+xml,image/webp"
                           class="hidden">
                </label>

                <?php if ($editItem && !empty($editItem['logo_path'])): ?>
                <p class="text-xs text-slate-400 mt-2">
                    Pilih file baru untuk mengganti, atau biarkan kosong untuk mempertahankan logo saat ini.
                </p>
                <?php endif; ?>
            </div>

            <!-- Sort Order + Active -->
            <div class="flex items-center gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" min="0"
                           value="<?php echo (int)($editItem['sort_order'] ?? 0); ?>"
                           class="w-24 h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
                <label class="flex items-center gap-2 text-sm font-medium text-slate-700 mt-5 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1"
                           <?php echo ($editItem['is_active'] ?? 1) ? 'checked' : ''; ?>
                           class="w-4 h-4 rounded accent-brandBlue">
                    Tampilkan di website
                </label>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <button type="submit"
                        class="px-7 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save
                </button>
                <a href="<?php echo url('admin/logos.php'); ?>"
                   class="px-7 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- ── LIST TABLE ── -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide w-24">Logo</th>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Name</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Type</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="w-20 h-10 flex items-center justify-center bg-slate-50 rounded-lg border border-slate-100">
                            <img src="<?php echo asset($item['logo_path']); ?>"
                                 alt="<?php echo e($item['name']); ?>"
                                 class="max-h-8 max-w-[72px] object-contain"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                            <span style="display:none" class="text-slate-300 text-xs">No img</span>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-medium text-slate-800"><?php echo e($item['name']); ?></p>
                        <?php if ($item['website_url']): ?>
                        <p class="text-slate-400 text-xs mt-0.5 truncate max-w-[200px]"><?php echo e($item['website_url']); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold
                            <?php echo $item['type'] === 'client' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700'; ?>">
                            <?php echo $item['type'] === 'client' ? 'Client' : 'Collaboration'; ?>
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="<?php echo url('admin/logos.php?action=toggle&id=' . $item['id']); ?>"
                           class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold cursor-pointer
                               <?php echo $item['is_active'] ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'; ?> transition-colors">
                            <?php echo $item['is_active'] ? '● Active' : '○ Hidden'; ?>
                        </a>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/logos.php?edit=' . $item['id']); ?>"
                               class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                                Edit
                            </a>
                            <a href="<?php echo url('admin/logos.php?action=delete&id=' . $item['id']); ?>"
                               onclick="return confirm('Hapus logo <?php echo e(addslashes($item['name'])); ?>?')"
                               class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-sm">
                        Belum ada logo. Klik <strong>+ Add Logo</strong> untuk menambahkan.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Live Preview Script -->
<script>
(function () {
    const input    = document.getElementById('logo-file-input');
    const preview  = document.getElementById('logo-preview');
    const wrap     = document.getElementById('preview-wrap');
    const hint     = document.getElementById('upload-hint');
    const label    = document.getElementById('preview-label');
    const dropZone = document.getElementById('drop-zone');

    if (!input) return;

    function handleFile(file) {
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            wrap.classList.remove('hidden');
            hint.classList.add('hidden');
            if (label) label.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
        };
        reader.readAsDataURL(file);
    }

    // File input change
    input.addEventListener('change', function () {
        if (this.files && this.files[0]) handleFile(this.files[0]);
    });

    // Drag & drop
    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        this.classList.add('border-brandBlue', 'bg-blue-50/50');
    });
    dropZone.addEventListener('dragleave', function () {
        this.classList.remove('border-brandBlue', 'bg-blue-50/50');
    });
    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        this.classList.remove('border-brandBlue', 'bg-blue-50/50');
        const file = e.dataTransfer.files[0];
        if (file) {
            // Transfer ke input supaya ikut tersubmit
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;
            handleFile(file);
        }
    });
})();
</script>

</body>
</html>
