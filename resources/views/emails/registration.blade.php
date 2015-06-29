<p>Click this link to activate your account:
    <a href="{{ url('auth/registration/' . $token->token) }}">
        Activate account
    </a>
</p>
<p>--<br>Sent from {{ config('app.url') }}</p>
