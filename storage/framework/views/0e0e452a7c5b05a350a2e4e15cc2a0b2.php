<?php $__env->startSection('content'); ?>
    <style>
        .editor-wrap {
            width: 900px;
            max-width: 100%;
            box-shadow: 0 6px 24px rgba(12, 18, 30, 0.08)
        }

        .editor_inner {
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            padding: 10px 14px;
            border-radius: 8px 8px 0 0;
            display: flex;
            gap: 12px;
            align-items: center
        }

        .toolbar {
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .btn {
            /* background: transparent; */
            border: 1px solid #e6e9ef;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px
        }

        .btn:active {
            transform: translateY(1px)
        }

        .btn.select {
            display: flex;
            align-items: center;
            gap: 6px
        }

        .btn input[type=file] {
            display: none
        }

        .controls {
            margin-left: auto;
            display: flex;
            gap: 8px;
            align-items: center
        }

        .editor {
            background: var(--panel);
            min-height: 100px;
            padding: 18px;
            border-radius: 0 0 8px 8px;
            border: 1px solid #eef2f6;
            outline: none;
            color: var(--accent);
            font-size: 16px;
            line-height: 1.5
        }

        .editor[contenteditable="true"]:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08)
        }

        .status {
            padding: 8px 12px;
            font-size: 13px;
            color: var(--muted);
            display: flex;
            gap: 12px;
            align-items: center
        }

        .small {
            font-size: 13px;
            color: var(--muted)
        }

        .search-box {
            display: flex;
            gap: 6px;
            align-items: center;
            border: 1px solid #e6e9ef;
            padding: 4px;
            border-radius: 6px
        }

        .search-box input {
            border: 0;
            outline: none;
            padding: 6px 8px;
            font-size: 14px
        }

        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #e6e9ef
        }

        .header_file {
            min-height: 186px;
            box-shadow: 0 6px 24px rgba(12, 18, 30, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width:640px) {
            header {
                flex-direction: column;
                align-items: stretch;
                padding: 12px
            }

            .controls {
                margin-left: 0;
                justify-content: flex-end
            }
        }

        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .section-header {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 12px;
        }

        .section-title {
            color: #212529;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .section-subtitle {
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-container {
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 160px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .upload-container:hover {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), 0.05) !important;
        }

        .upload-placeholder i {
            opacity: 0.6;
        }

        .current-image img {
            border: 2px solid #dee2e6;
        }

        .editor-content {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            padding: 16px;
        }

        .editor-content:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.25);
        }

        .editor-toolbar {
            border-bottom: 1px solid #dee2e6;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 16px;
                margin-bottom: 20px;
            }

            .upload-container {
                min-height: 120px;
            }
        }

        .btn-add-category {
            background: linear-gradient(135deg, #750096 0%, #CB6CE6 100%);
            color: white;
            border: none;
            padding: 0.5rem 2rem;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(233, 30, 99, 0.3);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-add-category:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 30, 99, 0.4);
        }

        .btn-add-category i {
            font-size: 1rem;
        }
    </style>

    <!-- Start Content -->
    <div class="content" id="profilePage">
        
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="mb-3 border-bottom pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold mb-0">Settings</h4>
                <?php if($letterheadCategory && $letterheadCategory->count() > 0): ?>
                    <button type="button" class="btn-add-category" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-circle"></i>
                        Add Letterhead Category
                    </button>
                <?php endif; ?>
            </div>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body p-0">
                <div class="settings-wrapper d-flex">
                    <?php if($letterheadCategory && $letterheadCategory->count() > 0): ?>
                        <!-- Sidebar -->
                        <div class="sidebars settings-sidebar" id="sidebar2" style="max-height: 70vh; overflow: auto;">
                            <div class="sidebar-inner" data-simplebar>
                                <div id="sidebar-menu5" class="sidebar-menu mt-0 p-0">
                                    <ul class="nav flex-column" id="permissionTabs" role="tablist">
                                        <?php $__currentLoopData = $letterheadCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo e($index == 0 ? 'active' : ''); ?>"
                                                    id="tab-<?php echo e($category->id); ?>" data-bs-toggle="tab"
                                                    href="#content-<?php echo e($category->id); ?>" role="tab"
                                                    aria-controls="content-<?php echo e($category->id); ?>"
                                                    aria-selected="<?php echo e($index == 0 ? 'true' : 'false'); ?>">
                                                    <i class="ti ti-device-desktop-cog me-2"></i>
                                                    <?php echo e($category->name); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Sidebar -->

                        <!-- Main Card -->
                        <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                            <div class="card-header border-bottom px-0 mx-3">

                                <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2">
                                    <div class="flex-grow-1">
                                        <h4 class="fw-bold mb-0" id="dynamic-title">
                                            <?php echo e($letterheadCategory->first()->name ?? ''); ?> Header Footer
                                        </h4>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Content -->
                            <div class="card-body px-0 mx-3">
                                <div class="tab-content" id="permissionTabsContent">
                                    <?php $__currentLoopData = $letterheadCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $setting = $letterheadSettings[$category->id] ?? null;
                                        ?>

                                        <div class="tab-pane fade <?php echo e($index == 0 ? 'show active' : ''); ?>"
                                            id="content-<?php echo e($category->id); ?>" role="tabpanel"
                                            aria-labelledby="tab-<?php echo e($category->id); ?>">

                                            <form id="form-<?php echo e($category->id); ?>"
                                                action="<?php echo e(route('letterhead.store', $category->id)); ?>" method="POST"
                                                enctype="multipart/form-data">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="letterhead_cat_id" value="<?php echo e($category->id); ?>">

                                                <div class="row g-4">
                                                    <!-- Header Image Section -->
                                                    <div class="col-lg-6">
                                                        <div class="form-section">
                                                            <div class="section-header mb-3">
                                                                <h5 class="section-title mb-1">Header Image</h5>
                                                                <p class="section-subtitle text-muted mb-0">
                                                                    <?php echo e($category->name); ?> • Recommended: 2230×300px
                                                                </p>
                                                            </div>

                                                            <div class="upload-area">
                                                                <div
                                                                    class="upload-container border border-2 border-dashed rounded-3 p-4 text-center position-relative bg-light hover-bg-primary-subtle transition-all">
                                                                    <?php if($setting && $setting->print_header): ?>
                                                                        <div class="current-image mb-3"
                                                                            id="preview-<?php echo e($category->id); ?>">
                                                                            <img src="<?php echo e($setting->print_header); ?>"
                                                                                class="img-fluid rounded shadow-sm"
                                                                                style="max-height: 120px; object-fit: cover;">
                                                                        </div>
                                                                        <div class="upload-overlay">
                                                                            <i
                                                                                class="bi bi-cloud-upload fs-4 text-primary mb-2"></i>
                                                                            <p class="mb-1 fw-medium">Click to replace image
                                                                            </p>
                                                                            <small class="text-muted">or drag and
                                                                                drop</small>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="upload-placeholder">
                                                                            <div class="current-image mb-3"
                                                                                id="preview-<?php echo e($category->id); ?>">

                                                                            </div>
                                                                            <i class="bi bi-image fs-1 text-muted mb-3"
                                                                                id="placeholder-logo"></i>
                                                                            <h6 class="fw-medium mb-2">Upload Header Image
                                                                            </h6>
                                                                            <p class="text-muted mb-0">Click to browse or
                                                                                drag
                                                                                and drop</p>
                                                                            <small class="text-muted">PNG, JPG, SVG up to
                                                                                5MB</small>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                    <input type="file"
                                                                        id="print_header_<?php echo e($category->id); ?>"
                                                                        name="print_header" accept="image/*"
                                                                        class="position-absolute top-0 start-0 opacity-0 w-100 h-100 cursor-pointer"
                                                                        accept="image/*">
                                                                </div>

                                                                <div class="upload-info mt-2">
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-info-circle me-1"></i>
                                                                        Optimal dimensions: 2230×300px for best display
                                                                        quality
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Footer Content Section -->
                                                    <div class="col-lg-6">
                                                        <div class="form-section">
                                                            <div class="section-header mb-3">
                                                                <h5 class="section-title mb-1">Footer Content</h5>
                                                                <p class="section-subtitle text-muted mb-0">
                                                                    <?php echo e($category->name); ?> • Rich text editor
                                                                </p>
                                                            </div>

                                                            <div class="editor-section">
                                                                <div
                                                                    class="editor-toolbar bg-white border rounded-top px-3 py-2">
                                                                    <div class="btn-toolbar" role="toolbar">
                                                                        <div class="btn-group btn-group-sm me-2">
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-cmd="bold" title="Bold">
                                                                                <i class="bi bi-type-bold"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-cmd="italic" title="Italic">
                                                                                <i class="bi bi-type-italic"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-cmd="underline" title="Underline">
                                                                                <i class="bi bi-type-underline"></i>
                                                                            </button>
                                                                        </div>
                                                                        <div class="btn-group btn-group-sm">
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-cmd="insertUnorderedList"
                                                                                title="Bullet List">
                                                                                <i class="bi bi-list-ul"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-outline-secondary"
                                                                                data-cmd="insertOrderedList"
                                                                                title="Numbered List">
                                                                                <i class="bi bi-list-ol"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="editor-container">
                                                                    <div id="editor-<?php echo e($category->id); ?>"
                                                                        class="form-control editor-content border-top-0 rounded-0 rounded-bottom"
                                                                        contenteditable="true" spellcheck="true"
                                                                        aria-label="Footer content editor"
                                                                        style="min-height: 150px; max-height: 300px; overflow-y: auto;">
                                                                        <?php echo $setting->print_footer ?? '<p class="text-muted">Start typing your footer content here...</p>'; ?>

                                                                    </div>

                                                                    <textarea name="print_footer" class="d-none" id="footer-input-<?php echo e($category->id); ?>">
                                                                    <?php echo $setting->print_footer ?? ''; ?>

                                                                </textarea>
                                                                </div>

                                                                <div class="editor-footer mt-2">
                                                                    <small class="text-muted">
                                                                        <i class="bi bi-info-circle me-1"></i>
                                                                        This content will appear at the bottom of printed
                                                                        documents
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 text-end">
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        <i class="fa fa-save me-1"></i> Save
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>


                            </div>
                            <!-- End Tab Content -->

                        </div>
                    <?php else: ?>
                        <?php echo $__env->make('admin.setup.letterhead_fallback', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endif; ?>

                </div>
            </div>
        </div>



        <!-- end card -->

    </div>
    <!-- End Content -->
    <?php echo $__env->make('components.modals.letterhead-category-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const editor = document.getElementById('editor');
        const wordCount = document.getElementById('wordCount');
        const charCount = document.getElementById('charCount');
        const lineCount = document.getElementById('lineCount');
        const fileInput = document.getElementById('fileInput');
        const saveBtn = document.getElementById('saveBtn');
        const fontSize = document.getElementById('fontSize');
        const findInput = document.getElementById('findInput');
        const replaceInput = document.getElementById('replaceInput');
        const replaceBtn = document.getElementById('replaceBtn');
        const toggleFull = document.getElementById('toggleFull');

        // Update stats
        function updateStats() {
            const text = editor.innerText || '';
            const words = text.trim().length ? text.trim().split(/\s+/).length : 0;
            const chars = text.replace(/\n/g, '').length;
            const lines = text.split(/\n|\r|<br>/).length || 1;
            wordCount.textContent = 'Words: ' + words;
            charCount.textContent = 'Chars: ' + chars;
            lineCount.textContent = 'Lines: ' + lines;
        }

        editor.addEventListener('input', updateStats);
        editor.addEventListener('paste', e => {
            // paste as plain text
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            document.execCommand('insertText', false, text);
            updateStats();
        });

        // Toolbar commands
        document.querySelectorAll('[data-cmd]').forEach(btn => {
            btn.addEventListener('click', () => {
                const cmd = btn.getAttribute('data-cmd');
                if (cmd === 'new') {
                    if (confirm('Discard current content and start a new document?')) editor.innerHTML = '';
                } else {
                    document.execCommand(cmd);
                }
                updateStats();
            });
        });

        // Font size change
        fontSize.addEventListener('change', () => {
            editor.style.fontSize = fontSize.value;
        });

        // Open file
        fileInput.addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                // try to keep plain text
                const content = reader.result;
                // if it's HTML, put as HTML; else as plain text
                if (file.type === 'text/html' || /<[^>]+>/.test(content)) {
                    editor.innerHTML = content;
                } else {
                    editor.textContent = content;
                }
                updateStats();
            };
            reader.readAsText(file);
            fileInput.value = null;
        });

        // Save file
        saveBtn.addEventListener('click', () => {
            const text = editor.innerText;
            const blob = new Blob([text], {
                type: 'text/plain'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'document.txt';
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(a.href);
        });

        // Find and replace (simple)
        replaceBtn.addEventListener('click', () => {
            const find = findInput.value;
            const replace = replaceInput.value;
            if (!find) return alert('Enter text to find');
            // operate on plain text to avoid HTML issues
            const text = editor.innerText;
            const replaced = text.split(find).join(replace);
            editor.textContent = replaced;
            updateStats();
        });

        // Fullscreen toggle
        toggleFull.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(() => {});
            } else {
                document.exitFullscreen().catch(() => {});
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                e.preventDefault();
                saveBtn.click();
            }
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'f') {
                e.preventDefault();
                findInput.focus();
            }
        });

        // Initialize
        updateStats();
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dynamicTitle = document.getElementById("dynamic-title");
            const tabs = document.querySelectorAll('#permissionTabs a[data-bs-toggle="tab"]');

            tabs.forEach(tab => {
                tab.addEventListener("shown.bs.tab", function(event) {
                    const categoryName = event.target.textContent.trim();
                    dynamicTitle.textContent = categoryName + " Header Footer";
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Select all file inputs with "print_header_" prefix
            document.querySelectorAll('input[type="file"][id^="print_header_"]').forEach(input => {
                input.addEventListener("change", function(event) {
                    const file = event.target.files[0];
                    const categoryId = event.target.id.split("_")[2]; // extract id from input
                    const previewContainer = document.getElementById("preview-" + categoryId);
                    const placeHolderLogo = document.getElementById('placeholder-logo')


                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            placeHolderLogo.classList.add("d-none");
                            previewContainer.innerHTML = `
                            <img src="${e.target.result}"
                                 class="img-fluid rounded shadow-sm"
                                 style="max-height: 120px; object-fit: cover;">
                        `;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        previewContainer.innerHTML = ""; // Clear if no file
                    }

                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // For each form
            document.querySelectorAll('form[id^="form-"]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    const categoryId = form.querySelector('input[name="letterhead_cat_id"]').value;
                    const editor = document.getElementById(`editor-${categoryId}`);
                    const textarea = document.getElementById(`footer-input-${categoryId}`);

                    // Copy editor content to hidden textarea
                    textarea.value = editor.innerHTML;
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');

            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');

                    setTimeout(() => alert.remove(), 500); // remove after fade-out
                }, 3000); // 3 seconds
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminLayout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hims\resources\views/admin/setup/letter_head_foot.blade.php ENDPATH**/ ?>