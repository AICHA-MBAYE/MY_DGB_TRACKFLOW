<x-guest-layout>
    <style>
        .forgot-password-box {
            background-color: #f0f4f8;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 500px;
            margin: 2rem auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .btn-custom {
            background-color: midnightblue;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-custom:hover {
            background-color: darkgreen;
        }

        label {
            font-weight: bold;
        }
    </style>

    <div class="forgot-password-box">
        <div class="mb-4 text-sm text-gray-700">
            🔐 Vous avez oublié votre mot de passe ? Pas de souci ! Entrez votre adresse email ci-dessous et nous vous enverrons un lien pour en créer un nouveau.
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                📧 Un lien de réinitialisation vous a été envoyé par email.
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="'Adresse email'" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="btn-custom">
                    Envoyer le lien de réinitialisation
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
