$(document).ready(function(){
  $('#data_upload').attr('class','menu-open nav-item');
  $('#data_upload > a').attr('class','nav-link active');
  $('#penerima-bantuan').attr('class','nav-link active');
  $('#penerima-bantuan > i').attr('class','far fa-circle nav-icon text-danger');

    getData();
    function getData(){
        $.ajax({
            method:'POT',
            dataType:'JSON',
            url:'getData',
            success:function(result){
              var dt = $('#list-bantuan').DataTable({
                  responsive: true,
                  bDestroy: true,
                  processing: true,
                  autoWidth : true,
                  pageLength: 10,
                  lengthChange: true,
                  scrollX: true,
                  aaData: result,
                  aoColumns: [
                    {"mDataProp":"no"},
                    {"mDataProp":"bulan"},
                    {"mDataProp":"tahun"},
                    {"mDataProp":"nama_kabupaten"},
                    {"mDataProp":"kelompok_tani"},
                    {"mDataProp":"kecamatan"},
                    {"mDataProp":"desa"},
                    {"mDataProp":"nama"},
                    {"mDataProp":"nik"},
                    {"mDataProp":"no_hp"},
                    {"mDataProp":"jml_anggota"},
                    {"mDataProp":"luas"},
                    {"mDataProp":"jenis_lahan"},
                    {"mDataProp":"benih"},
                    {"mDataProp":"varietas"},
                    {"mDataProp":"pupuk"},
                    {"mDataProp":"rhizobium"},
                    {"mDataProp":"herbisida"},
                    {"mDataProp":"jadwal"},
                    {"mDataProp":"provitas_existing"},
                    {"mDataProp":"provitas_target"}
                  ],
                  order: [[1, 'DESC']],
                  rowGroup: {
                      dataSrc: 'nama_kabupaten'
                  },
                  aoColumnDefs:[
                        {
                          "targets": [0],
                          "orderable": false
                        },
                  //     {
                  //         "targets": [ 5,6,7,8,9,10 ],
                  //         "visible": false
                  //     },
                      {
                          mRender: function (data, type, row){

                            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                              "Juli", "Agustus", "September", "Oktober", "November", "Desember"
                            ];

                              var $rowData = monthNames[row.bulan - 1];

                              return $rowData;
                          },
                          aTargets: [1]
                      },
                  ],


                  // fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                  //     var index = iDisplayIndexFull + 1;
                  //     $('td:eq(0)', nRow).html(' '+index);
                  //     return  ;
                  // },
              });
            }
        })
    }
    console.log('You are running jQuery version: ' + $.fn.jquery);

    $('#ok-delete').on('click', function(){
        const swalWithBootstrapButtons = Swal.mixin({
          customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
          },
          buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
          title: 'Anda Yakin, hapus Data ini?',
          text: "",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: '<i class="fas fa-check"></i> Ya',
          cancelButtonText: '<i class="fas fa-times"></i> Tidak',
          reverseButtons: true
        }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            type: 'post',
            dataType: 'json',
            url: 'deletedata',
            data : {
                    table    : 'bantuan',
                    bulan    : $('#bulan').val(),
                    tahun    : $('#tahun').val(),
                  },
            success: function(data)
            {
              Swal.fire({
                title: 'Sukses!',
                text: 'Hapus Data',
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
              });
              getData();
            }
          });
        }
      })

    });
});


function modalhapus(){
  $('#modal-delete').modal({
    show: true
  });

}
