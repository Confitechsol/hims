@extends('layouts.adminLayout')
@section('content')


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
            background: transparent;
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
    </style>

    <!-- Start Content -->
    <div class="content" id="profilePage">

        <!-- Page Header -->
        <div class="mb-3 border-bottom pb-3">
            <h4 class="fw-bold mb-0">Settings</h4>
        </div>
        <!-- End Page Header -->


        <div class="card">
            <div class="card-body p-0">
                <div class="settings-wrapper d-flex">

                    <!-- Sidebar -->
                    <div class="sidebars settings-sidebar" id="sidebar2">
                        <div class="sidebar-inner" data-simplebar>
                            <div id="sidebar-menu5" class="sidebar-menu mt-0 p-0">
                                <ul class="nav flex-column" id="permissionTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="dashboard-tab" data-bs-toggle="tab" href="#dashboard"
                                            role="tab" aria-controls="dashboard" aria-selected="true">
                                            <i class="ti ti-device-desktop-cog me-2"></i> Appointment
                                        </a>
                                    </li>
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
                                    <h4 class="fw-bold mb-0">Appointment Header Footer</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Content -->
                        <div class="card-body px-0 mx-3">
                            <form id="permissionsForm" method="POST" action="">
                                @csrf

                                <div class="tab-content" id="permissionTabsContent">
                                    <div class="row">
                                        <div class="col-md-6">`
                                            <div class="header_txt pb-2">
                                                <h6>Header Image(2230px X 300px)</h6>
                                            </div>
                                            <div class="header_file">
                                                <div class="rounded position-relative p-3 mb-3 text-center">
                                                    <span class="avatar avatar-sm bg-primary text-white mb-2">
                                                        <i class="ti ti-upload fs-16"></i>
                                                    </span>
                                                    <h6 class="mb-2">Drop files here</h6>
                                                    <p class="fs-13 mb-0">Select files to upload</p>
                                                    <input type="file"
                                                        class="position-absolute top-0 start-0 opacity-0 w-100 h-100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">`
                                            <div class="footer_txt pb-2">
                                                <h6>Footer Content</h6>
                                            </div>
                                            <div class="editor-wrap">
                                                <div class="editor_inner">
                                                    <div class="toolbar">
                                                        <button class="btn" data-cmd="new">New</button>
                                                        <label class="btn" title="Open file">
                                                            Open
                                                            <input id="fileInput" type="file"
                                                                accept="text/plain, .txt, .html" />
                                                        </label>
                                                        <button class="btn" id="saveBtn">Save</button>
                                                        <button class="btn" data-cmd="bold"><b>B</b></button>
                                                        <button class="btn" data-cmd="italic"><i>I</i></button>
                                                        <button class="btn" data-cmd="underline"><u>U</u></button>
                                                        <select id="fontSize" title="Font size">
                                                            <option value="14px">14</option>
                                                            <option value="16px" selected>16</option>
                                                            <option value="18px">18</option>
                                                            <option value="20px">20</option>
                                                            <option value="24px">24</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div id="editor" class="editor h-auto" contenteditable="true" spellcheck="true"
                                                    aria-label="Text editor">Start typing here...</div>

                                                <div class="status">
                                                    <div class="small" id="charCount">Chars: 0</div>
                                                    <div class="small" id="lineCount">Lines: 1</div>
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
                        <!-- End Tab Content -->

                    </div>
                </div>
            </div>
        </div>



        <!-- end card -->

    </div>
    <!-- End Content -->

    {{-- Optional JS for "Select All" functionality --}}
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
            const blob = new Blob([text], { type: 'text/plain' });
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
                document.documentElement.requestFullscreen().catch(() => { });
            } else {
                document.exitFullscreen().catch(() => { });
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


@endsection