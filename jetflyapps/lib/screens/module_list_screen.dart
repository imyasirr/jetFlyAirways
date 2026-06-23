import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/models.dart';
import '../providers/auth_provider.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import 'module_detail_screen.dart';

class ModuleListScreen extends StatefulWidget {
  const ModuleListScreen({super.key, required this.module, required this.title});

  final String module;
  final String title;

  @override
  State<ModuleListScreen> createState() => _ModuleListScreenState();
}

class _ModuleListScreenState extends State<ModuleListScreen> {
  late final TravelRepository _repo;
  List<ListingItem> _items = [];
  PaginatedMeta? _meta;
  bool _loading = true;
  String? _error;
  int _page = 1;

  final _searchController = TextEditingController();
  final _fromController = TextEditingController();
  final _toController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _repo = TravelRepository(context.read<ApiService>());
    _load();
  }

  Future<void> _load({bool refresh = false}) async {
    if (refresh) _page = 1;
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final filters = <String, String>{};
      if (_searchController.text.isNotEmpty) filters['q'] = _searchController.text;
      if (_fromController.text.isNotEmpty) filters['from'] = _fromController.text;
      if (_toController.text.isNotEmpty) filters['to'] = _toController.text;

      final result = await _repo.getModuleList(widget.module, filters: filters, page: _page);
      setState(() {
        if (refresh || _page == 1) {
          _items = result.items;
        } else {
          _items = [..._items, ...result.items];
        }
        _meta = result.meta;
      });
    } catch (e) {
      setState(() => _error = e.toString());
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text(widget.title)),
      body: Column(
        children: [
          if (['flights', 'buses', 'trains'].contains(widget.module))
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 0),
              child: Row(
                children: [
                  Expanded(child: TextField(controller: _fromController, decoration: const InputDecoration(hintText: 'From', isDense: true))),
                  const SizedBox(width: 8),
                  Expanded(child: TextField(controller: _toController, decoration: const InputDecoration(hintText: 'To', isDense: true))),
                  IconButton(onPressed: () => _load(refresh: true), icon: const Icon(Icons.search)),
                ],
              ),
            )
          else
            Padding(
              padding: const EdgeInsets.fromLTRB(16, 12, 16, 0),
              child: TextField(
                controller: _searchController,
                decoration: InputDecoration(
                  hintText: 'Search ${widget.title.toLowerCase()}...',
                  prefixIcon: const Icon(Icons.search),
                  suffixIcon: IconButton(icon: const Icon(Icons.arrow_forward), onPressed: () => _load(refresh: true)),
                ),
              ),
            ),
          Expanded(
            child: _loading && _items.isEmpty
                ? const Center(child: CircularProgressIndicator())
                : _error != null
                    ? Center(child: Text(_error!, style: const TextStyle(color: AppColors.alert)))
                    : _items.isEmpty
                        ? const Center(child: Text('No results found'))
                        : RefreshIndicator(
                            onRefresh: () => _load(refresh: true),
                            child: ListView.builder(
                              padding: const EdgeInsets.all(16),
                              itemCount: _items.length + (_meta != null && _page < _meta!.lastPage ? 1 : 0),
                              itemBuilder: (context, index) {
                                if (index == _items.length) {
                                  return TextButton(
                                    onPressed: () {
                                      _page++;
                                      _load();
                                    },
                                    child: const Text('Load more'),
                                  );
                                }
                                final item = _items[index];
                                return Card(
                                  margin: const EdgeInsets.only(bottom: 10),
                                  child: ListTile(
                                    title: Text(item.title, style: const TextStyle(fontWeight: FontWeight.w600)),
                                    subtitle: Text(item.subtitle, maxLines: 2, overflow: TextOverflow.ellipsis),
                                    trailing: Text('₹${item.price.toStringAsFixed(0)}', style: const TextStyle(fontWeight: FontWeight.bold, color: AppColors.primary)),
                                    onTap: () => Navigator.push(
                                      context,
                                      MaterialPageRoute(
                                        builder: (_) => ModuleDetailScreen(module: widget.module, slug: item.slug, title: item.title),
                                      ),
                                    ),
                                  ),
                                );
                              },
                            ),
                          ),
          ),
        ],
      ),
    );
  }
}
