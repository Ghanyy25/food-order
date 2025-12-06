Berikut adalah draf file `README.md` yang lengkap, mencakup **Dokumentasi Teknis** dan **Dokumentasi Pengguna** sesuai dengan struktur proyek Laravel yang Anda unggah.

Anda dapat menyalin teks di bawah ini dan menyimpannya sebagai file bernama `README.md` di direktori utama proyek Anda.

-----

# Aplikasi Food Order

Aplikasi pemesanan makanan berbasis web yang dibangun menggunakan kerangka kerja Laravel dan Tailwind CSS. Aplikasi ini memungkinkan pengguna untuk melihat menu dan melakukan pemesanan, serta menyediakan panel admin untuk mengelola produk dan kategori.

-----

## 📚 Dokumentasi Teknis

Bagian ini menjelaskan struktur proyek, prasyarat, dan cara menjalankan aplikasi di lingkungan lokal (local environment).

### Prasyarat (Requirements)

Sebelum memulai, pastikan komputer Anda telah terinstal:

  * **PHP** (Versi 8.1 atau lebih baru)
  * **Composer** (Manajer paket untuk PHP)
  * **Node.js & NPM** (Untuk mengelola aset frontend)
  * **MySQL** (Atau database lain yang didukung Laravel)

### Struktur Proyek

Berikut adalah penjelasan singkat mengenai struktur folder utama dalam proyek ini:

  * **`app/Http/Controllers`**: Berisi logika aplikasi.
      * `AdminController.php`: Mengatur dashboard dan fungsi admin.
      * `FrontController.php`: Mengatur tampilan halaman depan untuk pengguna.
      * `OrderController.php`: Menangani proses checkout dan penyimpanan pesanan.
  * **`app/Models`**: Representasi data database (Eloquent ORM).
      * `Product`, `Category`, `Order`, `OrderItem`.
  * **`database/migrations`**: File skema database untuk membuat tabel users, categories, products, dan orders.
  * **`resources/views`**: Tampilan antarmuka pengguna (Blade Templates).
      * `admin/`: Folder view khusus untuk halaman admin.
      * `front/`: Folder view untuk halaman publik/pengunjung.
  * **`routes/web.php`**: Definisi rute URL aplikasi.

### Instalasi & Cara Menjalankan

Ikuti langkah-langkah berikut untuk menginstal dependensi dan menjalankan aplikasi:

1.  **Kloning atau Unduh Proyek**
    Pastikan Anda berada di dalam direktori proyek `food-app`.

2.  **Instal Dependensi PHP (Composer)**

    ```bash
    composer install
    ```

3.  **Instal Dependensi Frontend (NPM)**

    ```bash
    npm install
    ```

4.  **Konfigurasi Environment**
    Salin file contoh konfigurasi `.env.example` menjadi `.env`:

    ```bash
    cp .env.example .env
    ```

    Buka file `.env` dan sesuaikan pengaturan database Anda:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nama_database_anda
    DB_USERNAME=root
    DB_PASSWORD=
    ```

5.  **Generate Application Key**

    ```bash
    php artisan key:generate
    ```

6.  **Migrasi Database**
    Jalankan migrasi untuk membuat tabel dan seeder (jika ada data dummy):

    ```bash
    php artisan migrate --seed
    ```

7.  **Jalankan Aplikasi**
    Anda perlu menjalankan dua terminal terpisah:

      * **Terminal 1 (Vite Development Server):**
        ```bash
        npm run dev
        ```
      * **Terminal 2 (Laravel Server):**
        ```bash
        php artisan serve
        ```

    Akses aplikasi melalui browser di: `http://localhost:8000`

-----

## 📖 Dokumentasi Pengguna

Panduan ini menjelaskan cara menggunakan fitur-fitur utama dalam aplikasi baik sebagai Pengguna Biasa (Customer) maupun Administrator.

### 1\. Pengguna Umum (Customer)

**A. Registrasi dan Login**

1.  Buka halaman utama aplikasi.
2.  Klik tombol **Register** di pojok kanan atas untuk membuat akun baru.
3.  Isi nama, email, dan kata sandi, lalu klik daftar.
4.  Jika sudah punya akun, klik **Login** dan masukkan kredensial Anda.

**B. Melihat Menu**

1.  Halaman utama (`Home`) menampilkan daftar produk makanan yang tersedia.
2.  Anda dapat melihat detail produk seperti nama, harga, dan kategori.

**C. Melakukan Pemesanan (Checkout)**

1.  Pilih produk yang diinginkan.
2.  Lakukan proses pemesanan melalui halaman checkout (sesuai alur di `OrderController`).
3.  Masukkan informasi yang diperlukan untuk menyelesaikan pesanan.

### 2\. Administrator

**A. Akses Dashboard Admin**

1.  Lakukan Login dengan akun yang memiliki hak akses admin.
2.  Setelah login, Anda akan diarahkan atau dapat mengakses menu **Dashboard**.

**B. Mengelola Produk (Products)**

1.  Masuk ke menu **Products** di dashboard admin.
2.  **Tambah Produk:** Klik tombol "Add New" atau sejenisnya, isi nama produk, harga, kategori, dan upload gambar jika tersedia.
3.  **Edit Produk:** Klik tombol edit pada daftar produk untuk mengubah detail.
4.  **Hapus Produk:** Klik tombol hapus untuk menghilangkan produk dari daftar menu.

**C. Mengelola Kategori**

1.  Admin dapat membuat kategori makanan baru (misal: Makanan Berat, Minuman, Snack) agar menu lebih terorganisir.

-----

**Catatan:** Jika Anda mengalami kendala saat instalasi atau penggunaan, pastikan konfigurasi database di file `.env` sudah sesuai dengan pengaturan MySQL lokal Anda.
