
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
    const newUrl = 'ec/datatable/' + startDate + '/' + endDate;
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
         if(row.status_transportasi == null && row.status_parkir_tol == null ){
          status = `<span class="badge text-bg-secondary">Belum Mengajukan</span>`;
      }
        else if(row.status_transportasi == 'Mengajukan' || row.status_parkir_tol == 'Mengajukan'){
          status = `<span class="badge text-bg-warning">Mengajukan</span>`;
        }
        return status;
    }}

      ];


      var jabatan = $('#datatable-ec').data('jabatan');

      if (jabatan === 'Manager') {
        columnsDataTableReport.unshift({data : 'id', class: 'one-check',    "checkboxes": {
            'selectRow' : true 
          }});
        columnsDataTableReport.splice(4, 0, { data: 'nama_pegawai' });
        columnsDataTableReport.splice(10, 0,{ data: 'id' , className: 'text-center' , 
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
     });
       
      } else {
        columnsDataTableReport.splice(10 ,0,  {data : 'id',   "class": "text-center",
        render : function(data, type, row){
          let btn = `<span class="badge text-bg-light" onclick="inputEC(${row.id})"><i class="bi bi-pencil"></i></span>`;
            return btn;
        }}) ;
      }

    var dt;
    var datatable = () => {
    const startDate = null;
    const endDate = null;
    dt =  $('#datatable-ec').DataTable({
        // responsive: true,
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
            url: 'ec/datatable/' + startDate + '/' + endDate
        },
        columns: columnsDataTableReport
        });

        dt.on('click', '.image-transportasi', function () {
       let log =   $('.picture-ec').attr('src', null);
 
          $('#image-ec-modal').modal('show');
          const rowIndex = dt.row($(this).closest('tr')).index();
          const rowData = dt.row(rowIndex).data();
        let s =  $('.picture-ec').attr('src', '/storage/ec/' + rowData.bukti_transportasi);
        console.log(s);
        });
        
        dt.on('click', '.image-parkir-tol', function () {
          $('.picture-ec').attr('src', null);
          $('#image-ec-modal').modal('show');
          const rowIndex = dt.row($(this).closest('tr')).index();
          const rowData = dt.row(rowIndex).data();
          $('.picture-ec').attr('src', '/storage/ec/' + rowData.bukti_parkir_tol);
        });
    } 

function inputEC(id){

  $('#thousand-separator-transportasi').val(0);
  $('#thousand-separator-parkir-tol').val(0);
  $.ajax({
    type: "GET",
    url: "ec/check-ec",
    data: {
      id: id
    },
    success: function (response) {

      if(response.transportasi !== null){
            // Pisahkan angka desimal dan ribuan
    let parts = response.transportasi.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Tambahkan koma untuk ribuan
    
    // Gabungkan kembali bagian angka
    let formattedValue = parts.join('.');
        $('#thousand-separator-transportasi').val(formattedValue);
      }

      if(response.parkir_tol !== null){
            // Pisahkan angka desimal dan ribuan
    let parts = response.parkir_tol.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Tambahkan koma untuk ribuan
    
    // Gabungkan kembali bagian angka
    let formattedValue = parts.join('.');
        $('#thousand-separator-parkir-tol').val(formattedValue);
      }
    }
  });

$('.ec-id').val(id);

$('#add-ec-modal').modal('show');

}

function thousand()
{
  document.getElementById('thousand-separator-transportasi').addEventListener('input', function(e){
    // Hapus karakter selain angka dan titik
    let sanitizedValue = e.target.value.replace(/[^\d.]/g, '');
    
    // Pisahkan angka desimal dan ribuan
    let parts = sanitizedValue.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Tambahkan koma untuk ribuan
    
    // Gabungkan kembali bagian angka
    let formattedValue = parts.join('.');
    
    e.target.value = formattedValue;
  });

  document.getElementById('thousand-separator-parkir-tol').addEventListener('input', function(e){
    // Hapus karakter selain angka dan titik
    let sanitizedValue = e.target.value.replace(/[^\d.]/g, '');
    
    // Pisahkan angka desimal dan ribuan
    let parts = sanitizedValue.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Tambahkan koma untuk ribuan
    
    // Gabungkan kembali bagian angka
    let formattedValue = parts.join('.');
    
    e.target.value = formattedValue;
  });
}


var formCreateEC = () => {
  $('#formCreateEC').submit(function (e) { 
    e.preventDefault();

    var formData = new FormData(this);
    var form = $(this);

    $.ajax({
      type: "POST",
      url: "ec/add-ec",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.status == 200) {
          dt.ajax.reload();
          form.trigger('reset');
          $('#add-ec-modal').modal('hide');
          toastMixin.fire({
            animation: true,
            title: 'Data Saved Successfully!'
          });
        }else{
       alert(response.messages.file_transportasi);
       if(response.messages.file_parkir_tol !== undefined){
       alert(response.messages.file_parkir_tol);
      }
        }
      
      }
    });
  });
}


document.addEventListener('DOMContentLoaded', function(){
    date();
    datatable();
    thousand();
    formCreateEC();


});