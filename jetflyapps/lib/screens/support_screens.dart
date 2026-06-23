import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../widgets/empty_state.dart';

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
          ? const Center(child: CircularProgressIndicator())
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
          ? const Center(child: CircularProgressIndicator())
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
          ? const Center(child: CircularProgressIndicator())
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
          ? const Center(child: CircularProgressIndicator())
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
  final _name = TextEditingController();
  final _email = TextEditingController();
  final _phone = TextEditingController();
  final _subject = TextEditingController();
  final _message = TextEditingController();
  Map<String, dynamic> _site = {};
  bool _loading = false;
  bool _siteLoading = true;

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

  Future<void> _loadSite() async {
    try {
      final site = await TravelRepository(context.read<ApiService>()).getSiteInfo();
      setState(() => _site = site);
    } catch (_) {
    } finally {
      setState(() => _siteLoading = false);
    }
  }

  Future<void> _submit() async {
    if (_name.text.isEmpty || _email.text.isEmpty || _message.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Please fill required fields')));
      return;
    }
    setState(() => _loading = true);
    try {
      final msg = await TravelRepository(context.read<ApiService>()).submitContact({
        'name': _name.text.trim(),
        'email': _email.text.trim(),
        'phone': _phone.text.trim(),
        'subject': _subject.text.trim(),
        'message': _message.text.trim(),
      });
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
        _message.clear();
        _subject.clear();
      }
    } catch (e) {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(e.toString())));
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Contact Us')),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          if (!_siteLoading && _site.isNotEmpty)
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    if (_site['phone'] != null) ListTile(dense: true, leading: const Icon(Icons.phone, color: AppColors.primary), title: Text(_site['phone'] as String)),
                    if (_site['email'] != null) ListTile(dense: true, leading: const Icon(Icons.email_outlined, color: AppColors.primary), title: Text(_site['email'] as String)),
                    if (_site['address'] != null) ListTile(dense: true, leading: const Icon(Icons.location_on_outlined, color: AppColors.primary), title: Text(_site['address'] as String)),
                  ],
                ),
              ),
            ),
          const SizedBox(height: 16),
          TextField(controller: _name, decoration: const InputDecoration(labelText: 'Name *')),
          const SizedBox(height: 12),
          TextField(controller: _email, decoration: const InputDecoration(labelText: 'Email *'), keyboardType: TextInputType.emailAddress),
          const SizedBox(height: 12),
          TextField(controller: _phone, decoration: const InputDecoration(labelText: 'Phone'), keyboardType: TextInputType.phone),
          const SizedBox(height: 12),
          TextField(controller: _subject, decoration: const InputDecoration(labelText: 'Subject')),
          const SizedBox(height: 12),
          TextField(controller: _message, decoration: const InputDecoration(labelText: 'Message *', alignLabelWithHint: true), maxLines: 5),
          const SizedBox(height: 20),
          SizedBox(
            width: double.infinity,
            child: ElevatedButton(
              onPressed: _loading ? null : _submit,
              child: _loading ? const SizedBox(height: 20, width: 20, child: CircularProgressIndicator(strokeWidth: 2, color: Colors.white)) : const Text('Send Message'),
            ),
          ),
        ],
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
          ? const Center(child: CircularProgressIndicator())
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
