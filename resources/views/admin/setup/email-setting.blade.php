@extends('layouts.adminLayout')
@section('content')
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

                    <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                        <div class="card-header border-bottom px-0 mx-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <h5 class="fw-bold">Email Settings</h5>
                                {{-- <a href="javascript:void(0);" class="btn btn-primary"><i class="ti ti-send me-1"></i>Send Test Mail</a> --}}
                            </div>
                        </div>
                        <div class="card-body px-0 mx-3">
                            <form action="{{route('email-setting-save')}}" method="post">
                                @csrf
                                @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{-- For validation errors --}}
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                            <!-- start row -->
                        <div class="row">

                            <div class="col-md-6">
                                <label for="phpMailer">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <span class="avatar avatar-lg p-2 bg-light rounded flex-shrink-0 me-2"><img src="assets/img/icons/php-mailer.svg" alt="Img"></span>
                                            <div>
                                                <p class="fw-medium text-dark mb-1">PHP Mailer</p>
                                                <p class="mb-0">Used to send emails safely and easily via PHP code from a web server.</p>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-radio-input m-0" id="phpMailer" value="php_mailer" type="radio" name="email_type" {{ old('type', $emailConfig->email_type ?? '') == 'php_mailer' ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                      
                                    </div>
                                     <!-- end card footer -->
                                </div> <!-- end card -->
                            </label>
                            </div> <!-- end col -->

                            <div class="col-md-6">
                                <label for="smtp">
                                <div class="card shadow-none">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <span class="avatar avatar-lg p-2 bg-light rounded flex-shrink-0 me-2"><img src="assets/img/icons/smtp.svg" alt="Img"></span>
                                            <div>
                                                <p class="fw-medium text-dark mb-1">SMTP</p>
                                                <p class="mb-0">SMTP is used to send, relay or forward messages from a mail client.</p>
                                            </div>
                                            <div class="form-check form-switch">
                                                <input class="form-radio-input m-0"  id="smtp" type="radio" value="smtp" name="email_type" {{ old('type', $emailConfig->email_type ?? '') == 'smtp' ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                      
                                    </div> <!-- end card body -->
                                    <!-- end card footer -->
                                </div> <!-- end card -->
                            </label>
                            </div> <!-- end col -->
                        </div>
                        <div id="smtpFields" class="mt-3" style="display: none;">
                            <div class="mb-3">
                                <label for="smtp_server">SMTP Server</label>
                                <input type="text" class="form-control" name="smtp_server" id="smtp_server"
                                       value="{{ old('smtp_server', $emailConfig->smtp_server ?? '') }}">
                            </div>
                        
                            <div class="mb-3">
                                <label for="smtp_port">SMTP Port</label>
                                <input type="number" class="form-control" name="smtp_port" id="smtp_port"
                                       value="{{ old('smtp_port', $emailConfig->smtp_port ?? '') }}">
                            </div>
                        
                            <div class="mb-3">
                                <label for="smtp_username">SMTP Username</label>
                                <input type="text" class="form-control" name="smtp_username" id="smtp_username"
                                       value="{{ old('smtp_username', $emailConfig->smtp_username ?? '') }}">
                            </div>
                        
                            <div class="mb-3">
                                <label for="smtp_password">SMTP Password</label>
                                <input type="password" class="form-control" name="smtp_password" id="smtp_password"
                                       value="{{ old('smtp_password', $emailConfig->smtp_password ?? '') }}">
                            </div>
                        
                            <div class="mb-3">
                                <label for="ssl_tls">Encryption</label>
                                <select class="form-select" name="ssl_tls" id="ssl_tls">
                                    <option value="" {{ old('ssl_tls', $emailConfig->ssl_tls ?? '') == '' ? 'selected' : '' }}>None</option>
                                    <option value="ssl" {{ old('ssl_tls', $emailConfig->ssl_tls ?? '') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                    <option value="tls" {{ old('ssl_tls', $emailConfig->ssl_tls ?? '') == 'tls' ? 'selected' : '' }}>TLS</option>
                                </select>
                            </div>
                        
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="smtp_auth" id="smtp_auth" value="1"
                                    {{ old('smtp_auth', $emailConfig->smtp_auth ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="smtp_auth">SMTP Auth</label>
                            </div>
                        </div>
                        
                        <button class="btn btn-primary" type="submit">Save</button>
                        <!-- end row -->
                    </form>
                        </div>
                    </div>
                </div>

            </div><!-- end card body -->
        </div><!-- end card -->
                        
    </div>
    <!-- End Content -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeRadios = document.querySelectorAll('input[name="email_type"]');
        const smtpFields = document.getElementById('smtpFields');

        function toggleFields() {
            const selected = document.querySelector('input[name="email_type"]:checked').value;
            if (selected === 'smtp') {
                smtpFields.style.display = 'block';
            } else {
                smtpFields.style.display = 'none';
            }
        }

        // Initial toggle on load
        toggleFields();

        // Add change listeners
        typeRadios.forEach(radio => {
            radio.addEventListener('change', toggleFields);
        });
    });
</script>
@endsection