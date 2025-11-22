<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $user_type
 * @property int $user_id
 * @property string $action
 * @property string $description
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog byAction($action)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog byUserType($userType)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog whereUserType($value)
 */
	class ActivityLog extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_admin
 * @property string $username
 * @property string $password
 * @property \Illuminate\Support\Carbon|null $last_login
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereIdAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUsername($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_kecamatan
 * @property string $nama_kecamatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pekerjaan> $pekerjaan
 * @property-read int|null $pekerjaan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Perusahaan> $perusahaan
 * @property-read int|null $perusahaan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistik> $statistik
 * @property-read int|null $statistik_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan whereNamaKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kecamatan whereUpdatedAt($value)
 */
	class Kecamatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_lamaran
 * @property int $id_pekerjaan
 * @property int $id_user
 * @property string $cv
 * @property string $status
 * @property \Illuminate\Support\Carbon $tanggal_terkirim
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cv_url
 * @property-read \App\Models\Pekerjaan $pekerjaan
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran byPekerjaan($idPekerjaan)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran byPelamar($idUser)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran byPerusahaan($idPerusahaan)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran query()
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereIdLamaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereIdPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereTanggalTerkirim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Lamaran whereUpdatedAt($value)
 */
	class Lamaran extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_pekerjaan
 * @property int $id_perusahaan
 * @property int $id_kecamatan
 * @property int $id_kategori
 * @property string $nama_pekerjaan
 * @property string $nama_perusahaan
 * @property string $gaji_min
 * @property string $gaji_max
 * @property string $deskripsi_pekerjaan
 * @property string $persyaratan_pekerjaan
 * @property string|null $benefit
 * @property int $jumlah_lowongan
 * @property string $jenis_pekerjaan
 * @property \Illuminate\Support\Carbon $tanggal_expired
 * @property string $status
 * @property \Illuminate\Support\Carbon $tanggal_posting
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $gaji_range
 * @property-read mixed $hari_sisa
 * @property-read mixed $is_aktif
 * @property-read mixed $is_berakhir
 * @property-read mixed $jumlah_pelamar
 * @property-read \App\Models\Sektor $kategori
 * @property-read \App\Models\Kecamatan $kecamatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lamaran> $lamaran
 * @property-read int|null $lamaran_count
 * @property-read \App\Models\Perusahaan $perusahaan
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan aktif()
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan berakhir()
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan byJenis($jenis)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan byKategori($idKategori)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan byKecamatan($idKecamatan)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan search($keyword)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereBenefit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereDeskripsiPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereGajiMax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereGajiMin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereIdKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereIdPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereIdPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereJenisPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereJumlahLowongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereNamaPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan wherePersyaratanPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereTanggalExpired($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereTanggalPosting($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pekerjaan whereUpdatedAt($value)
 */
	class Pekerjaan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_pendidikan
 * @property string $tingkatan_pendidikan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistik> $statistik
 * @property-read int|null $statistik_count
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereIdPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereTingkatanPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pendidikan whereUpdatedAt($value)
 */
	class Pendidikan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_perusahaan
 * @property string $username
 * @property string $password
 * @property string $nama_perusahaan
 * @property int $id_kecamatan
 * @property string|null $alamat
 * @property string|null $no_telepon
 * @property string $email
 * @property string|null $deskripsi
 * @property string $logo
 * @property bool $is_verified
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $tanggal_registrasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $logo_url
 * @property-read \App\Models\Kecamatan $kecamatan
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pekerjaan> $pekerjaan
 * @property-read int|null $pekerjaan_count
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan verified()
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereIdPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereNamaPerusahaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereTanggalRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perusahaan whereUsername($value)
 */
	class Perusahaan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_sektor
 * @property string $nama_kategori
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Pekerjaan> $pekerjaan
 * @property-read int|null $pekerjaan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistik> $statistik
 * @property-read int|null $statistik_count
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor whereIdSektor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor whereNamaKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sektor whereUpdatedAt($value)
 */
	class Sektor extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_statistik
 * @property int $id_kecamatan
 * @property int $id_pendidikan
 * @property int $id_usia
 * @property string $nama
 * @property string $jenis_kelamin
 * @property string $status
 * @property int|null $id_sektor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Kecamatan $kecamatan
 * @property-read \App\Models\Pendidikan $pendidikan
 * @property-read \App\Models\Sektor|null $sektor
 * @property-read \App\Models\Usia $usia
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik bekerja()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik byKecamatan($idKecamatan)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik byPendidikan($idPendidikan)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik byUsia($idUsia)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik menganggur()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik query()
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereIdPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereIdSektor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereIdStatistik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereIdUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Statistik whereUpdatedAt($value)
 */
	class Statistik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_user
 * @property string $nama
 * @property string $username
 * @property string $password
 * @property string $nik
 * @property string $jenis_kelamin
 * @property \Illuminate\Support\Carbon $tanggal_lahir
 * @property string $alamat
 * @property int $id_kecamatan
 * @property string $no_telepon
 * @property string $email
 * @property string $foto
 * @property string|null $cv
 * @property string|null $ktp
 * @property string|null $sertifikat
 * @property string|null $foto_diri
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $tanggal_registrasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $cv_url
 * @property-read mixed $foto_diri_url
 * @property-read mixed $foto_url
 * @property-read mixed $ktp_url
 * @property-read mixed $sertifikat_url
 * @property-read mixed $usia
 * @property-read \App\Models\Kecamatan $kecamatan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Lamaran> $lamaran
 * @property-read int|null $lamaran_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFotoDiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSertifikat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTanggalRegistrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_usia
 * @property string $kelompok_usia
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Statistik> $statistik
 * @property-read int|null $statistik_count
 * @method static \Illuminate\Database\Eloquent\Builder|Usia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Usia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Usia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Usia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Usia whereIdUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Usia whereKelompokUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Usia whereUpdatedAt($value)
 */
	class Usia extends \Eloquent {}
}

