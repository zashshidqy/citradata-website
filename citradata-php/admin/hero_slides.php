<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Hero Slides';
$activePage = 'hero_slides';

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

// ── DELETE ──────────────────────────────────────────────────────────────────
if ($action === 'delete' && isset($_GET['id'])) {
    $row = $pdo->prepare('SELECT image_path FROM hero_slides WHERE id=:id');
    $row->execute([':id' => (int)$_GET['id']]);
    $row = $row->fetch();
    if ($row && str_starts_with($row['image_path'], 'assets/hero_slides/')) {
        $fullPath = dirname(__DIR__) . '/' . $row['image_path'];
        if (file_exists($fullPath)) @unlink($fullPath);
    }
    $pdo->prepare('DELETE FROM hero_slides WHERE id=:id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/hero_slides.php?deleted=1'));
    exit;
}

// ── TOGGLE ACTIVE ─────────────────────────────────────────────────────────
if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE hero_slides SET is_active = NOT is_active WHERE id=:id')
        ->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/hero_slides.php'));
    exit;
}

// ── CREATE / UPDATE ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create', 'update'])) {
    $id          = (int)($_POST['id']                 ?? 0);
    $alt_text    = trim($_POST['alt_text']            ?? '');
    $is_active   = isset($_POST['is_active'])          ? 1 : 0;
    $sort_order  = (int)($_POST['sort_order']          ?? 0);
    $image_path  = trim($_POST['image_path_existing'] ?? '');
    $upload_error = '';

    if (!empty($_FILES['slide_file']['name'])) {
        $fileError = $_FILES['slide_file']['error'];
        if ($fileError !== UPLOAD_ERR_OK) {
            $upload_error = 'Upload error code: ' . $fileError;
        } else {
            $uploadDir = dirname(__DIR__) . '/assets/hero_slides/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext     = strtolower(pathinfo($_FILES['slide_file']['name'], PATHINFO_EXTENSION));
            $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];

            if (!in_array($ext, $allowed)) {
                $upload_error = 'Format tidak didukung: ' . $ext;
            } else {
                $filename = 'slide_' . uniqid() . '.' . $ext;
                $destPath = $uploadDir . $filename;
                if (move_uploaded_file($_FILES['slide_file']['tmp_name'], $destPath)) {
                    if ($image_path && str_starts_with($image_path, 'assets/hero_slides/')) {
                        $oldFile = dirname(__DIR__) . '/' . $image_path;
                        if (file_exists($oldFile)) @unlink($oldFile);
                    }
                    $image_path = 'assets/hero_slides/' . $filename;
                } else {
                    $upload_error = 'Gagal memindahkan file.';
                }
            }
        }
    }

    if ($image_path !== '') {
        if ($action === 'create') {
            $pdo->prepare('INSERT INTO hero_slides (image_path, alt_text, sort_order, is_active) VALUES(:p,:a,:o,:i)')
                ->execute([':p'=>$image_path,':a'=>$alt_text,':o'=>$sort_order,':i'=>$is_active]);
        } else {
            $pdo->prepare('UPDATE hero_slides SET image_path=:p, alt_text=:a, sort_order=:o, is_active=:i WHERE id=:id')
                ->execute([':p'=>$image_path,':a'=>$alt_text,':o'=>$sort_order,':i'=>$is_active,':id'=>$id]);
        }
        header('Location: ' . url('admin/hero_slides.php?saved=1'));
        exit;
    }

    if ($upload_error) $_SESSION['slide_upload_error'] = $upload_error;
    elseif ($image_path === '') $_SESSION['slide_upload_error'] = 'Image wajib diupload.';
}

// ── FETCH FOR EDIT ───────────────────────────────────────────────────────────
$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM hero_slides WHERE id=:id');
    $stmt->execute([':id' => (int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

$items = $pdo->query('SELECT * FROM hero_slides ORDER BY sort_order ASC, created_at ASC')->fetchAll();

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>

<div class="flex-grow p-8 max-w-5xl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Hero Slides</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola gambar hero yang tampil di semua halaman website</p>
        </div>
        <a href="<?php echo url('admin/hero_slides.php?new=1'); ?>"
           class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Slide
        </a>
    </div>

    <?php if (isset($_GET['saved'])): ?>
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fas fa-circle-check"></i> Slide berhasil disimpan.
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fas fa-trash"></i> Slide dihapus.
    </div>
    <?php endif; ?>
    <?php $errMsg = $_SESSION['slide_upload_error'] ?? null; unset($_SESSION['slide_upload_error']); ?>
    <?php if ($errMsg): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
        <i class="fas fa-circle-exclamation mr-2"></i><?php echo e($errMsg); ?>
    </div>
    <?php endif; ?>

    <!-- ── FORM ADD / EDIT ── -->
    <?php if (isset($_GET['new']) || $editItem): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <h2 class="font-semibold text-slate-800 mb-6 text-lg">
            <?php echo $editItem ? 'Edit Slide' : 'Add New Slide'; ?>
        </h2>
        <form method="POST" action="<?php echo url('admin/hero_slides.php'); ?>"
              enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?>
            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
            <input type="hidden" name="image_path_existing" value="<?php echo e($editItem['image_path']); ?>">
            <?php else: ?>
            <input type="hidden" name="image_path_existing" value="">
            <?php endif; ?>

            <!-- Upload -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Slide Image *</label>
                <label id="drop-zone"
                       class="flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-300 rounded-2xl p-6 cursor-pointer hover:border-brandBlue hover:bg-blue-50/30 transition-all bg-slate-50">
                    <div id="preview-wrap" class="mb-4 w-full <?php echo ($editItem && !empty($editItem['image_path'])) ? '' : 'hidden'; ?>">
                        <img id="slide-preview"
                             src="<?php echo ($editItem && !empty($editItem['image_path'])) ? asset($editItem['image_path']) : ''; ?>"
                             alt="Preview"
                             class="w-full max-h-48 object-cover rounded-xl border border-slate-200">
                        <p id="preview-label" class="text-[11px] text-slate-400 text-center mt-2">
                            <?php echo ($editItem && !empty($editItem['image_path'])) ? e($editItem['image_path']) : ''; ?>
                        </p>
                    </div>
                    <div id="upload-hint" class="flex flex-col items-center <?php echo ($editItem && !empty($editItem['image_path'])) ? 'hidden' : ''; ?>">
                        <i class="fas fa-cloud-arrow-up text-3xl text-slate-300 mb-2"></i>
                        <p class="text-sm font-semibold text-slate-600">Klik atau drag file ke sini</p>
                        <p class="text-xs text-slate-400 mt-1">PNG, JPG, WebP — Rekomendasi: 1920×600px</p>
                    </div>
                    <input type="file" name="slide_file" id="slide-file-input"
                           accept="image/png,image/jpeg,image/gif,image/webp" class="hidden">
                </label>
                <?php if ($editItem): ?>
                <p class="text-xs text-slate-400 mt-2">Pilih file baru untuk mengganti, atau biarkan kosong untuk mempertahankan gambar saat ini.</p>
                <?php endif; ?>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Alt Text <span class="font-normal text-slate-400">(deskripsi gambar)</span></label>
                    <input type="text" name="alt_text"
                           value="<?php echo e($editItem['alt_text'] ?? ''); ?>"
                           placeholder="e.g. Construction project banner"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" min="0"
                           value="<?php echo (int)($editItem['sort_order'] ?? 0); ?>"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
            </div>

            <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1"
                       <?php echo ($editItem['is_active'] ?? 1) ? 'checked' : ''; ?>
                       class="w-4 h-4 rounded accent-brandBlue">
                Tampilkan di website
            </label>

            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <button type="submit" class="px-7 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save
                </button>
                <a href="<?php echo url('admin/hero_slides.php'); ?>"
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
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide w-48">Preview</th>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Alt Text</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide w-20">Order</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide w-24">Status</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="w-40 h-20 rounded-lg overflow-hidden border border-slate-100 bg-slate-50">
                            <img src="<?php echo asset($item['image_path']); ?>"
                                 alt="<?php echo e($item['alt_text'] ?? ''); ?>"
                                 class="w-full h-full object-cover"
                                 onerror="this.style.display='none'">
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-slate-700"><?php echo e($item['alt_text'] ?: '—'); ?></p>
                        <p class="text-slate-400 text-xs mt-0.5 truncate max-w-[200px]"><?php echo e($item['image_path']); ?></p>
                    </td>
                    <td class="px-5 py-3 text-center text-slate-600 font-medium"><?php echo $item['sort_order']; ?></td>
                    <td class="px-5 py-3 text-center">
                        <a href="<?php echo url('admin/hero_slides.php?action=toggle&id=' . $item['id']); ?>"
                           class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold cursor-pointer
                               <?php echo $item['is_active'] ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'; ?> transition-colors">
                            <?php echo $item['is_active'] ? '● Active' : '○ Hidden'; ?>
                        </a>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/hero_slides.php?edit=' . $item['id']); ?>"
                               class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">
                                Edit
                            </a>
                            <a href="<?php echo url('admin/hero_slides.php?action=delete&id=' . $item['id']); ?>"
                               onclick="return confirm('Hapus slide ini?')"
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
                        Belum ada slide. Klik <strong>+ Add Slide</strong> untuk menambahkan.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <p class="text-xs text-slate-400 mt-4 flex items-center gap-1.5">
        <i class="fas fa-circle-info"></i>
        Rekomendasi ukuran gambar: <strong>1920 × 600 px</strong> (landscape, rasio 16:5). Format PNG atau JPG.
    </p>
</div>

<!-- Live Preview Script -->
<script>
(function () {
    const input    = document.getElementById('slide-file-input');
    const preview  = document.getElementById('slide-preview');
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

    input.addEventListener('change', function () {
        if (this.files && this.files[0]) handleFile(this.files[0]);
    });

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
