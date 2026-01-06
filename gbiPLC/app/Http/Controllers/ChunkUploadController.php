<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ChunkUploadController extends Controller
{
    /**
     * Handle chunk upload untuk file besar
     * Menggunakan protokol Resumable.js
     */
    public function upload(Request $request)
    {
        try {
            // Log semua request untuk debugging
            Log::info('Chunk upload request received', [
                'method' => $request->method(),
                'all_inputs' => $request->all(),
                'has_file' => $request->hasFile('file'),
                'files' => $request->allFiles(),
            ]);
            
            // Get request parameters
            $resumableIdentifier = $request->input('resumableIdentifier');
            $resumableFilename = $request->input('resumableFilename');
            $resumableChunkNumber = $request->input('resumableChunkNumber');
            $resumableTotalChunks = $request->input('resumableTotalChunks');
            $resumableChunkSize = $request->input('resumableChunkSize');
            
            // Validasi file type
            $extension = pathinfo($resumableFilename, PATHINFO_EXTENSION);
            $allowedExtensions = ['pdf', 'ppt', 'pptx'];
            
            if (!in_array(strtolower($extension), $allowedExtensions)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File harus berupa PDF atau PowerPoint (ppt, pptx)'
                ], 400);
            }

            // Tentukan direktori penyimpanan chunk
            $chunkDir = storage_path('app/chunks/' . $resumableIdentifier);
            
            // Buat direktori jika belum ada
            if (!file_exists($chunkDir)) {
                mkdir($chunkDir, 0755, true);
            }

            // Path untuk chunk ini
            $chunkFile = $chunkDir . '/chunk_' . $resumableChunkNumber;

            // Jika request GET, cek apakah chunk sudah ada (untuk resume upload)
            if ($request->isMethod('get')) {
                if (file_exists($chunkFile)) {
                    return response('', 200);
                } else {
                    return response('', 204); // Chunk belum ada
                }
            }

            // Handle POST request - simpan chunk
            if (!$request->hasFile('file')) {
                Log::error('No file in request', [
                    'method' => $request->method(),
                    'content_type' => $request->header('Content-Type'),
                    'all_files' => $request->allFiles(),
                    'inputs' => $request->all()
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'No file found in request. Please check your upload configuration.'
                ], 400);
            }
            
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $file->move($chunkDir, 'chunk_' . $resumableChunkNumber);
                
                Log::info("Chunk uploaded", [
                    'chunk' => $resumableChunkNumber,
                    'total' => $resumableTotalChunks,
                    'identifier' => $resumableIdentifier
                ]);

                // Cek apakah semua chunk sudah terupload
                $allChunksUploaded = true;
                for ($i = 1; $i <= $resumableTotalChunks; $i++) {
                    if (!file_exists($chunkDir . '/chunk_' . $i)) {
                        $allChunksUploaded = false;
                        break;
                    }
                }

                // Jika semua chunk sudah terupload, gabungkan mereka
                if ($allChunksUploaded) {
                    $finalFilename = 'materi-kotbah/' . Str::random(40) . '.' . $extension;
                    $finalPath = storage_path('app/public/' . $finalFilename);
                    
                    // Buat direktori jika belum ada
                    $finalDir = dirname($finalPath);
                    if (!file_exists($finalDir)) {
                        mkdir($finalDir, 0755, true);
                    }

                    // Gabungkan semua chunk
                    $finalFile = fopen($finalPath, 'wb');
                    for ($i = 1; $i <= $resumableTotalChunks; $i++) {
                        $chunkPath = $chunkDir . '/chunk_' . $i;
                        $chunk = fopen($chunkPath, 'rb');
                        stream_copy_to_stream($chunk, $finalFile);
                        fclose($chunk);
                    }
                    fclose($finalFile);

                    // Hapus chunk files dan direktori
                    for ($i = 1; $i <= $resumableTotalChunks; $i++) {
                        @unlink($chunkDir . '/chunk_' . $i);
                    }
                    @rmdir($chunkDir);

                    Log::info("File successfully merged", [
                        'filename' => $finalFilename,
                        'size' => filesize($finalPath)
                    ]);

                    return response()->json([
                        'status' => 'success',
                        'message' => 'File uploaded successfully',
                        'filename' => $finalFilename,
                        'original_name' => $resumableFilename
                    ], 200);
                }

                return response()->json([
                    'status' => 'chunk_uploaded',
                    'chunk' => $resumableChunkNumber
                ], 200);
            }
            
            // Jika sampai di sini berarti tidak ada file yang ditemukan
            Log::error('Reached end of upload function without file', [
                'has_file' => $request->hasFile('file'),
                'method' => $request->method()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'No file found'
            ], 400);

        } catch (\Exception $e) {
            Log::error("Chunk upload error", [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bersihkan chunk yang tidak selesai (optional)
     */
    public function cleanup(Request $request)
    {
        $identifier = $request->input('identifier');
        
        if ($identifier) {
            $chunkDir = storage_path('app/chunks/' . $identifier);
            if (file_exists($chunkDir)) {
                // Hapus semua file dalam direktori
                $files = glob($chunkDir . '/*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        unlink($file);
                    }
                }
                rmdir($chunkDir);
                
                return response()->json(['status' => 'success', 'message' => 'Chunks cleaned up']);
            }
        }
        
        return response()->json(['status' => 'error', 'message' => 'No identifier provided'], 400);
    }
}
