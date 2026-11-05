import 'package:flutter_test/flutter_test.dart';
import 'package:frontend/main.dart';
import 'package:frontend/widgets/language_switcher.dart';
import 'package:shared_preferences/shared_preferences.dart';

void main() {
  TestWidgetsFlutterBinding.ensureInitialized();

  setUp(() {
    SharedPreferences.setMockInitialValues({});
  });

  testWidgets('Portal frontend loads and displays language switcher and header', (WidgetTester tester) async {
    await tester.pumpWidget(const MyApp());
    await tester.pump();

    // Verify LanguageSwitcher exists
    expect(find.byType(LanguageSwitcher), findsWidgets);

    // Verify that ID and EN buttons exist
    expect(find.text('ID'), findsWidgets);
    expect(find.text('EN'), findsWidgets);
  });
}
