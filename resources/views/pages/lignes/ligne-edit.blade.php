
@extends('pages.layouts.layout')
@section('content')
<div class="card text-start">

<div class="card-body p-4">
    <h5 class="card-title fw-semibold mb-4">Modification des informations de la ligne</h5>
<form method="POST" action="{{ route('ligne.update', $ligne->id) }}">


    <div class="mb-3">
        <label for="itineraire" class="form-label">Remplacer le ficher de l'itinéraire (KML)</label>
        <input type="file" value="{{ old('itineraire') }}"  class="form-control @error('itineraire') is-invalid @enderror" id="itineraire" name="itineraire" accept=".kml">
        @error('itineraire')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="check_point" class="form-label">Check-Point</label>
        <textarea class="form-control @error('check_point') is-invalid @enderror" rows="10" id="check_point" name="check_point" required>{{ old('check_point', $ligne->check_point) }}</textarea>
        @error('check_point')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="tarifs" class="form-label">Tarifs</label>
        <textarea class="form-control @error('tarifs') is-invalid @enderror" rows="10" id="tarifs" name="tarifs" required>{{ old('tarifs', $ligne->tarifs) }}</textarea>
        @error('tarifs')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="numero" class="form-label">Numero</label>
        <input type="number" class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" value="{{ old('numero', $ligne->numero) }}" required>
        @error('numero')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
</div>
</div>

@endsection