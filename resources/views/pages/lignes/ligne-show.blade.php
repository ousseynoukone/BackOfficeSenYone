
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
<div class="col-7" style="  z-index: 0;
">
    <p class='text-center h5'>Itinéraire du bus <span class="hightlight">{{$ligne->numero}}</span> </p>
<div id='map' class="rounded" ></div>
</div>


</div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-omnivore/0.3.4/leaflet-omnivore.min.js" integrity="sha512-55AYz+N6WyuiC8bRpQftNyCcSBCl3AEutoTsb4EeZuFVFP1+G4gll30iczAvvTpdL9nz48F7ZFEUavRUXp3FNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js" integrity="sha512-BwHfrr4c9kmRkLw6iXFdzcdWV/PGkVgiIyIWLLlTSXzWQzxuSg4DiQUCpauz/EWjgk5TYQqX/kvn9pG1NpYfqg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
//     let geojsonData ={
// "type": "FeatureCollection",
// "name": "Ligne 1 AFTU",
// "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
// "features": [
// { "type": "Feature", "properties": { "Name": "Ligne 1 AFTU" }, "geometry": { "type": "LineString", "coordinates": [ [ -17.441367710821101, 14.669264756224599 ], [ -17.4417136419288, 14.6691152637275 ], [ -17.441840779380101, 14.669549599350701 ], [ -17.441364956837798, 14.6703051502754 ], [ -17.442221196388999, 14.671250715519101 ], [ -17.441088380071399, 14.672219519813 ], [ -17.441080717408902, 14.6723095425723 ], [ -17.4417276424373, 14.6728224950875 ], [ -17.442166696187599, 14.6733928445299 ], [ -17.442560266238601, 14.6741134264966 ], [ -17.442977828101601, 14.6747285760724 ], [ -17.4435543608156, 14.6752622572617 ], [ -17.444741714280902, 14.676367169529801 ], [ -17.4456742278769, 14.6772719700117 ], [ -17.446521701804301, 14.6780503835879 ], [ -17.448036727920599, 14.679517321014099 ], [ -17.449947171782998, 14.681318144027101 ], [ -17.45149007649, 14.682706424222699 ], [ -17.453436717822299, 14.684531054144699 ], [ -17.4531745138707, 14.685054451756899 ], [ -17.451270431161699, 14.6885524581357 ], [ -17.4495210231828, 14.691779681760501 ], [ -17.450679843963801, 14.6923121495764 ], [ -17.451822615176201, 14.6927998579199 ], [ -17.452395995476699, 14.693033676320701 ], [ -17.453682485910399, 14.693594873366299 ], [ -17.454095711241202, 14.6938131799364 ], [ -17.454506173348399, 14.6943071454101 ], [ -17.455565548656999, 14.6957502066199 ], [ -17.4555301527712, 14.6957589243353 ], [ -17.455554703198999, 14.6957352468675 ], [ -17.4561994536678, 14.6963291963276 ], [ -17.4562243571959, 14.6963530484961 ], [ -17.456800350034101, 14.696999857318 ], [ -17.4567752548664, 14.696975806666201 ], [ -17.456751240790702, 14.697048010719699 ], [ -17.4567755271121, 14.6969998646249 ], [ -17.4567757995286, 14.6970239303306 ], [ -17.456976538402198, 14.6971925360547 ], [ -17.457967346552799, 14.6983103533909 ], [ -17.457967871547901, 14.698334906456401 ], [ -17.4591772097559, 14.699703188587 ], [ -17.461102811608399, 14.702003235215001 ], [ -17.462928072835499, 14.7039401097223 ], [ -17.460920545529898, 14.7062734820539 ], [ -17.458211057885499, 14.7092946606202 ], [ -17.4572467595458, 14.710386932051099 ], [ -17.456706189417201, 14.7109831824148 ], [ -17.455610719524799, 14.712224597451 ], [ -17.454883360635598, 14.7130143846029 ], [ -17.454653377005201, 14.712982174642701 ], [ -17.4544479924967, 14.7131187239815 ], [ -17.454471605106502, 14.7133470316877 ], [ -17.454669413236701, 14.7136311426285 ], [ -17.454865771674498, 14.7142390974441 ], [ -17.4552469059246, 14.7153453962116 ], [ -17.455570474065699, 14.716251169502399 ], [ -17.456047495209699, 14.7177217639925 ], [ -17.456540480353699, 14.719137504008399 ], [ -17.456833300366299, 14.720017966671501 ], [ -17.4572712043534, 14.721331385642999 ], [ -17.457592288126801, 14.7222310039287 ], [ -17.457743272416501, 14.7226989209036 ], [ -17.457845148218802, 14.7231295950491 ], [ -17.457255932020601, 14.723184874257999 ], [ -17.457153833066201, 14.723796350561001 ], [ -17.4570060783409, 14.724770685293899 ], [ -17.456847633432702, 14.7259693644127 ], [ -17.456465670170299, 14.7284437426541 ], [ -17.456503540881101, 14.728532345042 ], [ -17.4569624428017, 14.728693523970099 ], [ -17.457238335938499, 14.728889208879499 ], [ -17.457686554097599, 14.729468757026901 ], [ -17.458567328758999, 14.7305806804418 ], [ -17.460455306129301, 14.7329953609031 ], [ -17.4614202167968, 14.7342138337597 ], [ -17.461846177292099, 14.7347702027535 ], [ -17.462690344606202, 14.735677700122601 ], [ -17.464170479445301, 14.737217211802699 ], [ -17.4633905116176, 14.737947729414699 ], [ -17.462251266875501, 14.739324622185499 ], [ -17.461379126290701, 14.740340359902801 ], [ -17.458689536545599, 14.7435847595523 ], [ -17.457493121793298, 14.7449144759772 ], [ -17.457038131626199, 14.7448650375679 ] ] } }
// ]
// }
 geojsonData = {!! $content !!};

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

        // map.add(geojsonData)

      L.geoJSON(geojsonData).addTo(map);
  // map.on('click', mapClicked);
        // loadKML(); // Load KML data

    var startMarker = L.marker([{{$tabStartAndEndCordinate[0]}}, {{$tabStartAndEndCordinate[1]}}]).addTo(map);
    var endMarker = L.marker([{{$tabStartAndEndCordinate[2]}}, {{$tabStartAndEndCordinate[3]}}]).addTo(map);


    }
    initMap();


</script>



@endsection