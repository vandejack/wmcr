@extends('layout')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
<style>
    #map { height: 400px; }
    .leaflet-layer,
.leaflet-control-zoom-in,
.leaflet-control-zoom-out,
.leaflet-control-attribution {
  filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(90%);
}
</style>
@endsection

@section('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

<script>
    // Initialize the map
    var center = { lng: 114.722891, lat: -3.394376};
    var map = L.map('map').setView(center, 7);

    // Load tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);
    var locations;
    function fetchLocationData() {
        fetch('/sector/odpSector/{{ $witel }}')
            .then(response => {
                if (!response.ok) {
                throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (!data || !data.locations) {
                throw new Error('Invalid data format');
                }

                // Create a new marker cluster group
                var markers = L.markerClusterGroup();
                 // Set locations for use in other functions
            locations = data.locations;
                // Add markers to the cluster group
                data.locations.forEach(location => {
                if (location.lat && location.lng && location.title) {
                    var marker = L.marker([location.lat, location.lng])
                    .bindPopup("Location: " + location.title + "<br>Location ID: " + location.id);
                    markers.addLayer(marker);
                } else {
                    console.warn('Skipping invalid location:', location);
                }
                });

                // Add marker cluster group to the map
                map.addLayer(markers);
            })
            .catch(error => console.error('Error fetching location data:', error));
        }
        fetchLocationData();

    // Initialize drawing features
    var drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems
        },
        draw: {
            polygon: true,
            polyline: false,
            circle: false,
            rectangle: false,
            marker: false,
            circlemarker: false
        }
    });
    map.addControl(drawControl);

    // Function to check if a point is inside a polygon
    function isPointInPolygon(point, polygon) {
// Create a Turf point from the point coordinates
var turfPoint = turf.point([point.lng, point.lat]); // Note that Turf uses [lng, lat]

// Ensure the polygon coordinates are closed by adding the first coordinate to the end
var closedPolygonCoordinates = [...polygon.map(coord => [coord.lng, coord.lat]), [polygon[0].lng, polygon[0].lat]];

// Create a Turf polygon from the closed coordinates
var turfPolygon = turf.polygon([closedPolygonCoordinates]);

// Check if the point is inside the polygon
return turf.booleanPointInPolygon(turfPoint, turfPolygon);
    }

    // Handle polygon creation
    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        drawnItems.addLayer(layer);

        // Get polygon coordinates
        var polygonCoordinates = layer.getLatLngs()[0];

        // Format coordinates for submission
        var coordinatesFormatted = polygonCoordinates.map(function (coord) {
            return [coord.lat, coord.lng];
        });
        document.getElementById('coordinates').value = JSON.stringify(coordinatesFormatted);
        console.log(coordinatesFormatted);
        if (locations) {
        // Find locations inside the polygon
        var locationsInsidePolygon = locations.filter(function(location) {
            var point = L.latLng(location.lat, location.lng);
            return isPointInPolygon(point, polygonCoordinates);
        });
        document.getElementById('alpro').value = JSON.stringify(locationsInsidePolygon);

        console.log("Locations inside polygon:", locationsInsidePolygon);
    } else {
        console.warn('Locations data is not available.');
    }
    });

    // Handle polygon edit
    map.on(L.Draw.Event.EDITED, function (event) {
        var layers = event.layers;
        layers.eachLayer(function (layer) {
            var polygonCoordinates = layer.getLatLngs()[0];

            // Format coordinates for submission
            var coordinatesFormatted = polygonCoordinates.map(function (coord) {
                return [coord.lat, coord.lng];
            }); 
            document.getElementById('coordinates').value = JSON.stringify(coordinatesFormatted);

            // Find locations inside the polygon
            var locationsInsidePolygon = locations.filter(function(location) {
                return isPointInPolygon(location, polygonCoordinates);
            });

            console.log("Locations inside polygon after edit:", locationsInsidePolygon);
        });
    });
</script>
@endsection
@section('title', 'Mapping Alpro')
@include('partial.alerts')
@section('content')
<div class="card shadow-sm">
	<div class="card-body pb-4">
        <div class="table-responsive">
            <form method="post">
            <div class="row">
                <h1>Select Polygon and Find Your Sector Alpro</h1>
                <div class="row">
                    <div class="col-sm-6 my-4">
                        <label for="sector_id" class="fw-semibold fs-6 mb-2">Sektor</label>
                        <select name="sector_id" class="form-control">
                            <option value=""></option>
                            @foreach ($sector as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="map"></div>
                
                    <div class="row">
                        <div class="col-sm-6 my-4">
                            <label class="fw-semibold fs-6 mb-2">Polygon Coordinates</label>
                            <textarea id="coordinates" name="coordinates" class="form-control" placeholder="Polygon Coordinates" readonly></textarea>
                        </div>
                        <div class="col-sm-6 my-4">
                            <label class="fw-semibold fs-6 mb-2">Alpro inside Polygon</label>
                            <textarea id="alpro" name="alpro" class="form-control" placeholder="Alpro inside Polygon" readonly></textarea>    
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
        </div>
    </div>
</div>


@endsection