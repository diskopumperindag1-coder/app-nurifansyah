import 'package:flutter/material.dart';
import 'translations.dart';

class AppLocalizations {
  final Locale locale;

  AppLocalizations(this.locale);

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations) ??
        AppLocalizations(const Locale('id'));
  }

  static const LocalizationsDelegate<AppLocalizations> delegate =
      _AppLocalizationsDelegate();

  String translate(String key) {
    return Translations.get(key, locale.languageCode);
  }

  // Convenient getters
  String get appTitle => translate('app_title');
  String get tagline => translate('tagline');
  String get navHome => translate('nav_home');
  String get navPosts => translate('nav_posts');
  String get navFaqs => translate('nav_faqs');
  String get navQuestions => translate('nav_questions');
  String get navContact => translate('nav_contact');
  String get searchPlaceholder => translate('search_placeholder');
  String get allCategories => translate('all_categories');
  String get featuredPosts => translate('featured_posts');
  String get latestPosts => translate('latest_posts');
  String get viewAll => translate('view_all');
  String get readMore => translate('read_more');
  String get popularTopics => translate('popular_topics');
  String get faqsTitle => translate('faqs_title');
  String get faqsSubtitle => translate('faqs_subtitle');
  String get questionsTitle => translate('questions_title');
  String get questionsSubtitle => translate('questions_subtitle');
  String get askQuestionBtn => translate('ask_question_btn');
  String get askDialogTitle => translate('ask_dialog_title');
  String get formName => translate('form_name');
  String get formEmail => translate('form_email');
  String get formPhone => translate('form_phone');
  String get formTopic => translate('form_topic');
  String get formSubject => translate('form_subject');
  String get formBody => translate('form_body');
  String get formSubmit => translate('form_submit');
  String get formSubmitting => translate('form_submitting');
  String get formSuccessTitle => translate('form_success_title');
  String get formSuccessDesc => translate('form_success_desc');
  String get formRequiredField => translate('form_required_field');
  String get formInvalidEmail => translate('form_invalid_email');
  String get formMinLength => translate('form_min_length');
  String get statusAnswered => translate('status_answered');
  String get statusPending => translate('status_pending');
  String get answeredBy => translate('answered_by');
  String get answeredAt => translate('answered_at');
  String get noPostsFound => translate('no_posts_found');
  String get noQuestionsFound => translate('no_questions_found');
  String get noFaqsFound => translate('no_faqs_found');
  String get loading => translate('loading');
  String get errorTitle => translate('error_title');
  String get retry => translate('retry');
  String get languageSwitcher => translate('language_switcher');
  String get langId => translate('lang_id');
  String get langEn => translate('lang_en');
  String get officeAddress => translate('office_address');
  String get officePhone => translate('office_phone');
  String get officeEmail => translate('office_email');
  String get quickLinks => translate('quick_links');
  String get copyright => translate('copyright');
  String get publishedAt => translate('published_at');
  String get views => translate('views');
  String get close => translate('close');
}

class _AppLocalizationsDelegate
    extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) {
    return ['id', 'en'].contains(locale.languageCode);
  }

  @override
  Future<AppLocalizations> load(Locale locale) async {
    return AppLocalizations(locale);
  }

  @override
  bool shouldReload(_AppLocalizationsDelegate old) => false;
}

