var scanBarcode = () => {

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

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

      function onScanSuccess(decodedText, decodedResult) {
          // alert(decodedText);
    
          let id = decodedText;        

          html5QrcodeScanner.clear().then(_ => {
          
              $.ajax({ 
                  url: "/absensi/process-scan-barcode-checkout",
                  type: 'POST',            
                  data: {
                      qr_code : id
                  },            
                  success: function (response) { 
                      console.log(response);
                      if(response.status == 200){
                        sessionStorage.setItem("checkout", "true");
                        window.location.href = `/absensi`;
                      }else{
                          alert(response.message);
                      }
                      
                  }
              });   
          }).catch(error => {
              alert('something wrong');
          });
          
      }
        
        function onScanFailure(error) {
          // handle scan failure, usually better to ignore and keep scanning.
          // for example:
          // console.warn(`Code scan error = ${error}`);
        }
        


let html5QrcodeScanner = new Html5QrcodeScanner(
"reader",
{ fps: 10, 
    qrbox: {width: 250, height: 250},  
    supportedScanTypes: [
    Html5QrcodeScanType.SCAN_TYPE_CAMERA
        ],
     },
/* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);  

}

document.addEventListener('DOMContentLoaded', function(){

scanBarcode();
});