/**
 * Chunk Upload Manager untuk file besar
 * Menggunakan Resumable.js
 */

let currentUploadMethod = 'normal'; // 'normal' or 'chunked'
let resumable;

// Toggle between normal and chunked upload
function toggleUploadMethod() {
    currentUploadMethod = currentUploadMethod === 'normal' ? 'chunked' : 'normal';
    
    if (currentUploadMethod === 'chunked') {
        document.getElementById('normal-upload-container').style.display = 'none';
        document.getElementById('chunked-upload-container').style.display = 'block';
        document.getElementById('toggle-text').textContent = 'Gunakan Upload Biasa';
        initResumable();
    } else {
        document.getElementById('normal-upload-container').style.display = 'block';
        document.getElementById('chunked-upload-container').style.display = 'none';
        document.getElementById('toggle-text').textContent = 'Gunakan Upload Cepat';
    }
}

// Handle file select untuk upload biasa
function handleFileSelect(input) {
    if (input.files.length > 0) {
        const file = input.files[0];
        const fileSize = file.size;
        const fileName = file.name;
        
        // Jika file lebih dari 50MB, sarankan pakai chunked upload
        if (fileSize > 50 * 1024 * 1024) {
            if (confirm('File yang Anda pilih berukuran ' + formatFileSize(fileSize) + '. \n\nUntuk file besar, disarankan menggunakan "Upload Cepat" untuk upload yang lebih stabil dan cepat.\n\nApakah Anda ingin beralih ke Upload Cepat?')) {
                input.value = ''; // Clear file input
                toggleUploadMethod();
            }
        } else {
            document.getElementById('label-kotbah').innerText = fileName;
        }
    }
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Initialize Resumable.js untuk chunked upload
function initResumable() {
    if (resumable) return; // Sudah diinisialisasi
    
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    if (!csrfToken) {
        console.error('CSRF token not found in meta tag');
        alert('Error: CSRF token tidak ditemukan. Silakan refresh halaman.');
        return;
    }
    
    console.log('Initializing Resumable.js with CSRF token:', csrfToken);
    
    resumable = new Resumable({
        target: window.chunkUploadRoute || '/pengurus/chunk-upload',
        query: {
            _token: csrfToken
        },
        fileType: ['pdf', 'ppt', 'pptx'],
        maxFiles: 1,
        chunkSize: 2 * 1024 * 1024, // 2MB per chunk untuk kecepatan optimal
        simultaneousUploads: 4, // Upload 4 chunk sekaligus untuk kecepatan maksimal
        testChunks: true, // Cek chunk yang sudah ada (untuk resume upload)
        throttleProgressCallbacks: 1,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        // Tambahan konfigurasi untuk cPanel
        maxFileSize: 2 * 1024 * 1024 * 1024, // 2GB
        fileParameterName: 'file', // Pastikan nama parameter konsisten
        generateUniqueIdentifier: function(file) {
            var relativePath = file.relativePath || file.webkitRelativePath || file.fileName || file.name;
            return file.size + '-' + relativePath.replace(/[^0-9a-zA-Z_-]/img, '');
        }
    });
    
    // Assign browse button
    resumable.assignBrowse(document.getElementById('browseButton'));
    
    // Event: File added
    resumable.on('fileAdded', function (file) {
        console.log('File added:', file.fileName, 'Size:', file.size);
        console.log('File unique identifier:', file.uniqueIdentifier);
        
        document.getElementById('upload-progress-container').style.display = 'block';
        document.getElementById('upload-success').style.display = 'none';
        document.getElementById('upload-status').textContent = 'Memulai upload: ' + file.fileName + ' (' + formatFileSize(file.size) + ')';
        
        // Reset progress bar
        const progressBar = document.getElementById('upload-progress-bar');
        progressBar.style.width = '0%';
        progressBar.textContent = '0%';
        progressBar.className = 'progress-bar progress-bar-striped progress-bar-animated';
        
        // Mulai upload otomatis
        setTimeout(function() {
            console.log('Starting upload...');
            resumable.upload();
        }, 100);
    });
    
    // Event: Upload progress
    resumable.on('fileProgress', function (file) {
        const progress = Math.floor(file.progress() * 100);
        document.getElementById('upload-progress-bar').style.width = progress + '%';
        document.getElementById('upload-progress-bar').textContent = progress + '%';
        document.getElementById('upload-progress-bar').setAttribute('aria-valuenow', progress);
        
        const uploaded = formatFileSize(file.size * file.progress());
        const total = formatFileSize(file.size);
        const speed = calculateUploadSpeed(file);
        
        document.getElementById('upload-status').textContent = 
            'Uploading: ' + uploaded + ' / ' + total + ' (' + progress + '%) - ' + speed;
    });
    
    // Event: Upload success
    resumable.on('fileSuccess', function (file, message) {
        console.log('File uploaded successfully:', message);
        
        try {
            const response = JSON.parse(message);
            
            if (response.status === 'success') {
                const progressBar = document.getElementById('upload-progress-bar');
                progressBar.classList.remove('progress-bar-animated');
                progressBar.classList.add('bg-success');
                document.getElementById('upload-status').textContent = 'Upload selesai!';
                document.getElementById('upload-success').style.display = 'block';
                document.getElementById('uploaded-filename').textContent = response.original_name;
                
                // Kirim path file ke Livewire component menggunakan Livewire
                if (window.Livewire) {
                    window.Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'))
                        .call('handleFileUploaded', response.filename);
                }
                
                // Reset resumable untuk upload berikutnya
                setTimeout(() => {
                    resumable.cancel();
                    document.getElementById('upload-progress-container').style.display = 'none';
                }, 3000);
            }
        } catch (e) {
            console.error('Error parsing response:', e);
            document.getElementById('upload-status').textContent = 'Error: ' + e.message;
        }
    });
    
    // Event: Upload error
    resumable.on('fileError', function (file, message) {
        console.error('Upload error:', message);
        console.error('File details:', {
            name: file.fileName,
            size: file.size,
            chunks: file.chunks.length
        });
        
        const progressBar = document.getElementById('upload-progress-bar');
        progressBar.classList.remove('progress-bar-animated');
        progressBar.classList.add('bg-danger');
        
        // Parse error message jika berupa JSON
        let errorMessage = message;
        try {
            const errorObj = JSON.parse(message);
            errorMessage = errorObj.message || message;
        } catch (e) {
            // Bukan JSON, gunakan message asli
        }
        
        document.getElementById('upload-status').textContent = 'Upload gagal:\n' + errorMessage;
        
        alert('Upload gagal:\n' + errorMessage + '\n\nSilakan coba lagi');
    });
    
    // Event: Upload complete
    resumable.on('complete', function () {
        console.log('Upload complete');
    });
}

// Calculate upload speed
let lastProgressTime = Date.now();
let lastProgressSize = 0;

function calculateUploadSpeed(file) {
    const currentTime = Date.now();
    const currentSize = file.size * file.progress();
    const timeDiff = (currentTime - lastProgressTime) / 1000; // seconds
    const sizeDiff = currentSize - lastProgressSize; // bytes
    
    if (timeDiff > 0) {
        const speed = sizeDiff / timeDiff; // bytes per second
        lastProgressTime = currentTime;
        lastProgressSize = currentSize;
        
        return formatFileSize(speed) + '/s';
    }
    
    return '0 B/s';
}

// Auto-hide flash messages
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const flashMessage = document.getElementById('flash-message');
        const flashError = document.getElementById('flash-error');
        if (flashMessage) flashMessage.style.display = 'none';
        if (flashError) flashError.style.display = 'none';
    }, 5000);
});
