class FaqModel {
  final int id;
  final String question;
  final String answer;
  final int? topicId;
  final String? topicName;

  FaqModel({
    required this.id,
    required this.question,
    required this.answer,
    this.topicId,
    this.topicName,
  });

  factory FaqModel.fromJson(Map<String, dynamic> json) {
    final topic = json['topic'] as Map<String, dynamic>?;
    return FaqModel(
      id: json['id'] as int,
      question: json['question'] as String? ?? '',
      answer: json['answer'] as String? ?? '',
      topicId: topic?['id'] as int?,
      topicName: topic?['name'] as String?,
    );
  }
}

