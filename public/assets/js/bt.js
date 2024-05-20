
$.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });


  var showError = (field, message) => {
    console.log(field, message);
    if(!message){
        $('#' + field)
        .addClass('is-valid')
        .removeClass('is-invalid')
        .siblings('.invalid-feedback')
        .text('');
    } else {
        $('#' + field)
        .addClass('is-invalid')
        .removeClass('is-valid')
        .siblings('.invalid-feedback')
        .text(message);
    }
}


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


  var columnsDataTableReport = [
    { data: 'no_spj' },
    { data: 'created_at',
      render: function(data, type, row) {
          if (data) {
              let date = moment(data).format('DD/MM/YYYY');
              return date;
          }
          return ''; // Return empty string if data is null or undefined
      }
    },
    { data: 'departure' ,
    render: function(data, type, row) {
     
            let date = row.departure + ' - ' + row.type_bt;
            return date;
      
     
    } },
    { data: 'deposit',
      render: function(data, type, row) {
          if (row.deposit != null) {
              let rupiahFormat = new Intl.NumberFormat('id-ID', {
                  currency: 'IDR',
              }).format(row.deposit);
              return 'Rp ' + rupiahFormat;
          }
          return 'Rp 0'; // Return default value if deposit is null or undefined
      }
    },
    { data: 'total_cost',
      render: function(data, type, row) {
          if (row.total_cost != null) {
              let rupiahFormat = new Intl.NumberFormat('id-ID', {
                  currency: 'IDR',
              }).format(row.total_cost);
              return 'Rp ' + rupiahFormat;
          }
          return 'Rp 0'; // Return default value if total_cost is null or undefined
      }
    },
    { data: 'status_manager',
      render: function(data, type, row) {
          if (row.status_manager) {
              let status = `<span class="badge text-bg-warning">${row.status_manager}</span>`;
              return status;
          }
          return '<span class="badge text-bg-secondary">Unknown</span>'; // Return default value if status_manager is null or undefined
      }
    },
    { data: null,
      render: function(data, type, row) {
          return '-';
      }
    },
    { data: null,
      render: function(data, type, row) {
          return '-';
      }
    }
];


    var dt;
    var datatable = () => {
    dt =  $('#bt-datatable').DataTable({
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
        order : [[1, 'desc']],
        stateSave: false,
        ajax: {
            url: 'bt/datatable-bt'
        },
        columns: columnsDataTableReport
        });
    } 


  var selectColleague = () => {
    $( '#colleague' ).select2( {
        theme: "bootstrap-5",
        width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
        placeholder: $( this ).data( 'placeholder' ),
        closeOnSelect: false,
        ajax: {
            url: '/bt/select-colleague',
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

  var selectDeparture = () => {
    $( '#departure' ).select2( {
        theme: "bootstrap-5",
        placeholder: $( this ).data( 'placeholder' ),
        allowClear: true,
        closeOnSelect: true,
        ajax: {
            url: '/bt/select-departure',
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
                    text: PrefixText + item.location + additionalText,
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

  var selectDestination = () => {
    $( '#departure' ).on('change', function(){

  const departureId = $(this).val();

    $( '#destination' ).select2( {
      theme: "bootstrap-5",
      width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
      placeholder: $( this ).data( 'placeholder' ),
      closeOnSelect: false,
        ajax: {
            url: '/bt/select-destination/' + departureId,
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
                    text: PrefixText + item.location_name + additionalText,
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
  });
  }
 
  var date = () => {
    $('#date').daterangepicker({
      singleDatePicker: true,
      locale: {
          format: 'DD/MM/YYYY',
      },
  });
  }


  var formCreate = () => {
    $('#formCreateBT').submit(function (e) { 
      e.preventDefault();

    let formCreate =  $(this).serialize();
    let form =  $(this);

   

    $.ajax({
      type: "POST",
      url: "/bt/create-bt",
      data: formCreate,
      success: function (response) {
      
        if(response.status == 400){
          showError('departure', response.messages.departure);
          showError('date', response.messages.date);
          showError('day', response.messages.day);
          showError('transportation', response.messages.transportation);
          showError('destination', response.messages.destination);
        }else if(response.status == 200){
          form.trigger('reset');
          $('#departure').val(null).trigger('change');
          var newOptionDestination = new Option(null, null, false, false);
          $('#destination').append(newOptionDestination).trigger('change');
          sessionStorage.setItem("createBT", "true");
          window.location.href = '/bt';
        }
      }
    });

      
    });
  }

document.addEventListener('DOMContentLoaded', function(){
  datatable();
selectColleague();
selectDeparture();
selectDestination();
date();
formCreate();
showError();


});