$(function () {
    "use strict";

    $(document).ready(function () {
        $("#example").DataTable({
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Semua"],
            ],
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
        });

        $("#stok_rendah").DataTable({
            dom: "Bfrtip", // B untuk tombol, f untuk filter, r untuk informasi tabel, t untuk tabel, i untuk informasi, p untuk pagination
            buttons: [
                {
                    text: '<i class="fadeIn animated bx bx-download me-1"></i> Download PDF', // Teks tombol
                    className: "btn btn-primary text-white mb-2", // Kelas CSS tombol
                    title: "Data Stok Rendah", // Judul file PDF
                    orientation: "portrait", // Orientasi halaman PDF
                    pageSize: "A4", // Ukuran halaman PDF
                    action: function (e, dt, node, config) {
                        // Aksi ketika tombol diklik
                        window.open("/laporan-bahan-baku", "_blank");
                    },
                },
            ],
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
            initComplete: function () {
                $(".dt-button").each(function () {
                    if ($(this).hasClass("buttons-pdf")) {
                        $(this).attr("id", "downloadPDF");
                    }
                });
                // Memindahkan tombol PDF ke sebelah kiri
                $("#stok_rendah_filter").prepend($(".dt-buttons"));
            },
        });

        $("#history_bahan_baku").DataTable({
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Semua"],
            ],
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
            order: [[0, "desc"]],
        });

        $("#id-sembunyi-table").DataTable({
            oLanguage: {
                sLengthMenu: "Tampilkan _MENU_ data",
                sInfo: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                sInfoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                sEmptyTable: "Tidak ada data",
                sZeroRecords: "Tidak ada data yang sesuai dengan pencarian",
            },
            lengthMenu: [
                [10, 25, 50, -1],
                ["10", "25", "50", "Semua"],
            ],
            language: {
                search: "Cari:",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "<i class='fa fa-angle-right'></i>",
                    previous: "<i class='fa fa-angle-left'></i>",
                },
            },
            order: [[0, "desc"]], // Mengurutkan kolom pertama secara descending
            columnDefs: [
                {
                    targets: 0, // Indeks kolom pertama
                    visible: false, // Menyembunyikan kolom pertama
                },
            ],
        });
    });

    $(document).ready(function () {
        var table = $("#example2").DataTable({
            lengthChange: false,
            buttons: ["copy", "excel", "pdf", "print"],
        });

        table.buttons().container().appendTo("#example2_wrapper .col-md-6:eq(0)");
    });
});
