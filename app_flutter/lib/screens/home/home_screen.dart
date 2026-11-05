import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../core/localization/app_localizations.dart';
import '../../providers/portal_provider.dart';
import '../../providers/post_provider.dart';
import '../../widgets/ask_question_dialog.dart';
import '../../widgets/banner_slider.dart';
import '../../widgets/post_card.dart';
import '../../widgets/responsive_scaffold.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final portal = Provider.of<PortalProvider>(context, listen: false);
      if (portal.slides.isEmpty && !portal.isLoading) {
        portal.fetchPortalData();
      }
      final posts = Provider.of<PostProvider>(context, listen: false);
      if (posts.featuredPosts.isEmpty) {
        posts.fetchFeaturedPosts();
      }
      if (posts.posts.isEmpty) {
        posts.fetchPosts(refresh: true);
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    final loc = AppLocalizations.of(context);
    final portal = Provider.of<PortalProvider>(context);
    final postProvider = Provider.of<PostProvider>(context);

    return ResponsiveScaffold(
      child: Center(
        child: ConstrainedBox(
          constraints: const BoxConstraints(maxWidth: 1200),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 24),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Banner Slider Section
                if (portal.slides.isNotEmpty) ...[
                  BannerSlider(slides: portal.slides),
                  const SizedBox(height: 36),
                ],

                // Quick Access / Topics Grid
                if (portal.topics.isNotEmpty) ...[
                  Text(
                    loc.popularTopics,
                    style: const TextStyle(
                      fontSize: 20,
                      fontWeight: FontWeight.bold,
                      color: AppTheme.textDark,
                    ),
                  ),
                  const SizedBox(height: 16),
                  _buildTopicsGrid(portal),
                  const SizedBox(height: 40),
                ],

                // Featured Posts Section
                if (postProvider.featuredPosts.isNotEmpty) ...[
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        loc.featuredPosts,
                        style: const TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: AppTheme.textDark,
                        ),
                      ),
                      TextButton.icon(
                        onPressed: () => context.push('/posts'),
                        icon: const Icon(Icons.arrow_forward, size: 16),
                        label: Text(loc.viewAll),
                      ),
                    ],
                  ),
                  const SizedBox(height: 16),
                  LayoutBuilder(
                    builder: (context, constraints) {
                      final count = constraints.maxWidth > 900 ? 3 : (constraints.maxWidth > 600 ? 2 : 1);
                      return GridView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: count,
                          crossAxisSpacing: 16,
                          mainAxisSpacing: 16,
                          childAspectRatio: 0.85,
                        ),
                        itemCount: postProvider.featuredPosts.take(3).length,
                        itemBuilder: (context, index) {
                          return PostCard(post: postProvider.featuredPosts[index]);
                        },
                      );
                    },
                  ),
                  const SizedBox(height: 40),
                ],

                // Ask Question CTA Banner
                _buildCtaBanner(context, loc, portal),
                const SizedBox(height: 40),

                // Latest Posts Grid
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      loc.latestPosts,
                      style: const TextStyle(
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                        color: AppTheme.textDark,
                      ),
                    ),
                    TextButton.icon(
                      onPressed: () => context.push('/posts'),
                      icon: const Icon(Icons.arrow_forward, size: 16),
                      label: Text(loc.viewAll),
                    ),
                  ],
                ),
                const SizedBox(height: 16),
                if (postProvider.isLoading && postProvider.posts.isEmpty)
                  const Center(
                    child: Padding(
                      padding: EdgeInsets.all(48),
                      child: CircularProgressIndicator(),
                    ),
                  )
                else if (postProvider.posts.isEmpty)
                  Center(
                    child: Padding(
                      padding: const EdgeInsets.all(32),
                      child: Text(
                        loc.noPostsFound,
                        style: const TextStyle(color: AppTheme.textMuted),
                      ),
                    ),
                  )
                else
                  LayoutBuilder(
                    builder: (context, constraints) {
                      final count = constraints.maxWidth > 900 ? 3 : (constraints.maxWidth > 600 ? 2 : 1);
                      return GridView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
                          crossAxisCount: count,
                          crossAxisSpacing: 16,
                          mainAxisSpacing: 16,
                          childAspectRatio: 0.85,
                        ),
                        itemCount: postProvider.posts.take(6).length,
                        itemBuilder: (context, index) {
                          return PostCard(post: postProvider.posts[index]);
                        },
                      );
                    },
                  ),
                const SizedBox(height: 48),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildTopicsGrid(PortalProvider portal) {
    return LayoutBuilder(
      builder: (context, constraints) {
        final count = constraints.maxWidth > 900 ? 4 : (constraints.maxWidth > 550 ? 2 : 1);
        return GridView.builder(
          shrinkWrap: true,
          physics: const NeverScrollableScrollPhysics(),
          gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
            crossAxisCount: count,
            crossAxisSpacing: 12,
            mainAxisSpacing: 12,
            childAspectRatio: 2.2,
          ),
          itemCount: portal.topics.length,
          itemBuilder: (context, index) {
            final topic = portal.topics[index];
            return Card(
              child: InkWell(
                borderRadius: BorderRadius.circular(16),
                onTap: () => context.push('/questions?topic=${topic.id}'),
                child: Padding(
                  padding: const EdgeInsets.all(12),
                  child: Row(
                    children: [
                      Container(
                        width: 44,
                        height: 44,
                        decoration: BoxDecoration(
                          color: AppTheme.primaryBlue.withValues(alpha: 0.08),
                          borderRadius: BorderRadius.circular(12),
                        ),
                        child: const Icon(
                          Icons.folder_special_outlined,
                          color: AppTheme.primaryBlue,
                        ),
                      ),
                      const SizedBox(width: 12),
                      Expanded(
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Text(
                              topic.name,
                              maxLines: 1,
                              overflow: TextOverflow.ellipsis,
                              style: const TextStyle(
                                fontWeight: FontWeight.bold,
                                fontSize: 13,
                              ),
                            ),
                            const SizedBox(height: 2),
                            Text(
                              '${topic.questionsCount} Pertanyaan • ${topic.faqsCount} FAQ',
                              style: const TextStyle(
                                fontSize: 11,
                                color: AppTheme.textMuted,
                              ),
                            ),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildCtaBanner(BuildContext context, AppLocalizations loc, PortalProvider portal) {
    return Container(
      padding: const EdgeInsets.all(28),
      decoration: BoxDecoration(
        gradient: const LinearGradient(
          colors: [Color(0xFF1E3A8A), Color(0xFF0D9488)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: const Color(0xFF1E3A8A).withValues(alpha: 0.25),
            blurRadius: 20,
            offset: const Offset(0, 8),
          ),
        ],
      ),
      child: LayoutBuilder(
        builder: (context, constraints) {
          final isDesktop = constraints.maxWidth > 700;

          final textSection = Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                loc.questionsTitle,
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
              ),
              const SizedBox(height: 8),
              Text(
                loc.questionsSubtitle,
                style: TextStyle(
                  color: Colors.white.withValues(alpha: 0.9),
                  fontSize: 14,
                  height: 1.4,
                ),
              ),
            ],
          );

          final buttonSection = ElevatedButton.icon(
            onPressed: () {
              showDialog(
                context: context,
                builder: (context) => AskQuestionDialog(topics: portal.topics),
              );
            },
            icon: const Icon(Icons.edit_note_rounded, color: AppTheme.primaryBlue),
            label: Text(
              loc.askQuestionBtn,
              style: const TextStyle(color: AppTheme.primaryBlue, fontWeight: FontWeight.bold),
            ),
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 22, vertical: 14),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
            ),
          );

          if (isDesktop) {
            return Row(
              children: [
                Expanded(child: textSection),
                const SizedBox(width: 24),
                buttonSection,
              ],
            );
          }

          return Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              textSection,
              const SizedBox(height: 20),
              SizedBox(width: double.infinity, child: buttonSection),
            ],
          );
        },
      ),
    );
  }
}

