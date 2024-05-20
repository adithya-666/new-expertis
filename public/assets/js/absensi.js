
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });


  // Sweet Alert Set Up
  var toastMixin = Swal.mixin({
    toast: true,
    icon: 'success',
    title: 'General Title',
    animation: false,
    position: 'top-right',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });

  var date = () => {
    $('#date-absensi').daterangepicker({
      "showButtonPanel": true,
      "ranges": {
        'Hari ini': [moment(), moment()],
        'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 Hari lalu': [moment().subtract(6, 'days'), moment()],
        '30 Hari Lalu': [moment().subtract(29, 'days'), moment()],
        'Bulan ini': [moment().startOf('month'), moment().endOf('month')],
        'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      locale:{
        format: 'YYYY/MM/DD'
    },
   
  }, function(start, end, label) {
    console.log("New date range selected: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
  
    const startDate = start.format('YYYY-MM-DD');
    const endDate = end.format('YYYY-MM-DD');
    const newUrl = 'absensi/datatable-absensi/' + startDate + '/' + endDate;
    dt.ajax.url(newUrl).load();
  });
  
  }

  var columnsDataTableReport = [
    {  
        "data": null,
        "class": "align-top",
        "orderable": false,
        "searchable": false,
        "render": function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
            }  
        },
    {data : 'hari'},
    {data: 'tanggal'},
    {data : 'jam_masuk',
    render : function(data, type, row){
        let checkIn = row.jam_masuk.slice(0, 5);
        return checkIn;
    }},
    {data : 'jam_keluar',
    render : function(data, type, row){
        let checkOut = '';
        if(row.jam_keluar !== null){
            checkOut = row.jam_keluar.slice(0, 5);
        } else {
            checkOut = '-';
        }
       return checkOut;
    }},
    {data : 'keterlambatan',
    render : function(data, type, row){

            late = row.keterlambatan + ' Menit';
   
       return late;
    }},
    {data : 'jam_kerja',
    render : function(data, type, row){
        let workTime = '';
        if(row.jam_kerja !== null){
            workTime = row.jam_kerja + ' Menit';
        } else {
            workTime = 0 + ' Menit';
        }
        
       return workTime;
    }},
    {data : 'jenis_presensi',
    render : function(data, type, row){
       let typeWork = '';
       if(row.jenis_presensi === 'Kerja'){
        typeWork = `<span class="badge text-bg-success">Working Day</span>`;
       }else{
        typeWork = `<span class="badge text-bg-warning">Business Trip</span>`;
       }
       return typeWork;
    }}
      ];

    var dt;
    var datatable = () => {
    const startDate = null;
    const endDate = null;
    dt =  $('#absensi-datatable').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        "language": {
            "paginate": {
                "previous": "<",
                "next": ">" 
            }
        },
        select: {
            style: 'single'
          },
        ordering: true,
        order : [[2, 'desc']],
        stateSave: false,
        ajax: {
            url: 'absensi/datatable-absensi/'+startDate+'/'+endDate
        },
        columns: columnsDataTableReport
        });
    } 

var checkIn = () => {

 $('.checkin-office').click(function (e) { 
    e.preventDefault();

// Memeriksa apakah browser mendukung geolocation
if ("geolocation" in navigator) {
   
    // Mengizinkan akses lokasi secara otomatis saat halaman dimuat
    navigator.geolocation.getCurrentPosition(function(position) {
        // Mendapatkan lokasi pengguna berhasil
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        $.ajax({
            type: "GET",
            url: "absensi/check-location",
            data: {
                latitude : latitude,
                longitude : longitude
            },
            success: function (res) {
                if(res.area === true){

                window.location.href = 'absensi/check-in-barcode/' + res.user_id ;

                } else if(res.area === false) {
               
                    toastMixin.fire({
                        animation: true,
                        title: 'Area to long from office',
                        icon : 'info'
                      });
                }
            }
        });

        // Lakukan tindakan dengan koordinat yang diperoleh
    }, function(error) {
    
        // Mendapatkan lokasi pengguna gagal
        switch(error.code) {
            case error.PERMISSION_DENIED:
                alert("Akses lokasi ditolak. Mohon izinkan akses lokasi untuk menggunakan fitur ini.");
                break;
            case error.POSITION_UNAVAILABLE:
                // Informasi lokasi tidak tersedia
                alert("Informasi lokasi tidak tersedia. Beberapa fitur mungkin tidak berfungsi dengan baik.");
                break;
            case error.TIMEOUT:
                // Permintaan lokasi pengguna melebihi waktu maksimum
                alert("Permintaan lokasi pengguna melebihi waktu maksimum. Beberapa fitur mungkin tidak berfungsi dengan baik.");
                break;
            case error.UNKNOWN_ERROR:
                // Kesalahan tidak diketahui
                alert("Terjadi kesalahan yang tidak diketahui. Beberapa fitur mungkin tidak berfungsi dengan baik.");
                break;
        }
    });
} else {
    // Geolocation tidak didukung oleh browser
    alert("Geolocation tidak didukung di browser ini. Beberapa fitur mungkin tidak berfungsi dengan baik.");
}


});
}


function validasiAbsensi(id, status) {
  $.ajax({
    type: "PUT",
    url: "absensi/update-status-absensi/" + id + '/' + status,
    success: function (response) {
        toastMixin.fire({
            animation: true,
            title: response.message
          });

          dt.ajax.reload();
    }
  });
}


function checkCheckIn(){
$.ajax({
    type: "GET",
    url: "absensi/check-check-in",
    success: function (response) {

        if(response.status == true){
        let checkInTime = response.data.jam_masuk.slice(0, 5);
        $('.checkin').html(`<h2 class="text-dark mb-1 font-weight-medium click-checkin">${checkInTime}</h2>`);
        
        if(response.data.keterlambatan == 0){
         $('.checkin').append(`<span class="badge bg-success font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none"><i class="bi bi-check"></i></span>`);
        } else {
        $('.checkin').append(`<span class="badge bg-danger font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none">${response.data.keterlambatan}</span>`);
        }
        } else {
            $('.checkin').html(`<h2 class="text-dark mb-1 font-weight-medium click-checkin" onclick="clickCheckin()"><i class="bi bi-arrow-down-circle-fill" ></i></h2>`);
        }      
}
});
}

function clickCheckin(){

$('#checkin-modal').modal('show');

}



function checkCheckOut(){
    $.ajax({
        type: "GET",
        url: "absensi/check-check-out",
        success: function (response) {

            if(response.status == true){
            let checkOut = response.data.jam_keluar.slice(0, 5);
    
            $('.checkout').html(`<h2 class="text-dark mb-1 font-weight-medium click-checkin">${checkOut}</h2>`);

             $('.checkout').append(`<span class="badge bg-success font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none">${response.data.jam_kerja}</span>`);
            
            } else {
             
                $('.checkout').append(`<h2 class="text-dark mb-1 font-weight-medium" onclick="clickCheckOut()"><i class="bi bi-arrow-up-circle-fill"></i></h2>`);
            }      
    }
    });
    }

    function clickCheckOut()
    {

        $.ajax({
            type: "GET",
            url: "absensi/checkout",
            success: function (response) {

                let status = response[0];

                switch (status) {
                    case 500:
                          alert(response.message);
                        break;
                    case 300:
                        $('.bt-update').data('presence-id', response.presence_id);
                        $('#checkout-modal').modal('show');
                        break;
                    case 200:
                        window.location.href = 'absensi/check-out-barcode';
                        break;
                    case 201:
                        toastMixin.fire({
                            animation: true,
                            title: `Type BT Can't Checkout`,
                            icon : 'info'
                          });
                        break;
                
                    default:
                        break;
                }
            }
        });
    }
    function checkOut(){
        $('.checkout-office').click(function (e) { 
            e.preventDefault();
            window.location.href = 'absensi/check-out-barcode';
        });
    }
    
    function checkInBT()
    {
        $('.checkin-bt').click(function (e) { 
            e.preventDefault();
         $.ajax({
            type: "POST",
            url: "absensi/process-scan-barcode-checkin",
            data: {
                typeWork : 'BT'
            },
            success: function (response) {
       
                toastMixin.fire({
                    animation: true,
                    title: 'Successfully Checkin BT'
                  });

                  dt.ajax.reload();

                  $('#checkin-modal').modal('hide');

                  checkCheckIn();
            }
         });
            
        });
    }


    var BTUpdate = () => {
        $('.bt-update').click(function (e) { 
            e.preventDefault();
         const presence_id  =  $(this).data('presence-id');
        
         $.ajax({
            type: "PUT",
            url: "absensi/bt-update/" + presence_id,
            success: function (response) {
                toastMixin.fire({
                    animation: true,
                    title: 'Successfully Checkin BT'
                  });
                  dt.ajax.reload();
                  $('#checkout-modal').modal('hide');
            }
         });
            
        });
    }

  
        function ot(){
           $.ajax({
            type: "GET",
            url: "absensi/check-ot",
            success: function (response) {

                let status = response.status;

                switch (status) {
                    case 200:
                        $('#overtime-modal').modal('show');
                        break;
                    case 404:
                       alert('Data Presence Not Found');
                        break;

                    case 500:
                       alert('You have not Check Out');
                        break;
                
                    default:
                        break;
                }
            }
           });
       
        }


    function infoDetailOTStartTime(){
        $.ajax({
            type: "GET",
            url: "absensi/detail-ot-start-time",
            success: function (response) {
                if(response.status){
                    let startTime = response.data.start_time.slice(0, 5);
                    $('.start-overtime').html(`<h2 class="text-dark mb-1 font-weight-medium click-checkin">${startTime}</h2>`);
                    } else {
                        $('.start-overtime').append(`<h2 class="text-dark mb-1 font-weight-medium" onclick="ot()"><i class="bi bi-clock"></i></h2>`);
                    }    
            }
        });
    }

    var formCreateOT  = () => {
        $('#formCreateOT').submit(function (e) { 
            e.preventDefault();

            let formData = $('#formCreateOT').serialize();
            let form = $('#formCreateOT');

            $.ajax({
                type: "POST",
                url: "absensi/add-ot",
                data : formData,
                success: function (response) {
                    toastMixin.fire({
                        animation: true,
                        title: response.message
                      });
                    form.trigger('reset');
                    $('#overtime-modal').modal('hide');
                    infoDetailOTStartTime();
                }
            });
        });
    }

    function infoDetailOTEndTime(){
        $.ajax({
            type: "GET",
            url: "absensi/detail-ot-end-time",
            success: function (response) {
                if(response.status == 200){
                    let endTime = response.data.end_time.slice(0, 5);
                    $('.end-overtime').html(`<h2 class="text-dark mb-1 font-weight-medium click-checkin">${endTime}</h2>`);
                    $('.end-overtime').append(`<span class="badge bg-success font-12 text-white font-weight-medium rounded-pill ms-2 d-lg-block d-md-none">${response.data.overtime}</span>`);
                    } else if(response.status == 201) {
                        $('.end-overtime').append(`<h2 class="text-dark mb-1 font-weight-medium" onclick="EndOverTime(${response.data.id})"><i class="bi bi-clock"></i></h2>`);
                    } else {
                        $('.end-overtime').append(`<h2 class="text-dark mb-1 font-weight-medium"><i class="bi bi-clock"></i></h2>`);
                    }
            }
        });
    }


    function EndOverTime(id)
    {
        Swal.fire({
            title: "Are you sure?",
            text: "you want to end it over time!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, end over time!",
            customClass: {
                confirmButton: 'btn btn-success', // Menambahkan kelas CSS pada tombol konfirmasi
                cancelButton: 'btn btn-danger'    // Menambahkan kelas CSS pada tombol batal
            }
          }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "PUT",
                    url: "absensi/end-overtime/" + id,
                    success: function (response) {
                        toastMixin.fire({
                            animation: true,
                            title: response.message
                          });
                    }
                   });
            }
          });
  
    }


function checkAll(){
    var selectedRows = dt.column(0).checkboxes.selected();

    var rowIds = [];

 $.each(selectedRows, function (key, presence_id) { 
  rowIds.push(presence_id);
 });

 console.log(rowIds);

 var data = { 
  rowIds : rowIds
 };

 $.ajax({
    type: "PUT",
    url: "absensi/update-status-validasi-all",
    data: data,
    success: function (response) {
        dt.ajax.reload(null, false); // Reload tabel tanpa reset posisi halaman
          // Reset checkbox selections
          dt.column(0).checkboxes.deselectAll(); // Unselect all checkboxes
    }
 });
}


    
    

document.addEventListener('DOMContentLoaded', function(){
    date();
    checkIn();
    checkCheckIn();
    checkOut();
    checkInBT();
    checkCheckOut();
    BTUpdate();
    datatable();
    formCreateOT();
    infoDetailOTStartTime();
    infoDetailOTEndTime();
    if(sessionStorage.getItem('checkin') == 'true'){

        toastMixin.fire({
            animation: true,
            title: 'Successfully Checkin Barcode'
          });
    
          sessionStorage.removeItem("checkin");
          sessionStorage.clear();
      }

    if(sessionStorage.getItem('checkout') == 'true'){

        toastMixin.fire({
            animation: true,
            title: 'Successfully Checkout Barcode'
          });
    
          sessionStorage.removeItem("checkout");
          sessionStorage.clear();
      }
  
});