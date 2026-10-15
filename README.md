# Prediksi Nama

Aplikasi Laravel untuk memprediksi asal negara sebuah nama menggunakan API [nationalize.io](https://nationalize.io).
User memasukkan nama, aplikasi menampilkan daftar negara (kode ISO, nama negara, dan probabilitasnya), lalu menyimpan hasilnya ke MySQL.

## Fitur

- Prediksi asal negara dari nama melalui `https://api.nationalize.io/?name={nama}`.
- Master data kode negara ISO 3166-1 alpha-2 (246 negara) dari [ssl.com](https://www.ssl.com/id/kode-negara-a/), disimpan di tabel `countries`.
- Hasil prediksi disimpan dan direlasikan ke tabel `countries` lewat foreign key.
- Nama yang sudah pernah diprediksi diambil dari database, tidak memanggil API lagi (menghemat kuota gratis nationalize.io, 100 request/hari).
- Riwayat pencarian dan hapus riwayat.
- Pesan error yang jelas saat kuota API habis (HTTP 429) atau API tidak bisa dihubungi.

## Struktur database

```
countries                    name_predictions             country_name_prediction
---------                    ----------------             -----------------------
code (PK, char 2)  <──┐      id (PK)            <──┐      id (PK)
name                  │      name (unique)         └───── name_prediction_id (FK, cascade delete)
                      │      sample_count                 probability
                      └────────────────────────────────── country_code (FK)
```

## Struktur kode

| File | Tanggung jawab |
| --- | --- |
| `app/Services/Nationalize/NationalizeClient.php` | HTTP client untuk nationalize.io, mengubah error HTTP menjadi `NationalizeException` |
| `app/Services/Nationalize/NationalityResult.php` | DTO hasil API (nama, jumlah sampel, probabilitas per negara) |
| `app/Services/NamePredictionService.php` | Mengambil hasil dari database atau API, lalu menyimpannya dalam satu transaksi |
| `app/Http/Requests/PredictNameRequest.php` | Validasi input nama |
| `app/Http/Controllers/NamePredictionController.php` | Form, hasil, riwayat, hapus |
| `app/Models/Country.php`, `app/Models/NamePrediction.php` | Model Eloquent dan relasi many-to-many dengan pivot `probability` |
| `database/data/countries.json`, `database/seeders/CountrySeeder.php` | Data dan seeder kode negara |

## Kebutuhan

- PHP 8.2+ dengan ekstensi `pdo_mysql`
- Composer
- MySQL / MariaDB

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Buat database MySQL, lalu sesuaikan kredensial di `.env` jika berbeda:

```sql
CREATE DATABASE javan_name_prediction CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=javan_name_prediction
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration dan seeder kode negara:

```bash
php artisan migrate --seed
```

Jalankan aplikasi, lalu buka http://localhost:8000:

```bash
php artisan serve
```

## Testing

Test memakai SQLite in-memory dan response API palsu (`Http::fake`), jadi tidak butuh MySQL maupun kuota API:

```bash
php artisan test
```
