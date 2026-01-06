<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="GBI Philadelphia Life Center - Admin Panel" />
    <meta name="author" content="GBI PLC" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" as="style">
    
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/responsive-sidebar.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-layout.css') }}" rel="stylesheet">
    
    <title>{{ $title ?? 'Admin Panel' }} - GBI PLC</title>
    
    <!-- Favicon with cache buster -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="icon" type="image/png" href="{{ asset('logoplc.png') }}?v={{ time() }}">
    
    <!-- Additional head content -->
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <div class="sticky-top">
        <x-nav-bar-admin />
    </div>

    <!-- Main Layout Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Column -->
            <div class="col-lg-3 col-xl-2 px-0 d-none d-lg-block">
                <div class="position-sticky" style="top: 76px; height: calc(100vh - 76px); overflow-y: auto;">
                    <x-side-nav-bar-admin />
                </div>
            </div>
            
            <!-- Main Content Column -->
            <div class="col-12 col-lg-9 col-xl-10">
                <!-- Mobile Sidebar (only visible on mobile) -->
                <div class="d-lg-none">
                    <x-side-nav-bar-admin />
                </div>
                
                <!-- Page Content -->
                <main class="py-4">
                    <div class="container-fluid px-3 px-md-4">
                        {{ $slot }}
                    </div>
                </main>
                
                <!-- Footer -->
                <footer class="py-3 bg-light border-top mt-5">
                    <div class="container-fluid px-3 px-md-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="mb-0 text-muted small">
                                    &copy; {{ date('Y') }} GBI Philadelphia Life Center. All rights reserved.
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-0 text-muted small">
                                    Version 1.0.0
                                </p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <!-- JavaScript Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script src="{{ asset('js/scripts.js') }}"></script>
    
    <!-- Custom JavaScript -->
    <script src="{{ asset('js/mobile-nav.js') }}"></script>
    
    <!-- Optional Scripts (only if needed for specific pages) -->
    <script>
        // Initialize DataTables if the library is loaded and tables exist
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof simpleDatatables !== 'undefined') {
                const datatables = document.querySelectorAll('.datatable');
                datatables.forEach(table => {
                    new simpleDatatables.DataTable(table);
                });
            }
        });
    </script>
    
    <!-- Additional scripts -->
    @stack('scripts')
</body>
</html>




