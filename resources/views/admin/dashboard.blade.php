@extends('layouts.app')

@section('title', 'Dashboard - Boothcare')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* Modern Dashboard Styles */
    .dashboard-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow-x: hidden;
    }

    .dashboard-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.15) 0%, transparent 50%),
            radial-gradient(circle at 40% 40%, rgba(120, 119, 198, 0.2) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Mobile App Header */
    .mobile-app-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 20px 20px 30px 20px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .mobile-app-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 100%;
        height: 200%;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: rotate(-15deg);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .app-logo {
        width: 48px;
        height: 48px;
        background: rgba(255,255,255,0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .header-text h1 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
        color: white;
    }

    .header-text p {
        margin: 2px 0 0 0;
        font-size: 0.85rem;
        color: rgba(255,255,255,0.8);
    } 
   .notification-btn {
        width: 44px;
        height: 44px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        position: relative;
    }

    .notification-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: #ff4757;
        color: white;
        border-radius: 10px;
        padding: 2px 6px;
        font-size: 0.7rem;
        font-weight: 600;
        min-width: 18px;
        text-align: center;
    }

    /* Mobile App Dashboard */
    .mobile-app-dashboard {
        padding: 0 0 100px 0;
        background: transparent;
        position: relative;
        z-index: 2;
    }

    /* Section Styles */
    .stats-section,
    .chart-section,
    .activity-section,
    .actions-section {
        margin-bottom: 24px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 20px 16px 20px;
    }

    .section-header h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .refresh-btn {
        width: 36px;
        height: 36px;
        background: #f1f3f4;
        border: none;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #5f6368;
        font-size: 14px;
    }

    .view-all-btn {
        color: #1a73e8;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .period-text {
        color: #5f6368;
        font-size: 0.85rem;
        font-weight: 500;
    }    /*
 Stats Container */
    .stats-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 0 20px;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 24px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border-radius: 20px 20px 0 0;
    }

    .stat-item.primary {
        --gradient-start: #667eea;
        --gradient-end: #764ba2;
    }

    .stat-item.success {
        --gradient-start: #11998e;
        --gradient-end: #38ef7d;
    }

    .stat-item.danger {
        --gradient-start: #ff6b6b;
        --gradient-end: #ee5a52;
    }

    .stat-item.warning {
        --gradient-start: #feca57;
        --gradient-end: #ff9ff3;
    }

    .stat-item:active {
        transform: scale(0.98);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 1.6rem;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.95);
        line-height: 1.2;
        margin-bottom: 2px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .stat-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        opacity: 0.9;
    }

    .stat-item.primary .stat-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stat-item.success .stat-icon {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .stat-item.danger .stat-icon {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    }

    .stat-item.warning .stat-icon {
        background: linear-gradient(135deg, #feca57, #ff9ff3);
    } 
   /* Chart Card Mobile */
    .chart-card-mobile {
        background: white;
        border-radius: 20px;
        margin: 0 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f1f3f4;
        overflow: hidden;
    }

    .chart-card-mobile {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        margin: 0 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        overflow: hidden;
        position: relative;
    }

    .chart-card-mobile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        border-radius: 24px 24px 0 0;
    }

    /* Mobile Visual Chart Styles */
    .mobile-visual-chart {
        background: white;
        border-radius: 24px;
        margin: 0 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        overflow: hidden;
        position: relative;
    }

    .mobile-visual-chart::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        border-radius: 24px 24px 0 0;
    }

    .visual-chart-container {
        padding: 20px;
    }

    /* Status Breakdown Styles */
    .status-breakdown {
        margin-bottom: 24px;
    }

    .status-item {
        margin-bottom: 16px;
        padding: 12px;
        border-radius: 12px;
        background: #f8fafc;
    }

    .status-item.reported {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 4px solid #f59e0b;
    }

    .status-item.in-progress {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-left: 4px solid #3b82f6;
    }

    .status-item.resolved {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left: 4px solid #10b981;
    }

    .status-bar {
        width: 100%;
        height: 8px;
        background: rgba(255, 255, 255, 0.5);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .status-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.8s ease;
    }

    .status-item.reported .status-fill {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .status-item.in-progress .status-fill {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .status-item.resolved .status-fill {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .status-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.9rem;
    }

    .status-count {
        font-weight: 800;
        color: #1f2937;
        font-size: 1.1rem;
    }

    /* Priority Section Styles */
    .priority-section {
        margin-top: 24px;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 16px;
        text-align: center;
    }

    .priority-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .priority-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .priority-card.high {
        border-color: #ef4444;
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }

    .priority-card.medium {
        border-color: #f59e0b;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    .priority-card.low {
        border-color: #10b981;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }

    .priority-card.urgent {
        border-color: #dc2626;
        background: linear-gradient(135deg, #fef2f2 0%, #fecaca 100%);
    }

    .priority-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .priority-info {
        flex: 1;
        text-align: center;
    }

    .priority-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-count {
        display: block;
        font-size: 1.4rem;
        font-weight: 800;
        color: #1f2937;
    }

    .mobile-chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 24px 0 24px;
        margin-bottom: 15px;
    }

    .mobile-chart-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .chart-controls {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .period-selector select,
    #mobilePeriodSelector {
        min-width: 120px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        color: #374151;
        transition: all 0.3s ease;
    }

    .period-selector select:focus,
    #mobilePeriodSelector:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.6);
        box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
        transform: translateY(-1px);
    }

    .chart-wrapper {
        height: 240px;
        padding: 24px 24px 0 24px;
        position: relative;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        margin: 0 16px;
        backdrop-filter: blur(10px);
    }

    .chart-summary {
        display: flex;
        justify-content: space-around;
        padding: 20px 24px 24px 24px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 0 0 24px 24px;
    }

    .summary-item {
        text-align: center;
    }

    .summary-label {
        display: block;
        font-size: 0.75rem;
        color: #5f6368;
        font-weight: 500;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-value {
        display: block;
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    /* Activity List */
    .activity-list {
        padding: 0 20px;
    }

    .activity-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .activity-card:active {
        transform: scale(0.98);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    } 
   .activity-indicator {
        width: 4px;
        height: 40px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    .activity-indicator.low {
        background: #34d399;
    }

    .activity-indicator.medium {
        background: #fbbf24;
    }

    .activity-indicator.high {
        background: #f87171;
    }

    .activity-indicator.urgent {
        background: #dc2626;
    }

    .activity-details {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .activity-meta {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .activity-time {
        font-size: 0.8rem;
        color: #6b7280;
        font-weight: 500;
    }

    .activity-status {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .status-reported {
        background: #fef3c7;
        color: #92400e;
    }

    .status-in_progress {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-resolved {
        background: #d1fae5;
        color: #065f46;
    }

    .activity-arrow {
        color: #d1d5db;
        font-size: 12px;
    }    
.empty-state {
        text-align: center;
        padding: 40px 20px;
        background: white;
        border-radius: 16px;
        margin: 0 20px;
        border: 1px solid #f1f3f4;
    }

    .empty-icon {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 24px;
        color: #9ca3af;
    }

    .empty-text h3 {
        margin: 0 0 8px 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
    }

    .empty-text p {
        margin: 0;
        font-size: 0.9rem;
        color: #6b7280;
    }

    /* Action Buttons */
    .action-buttons {
        padding: 0 20px;
    }

    .action-button {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 20px;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .action-button:active {
        transform: scale(0.98);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .action-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: all 0.2s ease;
    }    
.action-button.primary::before {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .action-button.danger::before {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    }

    .action-button.success::before {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .action-button.info::before {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .action-button-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        color: white;
        flex-shrink: 0;
    }

    .action-button.primary .action-button-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .action-button.danger .action-button-icon {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    }

    .action-button.success .action-button-icon {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .action-button.info .action-button-icon {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }

    .action-button-text {
        flex: 1;
        min-width: 0;
    }

    .action-title {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 2px;
        line-height: 1.3;
    }

    .action-subtitle {
        display: block;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }

    .action-arrow {
        color: #d1d5db;
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .action-button:hover .action-arrow {
        color: #9ca3af;
        transform: translateX(2px);
    } 
   /* Desktop Header */
    .desktop-header {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin: 24px 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .desktop-title {
        color: #1a1a1a;
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }

    .desktop-subtitle {
        color: #666;
        margin: 4px 0 0 0;
        font-size: 1rem;
    }

    .desktop-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .desktop-action-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        text-decoration: none;
        color: #495057;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .desktop-action-btn:hover {
        background: #4285f4;
        color: white;
        border-color: #4285f4;
        transform: translateY(-1px);
    }

    .desktop-export-actions {
        margin-top: 8px;
    }

    .export-btn {
        background: #4285f4;
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
        font-size: 0.9rem;
    }

    .export-btn:hover {
        background: #3367d6;
        color: white;
    } 
   /* Desktop Stats Cards */
    .desktop-stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 20px;
        height: 120px;
        margin-top: 8px;
        margin-bottom: 16px;
    }

    .desktop-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .stat-icon-wrapper.primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stat-icon-wrapper.success {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .stat-icon-wrapper.danger {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    }

    .stat-icon-wrapper.warning {
        background: linear-gradient(135deg, #feca57, #ff9ff3);
    }

    .stat-details {
        flex: 1;
    }

    .stat-details h3 {
        font-size: 2.2rem;
        font-weight: 800;
        color: #1a1a1a;
        margin: 0 0 4px 0;
        line-height: 1;
    }

    .stat-details p {
        color: #666;
        margin: 0 0 8px 0;
        font-weight: 500;
        font-size: 1rem;
    }

    .stat-trend {
        font-size: 0.85rem;
        font-weight: 600;
    }

    .stat-trend.positive {
        color: #28a745;
    }

    .stat-trend.negative {
        color: #dc3545;
    }   
 /* Desktop Wrapper Content */
    .desktop-wrapper-content {
        padding: 40px 0;
        position: relative;
        z-index: 2;
    }

    /* Desktop Chart Card */
    .desktop-chart-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        height: 450px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .chart-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
    }

    .chart-actions {
        display: flex;
        gap: 8px;
    }

    .main-chart {
        height: 360px;
    }

    /* Desktop Activity Card */
    .desktop-activity-card,
    .desktop-actions-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        height: 400px;
        display: flex;
        flex-direction: column;
    }

    .card-header {
        padding: 20px 24px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .card-header h4 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a1a1a;
    }

    .view-all-link {
        color: #1a73e8;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .activity-list-desktop {
        flex: 1;
        padding: 16px 24px;
        overflow-y: auto;
    }  
  .desktop-activity-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 12px 0;
        border-bottom: 1px solid #f8f9fa;
    }

    .desktop-activity-item:last-child {
        border-bottom: none;
    }

    .empty-activity-desktop {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .empty-activity-desktop i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        opacity: 0.5;
    }

    /* Desktop Action List */
    .desktop-action-list {
        flex: 1;
        padding: 16px 24px;
    }

    .desktop-action-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px 0;
        text-decoration: none;
        color: inherit;
        border-bottom: 1px solid #f8f9fa;
        transition: all 0.2s ease;
    }

    .desktop-action-item:hover {
        background: #f8f9fa;
        margin: 0 -24px;
        padding: 16px 24px;
        border-radius: 8px;
    }

    .desktop-action-item:last-child {
        border-bottom: none;
    }

    .desktop-action-item .action-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
    }

    .desktop-action-item .action-icon.primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .desktop-action-item .action-icon.danger {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
    }

    .desktop-action-item .action-icon.success {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .desktop-action-item .action-icon.info {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    }    .a
ction-text {
        flex: 1;
    }

    .action-title {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 2px;
    }

    .action-subtitle {
        display: block;
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }

    /* Mobile Responsive - Enhanced */
    @media (max-width: 768px) {
        body {
            background: #f8fafc !important;
            overflow-x: hidden;
        }

        .desktop-header {
            display: none !important;
        }

        /* Hide desktop sections on mobile */
        .desktop-dashboard {
            display: none !important;
        }

        /* Show mobile sections */
        .mobile-app-dashboard {
            display: block !important;
        }

        /* Ensure full mobile experience */
        .main-content {
            margin-left: 0 !important;
            padding-top: 0 !important;
            background: #f8fafc !important;
            width: 100% !important;
            max-width: 100vw !important;
        }

        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            max-width: 100% !important;
        }

        /* Mobile app header fixes */
        .mobile-app-header {
            margin-top: 0 !important;
            padding: 15px 15px 25px 15px !important;
            width: 100%;
            box-sizing: border-box;
        }

        .header-content {
            width: 100%;
            max-width: 100%;
        }

        .header-left {
            flex: 1;
            min-width: 0;
        }

        .header-text h1 {
            font-size: 1.2rem;
            margin: 0;
        }

        .header-text p {
            font-size: 0.8rem;
            margin: 2px 0 0 0;
        }

        /* Stats container improvements */
        .stats-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 0 15px;
            width: 100%;
            box-sizing: border-box;
        }

        .stat-item {
            padding: 12px 10px;
            min-height: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-content {
            flex: 1;
            min-width: 0;
        }

        .stat-number {
            font-size: 1.2rem;
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .stat-label {
            font-size: 0.7rem;
            line-height: 1.2;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Section headers */
        .section-header {
            padding: 15px 15px 10px 15px;
        }

        .section-header h2 {
            font-size: 1.1rem;
        }

        /* Chart improvements - Make mobile visual chart visible */
        .chart-section {
            display: block !important;
        }

        .mobile-visual-chart {
            margin: 0 15px;
            width: calc(100% - 30px);
            box-sizing: border-box;
            display: block !important;
        }

        .visual-chart-container {
            padding: 16px;
        }

        .mobile-chart-header {
            display: flex !important;
            padding: 16px 16px 0 16px;
        }

        .status-breakdown {
            margin-bottom: 20px;
        }

        .status-item {
            margin-bottom: 12px;
            padding: 10px;
        }

        .priority-grid {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .priority-card {
            padding: 12px 8px;
        }

        .priority-icon {
            font-size: 1.2rem;
        }

        .priority-count {
            font-size: 1.2rem;
        }

        .status-label {
            font-size: 0.85rem;
        }

        .status-count {
            font-size: 1rem;
        }

        /* Activity and action lists */
        .activity-list,
        .action-buttons {
            padding: 0 15px;
        }

        .activity-card,
        .action-button {
            padding: 12px;
            margin-bottom: 10px;
        }

        .activity-card {
            gap: 10px;
        }

        .activity-title {
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .activity-time {
            font-size: 0.75rem;
        }

        .activity-status {
            font-size: 0.7rem;
            padding: 1px 6px;
        }

        /* Action buttons */
        .action-button {
            gap: 12px;
        }

        .action-button-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .action-title {
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .action-subtitle {
            font-size: 0.75rem;
            line-height: 1.2;
        }

        /* Notification button */
        .notification-btn {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
        }

        /* Empty states */
        .empty-state {
            margin: 0 15px;
            padding: 30px 15px;
        }

        .empty-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .empty-text h3 {
            font-size: 1rem;
        }

        .empty-text p {
            font-size: 0.85rem;
        }

        /* Fix mobile navigation positioning */
        .mobile-nav {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1060 !important;
            height: 64px !important;
        }

        .mobile-nav .container-fluid {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
        }

        .mobile-nav .navbar-brand {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .mobile-nav .navbar-brand img {
            max-height: 40px;
            width: auto;
        }

        .mobile-nav .navbar-toggler {
            position: relative;
            z-index: 1061;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            color: white;
            font-size: 16px;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .mobile-nav .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
            outline: none;
        }

        /* Adjust main content for mobile nav */
        .dashboard-wrapper {
            padding-top: 64px;
        }

        .mobile-app-header {
            margin-top: 0;
        }
    }

    /* Extra small mobile devices */
    @media (max-width: 480px) {
        .stats-container {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .stat-item {
            padding: 15px 12px;
        }

        .header-text h1 {
            font-size: 1.1rem;
        }

        .header-text p {
            font-size: 0.75rem;
        }

        .action-button {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }

        .action-button-text {
            text-align: center;
        }

        .chart-wrapper {
            height: 140px;
        }
    }   
 /* Desktop Responsive */
    @media (min-width: 992px) {
        .dashboard-wrapper {
            background: #f8fafc;
            min-height: 100vh;
        }

        .desktop-dashboard {
            padding-top: 0;
        }

        /* Hide mobile sections on desktop */
        .mobile-app-dashboard,
        .chart-section {
            display: none !important;
        }

        /* Show desktop wrapper content */
        .desktop-wrapper-content {
            display: block !important;
        }
    }

    /* Loading Animation */
    .loading {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Chart Error Styles */
    .chart-error {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #666;
        text-align: center;
    }

    .chart-error i {
        font-size: 2rem;
        margin-bottom: 8px;
        opacity: 0.5;
    }

    .chart-error p {
        margin: 0;
        font-size: 0.9rem;
    }

    /* Smooth Transitions */
    .mobile-stat-card,
    .mobile-chart-card,
    .mobile-activity-card,
    .mobile-actions-card,
    .chart-card {
        transition: all 0.3s ease;
    }

    /* Hover Effects */
    .mobile-stat-card:hover,
    .chart-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    /* Focus States for Accessibility */
    .action-item:focus,
    .mobile-stat-card:focus {
        outline: 2px solid #4285f4;
        outline-offset: 2px;
    }

    /* Haptic Feedback Simulation */
    .haptic-feedback:active {
        animation: hapticPulse 0.1s ease-out;
    }

    @keyframes hapticPulse {
        0% { transform: scale(1); }
        50% { transform: scale(0.98); }
        100% { transform: scale(1); }
    }

    /* ✨ STUNNING PROBLEMS OVERVIEW STYLES ✨ */
    
    /* Enhanced Chart Header */
    .chart-title-container {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .chart-icon-wrapper {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        animation: iconPulse 2s ease-in-out infinite;
    }

    @keyframes iconPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4); }
        50% { transform: scale(1.05); box-shadow: 0 12px 35px rgba(102, 126, 234, 0.6); }
    }

    .chart-title-text h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 800;
        background: linear-gradient(135deg, #ffffff 0%, #f0f9ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-shadow: none;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    .chart-subtitle {
        margin: 2px 0 0 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Modern Period Selector */
    .period-selector-wrapper {
        position: relative;
    }

    .modern-select {
        min-width: 140px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 16px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 700;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
        backdrop-filter: blur(20px);
        color: #374151;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        appearance: none;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .modern-select:focus {
        outline: none;
        border-color: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3), 0 8px 30px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .period-selector-wrapper::after {
        content: '▼';
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        font-size: 12px;
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .period-selector-wrapper:hover::after {
        transform: translateY(-50%) rotate(180deg);
    }

    /* Stunning Chart Background Effects */
    .chart-background-effects {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        overflow: hidden;
        border-radius: 16px;
    }

    .chart-glow-1, .chart-glow-2, .chart-glow-3 {
        position: absolute;
        border-radius: 50%;
        filter: blur(40px);
        opacity: 0.6;
        animation: float 6s ease-in-out infinite;
    }

    .chart-glow-1 {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        top: -20px;
        left: -20px;
        animation-delay: 0s;
    }

    .chart-glow-2 {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #f093fb, #f5576c);
        bottom: -10px;
        right: -10px;
        animation-delay: 2s;
    }

    .chart-glow-3 {
        width: 100px;
        height: 100px;
        background: linear-gradient(135deg, #4facfe, #00f2fe);
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        33% { transform: translateY(-10px) rotate(120deg); }
        66% { transform: translateY(5px) rotate(240deg); }
    }

    /* Live Data Indicator */
    .chart-overlay-stats {
        position: absolute;
        top: 16px;
        right: 16px;
        z-index: 10;
    }

    .live-indicator {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 8px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.7; transform: scale(1.2); }
    }

    /* Enhanced Chart Summary */
    .summary-label {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 600 !important;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .summary-value {
        color: rgba(255, 255, 255, 0.95) !important;
        font-weight: 800 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        font-size: 1.6rem !important;
    }

    /* Mobile Responsive Enhancements */
    @media (max-width: 768px) {
        .chart-title-container {
            gap: 12px;
        }

        .chart-icon-wrapper {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .chart-title-text h4 {
            font-size: 16px;
        }

        .chart-subtitle {
            font-size: 11px;
        }

        .modern-select {
            min-width: 120px;
            padding: 10px 14px;
            font-size: 12px;
        }

        .chart-glow-1, .chart-glow-2, .chart-glow-3 {
            filter: blur(20px);
        }
    }
</style>
@endpush

@section('content')

<!-- Remove all top gaps and make fully responsive -->
<style>
    /* Remove all top gaps globally */
    .main-content {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }
    
    /* Dashboard specific responsive fixes */
    .dashboard-wrapper {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
    
    .mobile-app-header {
        margin-top: 0 !important;
        padding-top: 15px !important;
    }
    
    .desktop-header {
        margin-top: 0 !important;
        padding-top: 15px !important;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        
        .mobile-app-header {
            padding: 15px 15px 25px 15px !important;
        }
        
        .stats-container {
            padding: 0 15px !important;
        }
        
        .section-header {
            padding: 15px 15px 10px 15px !important;
        }
        
        .chart-card-mobile {
            margin: 0 15px !important;
        }
        
        .activity-list,
        .action-buttons {
            padding: 0 15px !important;
        }
    }
    
    @media (min-width: 769px) {
        .desktop-header {
            margin: 0 !important;
            padding: 20px !important;
        }
        
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
    }
    
    /* Remove any inherited top spacing */
    * {
        margin-top: 0;
    }
    
    .row {
        margin-top: 0 !important;
    }
    
    .col-md-3, .col-md-6, .col-md-8, .col-md-4 {
        padding-top: 0 !important;
    }
</style>

<div class="dashboard-wrapper">
    <!-- Desktop Stats and Chart Section -->
    <div class="desktop-wrapper-content d-none d-lg-block">
        <div class="container-fluid">
            <!-- Desktop Stats Cards in Wrapper -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="desktop-stat-card primary">
                        <div class="stat-icon-wrapper primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_members'] ?? 0 }}</h3>
                            <p>Total Members</p>
                            <div class="stat-trend {{ ($stats['members_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($stats['members_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                {{ abs($stats['members_trend'] ?? 0) }}% from last week
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="desktop-stat-card success">
                        <div class="stat-icon-wrapper success">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['total_houses'] ?? 0 }}</h3>
                            <p>Total Houses</p>
                            <div class="stat-trend {{ ($stats['houses_trend'] ?? 0) >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ ($stats['houses_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                {{ abs($stats['houses_trend'] ?? 0) }}% from last week
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="desktop-stat-card danger">
                        <div class="stat-icon-wrapper danger">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ $stats['active_problems'] ?? 0 }}</h3>
                            <p>Active Problems</p>
                            <div class="stat-trend {{ ($stats['problems_trend'] ?? 0) >= 0 ? 'negative' : 'positive' }}">
                                <i class="fas fa-arrow-{{ ($stats['problems_trend'] ?? 0) >= 0 ? 'up' : 'down' }}"></i>
                                {{ abs($stats['problems_trend'] ?? 0) }}% from last week
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="desktop-stat-card warning">
                        <div class="stat-icon-wrapper warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-details">
                            <h3>{{ number_format($stats['resolution_rate'] ?? 0) }}%</h3>
                            <p>Resolution Rate</p>
                            <div class="stat-trend positive">
                                <i class="fas fa-check-circle"></i>
                                Good Performance
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Desktop Chart in Wrapper -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="desktop-chart-card">
                        <div class="chart-header">
                            <div class="chart-title-container">
                                <div class="chart-icon-wrapper">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h3 class="chart-title">Problems Overview</h3>
                            </div>
                            <div class="chart-controls">
                                <div class="period-selector">
                                    <select id="desktopPeriodSelector" class="form-select" onchange="changePeriod(this.value)">
                                        <option value="day" {{ ($period ?? 'day') == 'day' ? 'selected' : '' }}>Daily</option>
                                        <option value="month" {{ ($period ?? 'day') == 'month' ? 'selected' : '' }}>Monthly</option>
                                        <option value="year" {{ ($period ?? 'day') == 'year' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary" onclick="refreshChart()">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="main-chart">
                            <canvas id="wrapperChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Quick Actions -->
            <div class="row">
                <div class="col-lg-8 mb-4">
                    <div class="desktop-activity-card">
                        <div class="card-header">
                            <h4>Recent Activity</h4>
                            <a href="{{ route('problems.index') }}" class="view-all-link">View All</a>
                        </div>
                        <div class="activity-list-desktop">
                            @if(isset($recent_problems) && count($recent_problems) > 0)
                                @foreach(array_slice($recent_problems->toArray(), 0, 5) as $problem)
                                <div class="desktop-activity-item">
                                    <div class="activity-indicator {{ strtolower($problem['priority'] ?? 'medium') }}"></div>
                                    <div class="activity-content">
                                        <div class="activity-title">{{ $problem['title'] ?? 'New Problem' }}</div>
                                        <div class="activity-meta">
                                            <span class="activity-time">{{ \Carbon\Carbon::parse($problem['created_at'])->diffForHumans() }}</span>
                                            <span class="activity-status status-{{ strtolower($problem['status'] ?? 'pending') }}">
                                                {{ ucfirst($problem['status'] ?? 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="empty-activity-desktop">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>No recent activity</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 mb-4">
                    <div class="desktop-actions-card">
                        <div class="card-header">
                            <h4>Quick Actions</h4>
                        </div>
                        <div class="desktop-action-list">
                            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('members.create'))
                            <a href="{{ route('members.create') }}" class="desktop-action-item">
                                <div class="action-icon primary">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Add Member</span>
                                    <span class="action-subtitle">Register new family member</span>
                                </div>
                            </a>
                            @endif
                            
                            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
                            <a href="{{ route('problems.create') }}" class="desktop-action-item">
                                <div class="action-icon danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Report Problem</span>
                                    <span class="action-subtitle">Submit new issue</span>
                                </div>
                            </a>
                            @endif
                            
                            @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('houses.create'))
                            <a href="{{ route('houses.create') }}" class="desktop-action-item">
                                <div class="action-icon success">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">Add House</span>
                                    <span class="action-subtitle">Register new house</span>
                                </div>
                            </a>
                            @endif
                            
                            <a href="{{ route('reports.index') }}" class="desktop-action-item">
                                <div class="action-icon info">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div class="action-text">
                                    <span class="action-title">View Reports</span>
                                    <span class="action-subtitle">Analytics & insights</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile App-like Header -->
    <div class="mobile-app-header d-block d-md-none">
        <div class="header-content">
            <div class="header-left">
                <div class="app-logo">
                    <img src="{{ asset('images/logo_for_dashboard.png') }}" alt="Boothcare" style="width: 240px; height: 50px; object-fit: contain; border-radius: 8px;">
                </div>
                <div class="header-text">
                    <h1>Dashboard</h1>
                    <p>Welcome back, {{ Auth::user()->name }}</p>
                </div>
            </div>
            <div class="header-right">
                <button class="notification-btn">
                    <i class="fas fa-bell"></i>
                    @if(($stats['urgent_problems'] ?? 0) > 0)
                        <span class="notification-badge">{{ $stats['urgent_problems'] }}</span>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Desktop Dashboard Header -->
    <div class="desktop-header d-none d-md-block" style="margin-top: 20px !important; margin-bottom: 30px !important;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="desktop-title">System Summary</h1>
                    <p class="desktop-subtitle">Showing: {{ $chartData['dates'][0] ?? 'Today' }} - {{ $chartData['dates'][9] ?? 'Today' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <div class="desktop-actions mb-2">
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('members.create'))
                        <a href="{{ route('members.create') }}" class="desktop-action-btn">
                            <i class="fas fa-user-plus"></i>
                            <span>Add Member</span>
                        </a>
                        @endif
                        
                        @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
                        <a href="{{ route('problems.create') }}" class="desktop-action-btn">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Report Issue</span>
                        </a>
                        @endif
                        
                        <button class="desktop-action-btn" onclick="refreshDashboard()">
                            <i class="fas fa-sync-alt" id="refreshIcon"></i>
                            <span>Refresh</span>
                        </button>
                    </div>
                    
                    <div class="desktop-export-actions">
                        <button class="export-btn me-2" onclick="exportData()">
                            <i class="fas fa-download me-2"></i>Export
                        </button>
                        <button class="export-btn" onclick="shareData()">
                            <i class="fas fa-share me-2"></i>Share
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>   
 <!-- Mobile App Dashboard -->
    <div class="mobile-app-dashboard d-block d-lg-none">
        <!-- Stats Overview -->
        <div class="stats-section">
            <div class="section-header">
                <h2>Overview</h2>
                <button class="refresh-btn" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt" id="refreshIcon"></i>
                </button>
            </div>
            
            <div class="stats-container">
                <div class="stat-item primary">
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_members'] ?? 0 }}</div>
                        <div class="stat-label">Members</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                
                <div class="stat-item success">
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_houses'] ?? 0 }}</div>
                        <div class="stat-label">Houses</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                </div>
                
                <div class="stat-item danger">
                    <div class="stat-content">
                        <div class="stat-number">{{ $stats['total_problems'] ?? 0 }}</div>
                        <div class="stat-label">Problems</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
                
                <div class="stat-item warning">
                    <div class="stat-content">
                        <div class="stat-number">{{ number_format($stats['resolution_rate'] ?? 0) }}%</div>
                        <div class="stat-label">Resolved</div>
                    </div>
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div> 
       <!-- Chart Section -->
        <div class="chart-section">
            <div class="section-header">
                <h2>Problems Overview</h2>
                <div class="chart-period">
                    <span class="period-text">Last 7 days</span>
                </div>
            </div>
            
            <!-- Mobile-Optimized Visual Chart -->
            <div class="mobile-visual-chart">
                <div class="mobile-chart-header">
                    <div class="chart-title-container">
                        <div class="chart-icon-wrapper">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="chart-title-text">
                            <h4>Problems Overview</h4>
                            <p class="chart-subtitle">Visual breakdown</p>
                        </div>
                    </div>
                </div>
                
                <!-- Visual Progress Chart -->
                <div class="visual-chart-container">
                    <!-- Problem Status Breakdown -->
                    <div class="status-breakdown">
                        <div class="status-item reported">
                            <div class="status-bar">
                                <div class="status-fill" style="width: {{ $stats['total_problems'] > 0 ? (($stats['reported_problems'] ?? 0) / $stats['total_problems'] * 100) : 0 }}%"></div>
                            </div>
                            <div class="status-info">
                                <span class="status-label">Reported</span>
                                <span class="status-count">{{ $stats['reported_problems'] ?? 0 }}</span>
                            </div>
                        </div>
                        
                        <div class="status-item in-progress">
                            <div class="status-bar">
                                <div class="status-fill" style="width: {{ $stats['total_problems'] > 0 ? (($stats['in_progress_problems'] ?? 0) / $stats['total_problems'] * 100) : 0 }}%"></div>
                            </div>
                            <div class="status-info">
                                <span class="status-label">In Progress</span>
                                <span class="status-count">{{ $stats['in_progress_problems'] ?? 0 }}</span>
                            </div>
                        </div>
                        
                        <div class="status-item resolved">
                            <div class="status-bar">
                                <div class="status-fill" style="width: {{ $stats['total_problems'] > 0 ? (($stats['resolved_problems'] ?? 0) / $stats['total_problems'] * 100) : 0 }}%"></div>
                            </div>
                            <div class="status-info">
                                <span class="status-label">Resolved</span>
                                <span class="status-count">{{ $stats['resolved_problems'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Priority Breakdown -->
                    <div class="priority-section">
                        <h5 class="section-title">Priority Breakdown</h5>
                        <div class="priority-grid">
                            <div class="priority-card high">
                                <div class="priority-icon">🔴</div>
                                <div class="priority-info">
                                    <span class="priority-label">High</span>
                                    <span class="priority-count">{{ $stats['high_priority'] ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <div class="priority-card medium">
                                <div class="priority-icon">🟡</div>
                                <div class="priority-info">
                                    <span class="priority-label">Medium</span>
                                    <span class="priority-count">{{ $stats['medium_priority'] ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <div class="priority-card low">
                                <div class="priority-icon">🟢</div>
                                <div class="priority-info">
                                    <span class="priority-label">Low</span>
                                    <span class="priority-count">{{ $stats['low_priority'] ?? 0 }}</span>
                                </div>
                            </div>
                            
                            <div class="priority-card urgent">
                                <div class="priority-icon">🚨</div>
                                <div class="priority-info">
                                    <span class="priority-label">Urgent</span>
                                    <span class="priority-count">{{ $stats['urgent_priority'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>

        <!-- Recent Activity (Mobile Only) -->
        <div class="activity-section d-lg-none">
            <div class="section-header">
                <h2>Recent Activity</h2>
                <a href="{{ route('problems.index') }}" class="view-all-btn">View All</a>
            </div>
            
            <div class="activity-list">
                @if(isset($recent_problems) && count($recent_problems) > 0)
                    @foreach(array_slice($recent_problems->toArray(), 0, 4) as $problem)
                    <div class="activity-card">
                        <div class="activity-indicator {{ strtolower($problem['priority'] ?? 'medium') }}"></div>
                        <div class="activity-details">
                            <div class="activity-title">{{ Str::limit($problem['title'] ?? 'New Problem', 35) }}</div>
                            <div class="activity-meta">
                                <span class="activity-time">{{ \Carbon\Carbon::parse($problem['created_at'])->diffForHumans() }}</span>
                                <span class="activity-status status-{{ strtolower($problem['status'] ?? 'pending') }}">
                                    {{ ucfirst($problem['status'] ?? 'Pending') }}
                                </span>
                            </div>
                        </div>
                        <div class="activity-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="empty-text">
                            <h3>No Recent Activity</h3>
                            <p>New problems and updates will appear here</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>        
<!-- Quick Actions (Mobile Only) -->
        <div class="actions-section d-lg-none">
            <div class="section-header">
                <h2>Quick Actions</h2>
            </div>
            
            <div class="action-buttons">
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('members.create'))
                <a href="{{ route('members.create') }}" class="action-button primary">
                    <div class="action-button-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-button-text">
                        <span class="action-title">Add Member</span>
                        <span class="action-subtitle">Register new family member</span>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                @endif
                
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('problems.view'))
                <a href="{{ route('problems.create') }}" class="action-button danger">
                    <div class="action-button-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="action-button-text">
                        <span class="action-title">Report Problem</span>
                        <span class="action-subtitle">Submit new issue</span>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                @endif
                
                @if(Auth::user()->isAdmin() || Auth::user()->hasPermission('houses.create'))
                <a href="{{ route('houses.create') }}" class="action-button success">
                    <div class="action-button-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="action-button-text">
                        <span class="action-title">Add House</span>
                        <span class="action-subtitle">Register new house</span>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
                @endif
                
                <a href="{{ route('reports.index') }}" class="action-button info">
                    <div class="action-button-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-button-text">
                        <span class="action-title">View Reports</span>
                        <span class="action-subtitle">Analytics & insights</span>
                    </div>
                    <div class="action-arrow">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                </a>
            </div>
        </div>
    </div>  

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Wait for both DOM and Chart.js to load
function initCharts() {
    console.log('Initializing charts...');
    console.log('Chart.js available:', typeof Chart !== 'undefined');
    
    // Chart data from Laravel
    const chartData = {
        dates: {!! json_encode($chartData['dates'] ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']) !!},
        problems: {!! json_encode($chartData['problems'] ?? [5, 8, 3, 12, 7, 4, 9]) !!},
        resolved: {!! json_encode($chartData['resolved'] ?? [3, 6, 2, 8, 5, 3, 7]) !!}
    };
    
    console.log('Chart data:', chartData);

    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded');
        return;
    }

    // Mobile Chart
    const mobileCanvas = document.getElementById('mobileChart');
    console.log('Mobile canvas found:', !!mobileCanvas);
    
    if (mobileCanvas) {
        try {
            new Chart(mobileCanvas, {
                type: 'line',
                data: {
                    labels: chartData.dates.slice(-7),
                    datasets: [
                        {
                            label: '🔴 Problems Reported',
                            data: chartData.problems.slice(-7),
                            borderColor: '#ff4757',
                            backgroundColor: 'rgba(255, 71, 87, 0.15)',
                            borderWidth: 5,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ff4757',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 4,
                            pointRadius: 8,
                            pointHoverRadius: 12,
                            pointHoverBackgroundColor: '#ff4757',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 5,
                            shadowColor: 'rgba(255, 71, 87, 0.5)',
                            shadowBlur: 15,
                            shadowOffsetX: 0,
                            shadowOffsetY: 5
                        },
                        {
                            label: '🟢 Problems Resolved',
                            data: chartData.resolved.slice(-7),
                            borderColor: '#2ed573',
                            backgroundColor: 'rgba(46, 213, 115, 0.15)',
                            borderWidth: 5,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#2ed573',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 4,
                            pointRadius: 8,
                            pointHoverRadius: 12,
                            pointHoverBackgroundColor: '#2ed573',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 5,
                            shadowColor: 'rgba(46, 213, 115, 0.5)',
                            shadowBlur: 15,
                            shadowOffsetX: 0,
                            shadowOffsetY: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'center',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 20,
                                font: {
                                    size: 13,
                                    weight: '700',
                                    family: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                                },
                                color: 'rgba(255, 255, 255, 0.9)',
                                boxWidth: 10,
                                boxHeight: 10
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            cornerRadius: 12,
                            displayColors: true,
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: '700'
                            },
                            bodyFont: {
                                size: 13,
                                weight: '600'
                            },
                            callbacks: {
                                title: function(context) {
                                    return 'Date: ' + context[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' problems';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            grid: { 
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                font: { 
                                    size: 12, 
                                    weight: '600',
                                    family: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                                },
                                color: 'rgba(255, 255, 255, 0.8)',
                                padding: 10
                            }
                        },
                        y: {
                            display: true,
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)',
                                drawBorder: false,
                                lineWidth: 1
                            },
                            ticks: {
                                font: { 
                                    size: 12, 
                                    weight: '600',
                                    family: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                                },
                                color: 'rgba(255, 255, 255, 0.8)',
                                padding: 12,
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating mobile chart:', error);
        }
    }

    // Wrapper Chart (Desktop)
    const wrapperCanvas = document.getElementById('wrapperChart');
    console.log('Wrapper canvas found:', !!wrapperCanvas);
    
    if (wrapperCanvas) {
        try {
            const wrapperCtx = wrapperCanvas.getContext('2d');
            
            window.wrapperChart = new Chart(wrapperCtx, {
                type: 'line',
                data: {
                    labels: chartData.dates,
                    datasets: [{
                        label: 'Problems Reported',
                        data: chartData.problems,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }, {
                        label: 'Problems Resolved',
                        data: chartData.resolved,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 12,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#667eea',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            intersect: false,
                            mode: 'index'
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6b7280'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: {
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#6b7280'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            hoverRadius: 8
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating wrapper chart:', error);
        }
    }

    // Main Chart (now using wrapper chart)
    const mainCanvas = document.getElementById('wrapperChart');
    console.log('Main canvas found:', !!mainCanvas);
    
    if (mainCanvas) {
        try {
            new Chart(mainCanvas, {
                type: 'line',
                data: {
                    labels: chartData.dates,
                    datasets: [
                        {
                            label: '🔴 Problems Reported',
                            data: chartData.problems,
                            borderColor: '#ff4757',
                            backgroundColor: 'rgba(255, 71, 87, 0.2)',
                            borderWidth: 5,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ff4757',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 4,
                            pointRadius: 8,
                            pointHoverRadius: 12,
                            pointHoverBackgroundColor: '#ff4757',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 5,
                            shadowColor: 'rgba(255, 71, 87, 0.5)',
                            shadowBlur: 15,
                            shadowOffsetX: 0,
                            shadowOffsetY: 5
                        },
                        {
                            label: '🟢 Problems Resolved',
                            data: chartData.resolved,
                            borderColor: '#2ed573',
                            backgroundColor: 'rgba(46, 213, 115, 0.2)',
                            borderWidth: 5,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#2ed573',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 4,
                            pointRadius: 8,
                            pointHoverRadius: 12,
                            pointHoverBackgroundColor: '#2ed573',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 5,
                            shadowColor: 'rgba(46, 213, 115, 0.5)',
                            shadowBlur: 15,
                            shadowOffsetX: 0,
                            shadowOffsetY: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            align: 'center',
                            labels: {
                                usePointStyle: true,
                                pointStyle: 'circle',
                                padding: 25,
                                font: {
                                    size: 14,
                                    weight: '700',
                                    family: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                                },
                                color: '#374151',
                                boxWidth: 12,
                                boxHeight: 12
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.9)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#667eea',
                            borderWidth: 2,
                            cornerRadius: 12,
                            displayColors: true,
                            padding: 15,
                            titleFont: {
                                size: 14,
                                weight: '700'
                            },
                            bodyFont: {
                                size: 13,
                                weight: '600'
                            },
                            callbacks: {
                                title: function(context) {
                                    return 'Date: ' + context[0].label;
                                },
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y + ' problems';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { 
                                color: 'rgba(102, 126, 234, 0.1)',
                                lineWidth: 1,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: { 
                                    size: 12,
                                    weight: '600',
                                    family: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                                },
                                padding: 10,
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { 
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: { 
                                    size: 12,
                                    weight: '600',
                                    family: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif'
                                },
                                padding: 8
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creating main chart:', error);
        }
    }  
  // Navigation function
    window.navigateTo = function(url) {
        window.location.href = url;
    };

    // Refresh dashboard
    window.refreshDashboard = function() {
        const icon = document.getElementById('refreshIcon');
        if (icon) {
            icon.classList.add('loading');
        }
        
        setTimeout(() => {
            location.reload();
        }, 800);
    };

    // Refresh chart function
    window.refreshChart = function() {
        setTimeout(() => {
            location.reload();
        }, 500);
    };

    // Export and Share Functions
    window.exportData = function() {
        const data = {
            stats: {!! json_encode($stats) !!},
            chartData: {!! json_encode($chartData) !!},
            exportDate: new Date().toISOString()
        };
        
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'boothcare-dashboard-' + new Date().toISOString().split('T')[0] + '.json';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    };

    window.shareData = function() {
        if (navigator.share) {
            navigator.share({
                title: 'Boothcare Dashboard',
                text: 'System Summary - Total Problems: {{ $stats["total_problems"] ?? 0 }}, Total Members: {{ $stats["total_members"] ?? 0 }}',
                url: window.location.href
            });
        } else {
            const shareText = `Boothcare Dashboard Summary:
Total Problems: {{ $stats["total_problems"] ?? 0 }}
Total Members: {{ $stats["total_members"] ?? 0 }}
Total Houses: {{ $stats["total_houses"] ?? 0 }}
Resolution Rate: {{ $percentage ?? 0 }}%
View: ${window.location.href}`;
            
            navigator.clipboard.writeText(shareText).then(() => {
                alert('Dashboard summary copied to clipboard!');
            });
        }
    };
}

// Initialize charts when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure Chart.js is loaded
    setTimeout(initCharts, 100);
});

// Also try to initialize when window loads (fallback)
window.addEventListener('load', function() {
    if (typeof Chart !== 'undefined') {
        initCharts();
    }
});

// Period change function
function changePeriod(period) {
    // Show loading state
    const charts = document.querySelectorAll('canvas');
    charts.forEach(canvas => {
        const parent = canvas.parentElement;
        if (parent) {
            parent.style.opacity = '0.5';
        }
    });

    // Update URL and reload page with new period
    const url = new URL(window.location);
    url.searchParams.set('period', period);
    window.location.href = url.toString();
}

// Refresh chart function
function refreshChart() {
    const icon = document.getElementById('refreshIcon');
    if (icon) {
        icon.classList.add('loading');
    }
    
    // Reload page to refresh data
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Add haptic feedback for mobile interactions
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.stat-item, .action-button, .activity-card').forEach(element => {
        element.classList.add('haptic-feedback');
        
        element.addEventListener('click', function() {
            if (navigator.vibrate) {
                navigator.vibrate(10);
            }
        });
    });
});
</script>
@endpush
@endsection