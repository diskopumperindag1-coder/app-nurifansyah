import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import '../../screens/contact/contact_screen.dart';
import '../../screens/faqs/faq_screen.dart';
import '../../screens/home/home_screen.dart';
import '../../screens/pages/page_detail_screen.dart';
import '../../screens/posts/post_detail_screen.dart';
import '../../screens/posts/post_list_screen.dart';
import '../../screens/questions/question_screen.dart';

class AppRouter {
  static final GoRouter router = GoRouter(
    initialLocation: '/',
    routes: [
      GoRoute(
        path: '/',
        name: 'home',
        builder: (context, state) => const HomeScreen(),
      ),
      GoRoute(
        path: '/posts',
        name: 'posts',
        builder: (context, state) {
          final category = state.uri.queryParameters['category'];
          return PostListScreen(initialCategory: category);
        },
      ),
      GoRoute(
        path: '/posts/:slug',
        name: 'post_detail',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return PostDetailScreen(slug: slug);
        },
      ),
      GoRoute(
        path: '/pages/:slug',
        name: 'page_detail',
        builder: (context, state) {
          final slug = state.pathParameters['slug'] ?? '';
          return PageDetailScreen(slug: slug);
        },
      ),
      GoRoute(
        path: '/faqs',
        name: 'faqs',
        builder: (context, state) {
          final topicStr = state.uri.queryParameters['topic'];
          final topicId = topicStr != null ? int.tryParse(topicStr) : null;
          return FaqScreen(initialTopicId: topicId);
        },
      ),
      GoRoute(
        path: '/questions',
        name: 'questions',
        builder: (context, state) {
          final topicStr = state.uri.queryParameters['topic'];
          final topicId = topicStr != null ? int.tryParse(topicStr) : null;
          return QuestionScreen(initialTopicId: topicId);
        },
      ),
      GoRoute(
        path: '/contact',
        name: 'contact',
        builder: (context, state) => const ContactScreen(),
      ),
    ],
    errorBuilder: (context, state) {
      return Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 64, color: Colors.red),
              const SizedBox(height: 16),
              const Text(
                '404 - Halaman Tidak Ditemukan',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 8),
              Text(
                'Rute ${state.uri} tidak tersedia.',
                style: const TextStyle(color: Colors.grey),
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: () => context.go('/'),
                child: const Text('Kembali ke Beranda'),
              ),
            ],
          ),
        ),
      );
    },
  );
}

