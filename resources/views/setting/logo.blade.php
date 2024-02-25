<form action="{{ route('setting.update', $setting->id) }}?pills=logo" method="post" enctype="multipart/form-data">
    @csrf
    @method('put')

    <x-card>
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <strong class="d-block text-center">Logo Sekolah</strong>
                <div class="text-center">
                    <img src="{{ Storage::url($setting->logo_sekolah ?? '') }}" alt=""
                        class="img-thumbnail preview-logo_sekolah" width="200">
                </div>
                <div class="form-group mt-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="logo_sekolah" name="logo_sekolah"
                            onchange="preview('.preview-logo_sekolah', this.files[0])">
                        <label class="custom-file-label" for="logo_sekolah">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2"></div>
        </div>

        <x-slot name="footer">
            <button type="reset" class="btn btn-dark">Reset</button>
            <button class="btn btn-primary">Simpan</button>
        </x-slot>
    </x-card>
</form>
