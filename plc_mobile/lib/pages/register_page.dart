import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:plc_mobile/services/register_service.dart';
import 'dart:io';
import 'package:image_picker/image_picker.dart';
import 'dart:ui';
import 'package:image/image.dart' as img;
import 'package:path_provider/path_provider.dart';

class RegisterPage extends StatefulWidget {
  const RegisterPage({super.key});

  @override
  State<RegisterPage> createState() => _RegisterPageState();
}

class _RegisterPageState extends State<RegisterPage> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _nameController = TextEditingController();
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _alamatController = TextEditingController();
  final TextEditingController _tglLahirController = TextEditingController();
  final TextEditingController _noHpController = TextEditingController();
  final TextEditingController _instagramController = TextEditingController();
  final TextEditingController _facebookController = TextEditingController();
  String? _golDarah;
  File? _imageFile;
  bool _isLoading = false;

  Future<void> _pickDate() async {
    DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      _tglLahirController.text = DateFormat('yyyy-MM-dd').format(picked);
    }
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() {
        _imageFile = File(pickedFile.path);
      });
    }
  }

  Future<void> _pickImageFromCamera() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(
      source: ImageSource.camera,
      preferredCameraDevice: CameraDevice.front, // Menggunakan kamera depan
    );
    if (pickedFile != null) {
      final originalFile = File(pickedFile.path);
      final imageBytes = await originalFile.readAsBytes();
      final decodedImage = img.decodeImage(imageBytes);

      if (decodedImage != null) {
        // Resize image to max width 800px to reduce size
        int maxWidth = 800;
        int newWidth = decodedImage.width > maxWidth ? maxWidth : decodedImage.width;
        int newHeight = (decodedImage.height * newWidth / decodedImage.width).round();
        final resizedImage = img.copyResize(decodedImage, width: newWidth, height: newHeight);

        // Kompresi dan konversi ke JPEG dengan quality 85
        var compressedImage = img.encodeJpg(resizedImage, quality: 85);

        // Penamaan file sesuai nama lengkap
        final fileName = '${_nameController.text.replaceAll(' ', '_')}.jpeg';
        final directory = await getTemporaryDirectory();
        final compressedFile = File('${directory.path}/$fileName');

        await compressedFile.writeAsBytes(compressedImage);

        // Validasi ukuran file < 1MB
        if (compressedFile.lengthSync() < 1024 * 1024) {
          setState(() {
            _imageFile = compressedFile;
          });
        } else {
          // Coba kompresi dengan quality 60
          compressedImage = img.encodeJpg(resizedImage, quality: 60);
          await compressedFile.writeAsBytes(compressedImage);

          if (compressedFile.lengthSync() < 1024 * 1024) {
            setState(() {
              _imageFile = compressedFile;
            });
          } else {
            // Resize lagi ke width 600 dan kompresi quality 60
            newWidth = 600;
            newHeight = (decodedImage.height * newWidth / decodedImage.width).round();
            final resizedImage2 = img.copyResize(decodedImage, width: newWidth, height: newHeight);
            compressedImage = img.encodeJpg(resizedImage2, quality: 60);
            await compressedFile.writeAsBytes(compressedImage);

            if (compressedFile.lengthSync() < 1024 * 1024) {
              setState(() {
                _imageFile = compressedFile;
              });
            } else {
              // Hapus file yang tidak valid
              if (compressedFile.existsSync()) {
                compressedFile.deleteSync();
              }
              setState(() {
                _imageFile = null;
              });
              if (mounted) {
                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(content: Text('Ukuran file terlalu besar (> 1MB).')),
                );
              }
            }
          }
        }
      }
    }
  }

  Future<void> _showImagePickerOptions() async {
    showModalBottomSheet(
      context: context,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (BuildContext context) {
        return SafeArea(
          child: Padding(
            padding: const EdgeInsets.all(16.0),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(
                  'Pilih Sumber Foto',
                  style: TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 16),
                ListTile(
                  leading: Icon(Icons.camera_alt, color: Color(0xFF485563)),
                  title: Text('Kamera Depan'),
                  onTap: () {
                    Navigator.pop(context);
                    _pickImageFromCamera();
                  },
                ),
                ListTile(
                  leading: Icon(Icons.photo_library, color: Color(0xFF485563)),
                  title: Text('Galeri'),
                  onTap: () {
                    Navigator.pop(context);
                    _pickImage();
                  },
                ),
              ],
            ),
          ),
        );
      },
    );
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() { _isLoading = true; });
    try {
      final response = await registerUser(
        name: _nameController.text,
        email: _emailController.text,
        alamat: _alamatController.text,
        tglLahir: _tglLahirController.text,
        noHP: _noHpController.text,
        golDarah: _golDarah ?? '',
        instagram: _instagramController.text.isNotEmpty ? _instagramController.text : null,
        facebook: _facebookController.text.isNotEmpty ? _facebookController.text : null,
        imageFile: _imageFile,
      );
      if (response.statusCode == 200) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Registrasi berhasil!')));
          Navigator.pop(context);
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Registrasi gagal.')));
        }
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Error: $e')));
      }
    } finally {
      if (mounted) {
        setState(() { _isLoading = false; });
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      extendBodyBehindAppBar: true,
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        foregroundColor: Colors.white,
      ),
      body: Container(
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Color(0xFF0f2027), // deep blue
              Color(0xFF2c5364), // blue-grey
              Color(0xFF6a3093), // royal purple
              Color(0xFFa044ff), // purple-gold
              Color(0xFFf7971e), // gold
            ],
          ),
        ),
        child: Center(
          child: SingleChildScrollView(
            child: Padding(
              padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 32),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(24),
                child: Stack(
                  alignment: Alignment.center,
                  children: [
                    // Glassmorphism effect
                    BackdropFilter(
                      filter: ImageFilter.blur(sigmaX: 18, sigmaY: 18),
                      child: Container(
                        width: double.infinity,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(24),
                          gradient: LinearGradient(
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                            colors: [
                              Colors.white.withValues(alpha: 0.55),
                              Color(0xFFf8fafc).withValues(alpha: 0.35),
                              Color(0xFFe0c3fc).withValues(alpha: 0.25),
                            ],
                          ),
                          border: Border.all(
                            color: Color(0xFFa044ff).withValues(alpha: 0.25),
                            width: 2.2,
                          ),
                          boxShadow: [
                            BoxShadow(
                              color: Color(0xFFa044ff).withValues(alpha: 0.10),
                              blurRadius: 24,
                              offset: Offset(0, 8),
                            ),
                          ],
                        ),
                      ),
                    ),
                    // Formulir di atas efek glass
                    Padding(
                      padding: const EdgeInsets.all(24),
                      child: Form(
                        key: _formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.stretch,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            Text(
                              'Formulir Pendaftaran Jemaat',
                              style: TextStyle(
                                fontSize: 22,
                                fontWeight: FontWeight.bold,
                                color: Color.fromARGB(255, 237, 239, 240),
                                letterSpacing: 1.2,
                              ),
                              textAlign: TextAlign.center,
                            ),
                            const SizedBox(height: 24),
                            _buildTextField(_nameController, 'Nama Lengkap', Icons.person),
                            const SizedBox(height: 14),
                            _buildTextField(_emailController, 'Email', Icons.email),
                            const SizedBox(height: 14),
                            _buildTextField(_alamatController, 'Alamat', Icons.home),
                            const SizedBox(height: 14),
                            GestureDetector(
                              onTap: _pickDate,
                              child: AbsorbPointer(
                                child: _buildTextField(
                                  _tglLahirController,
                                  'Tanggal Lahir',
                                  Icons.cake,
                                  suffixIcon: Icon(Icons.calendar_today, color: Colors.grey[600]),
                                ),
                              ),
                            ),
                            const SizedBox(height: 14),
                            _buildTextField(_noHpController, 'No HP', Icons.phone),
                            const SizedBox(height: 14),
                            DropdownButtonFormField<String>(
                              initialValue: _golDarah,
                              items: ['A', 'B', 'AB', 'O']
                                  .map((g) => DropdownMenuItem(value: g, child: Text(g)))
                                  .toList(),
                              onChanged: (v) => setState(() => _golDarah = v),
                              decoration: InputDecoration(
                                labelText: 'Golongan Darah',
                                prefixIcon: Icon(Icons.bloodtype, color: Colors.red[700]),
                                border: OutlineInputBorder(
                                  borderRadius: BorderRadius.circular(12),
                                ),
                                filled: true,
                                fillColor: Colors.grey[100],
                              ),
                              validator: (v) => v == null || v.isEmpty ? 'Wajib dipilih' : null,
                            ),
                            const SizedBox(height: 14),
                            _buildTextField(
                              _instagramController,
                              'Instagram',
                              Icons.camera_alt,
                            ),
                            const SizedBox(height: 14),
                            _buildTextField(
                              _facebookController,
                              'Facebook',
                              Icons.facebook,
                            ),
                            const SizedBox(height: 18),
                            Row(
                              children: [
                                ElevatedButton.icon(
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: Color(0xFF232526),
                                    foregroundColor: Colors.white,
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(12),
                                    ),
                                    elevation: 4,
                                  ),
                                  onPressed: _showImagePickerOptions,
                                  icon: const Icon(Icons.photo),
                                  label: const Text('Pilih Foto'),
                                ),
                                const SizedBox(width: 10),
                                Expanded(
                                  child: Row(
                                    children: [
                                      _imageFile != null
                                          ? const Icon(Icons.check_circle, color: Colors.green)
                                          : const Icon(Icons.cancel, color: Colors.grey),
                                      const SizedBox(width: 6),
                                      Flexible(
                                        child: Text(
                                          _imageFile != null ? 'Foto dipilih' : 'Belum ada foto',
                                          style: TextStyle(
                                            color: _imageFile != null ? Colors.green : Colors.grey[600],
                                            fontWeight: FontWeight.w500,
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                            const SizedBox(height: 28),
                            Container(
                              width: double.infinity,
                              height: 48,
                              decoration: BoxDecoration(
                                gradient: const LinearGradient(
                                  colors: [Color(0xFF232526), Color(0xFF485563)],
                                  begin: Alignment.topLeft,
                                  end: Alignment.bottomRight,
                                ),
                                borderRadius: BorderRadius.circular(16),
                                boxShadow: [
                                  BoxShadow(
                                    color: Colors.black.withValues(alpha: 0.15),
                                    blurRadius: 8,
                                    offset: Offset(0, 4),
                                  ),
                                ],
                              ),
                              child: ElevatedButton(
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.transparent,
                                  shadowColor: Colors.transparent,
                                  foregroundColor: Colors.white,
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(16),
                                  ),
                                  elevation: 0,
                                ),
                                onPressed: _isLoading ? null : _submit,
                                child: _isLoading
                                    ? const CircularProgressIndicator(color: Colors.white)
                                    : const Text(
                                        'Register',
                                        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold, letterSpacing: 1.1),
                                      ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    _alamatController.dispose();
    _tglLahirController.dispose();
    _noHpController.dispose();
    _instagramController.dispose();
    _facebookController.dispose();
    super.dispose();
  }

  Widget _buildTextField(TextEditingController controller, String label, IconData icon, {Widget? suffixIcon}) {
    return TextFormField(
      controller: controller,
      decoration: InputDecoration(
        labelText: label,
        prefixIcon: Icon(icon, color: Color(0xFF485563)),
        suffixIcon: suffixIcon,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
        ),
        filled: true,
        fillColor: Colors.grey[100],
      ),
      validator: (v) => v == null || v.isEmpty ? 'Wajib diisi' : null,
    );
  }
}

