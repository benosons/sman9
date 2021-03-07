$( document ).ready(function() {
  $('#menu-so').addClass('mm-active');
  window.baseURL = $('#baseURL').val();
  loadkegiatan('')
});

function loadkegiatan(param){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'loadso',
        data : {
                param      : param,
         },
        success: function(result){
          $('#list-so').DataTable({
                    aaData: result,
                    lengthChange: false,
                    pageLength: 10,
                    aoColumns: [
                        { 'mDataProp': 'id', 'sClass':'text-center'},
                        { 'mDataProp': 'foto', 'sClass':'text-center'},
                        { 'mDataProp': 'singkatan', 'sClass':'text-center'},
                        { 'mDataProp': 'nama_jabatan'},
                        { 'mDataProp': 'nama_pejabat'},
                        { 'mDataProp': 'nipp'},
                        { 'mDataProp': 'deskripsi_jabatan'},
                        { 'mDataProp': 'id'},
                        { 'mDataProp': 'id'},

                    ],
                    // order: [0, 'ASC'],
                    aoColumnDefs:[
                      {
                          mRender: function ( data, type, row ) {
                            var el =
                              `<div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                                    <div class="avatar-icon rounded">
                                                        <img src="`+window.baseURL+data+`" alt="Avatar 5">
                                                    </div>
                                                </div>`;
                              return el;
                          },
                          aTargets: [ 1 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            var el = `<input id="pejabat-`+row.id+`" name="input-so" type="text" class="form-control" value="`+data+`">`;
                              return el;
                          },
                          aTargets: [ 4 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            var el = `<input id="nipp-`+row.id+`" name="input-so" type="text" class="form-control" value="`+data+`">`;
                              return el;
                          },
                          aTargets: [ 5 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            var el = `<textarea id="desc-`+row.id+`" name="input-so" type="text" class="form-control" value="`+data+`">`+data+`</textarea>`;
                              return el;
                          },
                          aTargets: [ 6 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            var el =
                              `<button class="mb-2 mr-2 btn btn-xs btn-success" onclick="action(`+row.id+`)" ><i class="fa fa-check" aria-hidden="true" title=""></i></button>`;
                              return el;
                          },
                          aTargets: [ 8 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            var el =
                              `<div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile-`+row.id+`">
                                <label class="custom-file-label" for="customFile-`+row.id+`">Pilih file</label>
                              </div>`;
                              return el;
                          },
                          aTargets: [ 7 ]
                      },
                    ],
                    // fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                    //     var index = iDisplayIndexFull + 1;
                    //     $('td:eq(0)', nRow).html('#'+index);
                    //     return  index;
                    // },
                    fnInitComplete: function () {

                      $(".custom-file-input").on("change", function() {
                        var fileName = $(this).val().split("\\").pop();
                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                      });

                        var that = this;
                        var td ;
                        var tr ;
                        this.$('td').click( function () {
                            td = this;
                        });
                        this.$('tr').click( function () {
                            tr = this;
                        });

                    }
                });

            }
    });
}

function action(id){
    var formData = new FormData();
    var files = $('#customFile-'+id)[0].files;
    formData.append('id', id);
    formData.append('file_data',files[0]);
    formData.append('nama_pejabat', $('#pejabat-'+id).val());
    formData.append('nipp', $('#nipp-'+id).val());
    formData.append('deskripsi_jabatan',$ ('#desc-'+id).val());

    $.ajax({
        type: 'post',
        url:'submitso',
        data: formData,
        contentType: false,
        processData: false,
        success:function(result){
          swal(
            "Sukses!",
            "Update!",
            "success"
          ).then((value) => {
            window.location.href = window.baseURL+'inputso';
          });
        }
      });
  }
