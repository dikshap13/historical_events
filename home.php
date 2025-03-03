<?php
require('database.php');
?>
<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Homepage</title>

    <meta content="" name="description">

    <meta content="" name="keywords">

    <!-- Favicons -->

    <link href="assets/ProNeta_PNG.png" rel="icon">

    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->

    <link href="https://fonts.gstatic.com" rel="preconnect">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->

    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">

    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">

    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">

    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">

    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

    <!-- Template Main CSS File -->

    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script src="assets/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- <style>
        #main_div {
            padding: 20px;
            margin-top: 50px;
        }

        #wrapper {
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 500px;
        }

        #timeline {
            width: 30%;
            overflow-y: auto;
            padding: 10px;
            background: #f4f4f4;
            height: 100%;
            border-right: 2px solid #ddd;
        }

        #map {
            width: 70%;
            height: 100%;
        }

        .timeline-event {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }

        .timeline-event:hover {
            background: #ddd;
        }
    </style> -->
    <style>
        #main_div {
            padding: 20px;
            margin-top: 50px;
        }

        #wrapper {
            display: flex;
            align-items: flex-start;
            gap: 20px;
        }

        #timeline-container {
            width: 300px;
            height: 500px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 10px;
            border-right: 2px solid #ddd;
            position: relative;
        }

        #timeline {
            position: relative;
            width: 100%;
        }

        .timeline-event {
            position: relative;
            padding: 10px;
            background: white;
            margin-bottom: 10px;
            border-left: 3px solid #6f42c5;
            cursor: pointer;
        }

        .timeline-event .arrow {
            position: absolute;
            top: 50%;
            left: 100%;
            transform-origin: left center;
            width: 0;
            height: 2px;
            background: red;
            transition: width 0.3s ease-in-out;
        }

        #map {
            flex-grow: 1;
            height: 500px;
        }
    </style>

</head>



<body>

    <!-- ======= Header ======= -->

    <?php

    include('headerbar.php');

    ?>
    <!-- <main id="main_div">
        <div id="wrapper">
            <div id="map"></div>
            <div id="timeline"></div>
        </div>
    </main> -->
    <main id="main_div">
        <div id="wrapper">
            <div id="timeline-container">
                <div id="timeline"></div>
            </div>
            <div id="map"></div>
        </div>
    </main>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->

    <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>

    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js">

    </script>

    <script src="assets/vendor/chart.js/chart.min.js"></script>

    <script src="assets/vendor/echarts/echarts.min.js"></script>

    <script src="assets/vendor/quill/quill.min.js"></script>

    <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>

    <script src="assets/vendor/tinymce/tinymce.min.js"></script>

    <script src="assets/vendor/php-email-form/validate.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('map').setView([20, 77], 5);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            let markers = {};
            let polylines = [];
            let timeline = document.getElementById("timeline");
            let timelineContainer = document.getElementById("timeline-container");

            // Fetch events from PHP
            fetch('fetch_events.php')
                .then(response => response.json())
                .then(events => {
                    let bounds = [];
                    events.forEach(event => {
                        // Add marker to the map
                        let marker = L.marker([event.latitude, event.longitude])
                            .addTo(map)
                            .bindPopup(`<b>${event.title}</b><br>${event.event_date}`);

                        markers[event.id] = marker;
                        bounds.push([event.latitude, event.longitude]);

                        // Add event to timeline
                        let eventDiv = document.createElement("div");
                        eventDiv.className = "timeline-event";
                        eventDiv.innerHTML = `<strong>${event.event_date}</strong> - ${event.title}`;

                        // Create arrow
                        let arrow = document.createElement("div");
                        arrow.className = "arrow";
                        eventDiv.appendChild(arrow);

                        eventDiv.dataset.eventId = event.id;
                        eventDiv.addEventListener("click", function() {
                            highlightEvent(event.id, eventDiv, arrow);
                        });

                        timeline.appendChild(eventDiv);
                    });

                    map.fitBounds(bounds);
                });

            function highlightEvent(eventId, eventElement, arrow) {
                polylines.forEach(line => map.removeLayer(line));
                polylines = [];

                let marker = markers[eventId];
                if (marker) {
                    marker.openPopup();

                    // Get event's position inside the scrollable div
                    let scrollTop = timelineContainer.scrollTop;
                    let rect = eventElement.getBoundingClientRect();
                    let containerRect = timelineContainer.getBoundingClientRect();
                    let timelineCenterX = containerRect.right;
                    let timelineCenterY = rect.top - containerRect.top + scrollTop + (rect.height / 2);

                    let eventPoint = map.containerPointToLatLng([timelineCenterX, timelineCenterY]);

                    let latLngs = [
                        eventPoint,
                        marker.getLatLng()
                    ];

                    let polyline = L.polyline(latLngs, {
                        color: 'red',
                        weight: 2
                    }).addTo(map);
                    polylines.push(polyline);

                    // **Position and Rotate Arrow Correctly**
                    let mapPoint = map.latLngToContainerPoint(marker.getLatLng());
                    let angle = Math.atan2(mapPoint.y - timelineCenterY, mapPoint.x - timelineCenterX) * (180 / Math.PI);

                    arrow.style.transform = `translateY(-50%) rotate(${angle}deg)`;
                    arrow.style.width = Math.hypot(mapPoint.x - timelineCenterX, mapPoint.y - timelineCenterY) + "px";
                }
            }
        });
    </script>

    <!-- Template Main JS File -->

    <script src="assets/js/main.js"></script>

</body>



</html>