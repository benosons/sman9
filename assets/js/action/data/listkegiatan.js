$( document ).ready(function() {
  window.baseU = $('#baseURL').val();
  $('#menu-kegiatan-list').addClass('mm-active');
  loadkegiatan('');

});
$('#file-doc').on('change', function(){
  $('#donlot-dong').attr('href', window.baseU+this.value);
});

function loadkegiatan(param){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'loadkegiatan',
        data : {
                param      : param,
         },
        success: function(result){
          console.log(result);
          $('#list-kegiatan').DataTable({
                    aaData: result,
                    lengthChange: false,
                    pageLength: 10,
                    aoColumns: [
                        { 'mDataProp': 'id'},
                        { 'mDataProp': 'id', 'sClass':'text-center'},
                        { 'mDataProp': 'indikator_ssd_name'},
                        { 'mDataProp': 'indikator_manager_name'},
                        { 'mDataProp': 'kegiatan'},
                        { 'mDataProp': 'create_date'},
                        { 'mDataProp': 'tanggal', sClass: 'text-center'},
                        { 'mDataProp': 'tanggal'},

                    ],
                    order: [[0, 'ASC']],
                    aoColumnDefs:[
                      {
                          mRender: function ( data, type, row ) {
                            let srcc = '';
                            if(row.files[0].param_val1 == 'images'){
                              srcc = row.files[0].path;
                            }
                            var el =
                              `<div class="avatar-icon-wrapper mr-3 avatar-icon-xl btn-hover-shine">
                                                    <div class="avatar-icon rounded">
                                                        <img src="`+srcc+`" alt="Avatar 5">
                                                    </div>
                                                </div>`;
                              return el;
                          },
                          aTargets: [ 1 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            for (var i = 0; i < row.files.length; i++) {
                              delete row.files[i].tmp_name;
                            }
                            let fil = JSON.stringify(row.files);

                            let fil1 = fil.replace('[{', '<');
                            let fil2 = fil1.replace('}]', '>');
                            let fil3 = fil2.replace(/"/g, "=");
                            let fil4 = fil3.replace(/,/g, "~");

                            var el = `<div role="group" class="btn-group-sm btn-group btn-group-toggle">
                                        <button type="button" class="btn btn-success" onclick="showmodal('`+fil4+`')"><i class="fa fa-download" aria-hidden="true" title="Copy to use edit"></i></button>
                                    </div>`;

                              return el;
                          },
                          aTargets: [ 6 ]
                      },
                      {
                          mRender: function ( data, type, row ) {
                            var el = `<div role="group" class="btn-group-sm btn-group btn-group-toggle">
                                <button type="button" class="btn btn-warning" onclick="action('edit',`+row.id+`,'`+row.dokumen+`')"><i class="fa fa-edit" aria-hidden="true" title="Copy to use edit"></i></button>
                                <button type="button" class="btn btn-danger" onclick="action('hapus',`+row.id+`,'`+row.dokumen+`')"><i class="fa fa-trash" aria-hidden="true" title="Copy to use edit"></i></button>
                            </div>`;

                              return el;
                          },
                          aTargets: [ 7 ]
                      },
                    ],
                    fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull){
                        var index = iDisplayIndexFull + 1;
                        $('td:eq(0)', nRow).html('#'+index);
                        return  index;
                    },
                    fnInitComplete: function () {
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

function action(param, id, dokumen){


    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'actionkegiatan',
        data : {
                param      : param,
                id         : id,
                dokumen    : dokumen
         },
        success: function(result){
          swal(
            "Sukses!",
            param.charAt(0).toUpperCase() + param.slice(1)+" Kegiatan!",
            "success"
          ).then((value) => {
            window.location.href = '/kegiatan';
          });
        }
      })
    }

    function showmodal(iObj){

      let fil = iObj;
      let fil1 = fil.replace('<', '[{');
      let fil2 = fil1.replace('>', '}]');
      let fil3 = fil2.replace(/=/g, '"');
      let fil4 = fil3.replace(/~/g, ",");
      let file = JSON.parse(fil4);
      console.log(file);

      let opt = '<option value="0">-Pilih-</option>';
      for (var i = 0; i < file.length; i++) {
        if(file[i].param_val1 == 'document'){
          opt += '<option value="'+file[i].path+'">'+file[i].name+'</option>';
        }
      }

      $('#file-doc').html(opt);
      $('[data-target="#modaldonlotdoc"]').trigger('click');
    }
