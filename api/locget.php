<?php
// Get coordinates from GET request with fallback defaults
$lat = isset($_GET['lat']) ? floatval($_GET['lat']) : 51.505;
$lon = isset($_GET['lon']) ? floatval($_GET['lon']) : -0.09;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Location Navigator</title>
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        /* ----- RESET & BASE ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #0b0e14;
            color: #e8edf5;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ----- HEADER ----- */
        .app-header {
            background: rgba(18, 24, 34, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 14px 20px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            z-index: 1000;
            flex-shrink: 0;
            gap: 10px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            font-size: 1.6rem;
            line-height: 1;
        }

        .brand h1 {
            font-size: 1.15rem;
            font-weight: 600;
            letter-spacing: -0.3px;
            background: linear-gradient(135deg, #a8e6cf, #56cfe1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .coord-badge {
            background: rgba(86, 207, 225, 0.12);
            border: 1px solid rgba(86, 207, 225, 0.2);
            border-radius: 40px;
            padding: 6px 16px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #b0e0e8;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
            white-space: nowrap;
        }

        .coord-badge span {
            color: #56cfe1;
            font-weight: 600;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.25s ease;
            backdrop-filter: blur(4px);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn:hover {
            background: rgba(86, 207, 225, 0.15);
            border-color: rgba(86, 207, 225, 0.3);
            color: #fff;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #56cfe1, #3b9eb3);
            border-color: #56cfe1;
            color: #0b0e14;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #6fd9ea, #4aafc4);
            border-color: #8ae2f0;
            color: #0b0e14;
        }

        /* ----- MAP CONTAINER ----- */
        #map {
            flex: 1;
            width: 100%;
            background: #1a1f2a;
            position: relative;
        }

        /* Leaflet overrides for dark theme */
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5) !important;
        }

        .leaflet-control-zoom a {
            background: rgba(18, 24, 34, 0.85) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            color: #e8edf5 !important;
            border-color: rgba(255, 255, 255, 0.06) !important;
        }

        .leaflet-control-zoom a:hover {
            background: rgba(86, 207, 225, 0.2) !important;
            color: #56cfe1 !important;
        }

        .leaflet-popup-content-wrapper {
            background: rgba(18, 24, 34, 0.92) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            color: #e8edf5 !important;
            border-radius: 16px !important;
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.6) !important;
        }

        .leaflet-popup-tip {
            background: rgba(18, 24, 34, 0.92) !important;
        }

        .leaflet-popup-content {
            font-size: 0.9rem;
            line-height: 1.5;
            padding: 4px 0;
        }

        .leaflet-popup-content strong {
            color: #56cfe1;
        }

        /* Custom marker pulse animation */
        .custom-marker {
            background: #56cfe1;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 3px solid #0b0e14;
            box-shadow: 0 0 0 0 rgba(86, 207, 225, 0.7);
            animation: pulse-marker 2s infinite;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .custom-marker:hover {
            transform: scale(1.2);
        }

        @keyframes pulse-marker {
            0% { box-shadow: 0 0 0 0 rgba(86, 207, 225, 0.7); }
            70% { box-shadow: 0 0 0 18px rgba(86, 207, 225, 0); }
            100% { box-shadow: 0 0 0 0 rgba(86, 207, 225, 0); }
        }

        /* Custom control - coordinate display */
        .coord-control {
            background: rgba(18, 24, 34, 0.85) !important;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border-radius: 12px !important;
            padding: 10px 16px !important;
            border: 1px solid rgba(255, 255, 255, 0.06) !important;
            color: #e8edf5 !important;
            font-size: 0.8rem !important;
            font-weight: 500;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4) !important;
            text-align: center;
            min-width: 120px;
        }

        .coord-control .label {
            color: #8a9bb5;
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        .coord-control .value {
            color: #56cfe1;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* Scale control styling */
        .leaflet-control-scale {
            margin-bottom: 30px !important;
        }

        .leaflet-control-scale-line {
            background: rgba(18, 24, 34, 0.8) !important;
            backdrop-filter: blur(4px);
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #cbd5e1 !important;
        }

        /* Responsive */
        @media (max-width: 640px) {
            .app-header {
                padding: 10px 14px;
            }

            .brand h1 {
                font-size: 0.95rem;
            }

            .coord-badge {
                font-size: 0.65rem;
                padding: 4px 12px;
                width: 100%;
                justify-content: center;
                order: 10;
            }

            .action-buttons .btn {
                font-size: 0.65rem;
                padding: 4px 10px;
            }

            .coord-control {
                font-size: 0.65rem !important;
                padding: 6px 12px !important;
                min-width: 90px;
            }
        }

        @media (max-width: 400px) {
            .brand-icon {
                font-size: 1.2rem;
            }
            .brand h1 {
                font-size: 0.8rem;
            }
            .btn {
                font-size: 0.55rem !important;
                padding: 3px 8px !important;
            }
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="app-header">
        <div class="brand">
            <span class="brand-icon">📍</span>
            <h1>Location Navigator</h1>
        </div>

        <div class="coord-badge">
            <span>📍</span>
            <span id="displayLat"><?= number_format($lat, 6) ?></span>,
            <span id="displayLon"><?= number_format($lon, 6) ?></span>
        </div>

        <div class="action-buttons">
            <a href="#" class="btn" id="resetViewBtn" title="Reset view">⟲ Reset</a>
            <a href="#" class="btn btn-primary" id="locateBtn">📍 Locate Me</a>
        </div>
    </header>

    <!-- MAP -->
    <div id="map"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    <script>
        (function() {
            'use strict';

            // ----- Get coords from PHP via data attributes -----
            const initialLat = <?= json_encode($lat) ?>;
            const initialLon = <?= json_encode($lon) ?>;

            // ----- Initialize map with a dark tile layer -----
            const map = L.map('map', {
                center: [initialLat, initialLon],
                zoom: 14,
                zoomControl: false,
                fadeAnimation: true,
                zoomAnimation: true,
                inertia: true,
            });

            // Add zoom control to top-right
            L.control.zoom({
                position: 'topright'
            }).addTo(map);

            // ----- Dark & modern tile layer (CartoDB Dark) -----
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a>, &copy; CartoDB',
                subdomains: 'abcd',
                maxZoom: 19,
                minZoom: 3,
            }).addTo(map);

            // ----- Custom marker icon with pulse -----
            const markerIcon = L.divIcon({
                className: 'custom-marker',
                iconSize: [18, 18],
                iconAnchor: [9, 9],
                popupAnchor: [0, -14],
            });

            // ----- Add marker at initial position -----
            let marker = L.marker([initialLat, initialLon], {
                icon: markerIcon,
                draggable: true,
                autoPan: true,
            }).addTo(map);

            // ----- Popup with details -----
            const popupContent = `
                    <strong>📍 Target Location</strong><br>
                    <span id="popupLat">${initialLat.toFixed(6)}</span>,
                    <span id="popupLon">${initialLon.toFixed(6)}</span><br>
                    <span style="font-size:0.75rem;color:#8a9bb5;">🖱️ Drag me to update</span>
                `;
            marker.bindPopup(popupContent, {
                closeButton: false,
                className: 'custom-popup',
                maxWidth: 260,
            }).openPopup();

            // ----- Update header & popup when marker is dragged -----
            function updateCoords(lat, lon) {
                const latStr = lat.toFixed(6);
                const lonStr = lon.toFixed(6);
                document.getElementById('displayLat').textContent = latStr;
                document.getElementById('displayLon').textContent = lonStr;

                // Update popup content
                const popup = marker.getPopup();
                if (popup) {
                    const content = `
                            <strong>📍 Target Location</strong><br>
                            <span id="popupLat">${latStr}</span>,
                            <span id="popupLon">${lonStr}</span><br>
                            <span style="font-size:0.75rem;color:#8a9bb5;">🖱️ Drag me to update</span>
                        `;
                    popup.setContent(content);
                }
            }

            marker.on('dragend', function(e) {
                const pos = marker.getLatLng();
                updateCoords(pos.lat, pos.lng);
                // Update URL without reload (history pushState)
                const url = new URL(window.location);
                url.searchParams.set('lat', pos.lat.toFixed(6));
                url.searchParams.set('lon', pos.lng.toFixed(6));
                window.history.pushState({ lat: pos.lat, lon: pos.lng }, '', url);
            });

            // ----- Custom control: show lat/lon on map (bottom-left) -----
            const coordControl = L.control({
                position: 'bottomleft'
            });

            coordControl.onAdd = function() {
                const div = L.DomUtil.create('div', 'coord-control');
                div.innerHTML = `
                        <span class="label">Current</span>
                        <span class="value" id="mapCoordDisplay">
                            ${initialLat.toFixed(6)}, ${initialLon.toFixed(6)}
                        </span>
                    `;
                return div;
            };
            coordControl.addTo(map);

            // Update the custom control when marker moves
            function updateMapCoordDisplay(lat, lon) {
                const el = document.getElementById('mapCoordDisplay');
                if (el) {
                    el.textContent = `${lat.toFixed(6)}, ${lon.toFixed(6)}`;
                }
            }

            // Override updateCoords to also update the custom control
            const originalUpdate = updateCoords;
            updateCoords = function(lat, lon) {
                originalUpdate(lat, lon);
                updateMapCoordDisplay(lat, lon);
            };

            // ----- Scale control (cool feature) -----
            L.control.scale({
                position: 'bottomright',
                metric: true,
                imperial: false,
            }).addTo(map);

            // ----- Reset view button -----
            document.getElementById('resetViewBtn').addEventListener('click', function(e) {
                e.preventDefault();
                const pos = marker.getLatLng();
                map.flyTo([pos.lat, pos.lng], 14, {
                    duration: 1.2,
                    easeLinearity: 0.4
                });
                marker.openPopup();
            });

            // ----- Locate Me button (browser geolocation) -----
            document.getElementById('locateBtn').addEventListener('click', function(e) {
                e.preventDefault();

                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }

                const btn = this;
                btn.textContent = '⏳ Locating...';
                btn.style.opacity = '0.6';

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        // Move marker
                        marker.setLatLng([lat, lon]);
                        updateCoords(lat, lon);

                        // Fly to location
                        map.flyTo([lat, lon], 15, {
                            duration: 1.5,
                            easeLinearity: 0.3
                        });

                        // Update URL
                        const url = new URL(window.location);
                        url.searchParams.set('lat', lat.toFixed(6));
                        url.searchParams.set('lon', lon.toFixed(6));
                        window.history.pushState({ lat, lon }, '', url);

                        // Open popup after fly
                        setTimeout(() => {
                            marker.openPopup();
                        }, 1600);

                        btn.textContent = '📍 Locate Me';
                        btn.style.opacity = '1';
                    },
                    function(error) {
                        console.warn('Geolocation error:', error);
                        let msg = 'Unable to get your location. ';
                        if (error.code === 1) msg += 'Please allow location access.';
                        else if (error.code === 2) msg += 'Position unavailable.';
                        else if (error.code === 3) msg += 'Request timed out.';
                        alert(msg);
                        btn.textContent = '📍 Locate Me';
                        btn.style.opacity = '1';
                    }, {
                        enableHighAccuracy: true,
                        timeout: 8000,
                        maximumAge: 0
                    }
                );
            });

            // ----- Handle popstate (browser back/forward) -----
            window.addEventListener('popstate', function(e) {
                if (e.state && typeof e.state.lat === 'number' && typeof e.state.lon === 'number') {
                    const lat = e.state.lat;
                    const lon = e.state.lon;
                    marker.setLatLng([lat, lon]);
                    updateCoords(lat, lon);
                    map.setView([lat, lon], map.getZoom(), { animate: true });
                }
            });

            // ----- Click on map to move marker (cool feature) -----
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lon = e.latlng.lng;
                marker.setLatLng([lat, lon]);
                updateCoords(lat, lon);

                const url = new URL(window.location);
                url.searchParams.set('lat', lat.toFixed(6));
                url.searchParams.set('lon', lon.toFixed(6));
                window.history.pushState({ lat, lon }, '', url);

                marker.openPopup();
            });

            // ----- Initial popup open after load -----
            setTimeout(() => {
                marker.openPopup();
            }, 400);

            // ----- Responsive: update map size on resize -----
            window.addEventListener('resize', function() {
                map.invalidateSize();
            });

            // ----- Keyboard shortcuts (cool) -----
            document.addEventListener('keydown', function(e) {
                // 'r' to reset view
                if (e.key === 'r' && !e.ctrlKey && !e.metaKey) {
                    e.preventDefault();
                    document.getElementById('resetViewBtn').click();
                }
                // 'l' to locate
                if (e.key === 'l' && !e.ctrlKey && !e.metaKey) {
                    e.preventDefault();
                    document.getElementById('locateBtn').click();
                }
                // Escape to close popup
                if (e.key === 'Escape') {
                    marker.closePopup();
                }
            });

            console.log('📍 Location Navigator initialized');
            console.log(`📍 Initial coordinates: ${initialLat}, ${initialLon}`);

        })();
    </script>

</body>
</html>
