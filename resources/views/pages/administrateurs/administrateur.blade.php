@extends('pages.layouts.layout')
@section('content')

<button type="button" id="triggerAddAdminModal" class="col-3 mb-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
  Ajouter un administrateur
</button>

<div class="row">
  <div class="col-lg-12 ml-5 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Liste des Administrateurs</h5>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
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
              @foreach ($Admins as $user)
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
                    <form method="POST" action="{{ route('admins.update', $user->id) }}">
                        @csrf
                        @method('PUT')              
                        @if ($user->status==false)
                        <button type="submit" class="btn btn-danger btn-sm">Désactiver</button>
     
                        @else
                        <button type="submit" class="btn btn-success btn-sm">Activer</button>

                        @endif
                  </form>
           

                  <form class="formDeleteAdmin formDeleteAdmin{{$user->id}}" action="{{ route('admins.destroy', $user->id) }}" method="POST" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="deleteBtn"  class="btn btn-sm btn-danger mt-1">Supprimer</button>
                </form>
                <form method="POST" class="formResetAdmin formResetAdmin{{$user->id}}" action="{{ route('admins.reset', $user->id) }}">
                  @csrf

                  <button type="submit" class="btn btn-danger btn-sm mt-1">Réinitialiser le mots de passe</button>

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

<!-- Modal add admin -->
<div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="addUserLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserLabel">Ajouter un nouveau administrateur</h5>
        <button type="button" id="" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('admins.store') }}">
          @csrf
          <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" value="{{ old('name') }}" required class="form-control @error('name') is-invalid @enderror" id="name" name="name">
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror" id="email" name="email">
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">Rôle</label>
            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
              <option value="Administrateur" @if(old('role') == 'Administrateur') selected @endif>Administrateur</option>
              <option value="Super Administrateur" @if(old('role') == 'Super Administrateur') selected @endif>Super Administrateur</option>
            </select>
            @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          

          <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" required class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
                                    <!-- Toggle password visibility button -->
             <span style="background-color: black; border-radius:5px; margin-left:0.3em; margin-top:0.3em" type="button" id="togglePassword" class="ml-2">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAABl0lEQVR4nO2Wv0oDQRCHY6FBNFErfQCxNDFYi71iJxa24kskWpigEUSUPISSYCfYWIldIsSHiPljKRKrfLIwgbjZ2btogk0+2GbvN7+5nZ3du0hkzJj/BlgHssAjUAfaMuoydwKkhplwB3ghPGVg+y8JV4AHfs89sDxo0j3g02HWBNJAEpiRsQZk5JnNB7AbJuEEcAp0HCZFIOaJjQElR5zxyhlvX9KCUraiGtjv4UpuuHZ6ABe4afpW6vCJAy3F69wWH6KTtrRTxgB4A2pA3sxZmiOP30FXlAC+PMKEZWoS2eQtjWk4jTawakQV/Pwos6zSpuZoNB8VI6qOILHZZx9VI9pUjk+XZIhSnw1Q6g6w0RVeeYQZR3PlZeVacx17/C5ts+chHac54F3xegIm7YAF4FUJKA1wgdwpHlVgXgtckq+LljwesFItaRlYDHrraeBWMWjJ5ZACZmWkZE+18t4Yz6Bq9ZZsXzk6YamJR+AW9SEXQc6zGq0q2UEaUgWIAlvy9TLd3+j59WnIXEE0Ud1pzJjI6PkGDLCOslXUiekAAAAASUVORK5CYII=">                  
             </span>
          </div>

          <div class="form-group mt-4">
            <label for="password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
            <input  id="passwordConfirmInput" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            @if ($errors->has('password_confirmation'))
                <div class="alert alert-danger mt-2" role="alert">
                    @foreach ($errors->get('password_confirmation') as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
                                         <!-- Toggle password visibility button -->
                <span style="background-color: black; border-radius:5px; margin-left:0.3em; margin-top:0.3em" type="button" id="togglePasswordConfirm" class="ml-2">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAABl0lEQVR4nO2Wv0oDQRCHY6FBNFErfQCxNDFYi71iJxa24kskWpigEUSUPISSYCfYWIldIsSHiPljKRKrfLIwgbjZ2btogk0+2GbvN7+5nZ3du0hkzJj/BlgHssAjUAfaMuoydwKkhplwB3ghPGVg+y8JV4AHfs89sDxo0j3g02HWBNJAEpiRsQZk5JnNB7AbJuEEcAp0HCZFIOaJjQElR5zxyhlvX9KCUraiGtjv4UpuuHZ6ABe4afpW6vCJAy3F69wWH6KTtrRTxgB4A2pA3sxZmiOP30FXlAC+PMKEZWoS2eQtjWk4jTawakQV/Pwos6zSpuZoNB8VI6qOILHZZx9VI9pUjk+XZIhSnw1Q6g6w0RVeeYQZR3PlZeVacx17/C5ts+chHac54F3xegIm7YAF4FUJKA1wgdwpHlVgXgtckq+LljwesFItaRlYDHrraeBWMWjJ5ZACZmWkZE+18t4Yz6Bq9ZZsXzk6YamJR+AW9SEXQc6zGq0q2UEaUgWIAlvy9TLd3+j59WnIXEE0Ud1pzJjI6PkGDLCOslXUiekAAAAASUVORK5CYII=">                  
            </span>
        </div>
        

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Enregistrer</button>
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
                Êtes-vous sûr de vouloir supprimer cet Administrateur ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation reset password Modal -->
<button hidden type="button" id="triggerConfirmationResetModal" class="col-3 mb-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmationResetModal">
    
</button>

<div class="modal fade" id="confirmationResetModal" tabindex="-1" aria-labelledby="" aria-hidden="true">

  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
              <button type="button" id="" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

          </div>
          <div class="modal-body">
              Êtes-vous sûr de vouloir réinitialiser le mots de passe de  cet Administrateur ?
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
              <button type="button" id="confirmResetDeleteBtn" class="btn btn-danger">Oui</button>
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



{{-- For confirming deletion --}}


<script>

    document.addEventListener("DOMContentLoaded", function() {
            let idUser ;
        // Add a submit event listener to each form with the class "formDeleteAdmin"
        const deleteForms = document.querySelectorAll('.formDeleteAdmin');
        deleteForms.forEach(function (form) {
            
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Use a regular expression to extract the last digit
                 idUser = event.target.action.match(/\d+$/)[0];

                // Use Bootstrap's modal for the confirmation dialog
                document.getElementById("triggerConfirmationModal").click();
            });
        });
        // Add a click event listener to the confirmation button
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            // Submit the form when confirmed
            const form = document.querySelector('.formDeleteAdmin'+idUser);
            form.submit();
        });
    });
</script>


<script>
    
    const passwordInput = document.getElementById('password');
    const togglePasswordButton = document.getElementById('togglePassword');

    togglePasswordButton.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePasswordButton.innerHTML=""
            togglePasswordButton.innerHTML='<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAACF0lEQVR4nOXXO2hUURAG4BWVxEYLRXAVC60tgpbB0ldI+vSiFgoRVMRKDKaRKNgZ7JS0FlopvhZsBK0UCbHSwuAL3wo+PjnkLBxuzr13V3QVnHJn/vnnzpx5bKPx3wuW9DwJWI9HGOkl6VLMmJcvGOol+VAkXUCORTiGFp7iEz5jDrdxCoPB7k+Qb8UH1fIQo78UgHryC5jEaVzEPfwoBHAdzW6JN+F54qS25oEEh/EiwT0Lvjol3YJXCfhrNw8Oy3E+wYdABhodRB2iFFN3qCbtm3EFwxlf+/A94uZK0x4GB+4kke7v8sFNZHzuSfy1ssMJBxOjqYz+aFnNE/Lcl59NcGNF5Sq8i8oH6M84uFZ4tTnyyxlcPx5HzNvAlSrHE4fbFqRj3uZ11N+tqnkJdlfif7yMeGcJ+H3Un6uqeQl2e+L/RDHVIQ1BZtGXAd+M+jfY2Ck5lsXF0071yqLBWBLVmYyDkUQfyKdi2rM1T3BpTx8oa6fw5NuyO2MzoVqKNW8ms72FxWW1aMZmF5t/b8ZmGDeiwzDhrsZWq+rzWazJkiaGA4V5G1K1ohJUP2T66vDpkmh/eZCXOIJ1FZjVuFRX81rB2rjaUgnz+z6m40qcjCsy/PatruYdS7w6RuM061TCIXC8mz6vC2Awnje34gYLZ89HPImnz8n09Ol2yPxWKZDPhIOyl+Q7IumGnpH+1T8JjX9NfgIx2g25x6OYaQAAAABJRU5ErkJggg==">'
    
        } else {
            passwordInput.type = 'password';
            togglePasswordButton.innerHTML=""

            togglePasswordButton.innerHTML='      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAABl0lEQVR4nO2Wv0oDQRCHY6FBNFErfQCxNDFYi71iJxa24kskWpigEUSUPISSYCfYWIldIsSHiPljKRKrfLIwgbjZ2btogk0+2GbvN7+5nZ3du0hkzJj/BlgHssAjUAfaMuoydwKkhplwB3ghPGVg+y8JV4AHfs89sDxo0j3g02HWBNJAEpiRsQZk5JnNB7AbJuEEcAp0HCZFIOaJjQElR5zxyhlvX9KCUraiGtjv4UpuuHZ6ABe4afpW6vCJAy3F69wWH6KTtrRTxgB4A2pA3sxZmiOP30FXlAC+PMKEZWoS2eQtjWk4jTawakQV/Pwos6zSpuZoNB8VI6qOILHZZx9VI9pUjk+XZIhSnw1Q6g6w0RVeeYQZR3PlZeVacx17/C5ts+chHac54F3xegIm7YAF4FUJKA1wgdwpHlVgXgtckq+LljwesFItaRlYDHrraeBWMWjJ5ZACZmWkZE+18t4Yz6Bq9ZZsXzk6YamJR+AW9SEXQc6zGq0q2UEaUgWIAlvy9TLd3+j59WnIXEE0Ud1pzJjI6PkGDLCOslXUiekAAAAASUVORK5CYII=">    '

        }
    });
</script>
  


{{-- For confirming reseting --}}
<script>
    
    document.addEventListener("DOMContentLoaded", function() {
            let idUser ;
        // Add a submit event listener to each form with the class "formDeleteAdmin"
        const deleteForms = document.querySelectorAll('.formResetAdmin');
        deleteForms.forEach(function (form) {
            
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                // Use a regular expression to extract the last digit
                 idUser = event.target.action.match(/\d+$/)[0];

                // Use Bootstrap's modal for the confirmation dialog
                document.getElementById("triggerConfirmationResetModal").click();
            });
        });
        // Add a click event listener to the confirmation button
        document.getElementById('confirmResetDeleteBtn').addEventListener('click', function() {
            // Submit the form when confirmed
            const form = document.querySelector('.formResetAdmin'+idUser);
            form.submit();
        });
    });
</script>





<script>




const passwordConfirmInput = document.getElementById('passwordConfirmInput');
console.log(passwordConfirmInput)
const togglePasswordConfirmButton = document.getElementById('togglePasswordConfirm');

togglePasswordConfirmButton.addEventListener('click', function () {
    if (passwordConfirmInput.type === 'password') {
        passwordConfirmInput.type = 'text';
        togglePasswordConfirmButton.innerHTML=""
        togglePasswordConfirmButton.innerHTML='<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAACF0lEQVR4nOXXO2hUURAG4BWVxEYLRXAVC60tgpbB0ldI+vSiFgoRVMRKDKaRKNgZ7JS0FlopvhZsBK0UCbHSwuAL3wo+PjnkLBxuzr13V3QVnHJn/vnnzpx5bKPx3wuW9DwJWI9HGOkl6VLMmJcvGOol+VAkXUCORTiGFp7iEz5jDrdxCoPB7k+Qb8UH1fIQo78UgHryC5jEaVzEPfwoBHAdzW6JN+F54qS25oEEh/EiwT0Lvjol3YJXCfhrNw8Oy3E+wYdABhodRB2iFFN3qCbtm3EFwxlf+/A94uZK0x4GB+4kke7v8sFNZHzuSfy1ssMJBxOjqYz+aFnNE/Lcl59NcGNF5Sq8i8oH6M84uFZ4tTnyyxlcPx5HzNvAlSrHE4fbFqRj3uZ11N+tqnkJdlfif7yMeGcJ+H3Un6uqeQl2e+L/RDHVIQ1BZtGXAd+M+jfY2Ck5lsXF0071yqLBWBLVmYyDkUQfyKdi2rM1T3BpTx8oa6fw5NuyO2MzoVqKNW8ms72FxWW1aMZmF5t/b8ZmGDeiwzDhrsZWq+rzWazJkiaGA4V5G1K1ohJUP2T66vDpkmh/eZCXOIJ1FZjVuFRX81rB2rjaUgnz+z6m40qcjCsy/PatruYdS7w6RuM061TCIXC8mz6vC2Awnje34gYLZ89HPImnz8n09Ol2yPxWKZDPhIOyl+Q7IumGnpH+1T8JjX9NfgIx2g25x6OYaQAAAABJRU5ErkJggg==">'

    } else {
        passwordConfirmInput.type = 'password';
        togglePasswordConfirmButton.innerHTML=""

        togglePasswordConfirmButton.innerHTML='      <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAABl0lEQVR4nO2Wv0oDQRCHY6FBNFErfQCxNDFYi71iJxa24kskWpigEUSUPISSYCfYWIldIsSHiPljKRKrfLIwgbjZ2btogk0+2GbvN7+5nZ3du0hkzJj/BlgHssAjUAfaMuoydwKkhplwB3ghPGVg+y8JV4AHfs89sDxo0j3g02HWBNJAEpiRsQZk5JnNB7AbJuEEcAp0HCZFIOaJjQElR5zxyhlvX9KCUraiGtjv4UpuuHZ6ABe4afpW6vCJAy3F69wWH6KTtrRTxgB4A2pA3sxZmiOP30FXlAC+PMKEZWoS2eQtjWk4jTawakQV/Pwos6zSpuZoNB8VI6qOILHZZx9VI9pUjk+XZIhSnw1Q6g6w0RVeeYQZR3PlZeVacx17/C5ts+chHac54F3xegIm7YAF4FUJKA1wgdwpHlVgXgtckq+LljwesFItaRlYDHrraeBWMWjJ5ZACZmWkZE+18t4Yz6Bq9ZZsXzk6YamJR+AW9SEXQc6zGq0q2UEaUgWIAlvy9TLd3+j59WnIXEE0Ud1pzJjI6PkGDLCOslXUiekAAAAASUVORK5CYII=">    '

    }
});
</script>
@endsection
