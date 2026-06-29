import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/models.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../widgets/empty_state.dart';
import '../widgets/jetfly_loader.dart';
import '../widgets/listing_card.dart';
import 'module_detail_screen.dart';

class ModuleListScreen extends StatefulWidget {
  const ModuleListScreen({
    super.key,
    required this.module,
    required this.title,
    this.initialFilters = const {},
  });

  final String module;
  final String title;
  final Map<String, String> initialFilters;

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
    final initialDestination = widget.initialFilters['destination'];
    if (initialDestination != null && initialDestination.isNotEmpty) {
      _searchController.text = initialDestination;
    }
    _load();
  }

  @override
  void dispose() {
    _searchController.dispose();
    _fromController.dispose();
    _toController.dispose();
    super.dispose();
  }

  Future<void> _load({bool refresh = false}) async {
    if (refresh) _page = 1;
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final filters = <String, String>{};
      if (widget.module == 'packages') {
        final dest = _searchController.text.trim();
        if (dest.isNotEmpty) filters['destination'] = dest;
      } else if (_searchController.text.isNotEmpty) {
        filters['q'] = _searchController.text;
      }
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
    final isRouteModule = ['flights', 'buses', 'trains'].contains(widget.module);

    return Scaffold(
      appBar: AppBar(title: Text(widget.title)),
      body: Column(
        children: [
          Container(
            color: Colors.white,
            padding: const EdgeInsets.fromLTRB(16, 12, 16, 12),
            child: isRouteModule
                ? Row(
                    children: [
                      Expanded(child: TextField(controller: _fromController, decoration: const InputDecoration(hintText: 'From', isDense: true, prefixIcon: Icon(Icons.flight_takeoff, size: 20)))),
                      const SizedBox(width: 8),
                      Expanded(child: TextField(controller: _toController, decoration: const InputDecoration(hintText: 'To', isDense: true, prefixIcon: Icon(Icons.flight_land, size: 20)))),
                      const SizedBox(width: 4),
                      IconButton.filled(onPressed: () => _load(refresh: true), icon: const Icon(Icons.search, size: 20)),
                    ],
                  )
                : TextField(
                    controller: _searchController,
                    decoration: InputDecoration(
                      hintText: 'Search ${widget.title.toLowerCase()}...',
                      prefixIcon: const Icon(Icons.search),
                      suffixIcon: IconButton(icon: const Icon(Icons.arrow_forward), onPressed: () => _load(refresh: true)),
                    ),
                    onSubmitted: (_) => _load(refresh: true),
                  ),
          ),
          if (_meta != null && !_loading)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
              child: Align(
                alignment: Alignment.centerLeft,
                child: Text('${_meta!.total} results', style: const TextStyle(color: AppColors.muted, fontSize: 12)),
              ),
            ),
          Expanded(
            child: _loading && _items.isEmpty
                ? Center(child: JetFlyLoader.center(message: 'Searching...'))
                : _error != null
                    ? EmptyState(icon: Icons.error_outline, title: 'Something went wrong', subtitle: _error, actionLabel: 'Retry', onAction: () => _load(refresh: true))
                    : _items.isEmpty
                        ? EmptyState(
                            icon: Icons.search_off,
                            title: 'No ${widget.title.toLowerCase()} found',
                            subtitle: 'Try different search terms or check back later. New listings are added regularly.',
                            actionLabel: 'Clear filters',
                            onAction: () {
                              _searchController.clear();
                              _fromController.clear();
                              _toController.clear();
                              _load(refresh: true);
                            },
                          )
                        : RefreshIndicator(
                            onRefresh: () => _load(refresh: true),
                            child: ListView.builder(
                              padding: const EdgeInsets.all(16),
                              itemCount: _items.length + (_meta != null && _page < _meta!.lastPage ? 1 : 0),
                              itemBuilder: (context, index) {
                                if (index == _items.length) {
                                  return Padding(
                                    padding: const EdgeInsets.symmetric(vertical: 8),
                                    child: Center(
                                      child: _loading
                                          ? const JetFlyLoader(size: 48)
                                          : OutlinedButton(onPressed: () { _page++; _load(); }, child: const Text('Load more')),
                                    ),
                                  );
                                }
                                final item = _items[index];
                                return ListingCard(
                                  item: item,
                                  module: widget.module,
                                  onTap: () => Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) => ModuleDetailScreen(module: widget.module, slug: item.slug, title: item.title),
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
