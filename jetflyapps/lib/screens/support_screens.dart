import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../widgets/empty_state.dart';
import '../widgets/jetfly_loader.dart';

// ─── Offers ───────────────────────────────────────────────────────────────────

class OffersScreen extends StatefulWidget {
  const OffersScreen({super.key});

  @override
  State<OffersScreen> createState() => _OffersScreenState();
}

class _OffersScreenState extends State<OffersScreen> {
  List<Map<String, dynamic>> _offers = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final offers = await TravelRepository(context.read<ApiService>()).getOffers();
      setState(() => _offers = offers);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Special Offers')),
      body: _loading
          ? Center(child: JetFlyLoader.center(message: 'Loading offers...'))
          : _offers.isEmpty
              ? const EmptyState(icon: Icons.local_offer_outlined, title: 'No offers right now', subtitle: 'Check back soon for exclusive deals.')
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _offers.length,
                  itemBuilder: (context, i) {
                    final o = _offers[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      clipBehavior: Clip.antiAlias,
                      child: Container(
                        decoration: const BoxDecoration(
                          gradient: LinearGradient(
                            colors: [Color(0xFFFFF8E1), Colors.white],
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                          ),
                        ),
                        padding: const EdgeInsets.all(20),
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Row(
                              children: [
                                const Icon(Icons.local_offer, color: AppColors.bookingBlue),
                                const SizedBox(width: 8),
                                Expanded(child: Text(o['title'] as String? ?? '', style: const TextStyle(fontSize: 17, fontWeight: FontWeight.bold))),
                              ],
                            ),
                            if (o['description'] != null) ...[
                              const SizedBox(height: 10),
                              Text(o['description'] as String, style: const TextStyle(color: AppColors.muted, height: 1.4)),
                            ],
                          ],
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}

// ─── FAQ ──────────────────────────────────────────────────────────────────────

class FaqScreen extends StatefulWidget {
  const FaqScreen({super.key});

  @override
  State<FaqScreen> createState() => _FaqScreenState();
}

class _FaqScreenState extends State<FaqScreen> {
  List<Map<String, dynamic>> _faqs = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final faqs = await TravelRepository(context.read<ApiService>()).getFaqs();
      setState(() => _faqs = faqs);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('FAQ')),
      body: _loading
          ? Center(child: JetFlyLoader.center(message: 'Loading FAQs...'))
          : _faqs.isEmpty
              ? const EmptyState(icon: Icons.help_outline, title: 'No FAQs yet', subtitle: 'Contact us if you have questions.')
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _faqs.length,
                  itemBuilder: (context, i) {
                    final f = _faqs[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 10),
                      child: ExpansionTile(
                        title: Text(f['question'] as String? ?? '', style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 14)),
                        children: [
                          Padding(
                            padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
                            child: Text(f['answer'] as String? ?? '', style: const TextStyle(color: AppColors.muted, height: 1.5)),
                          ),
                        ],
                      ),
                    );
                  },
                ),
    );
  }
}

// ─── Blogs ────────────────────────────────────────────────────────────────────

class BlogsScreen extends StatefulWidget {
  const BlogsScreen({super.key});

  @override
  State<BlogsScreen> createState() => _BlogsScreenState();
}

class _BlogsScreenState extends State<BlogsScreen> {
  List<Map<String, dynamic>> _blogs = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final result = await TravelRepository(context.read<ApiService>()).getBlogs();
      setState(() => _blogs = result.blogs);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Travel Blog')),
      body: _loading
          ? Center(child: JetFlyLoader.center(message: 'Loading articles...'))
          : _blogs.isEmpty
              ? const EmptyState(icon: Icons.article_outlined, title: 'No blog posts yet', subtitle: 'Travel tips and guides coming soon.')
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _blogs.length,
                  itemBuilder: (context, i) {
                    final b = _blogs[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 12),
                      child: ListTile(
                        contentPadding: const EdgeInsets.all(16),
                        title: Text(b['title'] as String? ?? '', style: const TextStyle(fontWeight: FontWeight.bold)),
                        subtitle: Padding(
                          padding: const EdgeInsets.only(top: 8),
                          child: Text(b['excerpt'] as String? ?? '', maxLines: 3, overflow: TextOverflow.ellipsis, style: const TextStyle(color: AppColors.muted)),
                        ),
                        trailing: const Icon(Icons.chevron_right),
                        onTap: () => Navigator.push(
                          context,
                          MaterialPageRoute(builder: (_) => BlogDetailScreen(slug: b['slug'] as String, title: b['title'] as String)),
                        ),
                      ),
                    );
                  },
                ),
    );
  }
}

class BlogDetailScreen extends StatefulWidget {
  const BlogDetailScreen({super.key, required this.slug, required this.title});

  final String slug;
  final String title;

  @override
  State<BlogDetailScreen> createState() => _BlogDetailScreenState();
}

class _BlogDetailScreenState extends State<BlogDetailScreen> {
  Map<String, dynamic>? _blog;
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final blog = await TravelRepository(context.read<ApiService>()).getBlog(widget.slug);
      setState(() => _blog = blog);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(widget.title, overflow: TextOverflow.ellipsis)),
      body: _loading
          ? Center(child: JetFlyLoader.center(message: 'Loading article...'))
          : _blog == null
              ? const EmptyState(icon: Icons.article_outlined, title: 'Blog not found')
              : SingleChildScrollView(
                  padding: const EdgeInsets.all(20),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(_blog!['title'] as String? ?? '', style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                      const SizedBox(height: 16),
                      Text(_blog!['content'] as String? ?? _blog!['excerpt'] as String? ?? '', style: const TextStyle(fontSize: 15, height: 1.6, color: AppColors.onSurface)),
                    ],
                  ),
                ),
    );
  }
}

// ─── Contact ──────────────────────────────────────────────────────────────────

class ContactScreen extends StatefulWidget {
  const ContactScreen({super.key});

  @override
  State<ContactScreen> createState() => _ContactScreenState();
}

class _ContactScreenState extends State<ContactScreen> {
  final _formKey = GlobalKey<FormState>();
  final _name = TextEditingController();
  final _email = TextEditingController();
  final _phone = TextEditingController();
  final _subject = TextEditingController();
  final _message = TextEditingController();
  Map<String, dynamic> _site = {};
  bool _loading = false;
  bool _siteLoading = true;
  bool _sent = false;

  static const _subjectSuggestions = [
    'General inquiry',
    'Booking help',
    'Payment issue',
    'Refund request',
    'Other',
  ];

  @override
  void initState() {
    super.initState();
    _loadSite();
    final auth = context.read<AuthProvider>();
    if (auth.isLoggedIn) {
      _name.text = auth.user!.name;
      _email.text = auth.user!.email;
      _phone.text = auth.user!.phone ?? '';
    }
  }

  @override
  void dispose() {
    _name.dispose();
    _email.dispose();
    _phone.dispose();
    _subject.dispose();
    _message.dispose();
    super.dispose();
  }

  Future<void> _loadSite() async {
    try {
      final site = await TravelRepository(context.read<ApiService>()).getSiteInfo();
      if (mounted) setState(() => _site = site);
    } catch (_) {
    } finally {
      if (mounted) setState(() => _siteLoading = false);
    }
  }

  Future<void> _submit() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() {
      _loading = true;
      _sent = false;
    });
    try {
      final msg = await TravelRepository(context.read<ApiService>()).submitContact({
        'name': _name.text.trim(),
        'email': _email.text.trim(),
        'phone': _phone.text.trim(),
        'subject': _subject.text.trim(),
        'message': _message.text.trim(),
      });
      if (!mounted) return;
      setState(() => _sent = true);
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
      _message.clear();
      _subject.clear();
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      if (mounted) setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final siteName = _site['name'] as String? ?? 'Jet Fly Airways';

    return Scaffold(
      backgroundColor: AppColors.surfaceLow,
      body: CustomScrollView(
        slivers: [
          SliverAppBar(
            expandedHeight: 168,
            pinned: true,
            flexibleSpace: FlexibleSpaceBar(
              title: const Text('Contact Us', style: TextStyle(fontWeight: FontWeight.bold, fontSize: 17)),
              background: Container(
                decoration: const BoxDecoration(
                  gradient: LinearGradient(
                    colors: [AppColors.footerDeep, AppColors.bookingBlue, AppColors.primary],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                ),
                child: Stack(
                  children: [
                    Positioned(
                      right: -24,
                      bottom: -20,
                      child: Icon(Icons.support_agent, size: 140, color: Colors.white.withValues(alpha: 0.1)),
                    ),
                    const Positioned(
                      left: 20,
                      right: 20,
                      bottom: 52,
                      child: Text(
                        'We\'re here to help — reach out anytime',
                        style: TextStyle(color: Colors.white70, fontSize: 13),
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
          SliverToBoxAdapter(
            child: Padding(
              padding: const EdgeInsets.fromLTRB(16, 8, 16, 24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  if (_sent)
                    Container(
                      width: double.infinity,
                      margin: const EdgeInsets.only(bottom: 16),
                      padding: const EdgeInsets.all(14),
                      decoration: BoxDecoration(
                        color: AppColors.success.withValues(alpha: 0.12),
                        borderRadius: BorderRadius.circular(12),
                        border: Border.all(color: AppColors.success.withValues(alpha: 0.35)),
                      ),
                      child: const Row(
                        children: [
                          Icon(Icons.check_circle, color: AppColors.success),
                          SizedBox(width: 10),
                          Expanded(
                            child: Text(
                              'Message sent! Our team will reply soon.',
                              style: TextStyle(color: AppColors.success, fontWeight: FontWeight.w600),
                            ),
                          ),
                        ],
                      ),
                    ),
                  if (_siteLoading)
                    Card(
                      child: Padding(
                        padding: const EdgeInsets.all(24),
                        child: Center(child: JetFlyLoader.center(message: 'Loading contact info...')),
                      ),
                    )
                  else ...[
                    Text(siteName, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 4),
                    const Text('Get in touch with our travel support team', style: TextStyle(color: AppColors.muted, fontSize: 13)),
                    const SizedBox(height: 16),
                    if (_site['phone'] != null)
                      _ContactInfoCard(
                        icon: Icons.phone_in_talk_rounded,
                        label: 'Call us',
                        value: _site['phone'] as String,
                        color: AppColors.primary,
                      ),
                    if (_site['email'] != null)
                      _ContactInfoCard(
                        icon: Icons.mail_rounded,
                        label: 'Email',
                        value: _site['email'] as String,
                        color: AppColors.bookingBlue,
                      ),
                    if (_site['whatsapp'] != null && (_site['whatsapp'] as String).isNotEmpty)
                      _ContactInfoCard(
                        icon: Icons.chat_rounded,
                        label: 'WhatsApp',
                        value: _site['whatsapp'] as String,
                        color: const Color(0xFF25D366),
                      ),
                    if (_site['address'] != null)
                      _ContactInfoCard(
                        icon: Icons.location_on_rounded,
                        label: 'Office',
                        value: _site['address'] as String,
                        color: AppColors.alert,
                      ),
                  ],
                  const SizedBox(height: 24),
                  const Text('Send a message', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 6),
                  const Text('Fill in the form and we\'ll get back to you within 24 hours.', style: TextStyle(color: AppColors.muted, fontSize: 13)),
                  const SizedBox(height: 14),
                  Card(
                    elevation: 0,
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(16),
                      side: const BorderSide(color: AppColors.secondaryContainer),
                    ),
                    child: Padding(
                      padding: const EdgeInsets.all(18),
                      child: Form(
                        key: _formKey,
                        child: Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            _contactField(
                              controller: _name,
                              label: 'Full name',
                              icon: Icons.person_outline,
                              required: true,
                              validator: (v) => v == null || v.trim().isEmpty ? 'Name is required' : null,
                            ),
                            const SizedBox(height: 14),
                            _contactField(
                              controller: _email,
                              label: 'Email address',
                              icon: Icons.email_outlined,
                              keyboard: TextInputType.emailAddress,
                              required: true,
                              validator: (v) {
                                if (v == null || v.trim().isEmpty) return 'Email is required';
                                if (!v.contains('@')) return 'Enter a valid email';
                                return null;
                              },
                            ),
                            const SizedBox(height: 14),
                            _contactField(
                              controller: _phone,
                              label: 'Phone number',
                              icon: Icons.phone_outlined,
                              keyboard: TextInputType.phone,
                            ),
                            const SizedBox(height: 14),
                            _contactField(
                              controller: _subject,
                              label: 'Subject',
                              icon: Icons.subject_rounded,
                            ),
                            const SizedBox(height: 10),
                            Wrap(
                              spacing: 8,
                              runSpacing: 8,
                              children: _subjectSuggestions.map((s) {
                                final selected = _subject.text == s;
                                return FilterChip(
                                  label: Text(s, style: TextStyle(fontSize: 12, color: selected ? Colors.white : AppColors.primary)),
                                  selected: selected,
                                  showCheckmark: false,
                                  selectedColor: AppColors.primary,
                                  backgroundColor: AppColors.secondaryContainer.withValues(alpha: 0.5),
                                  side: BorderSide(color: selected ? AppColors.primary : AppColors.secondaryContainer),
                                  onSelected: (_) => setState(() => _subject.text = s),
                                );
                              }).toList(),
                            ),
                            const SizedBox(height: 14),
                            _contactField(
                              controller: _message,
                              label: 'Your message',
                              icon: Icons.message_outlined,
                              maxLines: 5,
                              required: true,
                              validator: (v) => v == null || v.trim().length < 10 ? 'Please write at least 10 characters' : null,
                            ),
                            const SizedBox(height: 20),
                            SizedBox(
                              width: double.infinity,
                              height: 50,
                              child: ElevatedButton.icon(
                                onPressed: _loading ? null : _submit,
                                icon: _loading
                                    ? const SizedBox.shrink()
                                    : const Icon(Icons.send_rounded, size: 20),
                                label: _loading
                                    ? JetFlyLoader.button()
                                    : const Text('Send Message', style: TextStyle(fontSize: 16, fontWeight: FontWeight.w600)),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: AppColors.bookingBlue,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _contactField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    TextInputType? keyboard,
    int maxLines = 1,
    bool required = false,
    String? Function(String?)? validator,
  }) {
    return TextFormField(
      controller: controller,
      keyboardType: keyboard,
      maxLines: maxLines,
      validator: validator,
      decoration: InputDecoration(
        labelText: required ? '$label *' : label,
        prefixIcon: Icon(icon, color: AppColors.primary, size: 22),
        filled: true,
        fillColor: AppColors.surfaceLow,
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: BorderSide(color: AppColors.secondaryContainer.withValues(alpha: 0.8)),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.primary, width: 1.5),
        ),
        errorBorder: OutlineInputBorder(
          borderRadius: BorderRadius.circular(12),
          borderSide: const BorderSide(color: AppColors.alert),
        ),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
      ),
    );
  }
}

class _ContactInfoCard extends StatelessWidget {
  const _ContactInfoCard({
    required this.icon,
    required this.label,
    required this.value,
    required this.color,
  });

  final IconData icon;
  final String label;
  final String value;
  final Color color;

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 10),
      elevation: 0,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(14),
        side: BorderSide(color: AppColors.secondaryContainer.withValues(alpha: 0.9)),
      ),
      child: Padding(
        padding: const EdgeInsets.all(14),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Container(
              width: 46,
              height: 46,
              decoration: BoxDecoration(
                color: color.withValues(alpha: 0.12),
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(icon, color: color, size: 24),
            ),
            const SizedBox(width: 14),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(label.toUpperCase(), style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: color, letterSpacing: 0.8)),
                  const SizedBox(height: 4),
                  Text(value, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.w600, height: 1.35)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// ─── Announcements ────────────────────────────────────────────────────────────

class AnnouncementsScreen extends StatefulWidget {
  const AnnouncementsScreen({super.key});

  @override
  State<AnnouncementsScreen> createState() => _AnnouncementsScreenState();
}

class _AnnouncementsScreenState extends State<AnnouncementsScreen> {
  List<Map<String, dynamic>> _items = [];
  bool _loading = true;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    try {
      final items = await TravelRepository(context.read<ApiService>()).getAnnouncements();
      setState(() => _items = items);
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Announcements')),
      body: _loading
          ? Center(child: JetFlyLoader.center(message: 'Loading announcements...'))
          : _items.isEmpty
              ? const EmptyState(icon: Icons.campaign_outlined, title: 'No announcements', subtitle: 'You are all caught up!')
              : ListView.builder(
                  padding: const EdgeInsets.all(16),
                  itemCount: _items.length,
                  itemBuilder: (context, i) {
                    final a = _items[i];
                    return Card(
                      margin: const EdgeInsets.only(bottom: 10),
                      child: ListTile(
                        leading: Icon(
                          a['is_read'] == true ? Icons.mark_email_read : Icons.mark_email_unread,
                          color: AppColors.primary,
                        ),
                        title: Text(a['title'] as String? ?? '', style: const TextStyle(fontWeight: FontWeight.w600)),
                        subtitle: Text(a['body'] as String? ?? '', maxLines: 3, overflow: TextOverflow.ellipsis),
                      ),
                    );
                  },
                ),
    );
  }
}
