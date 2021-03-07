$(document).ready(function(){
  $('#data_upload').attr('class','menu-open nav-item');
  $('#data_upload > a').attr('class','nav-link active');
  $('#laporan-kegiatan').attr('class','nav-link active');
  $('#laporan-kegiatan > i').attr('class','far fa-circle nav-icon text-danger');

    getData();
    function getData(){
        $.ajax({
            method:'POST',
            dataType:'JSON',
            url:'getData',
            data : { param: 'laporan' },
            success:function(result){

              var dt = $('#list-kegiatan').DataTable({
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
                    {"mDataProp":"jenis"},
                    {"mDataProp":"kabupaten"},
                    {"mDataProp":"jumlah_kec"},
                    {"mDataProp":"jumlah_desa"},
                    {"mDataProp":"jumlah_poktan"},
                    {"mDataProp":"sasaran_areal"},
                    {"mDataProp":"sk_penetapan"},
                    {"mDataProp":"realisasi_kontrak"},
                    {"mDataProp":"realisasi_distribusi"},
                    {"mDataProp":"apr"},
                    {"mDataProp":"mei"},
                    {"mDataProp":"juni"},
                    {"mDataProp":"juli"},
                    {"mDataProp":"ags"},
                    {"mDataProp":"sep"},
                    {"mDataProp":"okt"},
                    {"mDataProp":"nop"},
                    {"mDataProp":"des"},
                    {"mDataProp":"jumlah"},
                    {"mDataProp":"realisasi_panen_luas"},
                    {"mDataProp":"realisasi_panen_produktivitas"},
                    {"mDataProp":"realisasi_panen_produksi"},
                    {"mDataProp":"tidak_dilaksanakan"},
                    {"mDataProp":"provitas_sebelum"},
                    {"mDataProp":"ket"},
                  ],
                  order: [[1, 'DESC']],
                  rowGroup: {
                      dataSrc: 'jenis'
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
                        table    : 'laporan_bulanan_kegiatan',
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
