<!DOCTYPE html>
<html lang="id" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login - Bootstrap 5.3</title>
    <!-- CDN Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CDN Bootstrap Icons (Opsional) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-body-secondary d-flex align-items-center min-vh-100 py-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <!-- Card Login -->
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-sm-5">

                        <!-- Header Form -->
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle fs-1 text-primary"></i>
                            <h3 class="fw-bold mt-2">Selamat Datang</h3>
                            <p class="text-muted small">Silakan masuk ke akun Anda</p>
                        </div>

                        <!-- Form -->
                        <form action="{{ route('login.process') }}" method="POST">
                            @csrf
                            <!-- Input Email -->
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Username" required>
                                <label for="username">Username</label>
                            </div>

                            <!-- Input Password -->
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>

                            <!-- Checkbox & Lupa Password -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check_password">
                                    <label class="form-check-label small" for="check_password">
                                        Tampilkan Password
                                    </label>
                                </div>
                                {{-- <a href="#" class="text-decoration-none small">Lupa Password?</a> --}}
                            </div>

                            <!-- Tombol Submit -->
                            <button class="btn btn-primary w-100 py-2 fw-semibold" type="submit">
                                Masuk
                            </button>
                        </form>

                        <!-- Footer Card -->
                        {{-- <div class="text-center mt-4">
                            <p class="small text-muted mb-0">Belum punya akun? <a href="#"
                                    class="text-decoration-none fw-bold">Daftar</a></p>
                        </div> --}}

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JS Bootstrap 5.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        $(document).on('change', '#check_password', function() {
            $('#password').attr('type', this.checked ? 'text' : 'password');
        });
    </script>
</body>

</html>
