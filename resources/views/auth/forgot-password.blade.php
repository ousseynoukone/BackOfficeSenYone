<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __("Vous avez oublié votre mot de passe ? Pas de problème. Indiquez-nous simplement votre adresse e-mail et nous vous enverrons un lien de réinitialisation de mot de passe par e-mail, qui vous permettra d'en choisir un nouveau.") }}
    </div>

    <!-- Statut de la session -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <!-- Adresse e-mail -->
    <div>
        <x-input-label for="email" :value="__('Adresse e-mail')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __("Envoyer le lien de réinitialisation du mot de passe par e-mail") }}
        </x-primary-button>
    </div>
</form>
</x-guest-layout>



