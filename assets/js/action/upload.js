$('.select2').select2();
$(document).ready(function(){


$('.fileinput-upload').on('click', function(){
  var formData = new FormData();
  var files = $('#file-4')[0].files;

  formData.append('file_data',files[0]);
  formData.append('nama_file',$('#nama_file').val());
  formData.append('bulan', $('#bulan').val());
  formData.append('tahun',$('#tahun').val());

  if($('#nama_file').val() == '0'){
    Swal.fire(
        'Pilih Nama File.',
        'Silahkan Pilih Sesuai Nama yang ditentukan.',
        'question'
      );
      return;
  };

  if($('#bulan').val() == '0'){
    Swal.fire(
        'Pilih Bulan Data.',
        'Silahkan Pilih Sesuai Bulan.',
        'question'
      );
      return;
  };

  if($('#tahun').val() == ""){
    Swal.fire(
        'Pilih Tahun Data.',
        'Silahkan Pilih Sesuai Tahun.',
        'question'
      );
      return;
  };

  if(files[0]['name'] != $('#nama_file').val()){
    Swal.fire(
        'Nama File Tidak Sesuai.',
        'Silahkan Pilih Sesuai Nama yang ditentukan.',
        'question'
      );
      return;
  };


  $.ajax({
      url:'form',
      type: 'post',
      data: formData,
      contentType: false,
      processData: false,
      success:function(result){
        Swal.fire({
          title: 'Sukses!',
          text: "Berhasil Upload Excell",
          icon: 'success',
          showConfirmButton: true,
          confirmButtonText: '<i class="fas fa-check"></i>'
        }).then((result) => {
        if (result.isConfirmed) {
          location.reload();
          }
        });
      }
  })
});

  $('#upload > a').attr('class','nav-link active');
    function getData(){
        $.ajax({
            method:'POT',
            dataType:'JSON',
            url:'getData',
            success:function(result){
                $('#list-pangan').DataTable({
                    responsive: true,
                    data: result
                });
                console.log(result);
                new $.fn.dataTable.FixedHeader( table );
            // $('#list-pangan').html($html);
            }
        })
    }

    console.log('You are running jQuery version: ' + $.fn.jquery);
});
