# Citradata Project Information Services - Website Facelift

![Citradata Preview](/assets/images/hero.png) Sebuah pembaruan antarmuka (*facelift*) modern, responsif, dan elegan untuk *website* **PT Citradata Indonusa**. Proyek ini bertujuan untuk menyajikan informasi layanan data konstruksi dan wawasan pasar (*Market Insights*) dengan pengalaman pengguna (UX) yang lebih baik, desain yang bersih (*clean design*), dan performa yang cepat.

## Fitur Utama

- **Desain Modern & Profesional:** Mengadopsi gaya desain minimalis modern (terinspirasi dari *Apple Design Guidelines*, *shadcn/ui*, dan *Aceternity UI*) dengan penggunaan ruang kosong (*whitespace*) yang optimal dan tipografi yang kuat.
- **Glassmorphism UI:** Efek *blur* tembus pandang pada navigasi dan elemen kartu (*cards*) untuk memberikan kesan premium dan *stylish*.
- **Responsif Sepenuhnya (Mobile-First):** Tampilan beradaptasi dengan sempurna dari layar *smartphone* hingga monitor *desktop* beresolusi tinggi.
- **Interactive Swiper Carousel:** Menggunakan `Swiper.js` untuk menampilkan kolaborasi strategis secara dinamis pada halaman utama.
- **Mobile Menu terintegrasi:** Navigasi panel menu *dropdown* yang elegan khusus untuk pengguna perangkat seluler (*mobile*).

## Struktur Halaman

Proyek ini terdiri dari 4 halaman utama statis (`.html`):

1. **`index.html` (Home):** Halaman pendaratan (*landing page*) yang menampilkan *Hero section* interaktif, kolaborasi perusahaan (Swiper slider), kartu fitur layanan utama (Construction Data & Market Insights), berita terbaru, klien berharga, dan testimoni.
2. **`pages/product.html` (Product):** Penjelasan mendalam mengenai layanan inti: *Construction Data* (edisi digital & cetak) dan *Market Insights* (ringkasan proyek bulanan & 7 sektor konstruksi).
3. **`pages/about.html` (About Us):** Halaman profil perusahaan yang memuat Sejarah (sejak 1988), Misi (Fokus pada kualitas & kemitraan), dan Visi perusahaan.
4. **`pages/contact.html` (Contact Us):** Halaman *Call-to-Action* dan formulir kontak bergaya *shadcn/ui* bagi calon klien yang ingin terhubung atau mendapatkan pratinjau data.

## Teknologi yang Digunakan

Proyek ini dibangun murni menggunakan teknologi *Front-End* statis modern tanpa perlu proses *build* atau *compile* (*Zero-build-step*):

- **HTML5 & CSS3:** Struktur fundamental website.
- **Tailwind CSS (via CDN):** *Utility-first CSS framework* untuk *styling* yang cepat, responsif, dan konsisten.
- **JavaScript (Vanilla):** Digunakan untuk interaktivitas dasar (seperti fungsi Hamburger Mobile Menu).
- **Swiper.js (via CDN):** *Library slider/carousel* modern untuk menampilkan galeri gambar kolaborasi.
- **FontAwesome (via CDN):** *Library* ikon berbasis vektor untuk *user interface*.
- **Google Fonts (Inter):** Jenis huruf sans-serif yang bersih, sangat mudah dibaca, dan memberikan kesan teknikal yang elegan.

## Struktur Direktori

```text
citradata-facelift/
│
├── index.html               # Halaman Utama (Home)
├── pages/
│   ├── product.html         # Halaman Produk & Layanan
│   ├── about.html           # Halaman Profil Perusahaan
│   └── contact.html         # Halaman Kontak & Form
│
├──css/   
│  ├── style.css
│
├──js/   
│  ├── main.js
│
├── assets/
│   └── images/              # Direktori penyimpanan gambar
│       ├── citradata-logo.png
│       ├── hero.png
│       ├── 1.png hingga 6.png (Slider Images)
│       ├── quantum-logo.png
│       ├── modulus-logo.png
│       └── ... (Logo klien lainnya)
│
└── README.md                # Dokumentasi proyek ini