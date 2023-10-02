@extends('pages.layouts.layout')
@section('content')



<div class="row">
  <div class="col-lg-12 ml-5 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Liste des usagers</h5>
        <div class="table-responsive">
          <table id="table" class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">ID</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Nom</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Email</h6>
                </th>
  
                <th class="border-bottom-0">
                    <h6 class="fw-semibold mb-0">État du compte</h6>
                  </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Action</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($Usagers as $user)
              <tr>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $user->id }}</h6></td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-1">{{ $user->name }}</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-1">{{ $user->email }}</h6>
                </td>
 
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1   {{ $user->status == false ? 'active' : 'notActive' }}">
                        @if ($user->status == false)
                            Activée
                        @else
                            Désactivée
                        @endif
                    </h6>
                </td>
                
                <td class="border-bottom-0">
                    <form method="POST" action="{{ route('usagers.update', $user->id) }}">
                        @csrf
                        @method('PUT')              
                        @if ($user->status==false)
                        <button type="submit" class="btn btn-danger btn-sm submit-button">Désactiver</button>
     
                        @else
                        <button type="submit" class="btn btn-success submit-button btn-sm">Activer</button>

                        @endif
                  </form>
           

         
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>








@endsection
