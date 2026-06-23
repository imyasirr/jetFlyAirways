import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/models.dart';
import '../services/api_service.dart';
import '../services/travel_repository.dart';
import '../theme/app_theme.dart';
import '../widgets/empty_state.dart';
import '../widgets/module_tile.dart';
import 'module_list_screen.dart';

class ExploreScreen extends StatefulWidget {
  const ExploreScreen({super.key});

  @override
  State<ExploreScreen> createState() => _ExploreScreenState();
}

class _ExploreScreenState extends State<ExploreScreen> {
  List<ModuleInfo> _modules = [];
  bool _loading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _load();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });
    try {
      final modules = await TravelRepository(context.read<ApiService>()).getModules();
      setState(() => _modules = modules);
    } catch (e) {
      setState(() => _error = e.toString());
    } finally {
      setState(() => _loading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Explore')),
      body: _loading
          ? const Center(child: CircularProgressIndicator())
          : _error != null
              ? EmptyState(
                  icon: Icons.wifi_off,
                  title: 'Could not load modules',
                  subtitle: _error,
                  actionLabel: 'Retry',
                  onAction: _load,
                )
              : RefreshIndicator(
                  onRefresh: _load,
                  child: ListView(
                    padding: const EdgeInsets.all(16),
                    children: [
                      Container(
                        padding: const EdgeInsets.all(20),
                        decoration: BoxDecoration(
                          gradient: const LinearGradient(
                            colors: [AppColors.bookingBlue, AppColors.primary],
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                          ),
                          borderRadius: BorderRadius.circular(16),
                        ),
                        child: const Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Text('Explore everything', style: TextStyle(color: Colors.white, fontSize: 22, fontWeight: FontWeight.bold)),
                            SizedBox(height: 6),
                            Text('Flights, hotels, buses, trains, cabs, visa & insurance — all in one app.', style: TextStyle(color: Colors.white70, fontSize: 13)),
                          ],
                        ),
                      ),
                      const SizedBox(height: 20),
                      ..._modules.map((m) => Padding(
                            padding: const EdgeInsets.only(bottom: 12),
                            child: ModuleTile(
                              module: m,
                              large: true,
                              onTap: () => Navigator.push(
                                context,
                                MaterialPageRoute(builder: (_) => ModuleListScreen(module: m.key, title: m.title)),
                              ),
                            ),
                          )),
                    ],
                  ),
                ),
    );
  }
}
