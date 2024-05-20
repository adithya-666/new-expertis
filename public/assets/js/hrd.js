
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
    const newUrl = '/absensi/datatable-absensi-hrd/' + startDate + '/' + endDate;
    dt.ajax.url(newUrl).load();
  });
  
  }

  var columnsDataTableReport = [
    {data : 'id', class: 'one-check',    "checkboxes": {
        'selectRow' : true ,  name:'presences.id'
      }},
    {  
        "data": null,
        "class": "align-top",
        "orderable": false,
        "searchable": false,
        "render": function (data, type, row, meta) {
        return meta.row + meta.settings._iDisplayStart + 1;
            }  
        },
    {data : 'hari',  name:'presences.hari'},
    {data: 'tanggal',  name:'presences.tanggal'},
    { data: 'nama_pegawai', name:'employees.nama_pegawai' },
    {data : 'jam_masuk',   name:'presences.jam_masuk',
    render : function(data, type, row){
        let checkIn = row.jam_masuk.slice(0, 5);
        return checkIn;
    }},
    {data : 'jam_keluar',  name:'presences.jam_keluar',
    render : function(data, type, row){
        let checkOut = '';
        if(row.jam_keluar !== null){
            checkOut = row.jam_keluar.slice(0, 5);
        } else {
            checkOut = '-';
        }
       return checkOut;
    }},
    {data : 'keterlambatan',  name:'presences.keterlambatan',
    render : function(data, type, row){

            late = row.keterlambatan + ' Menit';
   
       return late;
    }},
    {data : 'jam_kerja',  name:'presences.jam_kerja',
    render : function(data, type, row){
        let workTime = '';
        if(row.jam_kerja !== null){
            workTime = row.jam_kerja + ' Menit';
        } else {
            workTime = 0 + ' Menit';
        }
        
       return workTime;
    }},
    {data : 'jenis_presensi',  name:'presences.jenis_presensi',
    render : function(data, type, row){
       let typeWork = '';
       if(row.jenis_presensi === 'Kerja'){
        typeWork = `<span class="badge text-bg-success">Working Day</span>`;
       }else{
        typeWork = `<span class="badge text-bg-warning">Business Trip</span>`;
       }
       return typeWork;
    }},
    { data: 'status_validasi',   name:'presences.status_validasi',
  render : function(data, type, row){
        let status = '';
        if(data !== null){
            status = `<span class="badge text-bg-info">${data}</span>`;
        } else {
            status ='-';
        }
        
       return status;
    } },
    { data: 'id' , className: 'text-center' ,  name:'presences.id',
    render : function(data, type, row){

let btn = ``;

btn = `
<span class="badge text-bg-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
<i class="bi bi-three-dots"></i>
</span>
<ul class="dropdown-menu">`;
if(row.status_validasi == null){
  btn += `<li><button class="dropdown-item text-success text-center" data-status="validasi" onclick="validasiAbsensi(${row.id} , 'validasi')">Validasi <i class="bi bi-check-square"></i></button></li>
 `;
} else {
btn += `<li><button class="dropdown-item text-danger text-center" data-status="rejected" onclick="validasiAbsensi(${row.id}, 'rejected')">Rejected <i class="bi bi-x-square"></i></button></li>
`;
}
btn += ` </ul>`;
return btn;
    }
 }
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
            url: '/absensi/datatable-absensi-hrd/'+startDate+'/'+endDate
        },
        columns: columnsDataTableReport
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

function checkAll(){
    var selectedRows = dt.column(0).checkboxes.selected();

    var rowIds = [];

 $.each(selectedRows, function (key, presence_id) { 
  rowIds.push(presence_id);
 });

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


var dateEC = () => {
    $('#date-ec').daterangepicker({
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
  
    const startDate = start.format('YYYY-MM-DD');
    const endDate = end.format('YYYY-MM-DD');
    const newUrl = '/ec/datatable-explains-claims-manager/' + startDate + '/' + endDate;
    dtEC.ajax.url(newUrl).load();
  });
  
  }

  var columnsDataTableEC = [
    {data : 'id', class: 'one-check',    "checkboxes": {
        'selectRow' : true 
      }},
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
    { data: 'nama_pegawai', name: 'employees.nama_pegawai' },
    {data : 'uang_makan',
    render : function(data, type, row){
      if(row.uang_makan !== null){
        let rupiahFormat = new Intl.NumberFormat('id-ID', {
          currency: 'IDR',
        }).format(row.uang_makan);
      return 'Rp ' + rupiahFormat;
      } else {
        return '-';
      }
    }},
    {data : 'transportasi',
    render : function(data, type, row){
      if(row.transportasi !== null){
        let rupiahFormat = new Intl.NumberFormat('id-ID', {
          currency: 'IDR',
        }).format(row.transportasi);
      return `<p class="image-transportasi ">Rp ${rupiahFormat}</p>`;
      } else {
        return '-';
      }
   
    }},
    {data : 'parkir_tol',
    render : function(data, type, row){
      if(row.parkir_tol !== null){
        let rupiahFormat = new Intl.NumberFormat('id-ID', {
          currency: 'IDR',
        }).format(row.parkir_tol);
        return `<p class="image-parkir-tol">Rp ${rupiahFormat}</p>`;
      } else {
        return '-';
      }
    }
  },
    {data : 'lain_lain',
    render : function(data, type, row){
      if(row.lain_lain !== null){
        let rupiahFormat = new Intl.NumberFormat('id-ID', {
          currency: 'IDR',
        }).format(row.lain_lain);
      return 'Rp ' + rupiahFormat;
      } else {
        return '-';
      }
    }},
    {data : 'status_transportasi',
    render : function(data, type, row){
        let status = '';
        if(row.status_acc_finance == 'Dibayar'){
            status = `<span class="badge text-bg-info">Dibayar/span>`;
          }
      else  if(row.status_acc_hrd == 'Diterima'){
            status = `<span class="badge text-bg-success">Divalidasi</span>`;
          }
      else  if(row.status_acc_manager == 'Divalidasi'){
            status = `<span class="badge text-bg-primary">Divalidasi</span>`;
          }
       else if(row.status_transportasi == 'Mengajukan' || row.status_parkir_tol == 'Mengajukan'){
            status = `<span class="badge text-bg-warning">Mengajukan</span>`;
          }
        else if(row.status_transportasi == null && row.status_parkir_tol == null ){
          status = `<span class="badge text-bg-secondary">Belum Mengajukan</span>`;
      }
       
        return status;
    }},

    { data: 'id' , className: 'text-center' , 
        render : function(data, type, row){

    let btn = ``;
if(row.status_acc_hrd !== 'Diterima' || row.status_acc_finance !== 'Dibayar'){
  if(row.status_transportasi == 'Mengajukan' || row.status_parkir_tol == 'Mengajukan'){
     btn = `<span class="badge text-bg-success" onclick="validasiEC(${row.id} , 'validasi', '${row.status_transportasi}', '${row.status_parkir_tol}')">Validasi <i class="bi bi-check-square"></i></span>`;
} else {
    btn = `<span class="badge text-bg-danger" onclick="validasiEC(${row.id}, 'rejected', '${row.status_transportasi}', '${row.status_parkir_tol}')">Rejected <i class="bi bi-x-square"></i></span>`;
}
}
    return btn;
        }
     }

      ];

    var dtEC;
    var datatableEC = () => {
    const startDate = null;
    const endDate = null;
    dtEC =  $('#datatable-ec').DataTable({
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
            url: '/ec/datatable-explains-claims-manager/' + startDate + '/' + endDate
        },
        columns: columnsDataTableEC
        });

        dtEC.on('click', '.image-transportasi', function () {
       let log =   $('.picture-ec').attr('src', null);
 
          $('#image-ec-modal').modal('show');
          const rowIndex = dtEC.row($(this).closest('tr')).index();
          const rowData = dtEC.row(rowIndex).data();
         $('.picture-ec').attr('src', '/storage/ec/' + rowData.bukti_transportasi);
   
        });
        
        dtEC.on('click', '.image-parkir-tol', function () {
          $('.picture-ec').attr('src', null);
          $('#image-ec-modal').modal('show');
          const rowIndex = dt.row($(this).closest('tr')).index();
          const rowData = dt.row(rowIndex).data();
          $('.picture-ec').attr('src', '/storage/ec/' + rowData.bukti_parkir_tol);
        });
    }     


    function validasiEC(id, status, status_transportasi = null, status_parkir_tol = null) {
        $.ajax({
          type: "PUT",
          url: "/ec/update-status-ec/" + id + '/' + status,
          data :{
            status_transportasi : status_transportasi,
            status_parkir_tol : status_parkir_tol 
          },
          success: function (response) {
              toastMixin.fire({
                  animation: true,
                  title: response.message
                });
      
                dtEC.ajax.reload();
          }
        });
      }
    

document.addEventListener('DOMContentLoaded', function(){
    date();
    datatable();
    dateEC();
    datatableEC();
});