import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:plc_mobile/widgets/profile_card_widget.dart';
import 'package:plc_mobile/services/auth_service.dart';
import 'package:plc_mobile/main.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import 'package:intl/intl.dart';
import 'package:plc_mobile/pages/membaca_alkitab_page.dart';

class Mypage extends StatelessWidget {
  const Mypage({super.key});

  Future<void> _handleMembacaAlkitab(BuildContext context) async {
    try {
      // Get token from secure storage
      final storage = FlutterSecureStorage();
      final token = await storage.read(key: 'access_token');
      
      if (token == null || token.isEmpty) {
        if (!context.mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Token tidak ditemukan. Silakan login terlebih dahulu.'),
            backgroundColor: Colors.red,
          ),
        );
        return;
      }
      
      // Get current date
      final currentDate = DateFormat('yyyy-MM-dd').format(DateTime.now());
      
      if (!context.mounted) return;
      
      // Navigate to membaca_alkitab with token and date using MaterialPageRoute
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => const MembacaAlkitabPage(),
          settings: RouteSettings(
            arguments: {
              'token': token,
              'tanggal': currentDate,
            },
          ),
        ),
      );
    } catch (e) {
      if (!context.mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Gagal mengakses halaman: $e'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  void _handleLogout(BuildContext context) async {
    // Tampilkan dialog konfirmasi
    final shouldLogout = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Apakah Anda yakin ingin logout?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context, false),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () => Navigator.pop(context, true),
            child: const Text('Logout'),
          ),
        ],
      ),
    );

    if (shouldLogout == true) {
      // Tampilkan loading
      if (!context.mounted) return;
      showDialog(
        context: context,
        barrierDismissible: false,
        builder: (context) => const Center(
          child: CircularProgressIndicator(),
        ),
      );

      try {
        final authService = AuthService();
        await authService.logout();

        if (!context.mounted) return;
        
        // Tutup loading dialog
        Navigator.pop(context);

        // Tampilkan snackbar sukses
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Logout berhasil'),
            backgroundColor: Colors.green,
          ),
        );

        // Navigate ke HomePage
        Navigator.pushAndRemoveUntil(
          context,
          MaterialPageRoute(builder: (context) => const HomePage()),
          (route) => false,
        );
      } catch (e) {
        if (!context.mounted) return;
        
        // Tutup loading dialog
        Navigator.pop(context);

        // Tampilkan error
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Logout gagal: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  Widget _buildImageGridItem(
    BuildContext context,
    String imagePath, {
    VoidCallback? onTap,
  }) {
    return GestureDetector(
      onTap: onTap,
      child: AspectRatio(
        aspectRatio: 1.0,
        child: Container(
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(12),
            image: DecorationImage(
              image: AssetImage(imagePath),
              fit: BoxFit.cover,
            ),
            boxShadow: [
              BoxShadow(
                color: Colors.black.withValues(alpha: 0.25),
                blurRadius: 8,
                offset: const Offset(0, 4),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.black,
      appBar: AppBar(
        automaticallyImplyLeading: false ,
        backgroundColor: const Color.fromARGB(255, 7, 10, 71),
        foregroundColor: Colors.white,
        title: Text(
          'My Profile',
          style: GoogleFonts.inter(
            fontSize: 18,
            fontWeight: FontWeight.bold,
            color: Colors.white,
          ),
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            Container(
              margin: const EdgeInsets.all(16.0),
              decoration: BoxDecoration(
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.25),
                    blurRadius: 8,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: const ProfileCardWidget(),
            ),
            const SizedBox(height: 20),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16.0),
              child: Column(
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/saat_teduh.jpg',
                          onTap: () => Navigator.pushNamed(context, '/saat-teduh'),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/materi_kotbah.jpg',
                          onTap: () => Navigator.pushNamed(context, '/materi-kotbah'),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/ibadah_raya.jpg',
                          onTap: () => Navigator.pushNamed(context, '/ibadahraya'),
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Row(
                    children: [
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/membaca_alkitab.jpg',
                          onTap: () => _handleMembacaAlkitab(context),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/happy_birthday.jpg',
                          onTap: () => Navigator.pushNamed(context, '/birthday'),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/logout.jpg',
                          onTap: () => _handleLogout(context),
                        ),
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 20),
          ],
        ),
      ),
      bottomNavigationBar: Container(
        color: Color.fromARGB(255, 7, 10, 71),
        padding: const EdgeInsets.symmetric(vertical: 16, horizontal: 20),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: const [
            Text(
              'GBI Philadelphia Life Center',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: Colors.white70,
              ),
            ),
            SizedBox(height: 6),
            Text(
              'Alamat: Jl. Babarsari No.45, Janti, Caturtunggal, Kec. Depok, Kab Sleman, Yogyakarta 55281',
              style: TextStyle(color: Colors.white70),
            ),
            SizedBox(height: 8),
            Text(
              'Kontak',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                color: Colors.white70,
              ),
            ),
            SizedBox(height: 6),
            Row(
              children: [
                Icon(Icons.phone, size: 16, color: Colors.amberAccent),
                SizedBox(width: 4),
                Text(
                  'Telp: 0853-3661-8852',
                  style: TextStyle(color: Colors.white70),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
