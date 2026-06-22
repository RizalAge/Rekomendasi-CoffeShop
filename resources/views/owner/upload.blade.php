@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-warning text-dark">Form Pengajuan & Profil Cafe</div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('owner.upload.process') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        
                        <h5 class="mb-3 border-bottom pb-2">A. Informasi Dasar</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Cafe</label>
                                <input type="text" class="form-control" name="nama_cafe" value="{{ old('nama_cafe') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Alamat Lengkap Cafe</label>
                                <input type="text" class="form-control" name="alamat_cafe" value="{{ old('alamat_cafe') }}" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Nama Pemilik</label>
                                <input type="text" class="form-control" name="nama_pemilik" value="{{ old('nama_pemilik') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NIK Pemilik</label>
                                <input type="text" class="form-control" name="nik_pemilik" value="{{ old('nik_pemilik') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No. Telepon / WA</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                            </div>
                        </div>

                        <h5 class="mb-3 border-bottom pb-2">B. Kategori Cafe (Bisa pilih lebih dari satu)</h5>
                        <div class="row mb-4">
                            @php
                                $kategoris = ['Coffee Shop', 'Dessert Cafe', 'Bakery Cafe', 'Brunch Cafe', 'Family Cafe', 'Co-Working Cafe', 'Rooftop Cafe', 'Garden Cafe', 'Book Cafe', 'Gaming / Board Game Cafe', 'Pet Friendly Cafe', 'Healthy Cafe', 'Themed Cafe', 'Live Music Cafe'];
                            @endphp
                            @foreach($kategoris as $kat)
                                <div class="col-md-3 col-sm-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="kategori[]" value="{{ $kat }}" id="kat_{{ $loop->index }}">
                                        <label class="form-check-label" for="kat_{{ $loop->index }}">{{ $kat }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <h5 class="mb-3 border-bottom pb-2">C. Fasilitas Cafe</h5>
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3">
                                <h6>Internet & Kerja</h6>
                                @foreach(['WiFi Gratis', 'Stop Kontak', 'Ruang Meeting', 'Area Kerja (Co-working)', 'Printer'] as $fas)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}"><label class="form-check-label">{{ $fas }}</label></div>
                                @endforeach
                                
                                <h6 class="mt-3">Kenyamanan</h6>
                                @foreach(['AC', 'Indoor Area', 'Outdoor Area', 'Rooftop', 'Smoking Area', 'Non-Smoking Area'] as $fas)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}"><label class="form-check-label">{{ $fas }}</label></div>
                                @endforeach
                            </div>

                            <div class="col-md-4 mb-3">
                                <h6>Parkir & Akses</h6>
                                @foreach(['Parkir Motor', 'Parkir Mobil', 'Akses Kursi Roda', 'Toilet Difabel'] as $fas)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}"><label class="form-check-label">{{ $fas }}</label></div>
                                @endforeach

                                <h6 class="mt-3">Hiburan</h6>
                                @foreach(['Live Music', 'Board Game', 'Karaoke', 'TV / Projector'] as $fas)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}"><label class="form-check-label">{{ $fas }}</label></div>
                                @endforeach
                            </div>

                            <div class="col-md-4 mb-3">
                                <h6>Pembayaran</h6>
                                @foreach(['Tunai', 'QRIS', 'Debit Card', 'Credit Card', 'E-Wallet'] as $fas)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}"><label class="form-check-label">{{ $fas }}</label></div>
                                @endforeach

                                <h6 class="mt-3">Layanan & Umum</h6>
                                @foreach(['Reservasi', 'Delivery', 'Take Away', 'Drive Thru', 'Toilet', 'Mushola', 'Charging Station', 'Kids Area', 'Pet Area'] as $fas)
                                    <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $fas }}"><label class="form-check-label">{{ $fas }}</label></div>
                                @endforeach
                            </div>
                        </div>

                        <h5 class="mb-3 border-bottom pb-2">D. Dokumen Validasi & Foto Cafe</h5>
                        <div class="mb-4">
                            <label class="form-label text-muted">Upload Foto Cafe (Utama) & Dokumen Pendukung</label>
                            
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="position-relative">
                                        <input class="d-none file-input" type="file" id="dokumen_1" name="image" accept=".jpg,.jpeg,.png" required>
                                        <label for="dokumen_1" id="label_1" class="w-100 p-2 text-center border rounded d-flex flex-column align-items-center justify-content-center overflow-hidden" style="border: 2px dashed #ccc !important; cursor: pointer; background-color: #f8f9fa; height: 150px;">
                                            <div class="preview-icon"><span style="font-size: 2rem;">➕</span><p class="text-muted small mb-0 mt-2">Foto Utama</p></div>
                                            <img id="preview_1" src="" class="d-none w-100 h-100" style="object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">
                                        </label>
                                        <button type="button" class="btn btn-sm btn-danger position-absolute d-none remove-btn" data-index="1" style="top: 5px; right: 5px; z-index: 2; border-radius: 50%; width: 30px; height: 30px; padding: 0;">&times;</button>
                                    </div>
                                </div>

                                @for ($i = 2; $i <= 4; $i++)
                                <div class="col-6 col-md-3">
                                    <div class="position-relative">
                                        <input class="d-none file-input" type="file" id="dokumen_{{ $i }}" name="dokumen[]" accept=".jpg,.jpeg,.png,.pdf">
                                        <label for="dokumen_{{ $i }}" id="label_{{ $i }}" class="w-100 p-2 text-center border rounded d-flex flex-column align-items-center justify-content-center overflow-hidden" style="border: 2px dashed #ccc !important; cursor: pointer; background-color: #f8f9fa; height: 150px;">
                                            <div class="preview-icon"><span style="font-size: 2rem;">➕</span><p class="text-muted small mb-0 mt-2">Dokumen {{ $i }}</p></div>
                                            <img id="preview_{{ $i }}" src="" class="d-none w-100 h-100" style="object-fit: cover; position: absolute; top: 0; left: 0; z-index: 1;">
                                        </label>
                                        <button type="button" class="btn btn-sm btn-danger position-absolute d-none remove-btn" data-index="{{ $i }}" style="top: 5px; right: 5px; z-index: 2; border-radius: 50%; width: 30px; height: 30px; padding: 0;">&times;</button>
                                    </div>
                                </div>
                                @endfor
                            </div>
                            <small class="text-muted d-block mt-2">*Kotak pertama wajib diisi (Foto Cafe). Format: JPG, PNG, atau PDF (Maks. 2MB per file).</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Kirim Pengajuan Cafe</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fitur memunculkan preview
    document.querySelectorAll('.file-input').forEach(input => {
        input.addEventListener('change', function(event) {
            const index = this.id.split('_')[1]; 
            const previewImg = document.getElementById('preview_' + index);
            const iconDiv = document.querySelector('#label_' + index + ' .preview-icon');
            const removeBtn = document.querySelector('.remove-btn[data-index="' + index + '"]');

            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                if (file.type.match('image.*')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewImg.classList.remove('d-none'); 
                        iconDiv.classList.add('d-none');       
                        removeBtn.classList.remove('d-none');  
                    }
                    reader.readAsDataURL(file);
                } else {
                    previewImg.src = 'https://upload.wikimedia.org/wikipedia/commons/8/87/PDF_file_icon.svg'; 
                    previewImg.style.objectFit = 'contain';
                    previewImg.style.padding = '20px';
                    previewImg.classList.remove('d-none');
                    iconDiv.classList.add('d-none');
                    removeBtn.classList.remove('d-none');
                }
            }
        });
    });

    // Fitur tombol Hapus (X)
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const index = this.getAttribute('data-index');
            const input = document.getElementById('dokumen_' + index);
            const previewImg = document.getElementById('preview_' + index);
            const iconDiv = document.querySelector('#label_' + index + ' .preview-icon');
            
            input.value = ''; 
            previewImg.src = '';
            previewImg.classList.add('d-none');
            iconDiv.classList.remove('d-none');
            this.classList.add('d-none');
        });
    });
</script>
@endsection