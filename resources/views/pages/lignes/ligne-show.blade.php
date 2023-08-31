
@extends('pages.layouts.layout')
@section('content')


<div class="row">
  
<div class="card text-start col-5">

<div class="card-body p-4">
    <h5 class="card-title fw-semibold mb-4">Informations sur la  <span class="hightlight">ligne </span></h5>
<form method="POST" action="{{ route('ligne.update', $ligne->id) }}">
    @csrf
    @method('PUT')

    {{-- <div class="mb-3">
        <label for="itineraire" class="form-label">Ficher de l'itinéraire (KML)</label>
        <input type="file" readonly value="{{ old('itineraire') }}" class="form-control @error('itineraire') is-invalid @enderror" id="itineraire" name="itineraire" accept=".kml">
        @error('itineraire')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div> --}}
    <div class="mb-3">
        <label for="check_point"  class="form-label">Check-Point</label>
        <textarea class="form-control   @error('check_point') is-invalid @enderror" rows="10" readonly id="check_point" name="check_point" required>{{ old('check_point', $ligne->check_point) }}</textarea>
        @error('check_point')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    

    <div class="mb-3">
        <label for="numero" class="form-label">Numero</label>
        <input type="number " readonly class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $ligne->numero) }}" required>
        @error('numero')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

</form>
</div>
</div>

<div class="col-7">
    <p class='text-center h5'>Itinéraire du bus <span class="hightlight">{{$ligne->numero}}</span> </p>
<div id='map' class="rounded"></div>
</div>


</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-omnivore/0.3.4/leaflet-omnivore.min.js" integrity="sha512-55AYz+N6WyuiC8bRpQftNyCcSBCl3AEutoTsb4EeZuFVFP1+G4gll30iczAvvTpdL9nz48F7ZFEUavRUXp3FNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    let map, markers = [];
    /* ----------------------------- Initialize Map ----------------------------- */
    function initMap() {
        map = L.map('map', {
            center: {
                lat: 14.6999, // Latitude of Dakar, Senegal
                lng: -17.4477, // Longitude of Dakar, Senegal
            },
            zoom: 12
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        map.on('click', mapClicked);
        initMarkers();
    }
    initMap();

    /* --------------------------- Initialize Markers --------------------------- */
    // function initMarkers() {
    //     const initialMarkers = <?php echo json_encode($initialMarkers); ?>;

    //     for (let index = 0; index < initialMarkers.length; index++) {

    //         const data = initialMarkers[index];
    //         const marker = generateMarker(data, index);
    //         marker.addTo(map).bindPopup(`<b>${data.position.lat},  ${data.position.lng}</b>`);
    //         map.panTo(data.position);
    //         markers.push(marker)
    //     }
    // }

    // function generateMarker(data, index) {
    //     return L.marker(data.position, {
    //             draggable: data.draggable
    //         })
    //         .on('click', (event) => markerClicked(event, index))
    //         .on('dragend', (event) => markerDragEnd(event, index));
    // }

    /* ------------------------- Handle Map Click Event ------------------------- */
    function mapClicked($event) {
        console.log("{{ asset($ligne->itineraire) }}")
        console.log(map);
        console.log($event.latlng.lat, $event.latlng.lng);
    }

    /* ------------------------ Handle Marker Click Event ----------------------- */
    function markerClicked($event, index) {
        console.log(map);
        console.log($event.latlng.lat, $event.latlng.lng);
    }

    /* ----------------------- Handle Marker DragEnd Event ---------------------- */
    function markerDragEnd($event, index) {
        console.log(map);
        console.log($event.target.getLatLng());
    }
</script>



@endsection