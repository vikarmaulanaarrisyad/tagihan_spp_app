@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';
        let perubahanData = false; // Tambahkan variabel perubahanData

        $(function() {
            $('#spinner-border').hide();
        });

        table = $('.table-pengguna').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            language: {
                "processing": "Mohon bersabar..."
            },
            ajax: {
                url: '{{ route('wali.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'name',
                },
                {
                    data: 'username',
                },
                {
                    data: 'email',
                },
                {
                    data: 'aksi',
                    searchable: false,
                    sortable: false
                },
            ]
        });

        // Fungsi tambah data
        function addForm(url, title = 'Form Tambah Data Pengguna') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('POST');
            $(`${modal} #password-field`).show();

            $('#spinner-border').hide();

            $(button).show();
            $(button).prop('disabled', false);
            resetForm(`${modal} form`);
        }

        // Fungsi edit data
        function editData(url, title = "Form Edit Data Pengguna") {
            $.ajax({
                url: url,
                type: "GET",
                cache: false,
                success: function(response) {
                    // Menampilkan modal
                    $(modal).modal('show');

                    // Mengatur judul modal dan atribut form
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('PUT');

                    // Menyembunyikan input password
                    $(`${modal} #password-field`).hide();

                    // Menyembunyikan spinner dan mengaktifkan tombol
                    $('#spinner-border').hide();
                    $(button).prop('disabled', false);

                    // Mereset formulir dan mengisi data
                    resetForm(`${modal} form`);
                    loopForm(response.data);

                    // Mengisi pilihan roles dengan data yang diterima
                    var option = new Option(response.data.role.name, response.data.role_id, true, true);
                    $('#roles').append(option).trigger('change');
                },
                error: function(errors) {
                    // Menampilkan pesan kesalahan
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message ||
                            'Terjadi kesalahan saat memproses permintaan.',
                        showConfirmButton: true,
                    }).then(() => {
                        // Menyembunyikan spinner dan mengaktifkan tombol
                        $('#spinner-border').hide();
                        $(button).prop('disabled', false);
                    });
                }
            });
        }
        // Fungsi hapus data
        function deleteData(url, name) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true,
            })
            swalWithBootstrapButtons.fire({
                title: 'Apakah anda yakin?',
                text: 'Anda akan menghapus ' + name + ' ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Iya Hapus',
                cancelButtonText: 'Batalkan',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "delete",
                        url: url,
                        dataType: "json",
                        success: function(response) {
                            if (response.status = 200) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    table.ajax.reload();
                                })
                            }
                        },
                        error: function(xhr, status, error) {
                            // Menampilkan pesan error
                            Swal.fire({
                                icon: 'error',
                                title: 'Opps! Gagal',
                                text: xhr.responseJSON.message,
                                showConfirmButton: true,
                            }).then(() => {
                                // Refresh tabel atau lakukan operasi lain yang diperlukan
                                table.ajax.reload();
                            });
                        }
                    });
                }
            });
        }

        // Fungsi submit form
        function submitForm(originalForm) {
            $(button).prop('disabled', true);
            $('#spinner-border').show();

            $.post({
                    url: $(originalForm).attr('action'),
                    data: new FormData(originalForm),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false
                })
                .done(response => {
                    $(modal).modal('hide');
                    if (response.status = 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            $('#spinner-border').hide();

                            table.ajax.reload();
                        })
                    }
                })
                .fail(errors => {
                    $('#spinner-border').hide();
                    $(button).prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                    if (errors.status == 422) {
                        $('#spinner-border').hide()
                        $(button).prop('disabled', false);
                        loopErrors(errors.responseJSON.errors);
                        return;
                    }
                });
        }

        // Fungsi select2
        $('#roles').select2({
            placeholder: 'Pilih Role',
            theme: 'bootstrap4',
            closeOnSelect: true,
            allowClear: true,
            ajax: {
                url: '{{ route('users.roles.search') }}',
                processResults: function(data) {
                    return {
                        results: data.map(function(role) {
                            return {
                                id: role.id,
                                text: role.name
                            }
                        })
                    }
                }
            }
        })
    </script>
@endpush
