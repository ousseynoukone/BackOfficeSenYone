<x-guest-layout>
    
    <!-- Statut de la session -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
    
        <!-- Adresse e-mail -->
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
    
        <!-- Mot de passe -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mot de passe')" />
    
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
    
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                
        </div>
                        <!-- Toggle password visibility button -->
                        <button type="button" id="togglePassword" class="ml-1">
                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAABl0lEQVR4nO2Wv0oDQRCHY6FBNFErfQCxNDFYi71iJxa24kskWpigEUSUPISSYCfYWIldIsSHiPljKRKrfLIwgbjZ2btogk0+2GbvN7+5nZ3du0hkzJj/BlgHssAjUAfaMuoydwKkhplwB3ghPGVg+y8JV4AHfs89sDxo0j3g02HWBNJAEpiRsQZk5JnNB7AbJuEEcAp0HCZFIOaJjQElR5zxyhlvX9KCUraiGtjv4UpuuHZ6ABe4afpW6vCJAy3F69wWH6KTtrRTxgB4A2pA3sxZmiOP30FXlAC+PMKEZWoS2eQtjWk4jTawakQV/Pwos6zSpuZoNB8VI6qOILHZZx9VI9pUjk+XZIhSnw1Q6g6w0RVeeYQZR3PlZeVacx17/C5ts+chHac54F3xegIm7YAF4FUJKA1wgdwpHlVgXgtckq+LljwesFItaRlYDHrraeBWMWjJ5ZACZmWkZE+18t4Yz6Bq9ZZsXzk6YamJR+AW9SEXQc6zGq0q2UEaUgWIAlvy9TLd3+j59WnIXEE0Ud1pzJjI6PkGDLCOslXUiekAAAAASUVORK5CYII=">                  
                        
                        </button>
    
        <!-- Se souvenir de moi -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Se souvenir de moi') }}</span>
            </label>
        </div>
    
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
    
            <x-primary-button class="ml-3">
                {{ __('Se connecter') }}
            </x-primary-button>
            
        </div>
    </form>


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
</x-guest-layout>



    