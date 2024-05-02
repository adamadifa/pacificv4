
$(function () {
    const select2Kodecabangsearch = $('.select2Kodecabangsearch');
    if (select2Kodecabangsearch.length) {
        select2Kodecabangsearch.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Semua Cabang',
                allowClear: true,
                dropdownParent: $this.parent()
            });
        });
    }
    $(".btnShow").click(function (e) {
        e.preventDefault();
        var no_mutasi = $(this).attr("no_mutasi");
        e.preventDefault();
        $("#modal").modal("show");
        $(".modal-title").text("Detail Surat Jalan");
        $("#loadmodal").load(`/suratjalan/${no_mutasi}/show`);
    });

    $(".btnEdit").click(function (e) {
        e.preventDefault();
        var no_mutasi = $(this).attr("no_mutasi");
        e.preventDefault();
        $("#modal").modal("show");
        $(".modal-title").text("Edit Surat Jalan");
        $("#loadmodal").load(`/suratjalan/${no_mutasi}/edit`);
    });

    $(".btnApprove").click(function (e) {
        e.preventDefault();
        var no_mutasi = $(this).attr("no_mutasi");
        e.preventDefault();
        $("#modal").modal("show");
        $(".modal-title").text("Approve Surat Jalan");
        $("#loadmodal").load(`/suratjalan/${no_mutasi}/approveform`);
    });
});

