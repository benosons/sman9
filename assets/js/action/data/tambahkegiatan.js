$( document ).ready(function() {
  $('#menu-kegiatan-tambah').addClass('mm-active');
  $(".custom-file-input").on("change", function() {
    let filenames = [];
    for (var i = 0; i < this.files.length; i++) {
      filenames.push(this.files[i].name);
    }
    // var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(filenames.join([separator = ', ']));
  });

});

function submitkegiatan(){
  let formData = new FormData();

  let img = $('#customFile')[0].files;
  let doc = $('#customFile-1')[0].files;

  for (var i = 0; i < img.length; i++) {
    formData.append('img[]',img[i]);
  }

  for (var i = 0; i < doc.length; i++) {
    formData.append('doc[]',doc[i]);
  }

  formData.append('indikator_ssd',$('#indikator_ssd').val());
  formData.append('indikator_manager', $('#indikator_manager').val());
  formData.append('uraian_indikator',$('#uraian_indikator').val());
  formData.append('kegiatan',$('#kegiatan').val());
  formData.append('tanggal',$('#tanggal').val());

  $.ajax({
      type: 'post',
      url:'submitkegiatan',
      data: formData,
      contentType: false,
      processData: false,
      success:function(result){
        swal(
          "Sukses!",
          "Tambah Kegiatan!",
          "success"
        ).then((value) => {
          window.location.href = '/kegiatan';
        });
      }
    });
}
