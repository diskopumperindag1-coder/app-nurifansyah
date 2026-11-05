class SettingModel {
  final String siteName;
  final String? siteTagline;
  final String? siteDescription;
  final String? officeAddress;
  final String? officePhone;
  final String? officeEmail;
  final String? socialFacebook;
  final String? socialInstagram;

  SettingModel({
    required this.siteName,
    this.siteTagline,
    this.siteDescription,
    this.officeAddress,
    this.officePhone,
    this.officeEmail,
    this.socialFacebook,
    this.socialInstagram,
  });

  factory SettingModel.fromJson(Map<String, dynamic> json) {
    // Backend returns { grouped: { general: {...}, contact: {...}, social: {...} }, settings: {...} }
    final grouped = json['grouped'] as Map<String, dynamic>? ?? {};
    final general = grouped['general'] as Map<String, dynamic>? ?? {};
    final contact = grouped['contact'] as Map<String, dynamic>? ?? {};
    final social = grouped['social'] as Map<String, dynamic>? ?? {};
    final flatSettings = json['settings'] as Map<String, dynamic>? ?? {};

    return SettingModel(
      siteName: general['site_name'] as String? ??
          flatSettings['site_name'] as String? ??
          'Portal Resmi Instansi',
      siteTagline: general['site_tagline'] as String? ??
          flatSettings['site_tagline'] as String?,
      siteDescription: general['site_description'] as String? ??
          flatSettings['site_description'] as String?,
      officeAddress: contact['office_address'] as String? ??
          flatSettings['office_address'] as String?,
      officePhone: contact['office_phone'] as String? ??
          flatSettings['office_phone'] as String?,
      officeEmail: contact['office_email'] as String? ??
          flatSettings['office_email'] as String?,
      socialFacebook: social['social_facebook'] as String? ??
          flatSettings['social_facebook'] as String?,
      socialInstagram: social['social_instagram'] as String? ??
          flatSettings['social_instagram'] as String?,
    );
  }
}

