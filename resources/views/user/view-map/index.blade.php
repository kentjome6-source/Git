@extends('layouts.app')

@section('title', 'Map')

@section('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    :root {
        --slate: #0f172a;
        --slate-light: #1e293b;
        --blue: #3b82f6;
        --purple: #8b5cf6;
        --green: #10b981;
        --orange: #f59e0b;
        --gray: #64748b;
        --gray-light: #f1f5f9;
        --gray-lighter: #f8fafc;
    }

    .map-page {
        font-family: 'Sora', -apple-system, BlinkMacSystemFont, sans-serif;
        background: var(--gray-lighter);
        min-height: 100vh;
    }

    /* Page Header */
    .page-header {
        padding: 40px 20px;
        text-align: center;
        animation: fadeInDown 0.6s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .label {
        display: inline-block;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--blue);
        margin-bottom: 12px;
        font-weight: 600;
    }

    .page-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--slate);
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .page-subtitle {
        font-size: 1.1rem;
        color: var(--gray);
    }

    /* Main Layout - Map on right, shelters on left */
    .main-layout {
        display: flex;
        flex-wrap: wrap;
        min-height: calc(100vh - 200px);
        padding: 0 20px 40px;
        gap: 24px;
        max-width: 1800px;
        margin: 0 auto;
        width: 100%;
    }

    /* Left Side - Shelters Section */
    .shelters-column {
        flex: 1;
        min-width: 300px;
        display: flex;
        flex-direction: column;
    }

    .shelters-container {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .section-header {
        margin-bottom: 24px;
        position: sticky;
        top: 0;
        background: white;
        padding-bottom: 16px;
        z-index: 10;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 20px;
    }

    .section-title {
        font-size: clamp(1.25rem, 2vw, 1.75rem);
        font-weight: 700;
        color: var(--slate);
        letter-spacing: -0.02em;
        margin-bottom: 6px;
    }

    .section-subtitle {
        font-size: 0.9rem;
        color: var(--gray);
    }

    /* Right Side - Map Section */
    .map-column {
        flex: 1;
        min-width: 400px;
        display: flex;
        flex-direction: column;
    }

    .map-wrapper {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        height: 100%;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .map-header {
        margin-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 16px;
    }

    .map-title {
        font-size: clamp(1.25rem, 2vw, 1.75rem);
        font-weight: 700;
        color: var(--slate);
        letter-spacing: -0.02em;
        margin-bottom: 6px;
    }

    .map-subtitle {
        font-size: 0.9rem;
        color: var(--gray);
    }

    .map-container {
        position: relative;
        flex: 1;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        min-height: 500px;
    }

    #shelterMap {
        height: 100%;
        width: 100%;
    }

    .map-controls {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1000;
        display: flex;
        gap: 8px;
    }

    .map-btn {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        transition: all 0.2s;
    }

    .map-btn:hover {
        background: var(--gray-light);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .map-btn svg {
        width: 16px;
        height: 16px;
        stroke: var(--slate);
    }

    /* Shelters Grid */
    .shelters-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        overflow-y: auto;
        flex: 1;
        padding-right: 4px;
    }

    .shelter-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }

    .shelter-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        border-color: var(--green);
    }

    .shelter-card.active {
        border-color: var(--blue);
        background: rgba(59, 130, 246, 0.05);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
    }

    .shelter-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .shelter-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--green) 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .shelter-icon svg {
        width: 20px;
        height: 20px;
        stroke: white;
    }

    .shelter-info {
        flex: 1;
    }

    .shelter-info h3 {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 4px;
        letter-spacing: -0.01em;
        line-height: 1.3;
    }

    .shelter-info p {
        font-size: 0.8rem;
        color: var(--gray);
        margin: 0;
        line-height: 1.2;
    }

    .vet-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        background: rgba(16, 185, 129, 0.1);
        color: var(--green);
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .vet-badge svg {
        width: 12px;
        height: 12px;
        stroke: currentColor;
    }

    .shelter-description {
        color: var(--gray);
        font-size: 0.85rem;
        line-height: 1.5;
        margin-bottom: 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.8em;
    }

    .shelter-details {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
    }

    .detail-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        color: var(--gray);
        font-size: 0.8rem;
        line-height: 1.4;
    }

    .detail-item svg {
        width: 14px;
        height: 14px;
        stroke: var(--blue);
        flex-shrink: 0;
        margin-top: 2px;
    }

    .detail-item span {
        word-break: break-word;
        flex: 1;
    }

    .view-on-map {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--blue);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        padding: 8px 0;
        border-top: 1px solid #e2e8f0;
        margin-top: 12px;
        padding-top: 12px;
        transition: color 0.2s;
    }

    .view-on-map:hover {
        color: #2563eb;
    }

    .view-on-map svg {
        width: 14px;
        height: 14px;
        stroke: currentColor;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .empty-icon {
        margin-bottom: 16px;
        color: var(--gray);
        opacity: 0.4;
    }

    .empty-icon svg {
        width: 60px;
        height: 60px;
        stroke: currentColor;
    }

    .empty-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--slate);
        margin-bottom: 8px;
    }

    .empty-text {
        font-size: 0.95rem;
        color: var(--gray);
        max-width: 300px;
    }

    /* Success Notification */
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--green);
        color: white;
        padding: 16px 20px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notification svg {
        width: 20px;
        height: 20px;
        stroke: currentColor;
    }

    /* Fullscreen Overlay */
    .fullscreen-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        display: none;
    }

    #fullscreen-map {
        height: 100%;
        width: 100%;
    }

    /* Custom scrollbar */
    .shelters-list::-webkit-scrollbar {
        width: 6px;
    }

    .shelters-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .shelters-list::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .shelters-list::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Responsive Breakpoints */
    @media (max-width: 1200px) {
        .main-layout {
            gap: 20px;
            padding: 0 20px 30px;
        }
        
        .shelters-column,
        .map-column {
            min-width: 350px;
        }
    }

    @media (max-width: 992px) {
        .main-layout {
            flex-direction: column;
            gap: 20px;
            min-height: auto;
        }
        
        .shelters-column,
        .map-column {
            min-width: 100%;
            width: 100%;
        }
        
        .shelters-container {
            height: auto;
            max-height: 500px;
        }
        
        .shelters-list {
            max-height: 350px;
        }
        
        .map-container {
            min-height: 450px;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 30px 15px;
        }

        .page-title {
            font-size: 1.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .main-layout {
            padding: 0 15px 25px;
            gap: 20px;
        }

        .shelters-container,
        .map-wrapper {
            padding: 20px;
        }

        .section-header,
        .map-header {
            padding-bottom: 12px;
            margin-bottom: 16px;
        }

        .map-container {
            min-height: 400px;
        }

        .section-title,
        .map-title {
            font-size: 1.3rem;
        }

        .shelter-card {
            padding: 14px;
        }

        .shelter-header {
            gap: 10px;
        }

        .shelter-icon {
            width: 40px;
            height: 40px;
        }

        .shelter-icon svg {
            width: 18px;
            height: 18px;
        }

        .map-controls {
            top: 10px;
            right: 10px;
            gap: 6px;
        }

        .map-btn {
            width: 34px;
            height: 34px;
        }

        .map-btn svg {
            width: 15px;
            height: 15px;
        }
    }

    @media (max-width: 576px) {
        .page-header {
            padding: 25px 12px;
        }

        .page-title {
            font-size: 1.5rem;
        }

        .page-subtitle {
            font-size: 0.95rem;
        }

        .main-layout {
            padding: 0 12px 20px;
            gap: 16px;
        }

        .shelters-container,
        .map-wrapper {
            padding: 16px;
            border-radius: 12px;
        }

        .map-container {
            min-height: 350px;
            border-radius: 8px;
        }

        .section-title,
        .map-title {
            font-size: 1.2rem;
        }

        .section-subtitle,
        .map-subtitle {
            font-size: 0.85rem;
        }

        .map-controls {
            top: 8px;
            right: 8px;
            gap: 4px;
        }

        .map-btn {
            width: 32px;
            height: 32px;
        }

        .map-btn svg {
            width: 14px;
            height: 14px;
        }

        .shelter-card {
            padding: 12px;
            border-radius: 10px;
        }

        .shelter-header {
            gap: 8px;
        }

        .shelter-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
        }

        .shelter-icon svg {
            width: 16px;
            height: 16px;
        }

        .shelter-info h3 {
            font-size: 1rem;
        }

        .shelter-info p {
            font-size: 0.75rem;
        }

        .vet-badge {
            font-size: 0.7rem;
            padding: 3px 8px;
        }

        .shelter-description {
            font-size: 0.8rem;
        }

        .detail-item {
            font-size: 0.75rem;
        }

        .detail-item svg {
            width: 12px;
            height: 12px;
        }

        .view-on-map {
            font-size: 0.75rem;
        }

        .notification {
            top: 10px;
            right: 10px;
            left: 10px;
            padding: 14px 16px;
        }
    }

    @media (max-width: 480px) {
        .page-header {
            padding: 20px 10px;
        }

        .main-layout {
            padding: 0 10px 16px;
            gap: 12px;
        }

        .shelters-container,
        .map-wrapper {
            padding: 14px;
        }

        .map-container {
            min-height: 300px;
        }

        .shelter-card {
            padding: 10px;
        }

        .shelter-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .shelter-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
        }

        .shelter-icon svg {
            width: 14px;
            height: 14px;
        }

        .shelter-info h3 {
            font-size: 0.95rem;
            margin-bottom: 2px;
        }

        .vet-badge {
            font-size: 0.65rem;
        }

        .empty-icon svg {
            width: 50px;
            height: 50px;
        }

        .empty-title {
            font-size: 1.1rem;
        }

        .empty-text {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 360px) {
        .map-container {
            min-height: 280px;
        }
        
        .shelters-container {
            max-height: 450px;
        }
        
        .shelters-list {
            max-height: 300px;
        }
        
        .shelter-card {
            padding: 8px;
        }
        
        .shelter-icon {
            width: 28px;
            height: 28px;
        }
        
        .shelter-icon svg {
            width: 12px;
            height: 12px;
        }
        
        .shelter-info h3 {
            font-size: 0.9rem;
        }
    }
</style>
@endsection

@section('content')
<div class="map-page">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Find Vet Shops & Services</h1>
        <p class="page-subtitle">Discover veterinarians and pet services near you</p>
    </div>

    <!-- Main Layout -->
    <div class="main-layout">
        <!-- Left Column - Available Veterinarians -->
        <div class="shelters-column">
            <div class="shelters-container">
                <div class="section-header">
                    <h2 class="section-title">Available Veterinarians</h2>
                    <p class="section-subtitle">{{ $shelters->count() }} veterinarians found</p>
                </div>
                
                @if($shelters->count() > 0)
                    <div class="shelters-list" id="shelters-list">
                        @foreach($shelters as $shelter)
                            <div class="shelter-card" 
                                 data-shelter-id="{{ $shelter->id }}"
                                 data-latitude="{{ $shelter->latitude }}"
                                 data-longitude="{{ $shelter->longitude }}">
                                <div class="shelter-header">
                                    <div class="shelter-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                        </svg>
                                    </div>
                                    <div class="shelter-info">
                                        <h3>{{ $shelter->name }}</h3>
                                        <p>{{ $shelter->city }}, {{ $shelter->province }}</p>
                                    </div>
                                </div>

                                <span class="vet-badge">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                                    </svg>
                                    Veterinarian
                                </span>

                                @if($shelter->description)
                                    <p class="shelter-description">{{ $shelter->description }}</p>
                                @endif

                                <div class="shelter-details">
                                    <div class="detail-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                        <span>{{ $shelter->address }}</span>
                                    </div>
                                    @if($shelter->phone)
                                        <div class="detail-item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                            </svg>
                                            <span>{{ $shelter->phone }}</span>
                                        </div>
                                    @endif
                                    @if($shelter->email)
                                        <div class="detail-item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                                <polyline points="22,6 12,13 2,6"/>
                                            </svg>
                                            <span>{{ $shelter->email }}</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="view-on-map" onclick="focusOnShelter('{{ $shelter->id }}', {{ $shelter->latitude }}, {{ $shelter->longitude }})">
                                    <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <span>View on map</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                                <line x1="9" y1="9" x2="9.01" y2="9"/>
                                <line x1="15" y1="9" x2="15.01" y2="9"/>
                            </svg>
                        </div>
                        <h3 class="empty-title">No veterinarians available</h3>
                        <p class="empty-text">Check back later for service locations</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column - Map -->
        <div class="map-column">
            <div class="map-wrapper">
                <div class="map-header">
                    <h2 class="map-title">Interactive Map</h2>
                    <p class="map-subtitle">Click on markers for details</p>
                </div>
                
                <div class="map-container">
                    <div id="shelterMap"></div>
                    <div class="map-controls">
                        <button id="fullscreen-btn" class="map-btn" title="Fullscreen">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="notification">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(function() {
            const notification = document.querySelector('.notification');
            if (notification) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100px)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 5000);
    </script>
@endif

<!-- Fullscreen Overlay -->
<div id="fullscreen-overlay" class="fullscreen-overlay">
    <div id="fullscreen-map"></div>
    <div class="map-controls">
        <button id="exit-fullscreen-btn" class="map-btn" title="Exit Fullscreen">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                <path d="M8 3v3a2 2 0 0 1-2 2H3m18 0h-3a2 2 0 0 1-2-2V3m0 18v-3a2 2 0 0 1 2-2h3M3 16h3a2 2 0 0 1 2 2v3"/>
            </svg>
        </button>
    </div>
</div>

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
// Map data
const mapShelters = @json($shelters);
const mapLostFoundItems = @json($lostFoundItems);
const mapData = [...mapShelters, ...mapLostFoundItems];
let shelterMarkers = new Map();
let activeMarker = null;

// Initialize map
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        if (typeof SharedMap !== 'undefined') {
            const sharedMap = new SharedMap('shelterMap', mapData, {
                fullscreenEnabled: true,
                showViewDetails: true,
                viewDetailsRoute: '/view-map/'
            });
            
            // Store markers for interaction
            sharedMap.markersLayer.eachLayer(function(layer) {
                if (layer._latlng) {
                    const lat = layer._latlng.lat;
                    const lng = layer._latlng.lng;
                    
                    // Find corresponding shelter
                    mapShelters.forEach(shelter => {
                        if (parseFloat(shelter.latitude) === lat && parseFloat(shelter.longitude) === lng) {
                            shelterMarkers.set(shelter.id, layer);
                            
                            // Add click event to marker
                            layer.on('click', function() {
                                highlightShelterCard(shelter.id);
                            });
                        }
                    });
                }
            });
            
            const urlParams = new URLSearchParams(window.location.search);
            const focusShelterId = urlParams.get('shelter');
            
            if (focusShelterId) {
                const focusShelter = mapData.find(item => item.id == focusShelterId && item.latitude && item.longitude);
                
                if (focusShelter) {
                    setTimeout(function() {
                        sharedMap.map.setView([parseFloat(focusShelter.latitude), parseFloat(focusShelter.longitude)], 15);
                        highlightShelterCard(focusShelterId);
                        
                        sharedMap.markersLayer.eachLayer(function(layer) {
                            if (layer._latlng.lat === parseFloat(focusShelter.latitude) && 
                                layer._latlng.lng === parseFloat(focusShelter.longitude)) {
                                layer.openPopup();
                            }
                        });
                    }, 500);
                }
            }
            
            window.shelterMap = sharedMap;
        } else {
            initBasicMap();
        }
        
        // Add click events to shelter cards
        document.querySelectorAll('.shelter-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on the view-on-map button
                if (e.target.closest('.view-on-map')) return;
                
                const shelterId = this.dataset.shelterId;
                const latitude = parseFloat(this.dataset.latitude);
                const longitude = parseFloat(this.dataset.longitude);
                
                if (!isNaN(latitude) && !isNaN(longitude)) {
                    focusOnShelter(shelterId, latitude, longitude);
                }
            });
        });
        
        // Add click events to view-on-map buttons
        document.querySelectorAll('.view-on-map').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const card = this.closest('.shelter-card');
                const shelterId = card.dataset.shelterId;
                const latitude = parseFloat(card.dataset.latitude);
                const longitude = parseFloat(card.dataset.longitude);
                
                if (!isNaN(latitude) && !isNaN(longitude)) {
                    focusOnShelter(shelterId, latitude, longitude);
                }
            });
        });
        
        initFullscreenFunctionality();
        
        // Fix map size on load
        setTimeout(() => {
            if (window.shelterMap && window.shelterMap.map) {
                window.shelterMap.map.invalidateSize();
            }
        }, 300);
    }, 100);
});

function initBasicMap() {
    const map = L.map('shelterMap', {
        zoomControl: false
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    
    mapShelters.forEach(shelter => {
        if (shelter.latitude && shelter.longitude) {
            const lat = parseFloat(shelter.latitude);
            const lng = parseFloat(shelter.longitude);
            
            const customIcon = L.divIcon({
                html: `<div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-user-md" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${shelter.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563; word-break: break-word;">
                        <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.address}<br>
                        ${shelter.city}, ${shelter.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.phone || 'Not provided'}
                    </div>
                    ${shelter.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563; word-break: break-word;">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                            ${shelter.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <a href="/view-map/${shelter.id}" style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
            
            // Store marker for interaction
            shelterMarkers.set(shelter.id, marker);
            
            // Add click event to marker
            marker.on('click', function() {
                highlightShelterCard(shelter.id);
            });
        }
    });
    
    mapLostFoundItems.forEach(item => {
        if (item.latitude && item.longitude) {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            
            let iconHtml = '';
            if (item.image_path) {
                iconHtml = `<div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>`;
            } else {
                const color = item.type === 'lost' ? '#e74c3c' : '#27ae60';
                const iconClass = item.type === 'lost' ? 'fas fa-heart-broken' : 'fas fa-heart';
                iconHtml = `<div style="background: ${color}; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                    <i class="${iconClass}" style="font-size: 16px;"></i>
                </div>`;
            }
            
            const customIcon = L.divIcon({
                html: iconHtml,
                iconSize: item.image_path ? [56, 56] : [46, 46],
                iconAnchor: item.image_path ? [28, 28] : [23, 23],
                popupAnchor: [0, -28],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        ${item.image_path ? 
                            `<div style="width: 60px; height: 60px; border-radius: 8px; overflow: hidden; margin-right: 12px;">
                                <img src="/storage/${item.image_path}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>` : 
                            `<div style="background: ${item.type === 'lost' ? '#e74c3c' : '#27ae60'}; color: white; width: 60px; height: 60px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                                <i class="fas ${item.type === 'lost' ? 'fa-heart-broken' : 'fa-heart'}" style="font-size: 24px;"></i>
                            </div>`
                        }
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${item.pet_name}</h4>
                            <span style="background: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Lost/Found</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-tag" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${item.pet_type}
                    </div>
                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <a href="/lost-found/${item.id}" style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(map);
        }
    });
    
    window.shelterMap = map;
}

// Function to focus on a specific shelter
function focusOnShelter(shelterId, latitude, longitude) {
    if (window.shelterMap && window.shelterMap.map) {
        const map = window.shelterMap.map || window.shelterMap;
        
        // Center map on shelter
        map.setView([latitude, longitude], 15);
        
        // Open marker popup if exists
        const marker = shelterMarkers.get(parseInt(shelterId));
        if (marker) {
            marker.openPopup();
            
            // Pulse animation for marker
            const icon = marker.getElement();
            if (icon) {
                icon.style.animation = 'pulse 0.5s 2';
                setTimeout(() => {
                    icon.style.animation = '';
                }, 1000);
            }
        }
        
        // Highlight shelter card
        highlightShelterCard(shelterId);
    }
}

// Function to highlight shelter card
function highlightShelterCard(shelterId) {
    // Remove active class from all cards
    document.querySelectorAll('.shelter-card').forEach(card => {
        card.classList.remove('active');
    });
    
    // Add active class to selected card
    const selectedCard = document.querySelector(`.shelter-card[data-shelter-id="${shelterId}"]`);
    if (selectedCard) {
        selectedCard.classList.add('active');
        
        // Scroll to card
        selectedCard.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

function initFullscreenFunctionality() {
    const fullscreenBtn = document.getElementById('fullscreen-btn');
    const exitFullscreenBtn = document.getElementById('exit-fullscreen-btn');
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', handleFullscreenToggle);
        fullscreenBtn.addEventListener('touchstart', handleFullscreenToggle);
    }
    
    if (exitFullscreenBtn) {
        exitFullscreenBtn.addEventListener('click', exitFullscreen);
        exitFullscreenBtn.addEventListener('touchstart', exitFullscreen);
    }
    
    if (fullscreenOverlay) {
        fullscreenOverlay.addEventListener('click', handleOverlayClick);
        fullscreenOverlay.addEventListener('touchstart', handleOverlayClick);
    }
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && fullscreenOverlay && fullscreenOverlay.style.display === 'block') {
            exitFullscreen();
        }
    });
}

function handleFullscreenToggle(e) {
    e.preventDefault();
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        if (!window.fullscreenMap) {
            initFullscreenMap();
        } else {
            setTimeout(() => {
                window.fullscreenMap.invalidateSize();
            }, 100);
        }
    }
}

function handleOverlayClick(e) {
    if (e.target.id === 'fullscreen-overlay') {
        exitFullscreen();
    }
}

function initFullscreenMap() {
    window.fullscreenMap = L.map('fullscreen-map', {
        zoomControl: false
    }).setView([8.504588, 125.975800], 15);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(window.fullscreenMap);
    
    // Add all markers to fullscreen map (same as main map)
    mapShelters.forEach(shelter => {
        if (shelter.latitude && shelter.longitude) {
            const lat = parseFloat(shelter.latitude);
            const lng = parseFloat(shelter.longitude);
            
            const customIcon = L.divIcon({
                html: `<div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"><i class="fas fa-user-md" style="font-size: 12px;"></i></div>`,
                iconSize: [36, 36],
                iconAnchor: [18, 18],
                popupAnchor: [0, -18],
                className: 'custom-marker'
            });
            
            const marker = L.marker([lat, lng], { icon: customIcon });
            
            const popupContent = `
                <div style="min-width: 250px;">
                    <div style="display: flex; align-items: center; margin-bottom: 10px;">
                        <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; font-size: 1.1rem; color: #1f2937;">${shelter.name}</h4>
                            <span style="background: #e5e7eb; color: #374151; padding: 2px 8px; border-radius: 12px; font-size: 0.75rem; font-weight: 600;">Veterinarian</span>
                        </div>
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563; word-break: break-word;">
                        <i class="fas fa-map-marker-alt" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.address}<br>
                        ${shelter.city}, ${shelter.province}
                    </div>
                    <div style="margin-bottom: 8px; color: #4b5563;">
                        <i class="fas fa-phone" style="color: #3b82f6; margin-right: 6px;"></i>
                        ${shelter.phone || 'Not provided'}
                    </div>
                    ${shelter.email ? `
                        <div style="margin-bottom: 12px; color: #4b5563; word-break: break-word;">
                            <i class="fas fa-envelope" style="color: #3b82f6; margin-right: 6px;"></i>
                            ${shelter.email}
                        </div>
                    ` : ''}
                    <div style="display: flex; justify-content: flex-end; margin-top: 12px;">
                        <a href="/view-map/${shelter.id}" style="background: #8b5cf6; color: white; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 0.8rem; font-weight: 600;">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            `;
            
            marker.bindPopup(popupContent);
            marker.addTo(window.fullscreenMap);
        }
    });
}

function exitFullscreen() {
    const fullscreenOverlay = document.getElementById('fullscreen-overlay');
    if (fullscreenOverlay) {
        fullscreenOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Handle window resize
window.addEventListener('resize', function() {
    if (window.shelterMap && window.shelterMap.map) {
        setTimeout(() => {
            window.shelterMap.map.invalidateSize();
        }, 300);
    }
});
</script>
@endsection