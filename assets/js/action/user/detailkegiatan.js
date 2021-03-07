$( document ).ready(function() {
  console.log('You are running jQuery version: ' + $.fn.jquery);
  // loadkegiatan();
});


function detailissue(id){
  $('#issueModal').modal({backdrop: 'static', keyboard: false})

  $('#issueModal').modal({
    show: true
  });
}

var current_page_isu = 1;
var current_page_pangan = 1;
var records_per_page_isu = 4;
var records_per_page_pangan = 4;
var data_isu = {};
var data_pangan = {};

function loadkegiatan(){
  $.ajax({
    method:'GET',
    dataType:'JSON',
    url:'loadkegiatan',
    success:function(result){
      data_kegiatan = result;
      console.log(data_kegiatan);
      // changePage_isu(1);
      let content = '';
      for (var i = 0; i < data_kegiatan.length; i++) {
        content += `<div class="col-md-4 news-item style-2">
                      <figure class="pic-hover">
                          <span class="center-xy"></span>
                          <img src="`+data_kegiatan[i].dokumen+`" class="img-responsive" alt="">
                      </figure>

                      <div class="inner">
                          <div class="date">
                              <span class="day">18</span>
                              <span class="month">Aug</span>
                          </div>

                          <div class="desc">
                              <a href="index.html#">
                                  <h4>Gocargo Says Happy New Years</h4>
                              </a>
                              Etiam pharetra, erat sed fermentum feugiat, velit mauris egest...
                              <br>
                          </div>
                      </div>
                  </div>`;
      }

      $('#view-kegiatan').html(content);
    }
  })
};


    function changePage_isu(page)
    {

    var btn_next = document.getElementById("btn_next_isu");
    var btn_prev = document.getElementById("btn_prev_isu");
    // var listing_table = document.getElementById("listingTable");
    var page_span = document.getElementById("page_isu");

    // Validate page
    if (page < 1) page = 1;
    if (page > numPages_isu()) page = numPages_isu();

    // listing_table.innerHTML = "";

    const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
      "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
    ];

    var content = "";
    for (var i = (page-1) * records_per_page_isu; i < (page * records_per_page_isu) && i < data_isu.length; i++) {

        content +=
          `<div class="col-md-6 d-flex align-items-stretch mt-4">
            <div class="card" style='background-image: url("`+data_isu[i].file+`");border:2px solid #00afef;'>
              <div class="card-body">
                <h5 class="card-title"><a href="">`+data_isu[i].judul+`</a></h5>
                <p class="card-text">`+data_isu[i].deskripsi.substr(0,150)+`</p>
                <div class="read-more"><a href="#!" id="`+data_isu[i].id+`" class="listIssue"><i class="icofont-arrow-right"></i> Read More</a></div>
              </div>
            </div>
          </div>`;
    }
    page_span.innerHTML = page;
    $('#list-issue').html(content);

    if (page == 1) {
        btn_prev.style.visibility = "hidden";
    } else {
        btn_prev.style.visibility = "visible";
    }

    if (page == numPages_isu()) {
        btn_next.style.visibility = "hidden";
    } else {
        btn_next.style.visibility = "visible";
    }
  }

    function changePage_pangan(page)
    {

    var btn_next = document.getElementById("btn_next_pangan");
    var btn_prev = document.getElementById("btn_prev_pangan");
    // var listing_table = document.getElementById("listingTable");
    var page_span = document.getElementById("page_pangan");

    // Validate page
    if (page < 1) page = 1;
    if (page > numPages_pangan()) page = numPages_pangan();

    // listing_table.innerHTML = "";

    const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
      "Jul", "Agu", "Sep", "Okt", "Nov", "Des"
    ];

    var x = {};
    var content = "";
    for (var i = (page-1) * records_per_page_pangan; i < (page * records_per_page_pangan) && i < data_pangan.length; i++) {
      var obj = data_pangan[i];

      if (x[obj.nama_varietas] === undefined)
          x[obj.nama_varietas] = [obj.nama_varietas];

      x[obj.nama_varietas].push(obj.nama_varietas);
      content +=
      `<div class="col-lg-4 col-md-6 portfolio-item `+obj.nama_varietas+`">
        <div class="portfolio-img"><img src="`+obj.foto+`" class="img-fluid" alt=""></div>
        <div class="portfolio-info">
          <h4>`+obj.nama_varietas+`</h4>
          <p>App</p>
          <a href="`+obj.foto+`" data-gall="portfolioGallery" class="venobox preview-link" title="App 1"><i class="bx bx-plus"></i></a>
          <a href="#!" data-toggle="modal" data-target="#myModal" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
        </div>
      </div>`;
    }
    page_span.innerHTML = page;
    $('#content-filter').html(content);

    if (page == 1) {
        btn_prev.style.visibility = "hidden";
    } else {
        btn_prev.style.visibility = "visible";
    }

    if (page == numPages_pangan()) {
        btn_next.style.visibility = "hidden";
    } else {
        btn_next.style.visibility = "visible";
    }
  }

    function prevPage_isu()
    {
        if (current_page_isu > 1) {
            current_page_isu--;
            changePage_isu(current_page_isu);
        }
    }

    function nextPage_isu()
    {

        if (current_page_isu < numPages_isu()) {

            current_page_isu++;
            changePage_isu(current_page_isu);
        }
    }

    function prevPage_pangan()
    {
        if (current_page_pangan > 1) {
            current_page_pangan--;
            changePage_pangan(current_page_pangan);
        }
    }

    function nextPage_pangan()
    {

        if (current_page_pangan < numPages_pangan()) {

            current_page_pangan++;
            changePage_pangan(current_page_pangan);
        }
    }

    function numPages_isu()
    {
        return Math.ceil(data_isu.length / records_per_page_isu);
    }

    function numPages_pangan()
    {
        return Math.ceil(data_pangan.length / records_per_page_pangan);
    }


function loadpangan(){
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: 'loadpangan',
        data : {
                param      : '',
         },
        success: function(result){
          data_pangan = result;
          changePage_pangan(1);
        var x = {};
        var cont = '';
        for (var i = 0; i < result.length; ++i) {
            var obj = result[i];

            if (x[obj.nama_varietas] === undefined)
                x[obj.nama_varietas] = [obj.nama_varietas];

            x[obj.nama_varietas].push(obj.nama_varietas);
            cont +=
            `<div class="col-lg-4 col-md-6 portfolio-item `+obj.nama_varietas+`">
              <div class="portfolio-img"><img src="`+obj.foto+`" class="img-fluid" alt=""></div>
              <div class="portfolio-info">
                <h4>`+obj.nama_varietas+`</h4>
                <p>App</p>
                <a href="`+obj.foto+`" data-gall="portfolioGallery" class="venobox preview-link" title="App 1"><i class="bx bx-plus"></i></a>
                <a href="#!" data-toggle="modal" data-target="#myModal" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
              </div>
            </div>`;
        }

        let varietas = Object.keys(x);
        var ul = `<li class="filter-active" onclick="filterSelection('all')" id="all">Semua</li>`;

        for (var i = 0; i < varietas.length; i++) {

          if(varietas[i] != 'null'){
            ul += `<li onclick="filterSelection('`+varietas[i]+`')" id="`+varietas[i]+`">`+varietas[i]+`</li>`;
          }
        }

        for (var i = 0; i < result.length; ++i) {
            console.log();
        }


        $('#pilih-filter').append(ul);
        // $('#content-filter').append(cont);
        filterSelection("all");


        }
    });
}


function filterSelection(c) {
  $('.portfolio-item').removeAttr("style");
  $('#pilih-filter > li').removeAttr("class");
  $('#'+ c).attr('class', 'filter-active');
  var x, i;
  x = document.getElementsByClassName("portfolio-item");
  if (c == "all") c = "";

  // Add the "show" class (display:block) to the filtered elements, and remove the "show" class from the elements that are not selected
  for (i = 0; i < x.length; i++) {

    if (x[i].className.indexOf(c) > -1){
      w3AddClass(x[i], "show");
      w3RemoveClass(x[i], "hide");

    }else{
      w3AddClass(x[i], "hide");
      w3RemoveClass(x[i], "show");

    }
  }
}

// Show filtered elements
function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {
      element.className += " " + arr2[i];
    }
  }
}

// Hide elements that are not selected
function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");

  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {

      arr1.splice(arr1.indexOf(arr2[i]), 1);
    }
  }
  element.className = arr1.join(" ");
}
