
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

  var columnsDataTableReport = [
    {  
        data : 'day', 
    },
    {  
        data : 'date', 
    },
    {data: 'commander'},
    {data: 'location'},
    {data: 'description'},
    {data: 'start_time'},
    {data: 'end_time'},
    {data: 'overtime'},
    {data: 'overtime_cost',
    render : function(data, type, row){
      if(data !== null){
        let rupiahFormat = new Intl.NumberFormat('id-ID', {
          currency: 'IDR',
        }).format(data);
        return `Rp ${rupiahFormat}`;
      } else {
        return '-';
      }
    }
    },
    {data: 'status',
    render : function(data, type, row){
      let status = '';
     if(data == 'Mengajukan'){
        status = `<span class="badge text-bg-warning text-white">Mengajukan</span>`;
      }
      return status;
  }
    },
    {data : null, render : function(data, type, row){
        return `<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bi bi-gear"></i>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
        </div>
    </li>`;
    }}
      ];

    var dt;
    var datatable = () => {
    dt =  $('#ot-datatable').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        select: {
            style: 'single'
          },
        ordering: true,
        order : [[0, 'desc']],
        stateSave: false,
        ajax: {
            url: '/absensi/datatable-overtime'
        },
        columns: columnsDataTableReport
        });
    } 


document.addEventListener('DOMContentLoaded', function(){
    datatable();
});