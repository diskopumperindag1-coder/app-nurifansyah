class QuestionTopicModel {
  final int id;
  final String name;
  final String slug;
  final String? description;
  final String? icon;
  final int questionsCount;
  final int faqsCount;

  QuestionTopicModel({
    required this.id,
    required this.name,
    required this.slug,
    this.description,
    this.icon,
    this.questionsCount = 0,
    this.faqsCount = 0,
  });

  factory QuestionTopicModel.fromJson(Map<String, dynamic> json) {
    return QuestionTopicModel(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      slug: json['slug'] as String? ?? '',
      description: json['description'] as String?,
      icon: json['icon'] as String?,
      questionsCount: json['questions_count'] as int? ?? 0,
      faqsCount: json['faqs_count'] as int? ?? 0,
    );
  }
}

