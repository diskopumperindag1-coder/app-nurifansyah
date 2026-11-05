class MenuItemModel {
  final int id;
  final String label;
  final String url;
  final String type;
  final int sortOrder;
  final List<MenuItemModel> children;

  MenuItemModel({
    required this.id,
    required this.label,
    required this.url,
    this.type = 'custom',
    this.sortOrder = 0,
    this.children = const [],
  });

  factory MenuItemModel.fromJson(Map<String, dynamic> json) {
    var rawChildren = json['children'] as List<dynamic>? ?? [];
    return MenuItemModel(
      id: json['id'] as int,
      label: json['label'] as String? ?? '',
      url: json['url'] as String? ?? '',
      type: json['type'] as String? ?? 'custom',
      sortOrder: json['sort_order'] as int? ?? 0,
      children: rawChildren
          .map((child) => MenuItemModel.fromJson(child as Map<String, dynamic>))
          .toList(),
    );
  }
}

class MenuModel {
  final int id;
  final String name;
  final String location;
  final List<MenuItemModel> items;

  MenuModel({
    required this.id,
    required this.name,
    required this.location,
    this.items = const [],
  });

  factory MenuModel.fromJson(Map<String, dynamic> json) {
    var rawItems = json['items'] as List<dynamic>? ?? [];
    return MenuModel(
      id: json['id'] as int,
      name: json['name'] as String? ?? '',
      location: json['location'] as String? ?? '',
      items: rawItems
          .map((item) => MenuItemModel.fromJson(item as Map<String, dynamic>))
          .toList(),
    );
  }
}

