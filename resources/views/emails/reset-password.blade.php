@component('mail::message')
# Reset Password

Halo **{{ $userName }}**,

Kami menerima permintaan untuk mereset password akun Anda di Portal Kerja Murung Raya.

Klik tombol di bawah ini untuk mereset password Anda:

@component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
Reset Password
@endcomponent

**Link ini akan kadaluarsa dalam 60 menit.**

Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.

Terima kasih,<br>
**{{ config('app.name') }}**

---

<small style="color: #999;">
Jika Anda mengalami kesulitan mengklik tombol "Reset Password", salin dan tempel URL berikut ke browser Anda:<br>
<a href="{{ $resetUrl }}" style="color: #3490dc;">{{ $resetUrl }}</a>
</small>
@endcomponent
