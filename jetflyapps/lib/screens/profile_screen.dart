import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  late final TextEditingController _name;
  late final TextEditingController _phone;
  late final TextEditingController _email;
  bool _saving = false;

  @override
  void initState() {
    super.initState();
    final user = context.read<AuthProvider>().user!;
    _name = TextEditingController(text: user.name);
    _phone = TextEditingController(text: user.phone ?? '');
    _email = TextEditingController(text: user.email);
  }

  @override
  void dispose() {
    _name.dispose();
    _phone.dispose();
    _email.dispose();
    super.dispose();
  }

  Future<void> _save() async {
    setState(() => _saving = true);
    try {
      final updated = await TravelRepository(context.read<ApiService>()).updateProfile(
        name: _name.text.trim(),
        email: _email.text.trim(),
        phone: _phone.text.trim(),
      );
      if (!mounted) return;
      await context.read<AuthProvider>().refreshUser();
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Profile updated')));
      _name.text = updated.name;
      _phone.text = updated.phone ?? '';
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      if (mounted) setState(() => _saving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user!;

    return Scaffold(
      appBar: AppBar(title: const Text('Edit Profile')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Center(
            child: CircleAvatar(
              radius: 48,
              backgroundColor: AppColors.secondaryContainer,
              child: Text(user.name.isNotEmpty ? user.name[0].toUpperCase() : 'U', style: const TextStyle(fontSize: 36, fontWeight: FontWeight.bold, color: AppColors.primary)),
            ),
          ),
          const SizedBox(height: 24),
          TextField(controller: _name, decoration: const InputDecoration(labelText: 'Full Name')),
          const SizedBox(height: 12),
          TextField(
            controller: _email,
            readOnly: true,
            decoration: const InputDecoration(labelText: 'Email', suffixIcon: Icon(Icons.lock_outline, size: 18)),
          ),
          const SizedBox(height: 12),
          TextField(controller: _phone, decoration: const InputDecoration(labelText: 'Phone'), keyboardType: TextInputType.phone),
          const SizedBox(height: 24),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _saving ? null : _save,
              child: _saving ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white)) : const Text('Save Changes'),
            ),
          ),
        ],
      ),
    );
  }
}
