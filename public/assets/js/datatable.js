$(document).ready(function() {
    $('#example').DataTable({
        // "pagingType": "full_numbers", // Menggunakan tipe pagination 'full_numbers'
        "language": {
            "paginate": {
                "previous": "<", // Mengubah teks tombol 'Previous'
                "next": ">" // Mengubah teks tombol 'Next'
            }
        },
    });
});
