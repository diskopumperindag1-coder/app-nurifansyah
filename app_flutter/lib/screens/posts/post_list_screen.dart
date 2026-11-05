import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../config/app_theme.dart';
import '../../core/localization/app_localizations.dart';
import '../../providers/portal_provider.dart';
import '../../providers/post_provider.dart';
import '../../widgets/post_card.dart';
import '../../widgets/responsive_scaffold.dart';

class PostListScreen extends StatefulWidget {
  final String? initialCategory;

  const PostListScreen({super.key, this.initialCategory});

  @override
  State<PostListScreen> createState() => _PostListScreenState();
}

class _PostListScreenState extends State<PostListScreen> {
  final TextEditingController _searchController = TextEditingController();

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      final postProvider = Provider.of<PostProvider>(context, listen: false);
      postProvider.fetchPosts(
        refresh: true,
        category: widget.initialCategory,
      );
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
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
                Text(
                  loc.navPosts,
                  style: const TextStyle(
                    fontSize: 26,
                    fontWeight: FontWeight.bold,
                    color: AppTheme.textDark,
                  ),
                ),
                const SizedBox(height: 16),

                // Search Bar
                TextField(
                  controller: _searchController,
                  decoration: InputDecoration(
                    hintText: loc.searchPlaceholder,
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear),
                            onPressed: () {
                              _searchController.clear();
                              postProvider.search('');
                            },
                          )
                        : null,
                  ),
                  onSubmitted: (query) => postProvider.search(query),
                ),
                const SizedBox(height: 16),

                // Categories Chips Filter
                if (portal.categories.isNotEmpty)
                  SingleChildScrollView(
                    scrollDirection: Axis.horizontal,
                    child: Row(
                      children: [
                        Padding(
                          padding: const EdgeInsets.only(right: 8),
                          child: FilterChip(
                            label: Text(loc.allCategories),
                            selected: postProvider.selectedCategory == null,
                            onSelected: (_) => postProvider.selectCategory(null),
                          ),
                        ),
                        ...portal.categories.map((cat) {
                          final isSelected = postProvider.selectedCategory == cat.slug;
                          return Padding(
                            padding: const EdgeInsets.only(right: 8),
                            child: FilterChip(
                              label: Text('${cat.name} (${cat.postsCount ?? 0})'),
                              selected: isSelected,
                              onSelected: (_) => postProvider.selectCategory(cat.slug),
                            ),
                          );
                        }),
                      ],
                    ),
                  ),
                const SizedBox(height: 24),

                // Posts List / Grid
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
                      padding: const EdgeInsets.all(48),
                      child: Column(
                        children: [
                          Icon(Icons.feed_outlined, size: 56, color: Colors.grey.shade400),
                          const SizedBox(height: 12),
                          Text(
                            loc.noPostsFound,
                            style: const TextStyle(fontSize: 16, color: AppTheme.textMuted),
                          ),
                        ],
                      ),
                    ),
                  )
                else ...[
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
                        itemCount: postProvider.posts.length,
                        itemBuilder: (context, index) {
                          return PostCard(post: postProvider.posts[index]);
                        },
                      );
                    },
                  ),
                  const SizedBox(height: 32),
                  if (postProvider.hasMore)
                    Center(
                      child: ElevatedButton(
                        onPressed: postProvider.isLoadingMore ? null : () => postProvider.loadMore(),
                        child: postProvider.isLoadingMore
                            ? const SizedBox(
                                width: 20,
                                height: 20,
                                child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2),
                              )
                            : const Text('Muat Lebih Banyak'),
                      ),
                    ),
                ],
                const SizedBox(height: 48),
              ],
            ),
          ),
        ),
      ),
    );
  }
}

