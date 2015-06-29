<p>Someone has requested a password reset for your account.</p>
<p>If you didn't request this reset, please ignore this message.</p>
<p>Click this link to reset your password:
    <a href="{{ url('auth/reset-confirm/' . $token->token) }}">
        Reset password
    </a>
</p>
<p>--<br>Sent from {{ config('app.url') }}</p>
