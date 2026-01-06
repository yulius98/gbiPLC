package com.example.plc_mobile

import android.content.ContentValues
import android.os.Build
import android.os.Environment
import android.provider.MediaStore
import io.flutter.embedding.android.FlutterActivity
import io.flutter.embedding.engine.FlutterEngine
import io.flutter.plugin.common.MethodChannel
import java.lang.Exception

class MainActivity : FlutterActivity() {
	private val CHANNEL = "plc_mobile/download"

	override fun configureFlutterEngine(flutterEngine: FlutterEngine) {
		super.configureFlutterEngine(flutterEngine)
		// Plugins are registered automatically by the FlutterActivity (v2 embedding).
		// Keep MethodChannel registration here for platform calls.

		MethodChannel(flutterEngine.dartExecutor.binaryMessenger, CHANNEL).setMethodCallHandler { call, result ->
			when (call.method) {
				"saveFile" -> {
					val fileName = call.argument<String>("fileName")
					val bytes = call.argument<ByteArray>("bytes")
					if (fileName == null || bytes == null) {
						result.error("INVALID_ARGS", "fileName or bytes missing", null)
						return@setMethodCallHandler
					}

					try {
						val resolver = applicationContext.contentResolver
						val values = ContentValues().apply {
							put(MediaStore.MediaColumns.DISPLAY_NAME, fileName)
							put(MediaStore.MediaColumns.MIME_TYPE, "application/pdf")
							// Use relative path so file appears in Downloads on Android Q+
							if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.Q) {
								put(MediaStore.MediaColumns.RELATIVE_PATH, Environment.DIRECTORY_DOWNLOADS)
							}
						}

						val collection = if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.Q) {
							MediaStore.Downloads.getContentUri(MediaStore.VOLUME_EXTERNAL_PRIMARY)
						} else {
							MediaStore.Files.getContentUri("external")
						}

						val uri = resolver.insert(collection, values)
						if (uri == null) {
							throw Exception("Failed to create MediaStore entry")
						}

						resolver.openOutputStream(uri).use { os ->
							if (os == null) throw Exception("Unable to open output stream")
							os.write(bytes)
							os.flush()
						}

						result.success(uri.toString())
					} catch (e: Exception) {
						// Try a fallback for older Android versions: write directly to public Downloads
						try {
							if (Build.VERSION.SDK_INT < Build.VERSION_CODES.Q) {
								val downloadsDir = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOWNLOADS)
								val outFile = java.io.File(downloadsDir, fileName)
								outFile.outputStream().use { it.write(bytes) }
								val fallbackUri = android.net.Uri.fromFile(outFile)
								result.success(fallbackUri.toString())
								return@setMethodCallHandler
							}
						} catch (fallbackEx: Exception) {
							// ignore and return original error below
						}

						// Return detailed error message to Dart for diagnostics
						val message = e.localizedMessage ?: e.toString()
						result.error("ERROR", message, null)
					}
				}
				else -> result.notImplemented()
			}
		}
	}
}
