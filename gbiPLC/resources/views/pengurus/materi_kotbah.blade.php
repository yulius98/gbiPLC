<!DOCTYPE html>
<html lang="en">
    <head>
        
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <title>GBI PLC</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('logoplc.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('logoplc.png') }}">
        
    </head>
    <body class="sb-nav-fixed">
        <x-nav-bar-admin/>
        
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <x-side-nav-bar-admin/>
            </div>
            <div id="layoutSidenav_content" >
                <main>
                    <div id="content-area" class="mt-5 container">
                        @livewire('materi-kotbah')                        
                        
                       
                    </div>
                </main>
             

                
            </div>
        </div>
        {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        @vite('resources/js/app.js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        {{-- <script src="assets/demo/chart-area-demo.js"></script> --}}
        {{-- <script src="assets/demo/chart-bar-demo.js"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        {{-- <script src="js/datatables-simple-demo.js"></script> --}}
        
        <!-- Resumable.js untuk chunk upload -->
        <script src="https://cdn.jsdelivr.net/npm/resumablejs@1.1.0/resumable.min.js"></script>
        <script>
            // Set route untuk chunk upload
            window.chunkUploadRoute = "{{ route('chunk.upload') }}";
        </script>
        <script src="{{ asset('js/chunk-upload.js') }}"></script>
    </body>
</html>




