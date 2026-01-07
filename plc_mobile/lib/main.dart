import 'package:flutter/material.dart';
import 'package:plc_mobile/pages/birthday_page.dart';
import 'package:plc_mobile/pages/event_page.dart';
import 'package:plc_mobile/pages/life_group_page.dart';
import 'package:plc_mobile/pages/loginuser_page.dart';
import 'package:plc_mobile/pages/materi_kotbah_page.dart';
import 'package:plc_mobile/pages/register_page.dart';
import 'package:plc_mobile/pages/saat_teduh_page.dart';
import 'package:plc_mobile/pages/ibadah_raya_page.dart';
import 'helpers/url_helper.dart';
import 'helpers/text_style_helper.dart';
import 'widgets/carousel_widget.dart';
import 'widgets/lazy_load_page.dart';
import 'services/cache_service.dart';
import 'package:flutter_native_splash/flutter_native_splash.dart';

void main() async {
  // Preserve splash screen while app initializes
  WidgetsBinding widgetsBinding = WidgetsFlutterBinding.ensureInitialized();
  FlutterNativeSplash.preserve(widgetsBinding: widgetsBinding);

  // Initialize base URL - change the value in url_helper.dart or pass here
  // For emulator: initBaseUrl('http://10.0.2.2:8000')
  // For phone/LAN: initBaseUrl('http://192.168.1.5:8000')
  // For production: initBaseUrl('https://philadelphialifecenter.com')
  initBaseUrl();

  // Initialize cache service
  await CacheService.init();

  // Remove splash screen after initialization
  FlutterNativeSplash.remove();

  runApp(const PlcApp());
}

class PlcApp extends StatelessWidget {
  const PlcApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'GBI PLC',
      theme: ThemeData(
        primaryColor: const Color(0xFF3540EF),
        useMaterial3: false,
      ),
      home: const HomePage(),
      routes: {
        '/saat-teduh': (_) => LazyLoadPage(
          builder: () => const SaatTeduhPage(),
          pageName: 'Saat Teduh',
        ),
        '/materi-kotbah': (_) => LazyLoadPage(
          builder: () => const MateriKotbahPage(),
          pageName: 'Materi Kotbah',
        ),
        '/birthday': (_) => LazyLoadPage(
          builder: () => const BirthdayPage(),
          pageName: 'Birthday',
        ),
        '/register': (_) => LazyLoadPage(
          builder: () => const RegisterPage(),
          pageName: 'Register',
        ),
        '/ibadahraya': (_) => LazyLoadPage(
          builder: () => const IbadahRayaPage(),
          pageName: 'Ibadah Raya',
        ),
        '/event': (_) =>
            LazyLoadPage(builder: () => const EventPage(), pageName: 'Event'),
        '/life-group': (_) => LazyLoadPage(
          builder: () => const LifeGroupPage(),
          pageName: 'Life Group',
        ),
        '/loginuser': (_) => LazyLoadPage(
          builder: () => const LoginUserPage(),
          pageName: 'Login',
        ),
      },
    );
  }
}

class HomePage extends StatefulWidget {
  const HomePage({super.key});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    // Precache critical images for faster display
    _precacheImages();
  }

  void _precacheImages() {
    precacheImage(const AssetImage('assets/button/saat_teduh.jpg'), context);
    precacheImage(const AssetImage('assets/button/materi_kotbah.jpg'), context);
    precacheImage(const AssetImage('assets/button/ibadah_raya.jpg'), context);
    precacheImage(const AssetImage('assets/button/event.jpg'), context);
    precacheImage(const AssetImage('assets/button/register.jpg'), context);
    precacheImage(const AssetImage('assets/button/login.jpg'), context);
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
        backgroundColor: const Color.fromARGB(255, 7, 10, 71),
        foregroundColor: Colors.white,
        title: const Text(
          'GBI Philadelphia Life Center',
          style: AppTextStyles.appBarTitle,
        ),
        centerTitle: true,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            Container(
              height: 500,
              margin: const EdgeInsets.all(16.0),
              decoration: BoxDecoration(
                //gradient: const LinearGradient(
                //  begin: Alignment.topLeft,
                //  end: Alignment.bottomRight,
                //  colors: [
                //    Color.fromARGB(255, 1, 7, 118),
                //    Color.fromARGB(255, 16, 132, 227),
                //  ],
                //),
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withValues(alpha: 0.25),
                    blurRadius: 8,
                    offset: const Offset(0, 4),
                  ),
                ],
              ),
              child: const Center(child: CarouselWidget()),
            ),
            const SizedBox(height: 10),
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
                          onTap: () =>
                              Navigator.pushNamed(context, '/saat-teduh'),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/materi_kotbah.jpg',
                          onTap: () =>
                              Navigator.pushNamed(context, '/materi-kotbah'),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/ibadah_raya.jpg',
                          onTap: () =>
                              Navigator.pushNamed(context, '/ibadahraya'),
                        ),
                      ),
                      //const SizedBox(width: 10),
                      //Expanded(
                      //  child: _buildImageGridItem(
                      //    context,
                      //    'assets/button/event.jpg',
                      //    onTap: () => Navigator.pushNamed(context, '/event'),
                      //  ),
                      //),
                    ],
                  ),
                  const SizedBox(height: 16),
                  Row(
                    children: [
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/event.jpg',
                          onTap: () => Navigator.pushNamed(context, '/event'),
                        ),
                      ),
                      //Expanded(
                      //  child: _buildImageGridItem(
                      //    context,
                      //    'assets/button/life_group.jpg',
                      //    onTap: () => Navigator.pushNamed(context, '/life-group'),
                      //  ),
                      //),
                      //const SizedBox(width: 10),
                      //Expanded(
                      //  child: _buildImageGridItem(
                      //    context,
                      //    'assets/button/youth.jpg',
                      //    onTap: () => Navigator.pushNamed(context, '/birthday'),
                      //  ),
                      //),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/register.jpg',
                          onTap: () =>
                              Navigator.pushNamed(context, '/register'),
                        ),
                      ),
                      const SizedBox(width: 10),
                      Expanded(
                        child: _buildImageGridItem(
                          context,
                          'assets/button/login.jpg',
                          onTap: () =>
                              Navigator.pushNamed(context, '/loginuser'),
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
