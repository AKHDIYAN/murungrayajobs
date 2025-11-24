@extends('layouts.app')

@section('title', 'Lupa Password - Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto">
        <!-- Header Card -->
        <div class="text-center mb-8">
            <div class="inline-block">
                <div class="bg-gradient-to-r from-gray-600 to-slate-700 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
            <p class="text-gray-600">Admin Portal</p>
        </div>

        <!-- Alert: Manual Reset Required -->
        <div class="bg-amber-50 border-l-4 border-amber-500 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold text-amber-800 mb-1">Reset Password Manual Diperlukan</p>
                    <p class="text-sm text-amber-700">
                        Untuk keamanan, reset password admin tidak dapat dilakukan secara otomatis melalui email. 
                        Silakan hubungi <span class="font-semibold">Super Admin</span> atau gunakan script reset password.
                    </p>
                </div>
            </div>
        </div>

        <!-- Form Card (Disabled) -->
        <form class="bg-white rounded-2xl shadow-lg p-8 space-y-6 opacity-60 cursor-not-allowed">
            @csrf

            <!-- Username Input (Disabled) -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                    Username Admin
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        id="username" 
                        name="username"
                        disabled
                        placeholder="Masukkan username admin"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                </div>
            </div>

            <!-- Disabled Submit Button -->
            <button 
                type="button"
                disabled
                class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold shadow-md cursor-not-allowed">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Fitur Tidak Tersedia
                </span>
            </button>

            <!-- Back to Login Link -->
            <div class="text-center">
                <a href="{{ route('admin.login') }}" 
                   class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </form>

        <!-- Information Box -->
        <div class="mt-6 bg-slate-50 border border-slate-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-slate-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="text-sm text-slate-700">
                    <p class="font-semibold mb-2">Cara Reset Password Admin:</p>
                    <ol class="list-decimal list-inside space-y-1 text-slate-600">
                        <li>Hubungi Super Admin untuk reset manual</li>
                        <li>Gunakan Laravel Tinker: <code class="bg-slate-200 px-1 rounded text-xs">php artisan tinker</code></li>
                        <li>Jalankan script reset yang telah disediakan</li>
                        <li>Minta bantuan tim IT untuk akses database</li>
                    </ol>
                    <p class="mt-3 text-xs text-slate-500">
                        💡 <span class="font-semibold">Tips:</span> Pastikan password baru memenuhi kriteria keamanan (minimal 8 karakter).
                    </p>
                </div>
            </div>
        </div>

        <!-- Alternative Solutions -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                </svg>
                <div class="text-sm text-blue-800">
                    <p class="font-semibold mb-1">Kontak Support:</p>
                    <ul class="space-y-1 text-blue-700">
                        <li>📧 Email: admin@murungraya.go.id</li>
                        <li>📱 WhatsApp: 0812-3456-7890</li>
                        <li>🏢 Kantor: Dinas Ketenagakerjaan Murung Raya</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
