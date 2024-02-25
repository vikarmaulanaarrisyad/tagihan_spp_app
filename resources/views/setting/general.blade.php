<form action="{{ route('setting.update', $setting->id) }}" method="post">
    @csrf
    @method('put')

    <x-card>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="email_sekolah">Email Sekolah</label>
                    <input type="email" class="form-control @error('email_sekolah') is-invalid @enderror"
                        name="email_sekolah" id="email_sekolah"
                        value="{{ old('email_sekolah') ?? $setting->email_sekolah }}">
                    @error('email_sekolah')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="telpon_sekolah">No. Telp</label>
                    <input type="text" class="form-control @error('telpon_sekolah') is-invalid @enderror"
                        name="telpon_sekolah" id="telpon_sekolah"
                        value="{{ old('telpon_sekolah') ?? $setting->telpon_sekolah }}">
                    @error('telpon_sekolah')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="nama_sekolah">Nama Sekolahan</label>
                    <input type="text" class="form-control @error('nama_sekolah') is-invalid @enderror"
                        name="nama_sekolah" id="nama_sekolah"
                        value="{{ old('nama_sekolah') ?? $setting->nama_sekolah }}">
                    @error('nama_sekolah')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="alamat_sekolah">Alamat</label>
            <textarea class="form-control @error('alamat_sekolah') is-invalid @enderror" name="alamat_sekolah" id="alamat_sekolah">{{ old('alamat_sekolah') ?? $setting->alamat_sekolah }}</textarea>
            @error('alamat_sekolah')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>


        <x-slot name="footer">
            <button type="reset" class="btn btn-dark">Reset</button>
            <button class="btn btn-primary">Simpan</button>
        </x-slot>
    </x-card>
</form>
