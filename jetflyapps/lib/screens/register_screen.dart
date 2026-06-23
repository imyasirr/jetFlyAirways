import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class RegisterScreen extends StatefulWidget {
  const RegisterScreen({super.key});

  @override
  State<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends State<RegisterScreen> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  final _phoneController = TextEditingController();
  final _passwordController = TextEditingController();
  final _referralController = TextEditingController();
  bool _loading = false;

  Future<void> _register() async {
    setState(() => _loading = true);
    try {
      await context.read<AuthProvider>().register(
            name: _nameController.text,
            email: _emailController.text,
            password: _passwordController.text,
            phone: _phoneController.text.isNotEmpty ? _phoneController.text : null,
            referral: _referralController.text.isNotEmpty ? _referralController.text : null,
          );
      if (mounted) Navigator.popUntil(context, (route) => route.isFirst);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Register')),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            TextField(controller: _nameController, decoration: const InputDecoration(labelText: 'Full Name')),
            const SizedBox(height: 12),
            TextField(controller: _emailController, decoration: const InputDecoration(labelText: 'Email'), keyboardType: TextInputType.emailAddress),
            const SizedBox(height: 12),
            TextField(controller: _phoneController, decoration: const InputDecoration(labelText: 'Phone (optional)'), keyboardType: TextInputType.phone),
            const SizedBox(height: 12),
            TextField(controller: _passwordController, decoration: const InputDecoration(labelText: 'Password'), obscureText: true),
            const SizedBox(height: 12),
            TextField(controller: _referralController, decoration: const InputDecoration(labelText: 'Referral Code (optional)')),
            const SizedBox(height: 24),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _loading ? null : _register,
                child: _loading ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white)) : const Text('Create Account'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
