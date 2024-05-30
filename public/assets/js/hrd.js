
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
    var start = moment();
    var end = moment();
    $('#date-absensi').daterangepicker({
      startDate: start,
      endDate: end,
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
   
  },  function (start, end){
    $("#date-absensi").html(start.format("YYYY-MM-DD") + "," + end.format("YYYY-MM-DD"));
  } );

    $('#date-absensi').on('change', function(){

    let employee = $('#employee').val();
    let department = $('#department').val();
    let unit_bisnis = $('#unit-bisnis').val();


    const startDate = $(this).data('daterangepicker').startDate.format('YYYY-MM-DD');
    const endDate = $(this).data('daterangepicker').endDate.format('YYYY-MM-DD');
    const newUrl = '/absensi/datatable-absensi-hrd/' + startDate + '/' + endDate + '/' + employee + '/' + department + '/' + unit_bisnis;
    dt.ajax.url(newUrl).load();


    $('#export-excel').attr('href', '/absensi/export-excel-hrd/'+ startDate + '/' + endDate + '/' + employee + '/' + department + '/' + unit_bisnis);
  });

  
  $('#employee').on('change',function (e) { 
    $('#date-absensi').trigger('change');
  });
  $('#department').on('change',function (e) { 
    $('#date-absensi').trigger('change');
  });
  $('#unit-bisnis').on('change',function (e) { 
    $('#date-absensi').trigger('change');
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
    { data: 'departemen', name:'employees.departemen' },
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

      if(row.status_validasi == null){
        btn = `<span class="badge text-bg-success" onclick="validasiAbsensi(${row.id} , 'validasi')">Validasi <i class="bi bi-check-square"></i></span>`;
   } else {
       btn = `<span class="badge text-bg-danger" onclick="validasiAbsensi(${row.id}, 'rejected')">Rejected <i class="bi bi-x-square"></i></span>`;
   }
      return btn;
          }
      }
      ];


    var dt;
    var datatable = () => {
    dt =  $('#absensi-datatable-hrd').DataTable({
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
        // stateSave: false,
        ajax: {
            url: '/absensi/datatable-absensi-hrd'
        },
        columns: columnsDataTableReport
        });
    } 



function validasiAbsensi(id, status) {
  $.ajax({
    type: "PUT",
    url: "/absensi/update-status-absensi/" + id + '/' + status,
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
  var start = moment();
  var end = moment();
  $('#date-ec').daterangepicker({
    startDate: start,
    endDate: end,
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
   
  },  function (start, end){
    $("#date-ec").html(start.format("YYYY-MM-DD") + "," + end.format("YYYY-MM-DD"));
  } );


  $('#date-ec').on('change', function(){

    let employee_id = $('#employee-ec').val();
    let department = $('#department-ec').val();
    let unit_bisnis = $('#unit-bisnis-ec').val();

  
    const startDate = $(this).data('daterangepicker').startDate.format('YYYY-MM-DD');
    const endDate = $(this).data('daterangepicker').endDate.format('YYYY-MM-DD');
    const newUrl = '/ec/datatable-explains-claims-hrd/' + startDate + '/' + endDate + '/' + employee_id + '/' + department + '/';
    dtEC.ajax.url(newUrl).load();

    $('#export-excel-ec').attr('href', '/ec/export-excel-hrd/'+ startDate + '/' + endDate + '/' + employee_id  + '/' + department  + '/' + unit_bisnis);
  });

  $('#employee-ec').on('change',function (e) { 
    $('#date-ec').trigger('change');
  });
  $('#department-ec').on('change',function (e) { 
    $('#date-ec').trigger('change');
  });
  $('#unit-bisnis-ec').on('change',function (e) { 
    $('#date-ec').trigger('change');
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
    { data: 'departemen', name: 'employees.departemen' },
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
        if(row.status_transportasi == 'Divalidasi' || row.status_transportasi == 'Dibayar'){
          return `<p class="image-transportasi text-success">Rp ${rupiahFormat}</p>`;
        }else if(row.status_transportasi == 'Ditolak'){
          return `<p class="image-transportasi text-danger">Rp ${rupiahFormat}</p>`;
        }else {
          return `<p class="image-transportasi">Rp ${rupiahFormat}</p>`;
        }
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
        if( row.status_parkir_tol == 'Divalidasi' || row.status_parkir_tol == 'Dibayar'){
          return `<p class="image-parkir-tol text-success">Rp ${rupiahFormat}</p>`;
        }else if(row.status_parkir_tol == 'Ditolak'){
          return `<p class="image-parkir-tol text-danger">Rp ${rupiahFormat}</p>`;
        } else {
          return `<p class="image-parkir-tol">Rp ${rupiahFormat}</p>`;
        }
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
    else  if(row.status_acc_hrd == 'Divalidasi'){
          status = `<span class="badge text-bg-success">Divalidasi</span>`;
        }
    else  if(row.status_acc_manager == 'Diterima'){
          status = `<span class="badge text-bg-primary">Diterima</span>`;
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

   
    
  if(row.status_acc_hrd === 'Diterima'){
    btn = `<span class="badge text-bg-danger" onclick="validasiEC(${row.id}, 'rejected', '${row.status_transportasi}', '${row.status_parkir_tol}')">Rejected <i class="bi bi-x-square"></i></span>`;
  } else {
    btn = `<span class="badge text-bg-success" onclick="validasiEC(${row.id} , 'validasi', '${row.status_transportasi}', '${row.status_parkir_tol}')">Validasi <i class="bi bi-check-square"></i></span>`;
}
    return btn;
        }
     }

      ];

    var dtEC;
    var datatableEC = () => {
    dtEC =  $('#datatable-ec').DataTable({
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
            url: '/ec/datatable-explains-claims-hrd'
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
          const rowIndex = dtEC.row($(this).closest('tr')).index();
          const rowData = dtEC.row(rowIndex).data();
          $('.picture-ec').attr('src', '/storage/ec/' + rowData.bukti_parkir_tol);
        });
    }     


    function validasiEC(id, status, status_transportasi = null, status_parkir_tol = null) {
        $.ajax({
          type: "PUT",
          url: "/ec/update-status-ec-hrd/" + id + '/' + status,
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


      var selectEmployee = () => {
        $( '#employee' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            allowClear: true,
            closeOnSelect: true,
            ajax: {
                url: '/absensi/select-employee',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    query: params.term // search term
                  };
                },
                processResults: function (data, params) {
        
                  // parse the results into the format expected by Select2
                  // since we are using custom formatting functions we do not need to
                  // alter the remote JSON data, except to indicate that infinite
                  // scrolling can be used
                  // params.page = params.page || 1;
        
                  return {
                    results: $.map(data, function (item) {
                      var additionalText = ''
                      var PrefixText = ''
                      PrefixText = item.id + " - "
                      // additionalText = " ["+item.general_code+"]"
        
                      return {
                        text: PrefixText + item.nama_pegawai + additionalText,
                        id: item.id
                      }
                    })
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 0,
              // tags: true, // for create new tags
              language: {
                inputTooShort: function () {
                  return 'Input is too short';
                },
                errorLoading: function () {
                  return `There's error on our side`;
                },
                noResults: function () {
                  return 'There are no result based on your search';
                }
              }
        } );
      }


      var selectDepartment = () => {
        $( '#department' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            allowClear: true,
            closeOnSelect: true,
            ajax: {
                url: '/absensi/select-department',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    query: params.term // search term
                  };
                },
                processResults: function (data, params) {
        
                  // parse the results into the format expected by Select2
                  // since we are using custom formatting functions we do not need to
                  // alter the remote JSON data, except to indicate that infinite
                  // scrolling can be used
                  // params.page = params.page || 1;
        
                  return {
                    results: $.map(data, function (item) {
                      var additionalText = ''
                      var PrefixText = ''
                   
                      // additionalText = " ["+item.general_code+"]"
        
                      return {
                        text: item.departemen ,
                        id: item.departemen 
                      }
                    })
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 0,
              // tags: true, // for create new tags
              language: {
                inputTooShort: function () {
                  return 'Input is too short';
                },
                errorLoading: function () {
                  return `There's error on our side`;
                },
                noResults: function () {
                  return 'There are no result based on your search';
                }
              }
        } );
      }

      var selectUnitBisnis = () => {
        $( '#unit-bisnis' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            allowClear: true,
            closeOnSelect: true,
            ajax: {
                url: '/absensi/select-unit-bisnis',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    query: params.term // search term
                  };
                },
                processResults: function (data, params) {
        
                  // parse the results into the format expected by Select2
                  // since we are using custom formatting functions we do not need to
                  // alter the remote JSON data, except to indicate that infinite
                  // scrolling can be used
                  // params.page = params.page || 1;
        
                  return {
                    results: $.map(data, function (item) {
        
                      return {
                        text: item.unit_bisnis ,
                        id: item.unit_bisnis 
                      }
                    })
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 0,
              // tags: true, // for create new tags
              language: {
                inputTooShort: function () {
                  return 'Input is too short';
                },
                errorLoading: function () {
                  return `There's error on our side`;
                },
                noResults: function () {
                  return 'There are no result based on your search';
                }
              }
        } );
      }


      var selectEmployeeEC = () => {
        $( '#employee-ec' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            allowClear: true,
            closeOnSelect: true,
            ajax: {
                url: '/ec/select-employee-ec',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    query: params.term // search term
                  };
                },
                processResults: function (data, params) {
        
                  // parse the results into the format expected by Select2
                  // since we are using custom formatting functions we do not need to
                  // alter the remote JSON data, except to indicate that infinite
                  // scrolling can be used
                  // params.page = params.page || 1;
        
                  return {
                    results: $.map(data, function (item) {
                      var additionalText = ''
                      var PrefixText = ''
                      PrefixText = item.id + " - "
                      // additionalText = " ["+item.general_code+"]"
        
                      return {
                        text: PrefixText + item.nama_pegawai + additionalText,
                        id: item.id
                      }
                    })
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 0,
              // tags: true, // for create new tags
              language: {
                inputTooShort: function () {
                  return 'Input is too short';
                },
                errorLoading: function () {
                  return `There's error on our side`;
                },
                noResults: function () {
                  return 'There are no result based on your search';
                }
              }
        } );
      }


      var selectDepartmentEC = () => {
        $( '#department-ec' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            allowClear: true,
            closeOnSelect: true,
            ajax: {
                url: '/ec/select-department-ec',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    query: params.term // search term
                  };
                },
                processResults: function (data, params) {
        
                  // parse the results into the format expected by Select2
                  // since we are using custom formatting functions we do not need to
                  // alter the remote JSON data, except to indicate that infinite
                  // scrolling can be used
                  // params.page = params.page || 1;
        
                  return {
                    results: $.map(data, function (item) {
                      var additionalText = ''
                      var PrefixText = ''
                   
                      // additionalText = " ["+item.general_code+"]"
        
                      return {
                        text: item.departemen ,
                        id: item.departemen 
                      }
                    })
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 0,
              // tags: true, // for create new tags
              language: {
                inputTooShort: function () {
                  return 'Input is too short';
                },
                errorLoading: function () {
                  return `There's error on our side`;
                },
                noResults: function () {
                  return 'There are no result based on your search';
                }
              }
        } );
      }

      var selectUnitBisnisEC = () => {
        $( '#unit-bisnis-ec' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            allowClear: true,
            closeOnSelect: true,
            ajax: {
                url: '/ec/select-unit-bisnis-ec',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                  return {
                    query: params.term // search term
                  };
                },
                processResults: function (data, params) {
        
                  // parse the results into the format expected by Select2
                  // since we are using custom formatting functions we do not need to
                  // alter the remote JSON data, except to indicate that infinite
                  // scrolling can be used
                  // params.page = params.page || 1;
        
                  return {
                    results: $.map(data, function (item) {
        
                      return {
                        text: item.unit_bisnis ,
                        id: item.unit_bisnis 
                      }
                    })
                  };
                },
                cache: true
              },
              escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
              // minimumInputLength: 0,
              // tags: true, // for create new tags
              language: {
                inputTooShort: function () {
                  return 'Input is too short';
                },
                errorLoading: function () {
                  return `There's error on our side`;
                },
                noResults: function () {
                  return 'There are no result based on your search';
                }
              }
        } );
      }

document.addEventListener('DOMContentLoaded', function(){
    date();
    datatable();
    dateEC();
    datatableEC();
    selectEmployee();
    selectDepartment();
    selectUnitBisnis();
    selectEmployeeEC();
    selectDepartmentEC();
    selectUnitBisnisEC();
});