$( document ).ready(function() {
  console.log('You are running jQuery version: ' + $.fn.jquery);
  window.baseURL = $('#baseURL').val();
  loadkegiatan('');
});

function detailissue(id){
  $('#issueModal').modal({backdrop: 'static', keyboard: false})

  $('#issueModal').modal({
    show: true
  });
}

function loadkegiatan(param){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'loadso',
        data : {
                param      : param,
         },
        success: function(result){
          console.log(result);
          for (var i = 0; i < result.length; i++) {
              if(result[i].foto){
                $('#foto-'+result[i].id).attr('src', window.baseURL+result[i].foto);
              }
              $('#singkatan-'+result[i].id).text(result[i].singkatan);
              $('#nama_jabatan-'+result[i].id).text(result[i].nama_jabatan);
              $('#nama_pejabat-'+result[i].id).text(result[i].nama_pejabat);
              $('#nipp-'+result[i].id).text(result[i].nipp);
              $('#desc-'+result[i].id).val(result[i].deskripsi_jabatan);
          }
        }
      });
    }

$('.tf-nc').on('click', function(){
  let foto = $(this).find("img").attr('src');
  let singkatan_id = $(this).find("h6").get(0).id;
    let singkatan = $('#'+singkatan_id).text();
  let nama_jabatan_id = $(this).find("h6").get(1).id;
    let nama_jabatan = $('#'+nama_jabatan_id).text();
  let nama_pejabat_id = $(this).find("h6").get(2).id;
    let nama_pejabat = $('#'+nama_pejabat_id).text();
  let nipp_id = $(this).find("h6").get(3).id;
    let nipp = $('#'+nipp_id).text();
  let desc_id = $(this).find("input").get(0).id;
    let desc = $('#'+desc_id).val();
  console.log(desc);
  $('#modal_foto').attr('src', foto);
  $('#modal_nama_jabatan').text(singkatan + ' - ' + nama_jabatan);
  $('#modal_nama').html('<strong>'+nama_pejabat+'</strong><br>'+nipp);
  $('#modal_desc').html('<label>Deskripsi : </label> ' + desc);
  $('#myModal').modal('show');
});
