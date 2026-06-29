import 'package:flutter/material.dart';

IconData trustCardIcon(String? name) {
  switch (name?.trim().toLowerCase()) {
    case 'lock':
      return Icons.lock_outline;
    case 'savings':
      return Icons.savings_outlined;
    case 'dashboard':
      return Icons.dashboard_outlined;
    case 'verified':
    case 'verified_user':
      return Icons.verified_user_outlined;
    case 'support_agent':
      return Icons.support_agent_outlined;
    case 'price_check':
      return Icons.price_check_outlined;
    case 'workspace_premium':
      return Icons.workspace_premium_outlined;
    case 'shield':
      return Icons.shield_outlined;
    case 'flight':
      return Icons.flight_outlined;
    case 'hotel':
      return Icons.hotel_outlined;
    case 'security':
      return Icons.security_outlined;
    case 'payments':
      return Icons.payments_outlined;
    default:
      return Icons.verified_outlined;
  }
}
