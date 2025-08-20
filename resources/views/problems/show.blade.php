@extends('layouts.app')

@section('title', 'Problem Details - Boothcare')
@section('page-title', 'Problem: ' . Str::limit($problem->title, 30))

@push('styles')
<style>
    /* Mobile-First App Design */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        min-height: 100vh;
        overflow-x: hidden;
    }

    .problem-wrapper {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow-x: hidden;
    }

    .problem-wrapper::before {
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
        z-index: 2;
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
        margin-bottom: 1rem;
    }

    .back-button {
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
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-button:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: scale(1.05);
    }

    .header-actions {
        display: flex;
        gap: 8px;
    }

    .action-btn-header {
        width: 44px;
        height: 44px;
        background: rgba(255,255,255,0.2);
        border: none;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .action-btn-header:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: scale(1.05);
    }

    .problem-header-info {
        position: relative;
        z-index: 2;
    }

    .problem-id {
        font-size: 0.85rem;
        opacity: 0.8;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .problem-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin-bottom: 1rem;
        line-height: 1.2;
        color: white;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .problem-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }

    .meta-badge {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Mobile App Content */
    .mobile-app-content {
        padding: 0 0 100px 0;
        background: transparent;
        position: relative;
        z-index: 2;
    }

    /* App Cards */
    .app-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        margin: 0 20px 20px 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .app-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--card-color, #667eea), var(--card-color-end, #764ba2));
        border-radius: 20px 20px 0 0;
    }

    .app-card.primary {
        --card-color: #667eea;
        --card-color-end: #764ba2;
    }

    .app-card.success {
        --card-color: #11998e;
        --card-color-end: #38ef7d;
    }

    .app-card.warning {
        --card-color: #feca57;
        --card-color-end: #ff9ff3;
    }

    .app-card.danger {
        --card-color: #ff6b6b;
        --card-color-end: #ee5a52;
    }

    .app-card:active {
        transform: scale(0.98);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .card-header-app {
        padding: 20px 24px 16px 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-icon-app {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        background: linear-gradient(135deg, var(--card-color, #667eea), var(--card-color-end, #764ba2));
    }

    .card-title-app {
        font-size: 1.1rem;
        font-weight: 700;
        color: rgba(255, 255, 255, 0.95);
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-body-app {
        padding: 0 24px 24px 24px;
    }

    /* Info Items */
    .info-grid-app {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .info-item-app {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
    }

    .info-label-app {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 4px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value-app {
        font-size: 0.9rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    /* Status Badges */
    .status-badge-app {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-reported {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .status-in_progress {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .status-resolved {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    /* Priority Badges */
    .priority-badge-app {
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .priority-urgent {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .priority-high {
        background: rgba(251, 191, 36, 0.2);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .priority-medium {
        background: rgba(59, 130, 246, 0.2);
        color: #3b82f6;
        border: 1px solid rgba(59, 130, 246, 0.3);
    }

    .priority-low {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    /* Description Box */
    .description-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 20px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 20px;
    }

    .description-text {
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.6;
        font-size: 0.95rem;
        margin: 0;
    }

    /* Image Display */
    .image-container {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 16px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 20px;
        text-align: center;
    }

    .problem-image {
        max-width: 100%;
        max-height: 250px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .problem-image:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
    }

    /* Timeline */
    .timeline-app {
        position: relative;
        padding: 20px 0;
    }

    .timeline-item-app {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 24px;
        position: relative;
    }

    .timeline-item-app:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 20px;
        top: 45px;
        width: 2px;
        height: calc(100% + 4px);
        background: rgba(255, 255, 255, 0.2);
    }

    .timeline-item-app.active:not(:last-child)::after {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .timeline-item-app.completed:not(:last-child)::after {
        background: linear-gradient(135deg, #11998e, #38ef7d);
    }

    .timeline-dot-app {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .timeline-item-app.completed .timeline-dot-app {
        background: linear-gradient(135deg, #11998e, #38ef7d);
        color: white;
        box-shadow: 0 4px 15px rgba(17, 153, 142, 0.3);
    }

    .timeline-item-app.active .timeline-dot-app {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        animation: pulse-app 2s infinite;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .timeline-item-app.pending .timeline-dot-app {
        background: rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.6);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    @keyframes pulse-app {
        0% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), 0 0 0 0 rgba(102, 126, 234, 0.4); }
        70% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), 0 0 0 10px rgba(102, 126, 234, 0); }
        100% { box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3), 0 0 0 0 rgba(102, 126, 234, 0); }
    }

    .timeline-content-app {
        flex: 1;
        min-width: 0;
    }

    .timeline-content-app h4 {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        margin: 0 0 4px 0;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .timeline-content-app .time {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
        font-weight: 500;
    }

    .timeline-content-app p {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        line-height: 1.4;
    }

    /* User Profile */
    .user-profile-app {
        text-align: center;
        padding: 20px;
    }

    .user-avatar-app {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 24px;
        color: rgba(255, 255, 255, 0.8);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .user-name-app {
        font-size: 1.1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 4px;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .user-role-app {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 16px;
        font-weight: 500;
    }

    .user-details-app {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: left;
    }

    .user-detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .user-detail-row:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .user-detail-label {
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .user-detail-value {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        text-align: right;
        flex: 1;
        margin-left: 12px;
    }

    /* Action Buttons */
    .action-buttons-app {
        padding: 0 20px 20px 20px;
    }

    .action-button-app {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 16px;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .action-button-app:active {
        transform: scale(0.98);
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    }

    .action-button-app::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, var(--action-color, #667eea), var(--action-color-end, #764ba2));
        transition: all 0.2s ease;
    }

    .action-button-app.primary {
        --action-color: #667eea;
        --action-color-end: #764ba2;
    }

    .action-button-app.success {
        --action-color: #11998e;
        --action-color-end: #38ef7d;
    }

    .action-button-app.secondary {
        --action-color: #6b7280;
        --action-color-end: #4b5563;
    }

    .action-button-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: white;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--action-color, #667eea), var(--action-color-end, #764ba2));
    }

    .action-button-text {
        flex: 1;
        min-width: 0;
    }

    .action-title {
        display: block;
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 2px;
        line-height: 1.3;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .action-subtitle {
        display: block;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .action-arrow {
        color: rgba(255, 255, 255, 0.6);
        font-size: 14px;
        transition: all 0.2s ease;
    }

    .action-button-app:hover .action-arrow {
        color: rgba(255, 255, 255, 0.8);
        transform: translateX(2px);
    }

    /* Feedback Section */
    .feedback-stars {
        display: flex;
        justify-content: center;
        gap: 4px;
        margin-bottom: 12px;
    }

    .feedback-stars i {
        font-size: 20px;
    }

    .feedback-text {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 16px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.5;
        font-size: 0.9rem;
    }

    /* Desktop Responsive */
    @media (min-width: 769px) {
        .problem-wrapper {
            background: #f8fafc;
            min-height: 100vh;
        }

        .mobile-app-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 1.5rem;
            margin: -1rem -1rem 2rem -1rem;
            border-radius: 0 0 20px 20px;
        }

        .problem-title {
            font-size: 2rem;
        }

        .mobile-app-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            padding: 0 1.5rem 2rem 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .app-card {
            margin: 0 0 2rem 0;
        }

        .action-buttons-app {
            padding: 0;
        }
    }

    /* Mobile Responsive - Enhanced */
    @media (max-width: 768px) {
        body {
            background: #f8fafc !important;
            overflow-x: hidden;
        }

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

        .problem-title {
            font-size: 1.3rem;
            margin: 0;
        }

        .problem-meta {
            gap: 6px;
        }

        .meta-badge {
            padding: 4px 8px;
            font-size: 0.7rem;
        }

        .app-card {
            margin: 0 15px 15px 15px;
            width: calc(100% - 30px);
            box-sizing: border-box;
        }

        .card-header-app {
            padding: 15px 20px 12px 20px;
        }

        .card-body-app {
            padding: 0 20px 20px 20px;
        }

        .info-grid-app {
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 8px;
        }

        .info-item-app {
            padding: 12px;
        }

        .info-label-app {
            font-size: 0.7rem;
        }

        .info-value-app {
            font-size: 0.85rem;
        }

        .description-box {
            padding: 16px;
        }

        .description-text {
            font-size: 0.9rem;
        }

        .timeline-item-app {
            gap: 12px;
            margin-bottom: 20px;
        }

        .timeline-dot-app {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }

        .timeline-content-app h4 {
            font-size: 0.95rem;
        }

        .timeline-content-app .time {
            font-size: 0.75rem;
        }

        .timeline-content-app p {
            font-size: 0.8rem;
        }

        .user-profile-app {
            padding: 16px;
        }

        .user-avatar-app {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .user-name-app {
            font-size: 1rem;
        }

        .user-role-app {
            font-size: 0.75rem;
        }

        .user-details-app {
            padding: 12px;
        }

        .user-detail-row {
            margin-bottom: 8px;
            padding-bottom: 6px;
        }

        .user-detail-label {
            font-size: 0.75rem;
        }

        .user-detail-value {
            font-size: 0.8rem;
        }

        .action-buttons-app {
            padding: 0 15px 20px 15px;
        }

        .action-button-app {
            padding: 12px 16px;
            margin-bottom: 10px;
            gap: 12px;
        }

        .action-button-icon {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .action-title {
            font-size: 0.9rem;
        }

        .action-subtitle {
            font-size: 0.75rem;
        }

        .problem-image {
            max-height: 200px;
        }

        .image-container {
            padding: 12px;
        }
    }

    /* Extra small mobile devices */
    @media (max-width: 480px) {
        .problem-title {
            font-size: 1.2rem;
        }

        .info-grid-app {
            grid-template-columns: 1fr;
        }

        .meta-badge {
            padding: 3px 6px;
            font-size: 0.65rem;
        }

        .card-header-app {
            padding: 12px 16px 10px 16px;
        }

        .card-body-app {
            padding: 0 16px 16px 16px;
        }

        .action-button-app {
            flex-direction: column;
            text-align: center;
            gap: 8px;
        }

        .action-button-text {
            text-align: center;
        }
    }
</style>

    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: white;
    }

    .card-icon.primary { background: #4f46e5; }
    .card-icon.success { background: #10b981; }
    .card-icon.warning { background: #f59e0b; }
    .card-icon.info { background: #3b82f6; }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Status Timeline */
    .timeline {
        position: relative;
        padding: 1rem 0;
    }

    .timeline-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
        position: relative;
    }

    .timeline-item:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 20px;
        top: 45px;
        width: 2px;
        height: calc(100% + 0.5rem);
        background: #e5e7eb;
    }

    .timeline-item.active:not(:last-child)::after {
        background: #4f46e5;
    }

    .timeline-item.completed:not(:last-child)::after {
        background: #10b981;
    }

    .timeline-dot {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .timeline-item.completed .timeline-dot {
        background: #10b981;
        color: white;
    }

    .timeline-item.active .timeline-dot {
        background: #4f46e5;
        color: white;
        animation: pulse 2s infinite;
    }

    .timeline-item.pending .timeline-dot {
        background: #f3f4f6;
        color: #9ca3af;
        border: 2px solid #e5e7eb;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
        100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
    }

    .timeline-content h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 0.25rem 0;
    }

    .timeline-content .time {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .timeline-content p {
        font-size: 0.9rem;
        color: #6b7280;
        margin: 0;
        line-height: 1.4;
    }

    /* Status Badges */
    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .status-reported {
        background: #fef3c7;
        color: #d97706;
    }

    .status-in_progress {
        background: #dbeafe;
        color: #2563eb;
    }

    .status-resolved {
        background: #d1fae5;
        color: #059669;
    }

    /* Priority Badges */
    .priority-badge {
        padding: 0.3rem 0.7rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .priority-urgent {
        background: #fee2e2;
        color: #dc2626;
    }

    .priority-high {
        background: #fef3c7;
        color: #d97706;
    }

    .priority-medium {
        background: #dbeafe;
        color: #2563eb;
    }

    .priority-low {
        background: #d1fae5;
        color: #059669;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .info-item {
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
        border-left: 3px solid #e5e7eb;
    }

    .info-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
        font-weight: 500;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
    }

    /* User Profile */
    .user-profile {
        text-align: center;
        padding: 1rem;
    }

    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: #6b7280;
    }

    .user-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .user-role {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 1rem;
    }

    .user-details {
        text-align: left;
        background: #f8fafc;
        padding: 1rem;
        border-radius: 8px;
    }

    .user-details .row {
        margin-bottom: 0.5rem;
    }

    .user-details .row:last-child {
        margin-bottom: 0;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .problem-header {
            padding: 1.5rem;
        }

        .problem-title {
            font-size: 1.5rem;
        }

        .problem-meta {
            flex-direction: column;
            gap: 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="problem-wrapper">
    <!-- Mobile App Header -->
    <div class="mobile-app-header">
        <div class="header-content">
            <a href="{{ route('problems.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="header-actions">
                @if(Auth::user()->isAdminOrSubAdmin())
                    <a href="{{ route('problems.status-update', $problem) }}" class="action-btn-header" title="Update Status">
                        <i class="fas fa-tools"></i>
                    </a>
                @endif
                @if($problem->familyMember && $problem->familyMember->user_id === Auth::id() && $problem->status === 'resolved' && !$problem->feedback_submitted)
                    <a href="{{ route('problems.feedback', $problem) }}" class="action-btn-header" title="Provide Feedback">
                        <i class="fas fa-star"></i>
                    </a>
                @endif
            </div>
        </div>
        
        <div class="problem-header-info">
            <div class="problem-id">Problem #{{ str_pad($problem->id, 6, '0', STR_PAD_LEFT) }}</div>
            <h1 class="problem-title">{{ $problem->title }}</h1>
            
            <div class="problem-meta">
                <div class="meta-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <span>{{ $problem->created_at->format('M d') }}</span>
                </div>
                <div class="meta-badge">
                    <i class="fas fa-tag"></i>
                    <span>{{ ucfirst($problem->category) }}</span>
                </div>
                <div class="meta-badge">
                    @switch($problem->status)
                        @case('reported')
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Reported</span>
                            @break
                        @case('in_progress')
                            <i class="fas fa-tools"></i>
                            <span>In Progress</span>
                            @break
                        @case('resolved')
                            <i class="fas fa-check-circle"></i>
                            <span>Resolved</span>
                            @break
                    @endswitch
                </div>
                <div class="meta-badge">
                    @switch($problem->priority)
                        @case('urgent')
                            <i class="fas fa-fire"></i>
                            <span>Urgent</span>
                            @break
                        @case('high')
                            <i class="fas fa-arrow-up"></i>
                            <span>High</span>
                            @break
                        @case('medium')
                            <i class="fas fa-minus"></i>
                            <span>Medium</span>
                            @break
                        @default
                            <i class="fas fa-arrow-down"></i>
                            <span>Low</span>
                    @endswitch
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile App Content -->
    <div class="mobile-app-content">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Problem Details -->
            <div class="app-card primary">
                <div class="card-header-app">
                    <div class="card-icon-app">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="card-title-app">Problem Details</h3>
                </div>
                <div class="card-body-app">
                    <div class="info-grid-app">
                        <div class="info-item-app">
                            <div class="info-label-app">Status</div>
                            <div class="info-value-app">
                                @switch($problem->status)
                                    @case('reported')
                                        <span class="status-badge-app status-reported">
                                            <i class="fas fa-exclamation-circle"></i>
                                            Reported
                                        </span>
                                        @break
                                    @case('in_progress')
                                        <span class="status-badge-app status-in_progress">
                                            <i class="fas fa-tools"></i>
                                            In Progress
                                        </span>
                                        @break
                                    @case('resolved')
                                        <span class="status-badge-app status-resolved">
                                            <i class="fas fa-check-circle"></i>
                                            Resolved
                                        </span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item-app">
                            <div class="info-label-app">Priority</div>
                            <div class="info-value-app">
                                @switch($problem->priority)
                                    @case('urgent')
                                        <span class="priority-badge-app priority-urgent">
                                            <i class="fas fa-fire"></i>
                                            Urgent
                                        </span>
                                        @break
                                    @case('high')
                                        <span class="priority-badge-app priority-high">
                                            <i class="fas fa-arrow-up"></i>
                                            High
                                        </span>
                                        @break
                                    @case('medium')
                                        <span class="priority-badge-app priority-medium">
                                            <i class="fas fa-minus"></i>
                                            Medium
                                        </span>
                                        @break
                                    @default
                                        <span class="priority-badge-app priority-low">
                                            <i class="fas fa-arrow-down"></i>
                                            Low
                                        </span>
                                @endswitch
                            </div>
                        </div>
                        <div class="info-item-app">
                            <div class="info-label-app">Reported</div>
                            <div class="info-value-app">{{ $problem->created_at->format('M d, Y') }}</div>
                        </div>
                        @if($problem->expected_resolution_date)
                        <div class="info-item-app">
                            <div class="info-label-app">Expected</div>
                            <div class="info-value-app">{{ \Carbon\Carbon::parse($problem->expected_resolution_date)->format('M d, Y') }}</div>
                        </div>
                        @endif
                        @if($problem->actual_resolution_date)
                        <div class="info-item-app">
                            <div class="info-label-app">Resolved</div>
                            <div class="info-value-app">{{ \Carbon\Carbon::parse($problem->actual_resolution_date)->format('M d, Y') }}</div>
                        </div>
                        @endif
                        @if($problem->estimated_cost)
                        <div class="info-item-app">
                            <div class="info-label-app">Est. Cost</div>
                            <div class="info-value-app">৳{{ number_format($problem->estimated_cost) }}</div>
                        </div>
                        @endif
                        @if($problem->actual_cost)
                        <div class="info-item-app">
                            <div class="info-label-app">Actual Cost</div>
                            <div class="info-value-app">৳{{ number_format($problem->actual_cost) }}</div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="description-box">
                        <div class="info-label-app" style="margin-bottom: 8px;">Description</div>
                        <p class="description-text">{{ $problem->description }}</p>
                    </div>
                    
                    @if($problem->problem_photo)
                    <div class="image-container">
                        <div class="info-label-app" style="margin-bottom: 12px;">Problem Photo</div>
                        <img src="{{ asset('storage/' . $problem->problem_photo) }}" 
                             alt="Problem Photo" 
                             class="problem-image" 
                             onclick="showImageModal(this.src)">
                    </div>
                    @endif
                    
                    @if($problem->resolution_notes)
                    <div class="description-box">
                        <div class="info-label-app" style="margin-bottom: 8px;">Resolution Notes</div>
                        <p class="description-text">{{ $problem->resolution_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- User Feedback -->
            @if($problem->feedback_submitted)
            <div class="app-card warning">
                <div class="card-header-app">
                    <div class="card-icon-app">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="card-title-app">User Feedback</h3>
                </div>
                <div class="card-body-app">
                    <div class="info-grid-app">
                        <div class="info-item-app">
                            <div class="info-label-app">Rating</div>
                            <div class="info-value-app">
                                <div class="feedback-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $problem->user_rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <span>({{ $problem->user_rating }}/5)</span>
                            </div>
                        </div>
                        @if($problem->feedback_date)
                        <div class="info-item-app">
                            <div class="info-label-app">Date</div>
                            <div class="info-value-app">{{ \Carbon\Carbon::parse($problem->feedback_date)->format('M d, Y') }}</div>
                        </div>
                        @endif
                    </div>
                    
                    @if($problem->user_feedback)
                    <div class="feedback-text">
                        {{ $problem->user_feedback }}
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="sidebar-content">
            <!-- Status Timeline -->
            <div class="app-card success">
                <div class="card-header-app">
                    <div class="card-icon-app">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="card-title-app">Status Timeline</h3>
                </div>
                <div class="card-body-app">
                    <div class="timeline-app">
                        <div class="timeline-item-app {{ $problem->status == 'reported' ? 'active' : 'completed' }}">
                            <div class="timeline-dot-app">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="timeline-content-app">
                                <h4>Problem Reported</h4>
                                <div class="time">{{ $problem->created_at->format('M d, Y h:i A') }}</div>
                                <p>Problem has been reported and is awaiting review.</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item-app {{ $problem->status == 'in_progress' ? 'active' : ($problem->status == 'resolved' ? 'completed' : 'pending') }}">
                            <div class="timeline-dot-app">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="timeline-content-app">
                                <h4>Work in Progress</h4>
                                <div class="time">
                                    @if($problem->status == 'in_progress' || $problem->status == 'resolved')
                                        {{ $problem->updated_at->format('M d, Y h:i A') }}
                                    @else
                                        Pending
                                    @endif
                                </div>
                                <p>Problem is being actively worked on by the team.</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item-app {{ $problem->status == 'resolved' ? 'completed' : 'pending' }}">
                            <div class="timeline-dot-app">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content-app">
                                <h4>Problem Resolved</h4>
                                <div class="time">
                                    @if($problem->status == 'resolved')
                                        {{ $problem->actual_resolution_date ? \Carbon\Carbon::parse($problem->actual_resolution_date)->format('M d, Y h:i A') : $problem->updated_at->format('M d, Y h:i A') }}
                                    @else
                                        Pending
                                    @endif
                                </div>
                                <p>Problem has been successfully resolved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Member Info -->
            <div class="app-card primary">
                <div class="card-header-app">
                    <div class="card-icon-app">
                        <i class="fas fa-user"></i>
                    </div>
                    <h3 class="card-title-app">Family Member</h3>
                </div>
                <div class="card-body-app">
                    @if($problem->familyMember)
                    <div class="user-profile-app">
                        <div class="user-avatar-app">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="user-name-app">{{ $problem->familyMember->name }}</div>
                        <div class="user-role-app">{{ $problem->familyMember->relationship ?? 'Family Member' }}</div>
                        
                        <div class="user-details-app">
                            @if($problem->familyMember->phone)
                            <div class="user-detail-row">
                                <div class="user-detail-label">Phone</div>
                                <div class="user-detail-value">{{ $problem->familyMember->phone }}</div>
                            </div>
                            @endif
                            @if($problem->familyMember->age)
                            <div class="user-detail-row">
                                <div class="user-detail-label">Age</div>
                                <div class="user-detail-value">{{ $problem->familyMember->age }}</div>
                            </div>
                            @endif
                            @if($problem->familyMember->house)
                            <div class="user-detail-row">
                                <div class="user-detail-label">Address</div>
                                <div class="user-detail-value">
                                    House {{ $problem->familyMember->house->house_number }}@if($problem->familyMember->house->booth), Booth {{ $problem->familyMember->house->booth->booth_number }}@endif@if($problem->familyMember->house->booth && $problem->familyMember->house->booth->area), {{ $problem->familyMember->house->booth->area->area_name }}@endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="text-center" style="color: rgba(255, 255, 255, 0.7); padding: 2rem;">
                        <i class="fas fa-user-slash fa-2x mb-2"></i>
                        <p>Family member information not available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons-app">
        @if(Auth::user()->isAdminOrSubAdmin())
            <a href="{{ route('problems.status-update', $problem) }}" class="action-button-app primary">
                <div class="action-button-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="action-button-text">
                    <span class="action-title">Update Status</span>
                    <span class="action-subtitle">Change problem status and add notes</span>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        @endif
        
        @if($problem->familyMember && $problem->familyMember->user_id === Auth::id())
            @if($problem->status === 'resolved' && !$problem->feedback_submitted)
            <a href="{{ route('problems.feedback', $problem) }}" class="action-button-app success">
                <div class="action-button-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="action-button-text">
                    <span class="action-title">Provide Feedback</span>
                    <span class="action-subtitle">Rate and review the resolution</span>
                </div>
                <div class="action-arrow">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
            @endif
        @endif
        
        <a href="{{ route('problems.index') }}" class="action-button-app secondary">
            <div class="action-button-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="action-button-text">
                <span class="action-title">Back to Problems</span>
                <span class="action-subtitle">View all problems list</span>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Problem Photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Problem Photo" class="img-fluid">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endpush

@endsection