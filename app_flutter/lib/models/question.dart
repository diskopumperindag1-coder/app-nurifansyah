class QuestionAnswerModel {
  final int id;
  final String body;
  final DateTime? answeredAt;
  final String? answeredBy;

  QuestionAnswerModel({
    required this.id,
    required this.body,
    this.answeredAt,
    this.answeredBy,
  });

  factory QuestionAnswerModel.fromJson(Map<String, dynamic> json) {
    return QuestionAnswerModel(
      id: json['id'] as int,
      body: json['body'] as String? ?? '',
      answeredAt: json['answered_at'] != null
          ? DateTime.tryParse(json['answered_at'] as String)
          : null,
      answeredBy: json['answered_by'] as String?,
    );
  }
}

class QuestionModel {
  final int id;
  final String name;
  final String subject;
  final String body;
  final String status;
  final String? topicName;
  final QuestionAnswerModel? answer;
  final DateTime? createdAt;

  QuestionModel({
    required this.id,
    required this.name,
    required this.subject,
    required this.body,
    required this.status,
    this.topicName,
    this.answer,
    this.createdAt,
  });

  bool get isAnswered => status == 'answered' && answer != null;

  factory QuestionModel.fromJson(Map<String, dynamic> json) {
    final topic = json['topic'] as Map<String, dynamic>?;
    final answerJson = json['answer'] as Map<String, dynamic>?;

    return QuestionModel(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      subject: json['subject'] as String? ?? '',
      body: json['body'] as String? ?? '',
      status: json['status'] as String? ?? 'pending',
      topicName: topic?['name'] as String?,
      answer: answerJson != null ? QuestionAnswerModel.fromJson(answerJson) : null,
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'] as String)
          : null,
    );
  }
}

