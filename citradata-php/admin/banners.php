<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';
requireAdmin();

$pdo        = getDbConnection();
$adminTitle = 'Banners';
$activePage = 'banners';

// Auto-create banners table if missing
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `banners` (
        `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `title`       VARCHAR(255) DEFAULT NULL,
        `image_path`  VARCHAR(500) NOT NULL,
        `link_url`    VARCHAR(500) DEFAULT NULL,
        `position`    ENUM('top','bottom','sidebar') NOT NULL DEFAULT 'top',
        `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
        `sort_order`  INT          NOT NULL DEFAULT 0,
        `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
} catch (Exception $e) { /* ignore */ }

$action = $_POST['action'] ?? ($_GET['action'] ?? '');

// ── DELETE ──────────────────────────────────────────────────────────────────
if ($action === 'delete' && isset($_GET['id'])) {
    $row = $pdo->prepare('SELECT image_path FROM banners WHERE id=:id');
    $row->execute([':id' => (int)$_GET['id']]);
    $row = $row->fetch();
    if ($row && str_starts_with($row['image_path'], 'assets/banners/')) {
        $f = dirname(__DIR__) . '/' . $row['image_path'];
        if (file_exists($f)) @unlink($f);
    }
    $pdo->prepare('DELETE FROM banners WHERE id=:id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/banners.php?deleted=1')); exit;
}

// ── TOGGLE ───────────────────────────────────────────────────────────────────
if ($action === 'toggle' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE banners SET is_active = NOT is_active WHERE id=:id')->execute([':id' => (int)$_GET['id']]);
    header('Location: ' . url('admin/banners.php')); exit;
}

// ── CREATE / UPDATE ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['create','update'])) {
    $id         = (int)($_POST['id']                   ?? 0);
    $title      = trim($_POST['title']                 ?? '');
    $link_url   = trim($_POST['link_url']              ?? '');
    $position   = in_array($_POST['position'] ?? '', ['top','bottom','sidebar']) ? $_POST['position'] : 'top';
    $is_active  = isset($_POST['is_active'])            ? 1 : 0;
    $sort_order = (int)($_POST['sort_order']            ?? 0);
    $image_path = trim($_POST['image_path_existing']   ?? '');
    $err = '';

    if (!empty($_FILES['banner_file']['name'])) {
        $fe = $_FILES['banner_file']['error'];
        if ($fe !== UPLOAD_ERR_OK) {
            $err = 'Upload error: ' . $fe;
        } else {
            $dir = dirname(__DIR__) . '/assets/banners/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['banner_file']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['png','jpg','jpeg','webp','gif'])) {
                $err = 'Format tidak didukung.';
            } else {
                $fn = 'banner_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['banner_file']['tmp_name'], $dir . $fn)) {
                    if ($image_path && str_starts_with($image_path, 'assets/banners/')) {
                        $old = dirname(__DIR__) . '/' . $image_path;
                        if (file_exists($old)) @unlink($old);
                    }
                    $image_path = 'assets/banners/' . $fn;
                } else { $err = 'Gagal menyimpan file.'; }
            }
        }
    }

    if ($err) { $_SESSION['banner_err'] = $err; }
    elseif ($image_path !== '') {
        if ($action === 'create') {
            $pdo->prepare('INSERT INTO banners (title,image_path,link_url,position,is_active,sort_order) VALUES(:ti,:ip,:lu,:po,:ia,:so)')
                ->execute([':ti'=>$title,':ip'=>$image_path,':lu'=>$link_url,':po'=>$position,':ia'=>$is_active,':so'=>$sort_order]);
        } else {
            $pdo->prepare('UPDATE banners SET title=:ti,image_path=:ip,link_url=:lu,position=:po,is_active=:ia,sort_order=:so WHERE id=:id')
                ->execute([':ti'=>$title,':ip'=>$image_path,':lu'=>$link_url,':po'=>$position,':ia'=>$is_active,':so'=>$sort_order,':id'=>$id]);
        }
        header('Location: ' . url('admin/banners.php?saved=1')); exit;
    } else { $_SESSION['banner_err'] = 'Image wajib diupload.'; }
}

// ── EDIT ─────────────────────────────────────────────────────────────────────
$editItem = null;
if (isset($_GET['edit'])) {
    $s = $pdo->prepare('SELECT * FROM banners WHERE id=:id');
    $s->execute([':id' => (int)$_GET['edit']]);
    $editItem = $s->fetch();
}

$items = $pdo->query('SELECT * FROM banners ORDER BY position ASC, sort_order ASC')->fetchAll();

require __DIR__ . '/includes/admin_head.php';
require __DIR__ . '/includes/admin_sidebar.php';
?>

<div class="flex-grow p-8 max-w-5xl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Banners</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola banner statis yang tampil di halaman website</p>
        </div>
        <a href="<?php echo url('admin/banners.php?new=1'); ?>"
           class="px-5 py-2 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i> Add Banner
        </a>
    </div>

    <?php if (isset($_GET['saved'])): ?>
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fas fa-circle-check"></i> Banner berhasil disimpan.
    </div>
    <?php endif; ?>
    <?php if (isset($_GET['deleted'])): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
        <i class="fas fa-trash"></i> Banner dihapus.
    </div>
    <?php endif; ?>
    <?php $errMsg = $_SESSION['banner_err'] ?? null; unset($_SESSION['banner_err']); ?>
    <?php if ($errMsg): ?>
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium">
        <i class="fas fa-circle-exclamation mr-2"></i><?php echo e($errMsg); ?>
    </div>
    <?php endif; ?>

    <!-- FORM -->
    <?php if (isset($_GET['new']) || $editItem): ?>
    <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 mb-8">
        <h2 class="font-semibold text-slate-800 mb-6 text-lg"><?php echo $editItem ? 'Edit Banner' : 'Add New Banner'; ?></h2>
        <form method="POST" action="<?php echo url('admin/banners.php'); ?>" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?>
            <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
            <input type="hidden" name="image_path_existing" value="<?php echo e($editItem['image_path']); ?>">
            <?php else: ?>
            <input type="hidden" name="image_path_existing" value="">
            <?php endif; ?>

            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Banner Image * <span class="font-normal text-slate-400">(PNG, JPG — rekomendasi 1200×300px)</span>
                </label>
                <label id="banner-drop-zone" class="flex flex-col items-center justify-center w-full border-2 border-dashed border-slate-300 rounded-2xl p-5 cursor-pointer hover:border-brandBlue hover:bg-blue-50/30 transition-all bg-slate-50">
                    <div id="banner-preview-wrap" class="mb-3 w-full <?php echo ($editItem && !empty($editItem['image_path'])) ? '' : 'hidden'; ?>">
                        <img id="banner-preview-img"
                             src="<?php echo ($editItem && !empty($editItem['image_path'])) ? asset($editItem['image_path']) : ''; ?>"
                             alt="Preview" class="w-full max-h-40 object-cover rounded-xl border border-slate-200">
                        <p id="banner-preview-label" class="text-[11px] text-slate-400 text-center mt-1">
                            <?php echo ($editItem && !empty($editItem['image_path'])) ? e($editItem['image_path']) : ''; ?>
                        </p>
                    </div>
                    <div id="banner-upload-hint" class="flex flex-col items-center <?php echo ($editItem && !empty($editItem['image_path'])) ? 'hidden' : ''; ?>">
                        <i class="fas fa-cloud-arrow-up text-2xl text-slate-300 mb-1"></i>
                        <p class="text-sm font-semibold text-slate-600">Klik atau drag gambar ke sini</p>
                        <p class="text-xs text-slate-400 mt-0.5">PNG, JPG, WebP — maks 2MB</p>
                    </div>
                    <input type="file" name="banner_file" id="banner-file-input" accept="image/png,image/jpeg,image/jpg,image/webp,image/gif" class="hidden">
                </label>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Title <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input type="text" name="title" value="<?php echo e($editItem['title'] ?? ''); ?>" placeholder="e.g. Promo Oktober 2026"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Link URL <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input type="url" name="link_url" value="<?php echo e($editItem['link_url'] ?? ''); ?>" placeholder="https://..."
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
            </div>

            <div class="grid sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Position</label>
                    <select name="position" class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                        <option value="top"     <?php echo ($editItem['position'] ?? 'top') === 'top'     ? 'selected' : ''; ?>>Top (bawah hero)</option>
                        <option value="bottom"  <?php echo ($editItem['position'] ?? '') === 'bottom'  ? 'selected' : ''; ?>>Bottom (sebelum footer)</option>
                        <option value="sidebar" <?php echo ($editItem['position'] ?? '') === 'sidebar' ? 'selected' : ''; ?>>Sidebar</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" min="0" value="<?php echo (int)($editItem['sort_order'] ?? 0); ?>"
                           class="w-full h-11 px-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-brandBlue/20 focus:border-brandBlue transition-all">
                </div>
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 text-sm font-medium text-slate-700 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" <?php echo ($editItem['is_active'] ?? 1) ? 'checked' : ''; ?> class="w-4 h-4 rounded accent-brandBlue">
                        Tampilkan di website
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2 border-t border-slate-100">
                <button type="submit" class="px-7 py-2.5 bg-brandBlue text-white text-sm font-semibold rounded-xl hover:bg-blue-800 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Save
                </button>
                <a href="<?php echo url('admin/banners.php'); ?>" class="px-7 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">Cancel</a>
            </div>
        </form>
    </div>
    <script>
    (function(){
        const input=document.getElementById('banner-file-input'),preview=document.getElementById('banner-preview-img'),wrap=document.getElementById('banner-preview-wrap'),hint=document.getElementById('banner-upload-hint'),lbl=document.getElementById('banner-preview-label'),drop=document.getElementById('banner-drop-zone');
        if(!input)return;
        function handleFile(f){if(!f||!f.type.startsWith('image/'))return;const r=new FileReader();r.onload=e=>{preview.src=e.target.result;wrap.classList.remove('hidden');hint.classList.add('hidden');lbl.textContent=f.name+' ('+(f.size/1024).toFixed(1)+' KB)';};r.readAsDataURL(f);}
        input.addEventListener('change',function(){if(this.files[0])handleFile(this.files[0]);});
        drop.addEventListener('dragover',e=>{e.preventDefault();drop.classList.add('border-brandBlue','bg-blue-50/50');});
        drop.addEventListener('dragleave',()=>drop.classList.remove('border-brandBlue','bg-blue-50/50'));
        drop.addEventListener('drop',e=>{e.preventDefault();drop.classList.remove('border-brandBlue','bg-blue-50/50');const f=e.dataTransfer.files[0];if(f){const dt=new DataTransfer();dt.items.add(f);input.files=dt.files;handleFile(f);}});
    })();
    </script>
    <?php endif; ?>

    <!-- LIST -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Preview</th>
                    <th class="px-5 py-3 text-left font-semibold text-slate-500 text-xs uppercase tracking-wide">Title</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Position</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Uploaded</th>
                    <th class="px-5 py-3 text-center font-semibold text-slate-500 text-xs uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-right font-semibold text-slate-500 text-xs uppercase tracking-wide">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($items as $item): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-3">
                        <div class="w-40 h-16 rounded-lg overflow-hidden border border-slate-100 bg-slate-50">
                            <img src="<?php echo asset($item['image_path']); ?>" alt="banner"
                                 class="w-full h-full object-cover" onerror="this.style.display='none'">
                        </div>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-slate-700"><?php echo e($item['title'] ?: '—'); ?></p>
                        <?php if ($item['link_url']): ?>
                        <p class="text-slate-400 text-xs mt-0.5 truncate max-w-[180px]"><?php echo e($item['link_url']); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">
                            <?php echo ucfirst($item['position']); ?>
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center text-slate-400 text-xs">
                        <?php echo date('d M Y', strtotime($item['created_at'])); ?>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <a href="<?php echo url('admin/banners.php?action=toggle&id=' . $item['id']); ?>"
                           class="inline-flex px-2 py-1 rounded-lg text-xs font-semibold cursor-pointer transition-colors
                               <?php echo $item['is_active'] ? 'bg-green-50 text-green-700 hover:bg-green-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'; ?>">
                            <?php echo $item['is_active'] ? '● Active' : '○ Hidden'; ?>
                        </a>
                    </td>
                    <td class="px-5 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="<?php echo url('admin/banners.php?edit=' . $item['id']); ?>"
                               class="px-3 py-1.5 bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg hover:bg-slate-200 transition-colors">Edit</a>
                            <a href="<?php echo url('admin/banners.php?action=delete&id=' . $item['id']); ?>"
                               onclick="return confirm('Hapus banner ini?')"
                               class="px-3 py-1.5 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors">Delete</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400 text-sm">Belum ada banner. Klik <strong>+ Add Banner</strong> untuk menambahkan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <p class="text-xs text-slate-400 mt-4 flex items-center gap-1.5">
        <i class="fas fa-circle-info"></i>
        Rekomendasi ukuran: <strong>1200 × 300 px</strong> untuk banner top/bottom. Format PNG atau JPG.
    </p>
</div>
</body>
</html>
