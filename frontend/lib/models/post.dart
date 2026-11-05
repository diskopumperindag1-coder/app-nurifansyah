import 'category.dart';

class AuthorModel {
  final int? id;
  final String? name;
  final String? avatar;

  AuthorModel({this.id, this.name, this.avatar});

  factory AuthorModel.fromJson(Map<String, dynamic>? json) {
    if (json == null) return AuthorModel();
    return AuthorModel(
      id: json['id'] as int?,
      name: json['name'] as String?,
      avatar: json['avatar'] as String?,
    );
  }
}

class PostModel {
  final int id;
  final String title;
  final String slug;
  final String summary;
  final String? content;
  final String? imageUrl;
  final bool isFeatured;
  final int viewCount;
  final DateTime? publishedAt;
  final CategoryModel? category;
  final AuthorModel? author;
  final DateTime? createdAt;

  PostModel({
    required this.id,
    required this.title,
    required this.slug,
    required this.summary,
    this.content,
    this.imageUrl,
    this.isFeatured = false,
    this.viewCount = 0,
    this.publishedAt,
    this.category,
    this.author,
    this.createdAt,
  });

  factory PostModel.fromJson(Map<String, dynamic> json) {
    return PostModel(
      id: json['id'] as int,
      title: json['title'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      summary: json['summary'] as String? ?? '',
      content: json['content'] as String?,
      imageUrl: json['image_url'] as String?,
      isFeatured: json['is_featured'] as bool? ?? false,
      viewCount: json['view_count'] as int? ?? 0,
      publishedAt: json['published_at'] != null
          ? DateTime.tryParse(json['published_at'] as String)
          : null,
      category: json['category'] != null
          ? CategoryModel.fromJson(json['category'] as Map<String, dynamic>)
          : null,
      author: AuthorModel.fromJson(json['author'] as Map<String, dynamic>?),
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'] as String)
          : null,
    );
  }
}

