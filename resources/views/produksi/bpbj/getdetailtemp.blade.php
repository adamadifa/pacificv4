@foreach ($detailtemp as $d)
    <tr>
        <td>{{ $d->kode_produk }}</td>
        <td>{{ $d->nama_produk }}</td>
        <td>{{ $d->shift }}</td>
        <td class="text-end">{{ formatRupiah($d->jumlah) }}</td>
        <td>
            <a href="#" id="{{ $d->id }}" class="delete"><i class="ti ti-trash text-danger"></i></a>
        </td>
    </tr>
@endforeach
<script>
    $(function() {
        function loaddetailtemp(kode_produk) {
            $("#loaddetailbpbjtemp").load("/bpbj/" + kode_produk + "/getdetailtemp");
        }

        $('.delete').click(function(event) {
            var form = $(this).closest("form");
            var name = $(this).data("name");
            var id = $(this).attr('id');
            var kode_produk = "{{ $kode_produk }}";
            event.preventDefault();
            Swal.fire({
                title: `Apakah Anda Yakin Ingin Menghapus Data Ini ?`,
                text: "Jika dihapus maka data akan hilang permanent.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
                confirmButtonColor: "#554bbb",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, Hapus Saja!"
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: '/bpbj/deletetemp',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id,
                        },
                        cache: false,
                        success: function(respond) {
                            if (respond === '0') {
                                Swal.fire({
                                    title: "Berhasil",
                                    text: "Data Berhasil Dihapus",
                                    icon: "success"
                                });
                                loaddetailtemp(kode_produk);
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: respond,
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            });
        });
    });
</script>
