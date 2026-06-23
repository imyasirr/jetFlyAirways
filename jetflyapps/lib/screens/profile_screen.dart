import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
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
  final _picker = ImagePicker();
  bool _saving = false;
  bool _uploadingPhoto = false;
  String? _localPreviewPath;

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

  Future<void> _pickPhoto(ImageSource source) async {
    try {
      final picked = await _picker.pickImage(source: source, maxWidth: 1200, maxHeight: 1200, imageQuality: 85);
      if (picked == null) return;

      setState(() {
        _uploadingPhoto = true;
        _localPreviewPath = picked.path;
      });

      await TravelRepository(context.read<ApiService>()).uploadAvatar(picked.path);
      if (!mounted) return;
      await context.read<AuthProvider>().refreshUser();
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Profile photo updated')));
    } catch (e) {
      if (mounted) {
        setState(() => _localPreviewPath = null);
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
      }
    } finally {
      if (mounted) setState(() => _uploadingPhoto = false);
    }
  }

  void _photoOptions() {
    final user = context.read<AuthProvider>().user!;
    showModalBottomSheet(
      context: context,
      shape: const RoundedRectangleBorder(borderRadius: BorderRadius.vertical(top: Radius.circular(16))),
      builder: (ctx) => SafeArea(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            ListTile(
              leading: const Icon(Icons.photo_camera_outlined),
              title: const Text('Take photo'),
              onTap: () {
                Navigator.pop(ctx);
                _pickPhoto(ImageSource.camera);
              },
            ),
            ListTile(
              leading: const Icon(Icons.photo_library_outlined),
              title: const Text('Choose from gallery'),
              onTap: () {
                Navigator.pop(ctx);
                _pickPhoto(ImageSource.gallery);
              },
            ),
            if (user.avatarUrl != null || _localPreviewPath != null)
              ListTile(
                leading: const Icon(Icons.delete_outline, color: AppColors.alert),
                title: const Text('Remove photo', style: TextStyle(color: AppColors.alert)),
                onTap: () async {
                  Navigator.pop(ctx);
                  setState(() => _uploadingPhoto = true);
                  try {
                    await TravelRepository(context.read<ApiService>()).removeAvatar();
                    if (!mounted) return;
                    setState(() => _localPreviewPath = null);
                    await context.read<AuthProvider>().refreshUser();
                    ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Profile photo removed')));
                  } catch (e) {
                    if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
                  } finally {
                    if (mounted) setState(() => _uploadingPhoto = false);
                  }
                },
              ),
          ],
        ),
      ),
    );
  }

  Widget _avatarWidget(String? networkUrl, String name) {
    ImageProvider? provider;
    if (_localPreviewPath != null) {
      provider = FileImage(File(_localPreviewPath!));
    } else if (networkUrl != null && networkUrl.isNotEmpty) {
      provider = NetworkImage(networkUrl);
    }

    return Stack(
      children: [
        CircleAvatar(
          radius: 52,
          backgroundColor: AppColors.secondaryContainer,
          backgroundImage: provider,
          child: provider == null
              ? Text(
                  name.isNotEmpty ? name[0].toUpperCase() : 'U',
                  style: const TextStyle(fontSize: 40, fontWeight: FontWeight.bold, color: AppColors.primary),
                )
              : null,
        ),
        Positioned(
          right: 0,
          bottom: 0,
          child: Container(
            decoration: BoxDecoration(
              color: AppColors.primary,
              shape: BoxShape.circle,
              border: Border.all(color: Colors.white, width: 2),
            ),
            child: IconButton(
              onPressed: _uploadingPhoto ? null : _photoOptions,
              icon: _uploadingPhoto
                  ? const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white))
                  : const Icon(Icons.camera_alt, color: Colors.white, size: 18),
              tooltip: 'Change photo',
            ),
          ),
        ),
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    final user = context.watch<AuthProvider>().user!;

    return Scaffold(
      appBar: AppBar(title: const Text('Edit Profile')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Center(child: _avatarWidget(user.avatarUrl, user.name)),
          const SizedBox(height: 8),
          const Center(child: Text('Tap camera to change photo', style: TextStyle(color: AppColors.muted, fontSize: 12))),
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
