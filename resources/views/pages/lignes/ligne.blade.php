@extends('pages.layouts.layout')
@section('content')

<button type="button" class="col-3 mb-3  btn btn-primary" id="triggerAddAdminModal" data-bs-toggle="modal" data-bs-target="#addLigne">
  Ajouter une ligne
</button>

    <div class="row">
     <!-- Button trigger modal -->

        <div class="col-lg-12 ml-5 d-flex align-items-stretch">

          <div class="card w-100">
       
            <div class="card-body p-4">
              <h5 class="card-title fw-semibold mb-4">Liste des lignes </h5>
              <div class="table-responsive">
                <table id="table" class="table text-nowrap mb-0 align-middle">
                  <thead class="text-dark fs-4">
                    <tr>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Id</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Itinéraire</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Check Point</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Numéro</h6>
                      </th>
                      <th class="border-bottom-0">
                        <h6 class="fw-semibold mb-0">Action</h6>
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($lignes as $ligne)
                        <tr>
                            <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{ $ligne->id }}</h6></td>
                            <td class="border-bottom-0">
                              <h6 class="fw-semibold mb-1">{{ strlen($ligne->itineraire) > 15 ? substr($ligne->itineraire, 0, 15) . '...' : $ligne->itineraire }}</h6>
                          </td>
                          
                          <td class="border-bottom-0">
                              <h6 class="fw-semibold mb-1">{{ strlen($ligne->check_point) > 15 ? substr($ligne->check_point, 0, 15) . '...' : $ligne->check_point }}</h6>
                          </td>
                          

                            <td class="border-bottom-0">
                                <p class="mb-0 fw-normal">{{ $ligne->numero }}</p>
                            </td>
                            <td class="border-bottom-0">
                              <a href="{{ route('ligne.show', $ligne) }}" class="btn btn-primary">Détail</a>
                              <a href="{{ route('ligne.edit', $ligne->id) }}" class="btn btn-primary">Modifier</a>
                              <form  class="formDeleteLigne formDeleteLigne{{$ligne->id}}" action="{{ route('ligne.destroy', $ligne->id) }}" method="POST" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Supprimer</button>
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



<!-- Modal -->
<div class="modal fade" id="addLigne" tabindex="-1" aria-labelledby="addLigneLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="addLigneLabel">Ajouter une nouvelle ligne</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form method="POST" action="{{route('ligne.store')}}" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label for="itineraire" class="form-label">Fichier de l'itinéraire (KML)</label>
                <input type="file" value="{{ old('itineraire') }}"  required class="form-control @error('itineraire') is-invalid @enderror" id="itineraire" name="itineraire" accept=".kml">
                @error('itineraire')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
              <label for="check_point" class="form-label">Check-Point</label>
              <textarea class="form-control @error('check_point') is-invalid @enderror" rows="10" id="check_point" name="check_point" required>{{ old('check_point') }}</textarea>
              @error('check_point')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
          </div>
          
              <div class="mb-3">
                  <label for="numero" class="form-label">Numero</label>
                  <input type="number" min="1" value="{{ old('numero') }}" required class="form-control @error('numero') is-invalid @enderror" id="numero" name="numero" required>
                  @error('numero')
                  <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>


          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary submit-button">Enregister</button>

          </form>
            <button type="button" data-bs-dismiss="modal" class="btn btn-primary">Fermer</button>
          </div>


      </div>
  </div>
</div>


<!-- Confirmation Modal -->
<button hidden type="button" id="triggerConfirmationModal" class="col-3 mb-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationModal">
    
</button>

<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
              <button type="button" id="" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

          </div>
          <div class="modal-body">
              Êtes-vous sûr de vouloir supprimer cette ligne ?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</button>
          </div>
      </div>
  </div>
</div>
<script>
  // Function to open the error modal
  function openErrorModal() {
    var triggerButton = document.getElementById("triggerAddAdminModal");
    if (triggerButton) {
      triggerButton.click();
    }
  }

  // Check for form validation errors (you can customize this part)
  function checkForErrors() {
    // Replace this with your actual form validation logic
    // For example, check if there are error messages on the page
    var hasErrors = document.getElementsByClassName('invalid-feedback').length > 0;
    if (hasErrors) {
      openErrorModal();
    }
  }

  // Call the function to check for errors when the page loads
  window.addEventListener('DOMContentLoaded', function() {
    checkForErrors();
  });
</script>

<script>

  document.addEventListener("DOMContentLoaded", function() {
          let idLigne ;
      // Add a submit event listener to each form with the class "formDeleteLigne"
      const deleteForms = document.querySelectorAll('.formDeleteLigne');
      deleteForms.forEach(function (form) {
          
          form.addEventListener('submit', function(event) {
              event.preventDefault(); // Prevent the default form submission
              // Use a regular expression to extract the last digit
               idLigne = event.target.action.match(/\d+$/)[0];
              // Use Bootstrap's modal for the confirmation dialog
              document.getElementById("triggerConfirmationModal").click();

          });
      });
      // Add a click event listener to the confirmation button
      document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
          // Submit the form when confirmed
          document.getElementById("confirmDeleteBtn").setAttribute("disabled","")

          const form = document.querySelector('.formDeleteLigne'+idLigne);
          form.submit();
      });
  });
</script>

@endsection