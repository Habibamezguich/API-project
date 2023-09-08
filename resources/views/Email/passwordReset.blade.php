<x-mail::message>
    # Réinitialisation de mot de passe

    Vous avez reçu cet email car une demande de réinitialisation de mot de passe a été faite pour votre compte.
{{-- here we are passing token in the url ( also i made the url to redirect to angular project where we will create the form) --}}
{{-- define this pass in angular later    --}}
<x-mail::button :url="url('http://localhost:4200/change-password?token=' . $token)">
        Réinitialiser le mot de passe
    </x-mail::button>

    Si vous n'avez pas fait cette demande, vous pouvez ignorer cet email en toute sécurité.

    Merci,<br>
    {{ config('app.name') }}
</x-mail::message>
