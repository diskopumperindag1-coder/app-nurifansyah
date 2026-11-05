import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:frontend/core/localization/app_locale_provider.dart';
import 'package:frontend/core/localization/app_localizations.dart';
import 'package:frontend/core/network/api_client.dart';
import 'package:frontend/models/category.dart';
import 'package:frontend/models/post.dart';
import 'package:frontend/providers/page_provider.dart';
import 'package:frontend/providers/portal_provider.dart';
import 'package:frontend/providers/post_provider.dart';
import 'package:frontend/providers/question_provider.dart';
import 'package:frontend/widgets/language_switcher.dart';
import 'package:frontend/widgets/post_card.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  setUp(() {
    SharedPreferences.setMockInitialValues({});
  });

  group('Localization Tests', () {
    test('AppLocalizations provides correct Indonesian and English strings', () {
      final locId = AppLocalizations(const Locale('id'));
      expect(locId.appTitle, 'Portal Layanan Publik');
      expect(locId.navHome, 'Beranda');
      expect(locId.navPosts, 'Berita & Informasi');

      final locEn = AppLocalizations(const Locale('en'));
      expect(locEn.appTitle, 'Public Service Portal');
      expect(locEn.navHome, 'Home');
      expect(locEn.navPosts, 'News & Info');
    });

    testWidgets('LanguageSwitcher toggles locale when tapped', (WidgetTester tester) async {
      final localeProvider = AppLocaleProvider();
      final apiClient = ApiClient();

      await tester.pumpWidget(
        MultiProvider(
          providers: [
            ChangeNotifierProvider.value(value: localeProvider),
            Provider.value(value: apiClient),
            ChangeNotifierProvider(create: (_) => PortalProvider(apiClient)),
            ChangeNotifierProvider(create: (_) => PostProvider(apiClient)),
            ChangeNotifierProvider(create: (_) => QuestionProvider(apiClient)),
            ChangeNotifierProvider(create: (_) => PageProvider(apiClient)),
          ],
          child: MaterialApp(
            home: const Scaffold(
              body: Center(child: LanguageSwitcher()),
            ),
          ),
        ),
      );

      await tester.pumpAndSettle();

      expect(find.text('ID'), findsOneWidget);
      expect(find.text('EN'), findsOneWidget);

      // Default is Indonesian
      expect(localeProvider.isIndonesian, isTrue);

      // Tap EN
      await tester.tap(find.text('EN'));
      await tester.pumpAndSettle();

      expect(localeProvider.isEnglish, isTrue);
      expect(apiClient.currentLocale, 'en');
    });
  });

  group('Model Deserialization Tests', () {
    test('CategoryModel correctly parses API JSON', () {
      final json = {
        'id': 1,
        'name': 'Berita Utama',
        'slug': 'berita-utama',
        'description': 'Kategori berita resmi',
        'icon': 'newspaper',
        'posts_count': 5,
      };

      final cat = CategoryModel.fromJson(json);
      expect(cat.id, 1);
      expect(cat.name, 'Berita Utama');
      expect(cat.slug, 'berita-utama');
      expect(cat.postsCount, 5);
    });

    test('PostModel correctly parses API JSON', () {
      final json = {
        'id': 10,
        'title': 'Judul Berita Terbaru',
        'slug': 'judul-berita-terbaru',
        'summary': 'Ringkasan singkat berita',
        'content': '<p>Konten HTML lengkap</p>',
        'image_url': 'http://localhost:8000/storage/posts/img.jpg',
        'is_featured': true,
        'view_count': 42,
        'published_at': '2026-11-04T02:24:52+00:00',
        'category': {
          'id': 1,
          'name': 'Berita Utama',
          'slug': 'berita-utama',
        },
        'author': {
          'id': 2,
          'name': 'Admin Instansi',
          'avatar': null,
        },
      };

      final post = PostModel.fromJson(json);
      expect(post.id, 10);
      expect(post.title, 'Judul Berita Terbaru');
      expect(post.isFeatured, isTrue);
      expect(post.category?.name, 'Berita Utama');
      expect(post.author?.name, 'Admin Instansi');
    });
  });

  group('Widget Card Tests', () {
    testWidgets('PostCard renders post details without overflow', (WidgetTester tester) async {
      tester.view.physicalSize = const Size(1280, 800);
      tester.view.devicePixelRatio = 1.0;
      addTearDown(tester.view.resetPhysicalSize);

      final post = PostModel(
        id: 1,
        title: 'Peluncuran Portal Layanan Publik',
        slug: 'peluncuran-portal-layanan-publik',
        summary: 'Deskripsi singkat seputar peluncuran portal terpadu.',
        isFeatured: true,
        viewCount: 150,
        publishedAt: DateTime(2026, 11, 4),
        category: CategoryModel(id: 1, name: 'Layanan', slug: 'layanan'),
      );

      await tester.pumpWidget(
        MaterialApp(
          localizationsDelegates: const [AppLocalizations.delegate],
          home: Scaffold(
            body: SizedBox(
              width: 380,
              child: PostCard(post: post),
            ),
          ),
        ),
      );

      await tester.pumpAndSettle();

      expect(find.text('Peluncuran Portal Layanan Publik'), findsOneWidget);
      expect(find.text('Layanan'), findsOneWidget);
      expect(find.text('Featured'), findsOneWidget);
    });
  });
}
