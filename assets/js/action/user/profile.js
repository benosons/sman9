$( document ).ready(function() {
  console.log('You are running jQuery version: ' + $.fn.jquery);
$('.select2').select2();
$('#pass-baru').val('');
window.img = '';
$("#foto-user").change(function() {
  readURL(this);
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#blah').attr('src', e.target.result);
      window.img = e.target.result;
    }
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$.when(loadkota()).done(loaduser());

  $('[name="form-pass"]').hide();
  $('#ubah-password').prop('checked', false);
  $('#ubah-password').on('change', function(){
    if(this.checked === true){
      $('[name="form-pass"]').show();
    }else{
      $('[name="form-pass"]').hide();
    }

  });

  $('#simpan-perubahan').on('click', function(){
    Swal.fire({
          title: "Masukan Password",
          input: "password",
          showCancelButton: true,
          confirmButtonText: '<i class="fa fa-check"></i>',
          cancelButtonText: '<i class="fa fa-times"></i>',
      }).then(function (pass) {

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'updateprofile',
            data : {
                    id        : $('#id_user').val(),
                    name      : $('#name').val(),
                    username  : $('#username').val(),
                    kotaKab   : $('#kota_kab').val(),
                    no_telp   : $('#notelp').val(),
                    email     : $('#email').val(),
                    password  : $('#pass-baru').val(),
                    validasi  : pass.value,
                    img       : window.img,
             },
            success: function(result){
              if(result.status === true){
              Swal.fire({
                icon  : 'success',
                title : 'Sukses Update Profile',
                text  : '',
                confirmButtonText: '<i class="fas fa-check"></i>'
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            }else{
              Swal.fire({
                icon  : 'error',
                title : 'Password salah',
                text  : '',
                confirmButtonText: '<i class="fas fa-check"></i>'
              }).then((result) => {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            }

            },
            failure: function (response) {

              }
          });
      })
  });

});

function loaduser(){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'loaduser',
        data : {
                id      : '',
         },
        success: function(result){
          console.log(result[0].kotaKab)
          $('#name').val(result[0].name);
          $('#username').val(result[0].username);
          $('[name="notelp"]').text(result[0].no_telp);
          $('#notelp').val(result[0].no_telp);
          $('[name="email"]').text(result[0].email);
          $('#email').val(result[0].email);
          $('#kategori').text(result[0].kategori);
          $('#kota_kab').val(result[0].kotaKab).trigger('change');
          $('#blah').attr('src', result[0].foto);
        }
      });
    }

    function loadkota(){
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'loadkota',
            data : {
                    param      : '',
             },
            success: function(result){
              $('#kota_kab').empty();
              var option ='<option value="0">-Pilih-</option>';
              for (var i = 0; i < result.length; i++) {
                option += '<option value="'+result[i].id+'">'+result[i].nama+'</option>';
              }
              $('#kota_kab').append(option);
            }
          });
        };

        function updateprofile(){
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: 'updateprofile',
                data : {
                        id        : $('#id').val(),
                        name      : $('#name').val(),
                        username  : $('#username').val(),
                        kotaKab   : $('#kotaKab').val(),
                        no_telp   : $('#notelp').val(),
                        email     : $('#email').val(),
                        password  : $('#pass-baru').val(),
                 },
                success: function(result){

                }
              });
            };
