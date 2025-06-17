function CalDay(start, end) {
  var oneDay = 24 * 60 * 60 * 1000; // hours*minutes*seconds*milliseconds
  var firstDate = new Date(start);
  var secondDate = new Date(end);

  var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime()) / (oneDay)));

  return diffDays;
}

$(document).ready(function () {
  $("main.main-content").scroll(function () {
    sessionStorage.scrollTop = $(this).scrollTop();
  });

  if (sessionStorage.scrollTop != "undefined") {
    $("main.main-content").scrollTop(sessionStorage.scrollTop);
  }

  $(".sidebar").scroll(function () {
    sessionStorage.scrollTopSidebar = $(this).scrollTop();
  });

  if (sessionStorage.scrollTop != "undefined") {
    $(".sidebar ").scrollTop(sessionStorage.scrollTopSidebar);
  }
});

function goSubmitRequired(act, id_required, id_form) {
  if (id_form == undefined)
    id_form = '#main_form';

  if (id_required != undefined) {
    var cek = $(id_required).val();
    if (cek.trim() == '') {
      alert("Data harus isi lengkap");
      return false;
    }
  }

  $(id_form + " #act").val(act);
  $(id_form).submit();
}

function goSubmit(act, id_form) {
  if (id_form == undefined)
    id_form = '#main_form';

  $(id_form + " #act").val(act);
  $(id_form).submit();
}

function goSubmitConfirm(act, id_form) {
  if (id_form == undefined)
    id_form = '#main_form';

  if (confirm("Apakah Anda akan melanjutkan ?")) {
    $(id_form + " #act").val(act);
    $(id_form).submit();
  } else {
    return false;
  }
}

function goSubmitValue(act, key, id_form) {
  if (id_form == undefined)
    id_form = '#main_form';

  $(id_form + " #act").val(act);
  $(id_form + " #idkey").val(key);
  $(id_form).submit();
}

function goGo(go, act, id_form) {
  if (id_form == undefined)
    id_form = '#main_form';

  if (act == undefined)
    act = 'save';

  $(id_form).attr("target", "_blank");

  $("#go").val(go);

  goSubmit(act, id_form)

  $("#go").val('');

  $(id_form).removeAttr("target");
  //return false;
}

function previos(url) {
  window.location.replace(url);
}

function addCommas(n) {
  var rx = /(\d+)(\d{3})/;
  return String(n).replace(/^\d+/, function (w) {
    while (rx.test(w)) {
      w = w.replace(rx, '$1,$2');
    }
    return w;
  });
}

function validDigits(n, dec) {
  n = n.replace(/[^\d\.]+/g, '');
  var ax1 = n.indexOf('.'), ax2 = -1;
  if (ax1 != -1) {
    ++ax1;
    ax2 = n.indexOf('.', ax1);
    if (ax2 > ax1) n = n.substring(0, ax2);
    if (typeof dec === 'number') n = n.substring(0, ax1 + dec);
  }
  return n;
}


// var run_task = function (){ 
//     $.ajax({
//       url:site_url('panelbackend/ajax/notif'),
//       success:function(d){
//         try{
//           $("#task_count").text(d.count);
//           var task_data = '';
//           for(i=0;i<d.content.length;i++){
//             var d1 = d.content[i];
//               task_data +="<li>"
//                 +"  <a href=\""+site_url(d1.url)+"\" class=\"waves-effect waves-block\">"
//                 +"      <div class=\"icon-circle bg-"+d1.bg+"\">"
//                 +"          <i class=\"material-icons\">"+d1.icon+"</i>"
//                 +"      </div>"
//                 +"      <div class=\"menu-info\">"
//                 +"          <p class=\"info\">"+d1.info+"</p>"
//                 +"          <p>"
//                 +"              <i class=\"material-icons\">access_time</i> "+d1.time
//                 +"              <i class=\"material-icons\">account_circle</i> "+d1.user
//                 +"          </p>"
//                 +"      </div>"
//                 +"  </a>"
//               +"</li>";
//           }
//           $("#task_data").html(task_data);
//         }catch(e){

//         }
//       },
//       dataType:'json'
//     });
//   };

// $(function(){
//   setInterval(run_task, 3000);
// });


/* global bootstrap: false */
(function () {
  'use strict'
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  tooltipTriggerList.forEach(function (tooltipTriggerEl) {
    new bootstrap.Tooltip(tooltipTriggerEl)
  })
})()


// const controller = document.querySelector('input[type=range]');
// const radialProgress = document.querySelector('.RadialProgress');
$(".RadialProgress").each(function () {
  var radialProgress = $(this).get(0);
  if (radialProgress) {
    var id = null;
    const setProgress = (progress) => {
      const value = `${progress}%`;
      radialProgress.style.setProperty('--progress', value)
      radialProgress.innerHTML = "<span>" + value + "</span>";
      radialProgress.setAttribute('aria-valuenow', value)
    }

    var pos = 0;
    var max = radialProgress.getAttribute('aria-valuenow');
    clearInterval(id);
    id = setInterval(frame, 15);
    function frame() {
      if (pos == max) {
        clearInterval(id);
      } else {
        pos++;
        setProgress(pos)
      }
    }
  }
})

// controller.oninput = () => {
//   setProgress(controller.value)
// }


$("#cari_risikoinput").keyup(function (e) {
  $("#cari_risiko").val($("#cari_risikoinput").val());
  if (event.keyCode === 13) {
    goSubmit("cari_risiko");
  }
});



// function formatRepo(repo) {
//   if (repo.loading) {
//     return repo.text;
//   }

//   var $container = $(
//     "<div class='select2-result-repository clearfix'>" +
//     "<div class='select2-result-repository__meta'>" +
//     "<div class='select2-result-repository__title'></div>" +
//     "<div class='select2-result-repository__description'></div>" +
//     "</div>" +
//     "</div>"
//   );

//   $container.find(".select2-result-repository__title").text(repo.nomor);
//   $container.find(".select2-result-repository__description").text(repo.nama);

//   return $container;
// }

function formatRepo(state) {
  if (!state.nomor) {
    return state.nama;
  }

  var $state = $(
    '<span>' + state.nama + '</span>'
  );
  return $state;
};

function formatRepoSelection(repo) {
  return repo.nomor;
}



// var firstEmptySelect = true;
// function formatRepo(result) {
//   if (!result.id) {
//     if (firstEmptySelect) {
//       console.log('showing row');
//       firstEmptySelect = false;
//       return '<div class="row">' +
//         '<div class="col-xs-3"><b>No.</b></div>' +
//         '<div class="col-xs-3"><b>Nama</b></div>' +
//         '</div>';
//     } else {
//       console.log('skipping row');
//       return false;
//     }
//   }
//   return '<div class="row">' +
//     '<div class="col-xs-3">' + result.nomor + '</div>' +
//     '<div class="col-xs-3">' + result.nama + '</div>' +
//     '</div>';
// }


$(function () {
  if ($.isFunction($.fn.select2)) {
    $(".select2risikounit").select2({
      ajax: {
        processResults: function (data, params) {
          return {
            results: data
          };
        },
        cache: true
      },
      templateResult: formatRepo,
      templateSelection: formatRepoSelection,
      placeholder: 'Pilih',
      allowClear: true,
    });
  }
})

let menuExpanded = true;
$(".icon-expand-minimize-sidebar").click(function () {
  SidebarOnOff()
})

$(".overlay-sidebar-right").click(function () {
  SidebarOnOff()
})

function SidebarOnOff() {
  if ($(".container-fluid.content").hasClass("expanded")) {
    $(".container-fluid.content").removeClass("expanded").addClass("minimized")
  } else {
    $(".container-fluid.content").removeClass("minimized").addClass("expanded")

  }
}

$(function () {
  if ($(".dataTable-header-fix").length == 0) {
    var el = $(".dataTable thead").clone();
    $(el).css({ "position": "fixed", "top": "55px", "background": "#fff", "margin-left": "-0.5px", "display": "none" });
    $(el).addClass("dataTable-header-fix");
    $(el).children("#first-row").remove();
    $(el).children("script").remove();
    $(el).children("tr").children("input").remove();
    $(".dataTable").append($(el).get(0));
  }
});

var padarr = new Array();
$("main.main-content").scroll(() => {
  padarr = new Array();
  $(".dataTable thead tr th").each(function () {
    var padding = $(this).css("padding").replace(/[^-\d\.]/g, '') * 2;
    var width = $(this).width();
    padarr.push(padding + width + 2);
  })

  if ($("main.main-content").scrollTop() >= 140) {
    $(".dataTable-header-fix").show();
    $(".dataTable thead.dataTable-header-fix tr th").each(function (idx) {
      $(this).css({ "width": (padarr[idx]) + "px" });
    })
  } else {
    $(".dataTable-header-fix").hide();
  }
})




function hrefAjaxModal(urlajax, cb) {
  if (urlajax == undefined)
    urlajax = null;

  if (urlajax !== undefined && urlajax !== null)
    $('#main_form_modal #urlajax').val(urlajax);
  else
    urlajax = $('#main_form_modal #urlajax').val();

  var content = "#modalbody";

  $("#btnsavemodal").html('');
  $("#btnbackmodal").html('');

  $.ajax({
    dataType: 'html',
    method: 'get',
    url: urlajax,
  }).done(function (html1) {
    if (cb !== undefined)
      cb(html1);
    else
      $(content).html(html1);
  });
}

function goSubmitAjax(urlajax, act, content, cb, data, id_form) {
  if (urlajax == undefined)
    urlajax = null;

  if (id_form == undefined)
    id_form = '#main_form';

  if (content == undefined)
    content = "#contentajax";

  if (data == undefined)
    data = {};

  var data1 = $(id_form).serializeArray();

  for (const key in data1) {
    data[data1[key].name] = data1[key].value;
  }

  data['act'] = act;

  $.ajax({
    dataType: 'html',
    method: 'post',
    url: urlajax,
    data: data,
  }).done(function (html1) {
    if (cb !== undefined)
      cb(html1);
    else if ($(content).get(0))
      $(content).html(html1);
  });
}

function goSubmitModalValue(urlajax, act, val) {
  $("#main_form_modal #key").val(val);
  goSubmitModal(urlajax, act);
}

function goSubmitModal(urlajax, act, title, call, data) {
  if (data == undefined)
    data = {};

  if (act)
    $('#main_form_modal #form').val(act);
  else
    act = $('#main_form_modal #form').val();

  // if (act == 'edit') {
  //   $("#btnsavemodal").show();
  // } else if (act != 'set_value') {
  $("#btnsavemodal").html('');
  $("#btnbackmodal").html('');
  // }

  if (urlajax != null) {
    if ($('#modalcontent').hasClass('in') == false) {
      $('#modalbody').html("");
    }
  }

  if (urlajax !== undefined && urlajax !== null)
    $('#main_form_modal #urlajax').val(urlajax);
  else
    urlajax = $('#main_form_modal #urlajax').val();

  goSubmitAjax(urlajax, act, '#modalbody', function (html) {
    $("#modaltitle").html(title);
    $("#modalcontent").modal("show");

    if (call != undefined)
      call(html);
    else {
      try {
        var json = JSON.parse(html);
        if (json.redirect) {
          hrefAjaxModal(json.redirect);
        } else if (json.success) {
          alert("Sukses");
        } else if (json.error)
          alert(json.error);
        else {
          alert(html);
        }
      } catch (e) {
        $('#modalbody').html(html);
      }
    }

  }, data, '#main_form_modal');
}

function goSaveModal(act) {
  var data = {};

  var urlajax = $('#main_form_modal #urlajax').val();

  goSubmitAjax(null, "save", undefined, function (html1) {
    try {
      var json = JSON.parse(html1);

      if (json.success) {
        $("#" + json.data.key).val(json.data.val);
        data[json.data.key] = json.data.val;
        goSubmitAjax(urlajax, act, "#modalbody", function (html1) {
          try {
            var json = JSON.parse(html1);

            if (json.success) {
              $("#modalcontent").modal("hide");
              $("#modalcontent").on('hidden.bs.modal', function (e) {
                $('#modalbody').html('');
                goSubmitAjax();
              });
            } else {
              alert(html1);
            }
          } catch (e) {
            $('#modalbody').html(html1);
          }

        }, data, '#main_form_modal');
      } else if (json.error)
        alert(json.error);
      else {
        alert(html1);
      }
    } catch (e) {
      $("#modalcontent").modal("hide");
      $("#modalcontent").on('hidden.bs.modal', function (e) {
        $('#contentajax').html(html1);
        $(window).scrollTop(0);
      });
    }
  });
}


function goSaveModalInline() {
  var urlajax = $('#main_form_modal #urlajax').val();
  goSubmitAjax(urlajax, "save", undefined, function (html) {
    try {
      var json = JSON.parse(html);
      if (json.redirect) {
        hrefAjaxModal(json.redirect);
      } else if (json.success) {
        alert("Sukses");
      } else if (json.error)
        alert(json.error);
      else {
        alert(html);
      }
    } catch (e) {
      $('#modalbody').html(html);
    }
  }, {}, '#main_form_modal');
}

function goDeleteAjax(urlajax) {
  goSubmitAjax(urlajax, "delete", undefined, function (html1) {
    try {
      var json = JSON.parse(html1);

      if (json.success) {
        goSubmitAjax();
      } else if (json.error)
        alert(json.error);
      else {
        alert(html1);
      }
    } catch (e) {
      goSubmitAjax();
    }
  });
}

$.fn.serializeObject = function () {
  var o = {};
  var a = this.serializeArray();
  $.each(a, function () {
    if (o[this.name]) {
      if (!o[this.name].push) {
        o[this.name] = [o[this.name]];
      }
      o[this.name].push(this.value || '');
    } else {
      o[this.name] = this.value || '';
    }
  });
  return o;
};
















function goOpenModal(urlajax, title) {
  if (urlajax == undefined)
    urlajax = null;

  if (urlajax !== undefined && urlajax !== null)
    $('#main_form_modal #urlajax').val(urlajax);
  else
    urlajax = $('#main_form_modal #urlajax').val();

  var content = "#modalbody";

  $("#btnsavemodal").html('');
  $("#btnbackmodal").html('');

  $.ajax({
    dataType: 'html',
    method: 'get',
    data: { "ismodal": 1 },
    url: urlajax,
  }).done(function (html1) {
    $("#modaltitle").html(title);
    $("#modalcontent").modal("show");
    $(content).html(html1);
  });
}

function hrefAjaxModal(urlajax, cb) {
  if (urlajax == undefined)
    urlajax = null;

  if (urlajax !== undefined && urlajax !== null)
    $('#main_form_modal #urlajax').val(urlajax);
  else
    urlajax = $('#main_form_modal #urlajax').val();

  var content = "#modalbody";

  $("#btnsavemodal").html('');
  $("#btnbackmodal").html('');

  $.ajax({
    dataType: 'html',
    method: 'get',
    data: { "ismodal": 1 },
    url: urlajax,
  }).done(function (html1) {
    if (cb !== undefined)
      cb(html1);
    else
      $(content).html(html1);
  });
}

function goSubmitModalValue(urlajax, act, val) {
  $("#main_form_modal #key").val(val);
  goSubmitModal(urlajax, act);
}

function goSubmitModalSave(urlajax, act, title, call, data) {
  goSubmitAjax(null, "save", undefined, function (html1) {
    try {
      var json = JSON.parse(html1);

      if (json.success) {
        if (json.data && json.data.key)
          $("#" + json.data.key).val(json.data.val);
        goSubmitModal(urlajax, act, title, call, data);
      } else if (json.error)
        alert(json.error);
      else {
        alert(html1);
      }
    } catch (e) {
      $('#mainajax').html(html1);
      $(window).scrollTop(0);
    }
  });
}


function goSubmitAjax(urlajax, act, content, cb, data, id_form) {
  if (urlajax == undefined)
    urlajax = null;

  if (id_form == undefined)
    id_form = '#main_form';

  if (content == undefined)
    content = "#mainajax";

  if (data == undefined)
    data = {};

  var data1 = $(id_form).serializeArray();

  for (const key in data1) {
    data[data1[key].name] = data1[key].value;
  }

  data['act'] = act;

  $.ajax({
    dataType: 'html',
    method: 'post',
    url: urlajax,
    data: data,
  }).done(function (html1) {
    if (cb !== undefined)
      cb(html1);
    else {
      try {
        var json = JSON.parse(html1);
        if (json.redirect) {
          hrefAjax(json.redirect);
        } else if (json.success) {
          $("#" + json.data.key).val(json.data.val);
        } else if (json.error)
          alert(json.error);
        else {
          alert(html1);
        }
      } catch (e) {
        $(content).html('');
        $(content).html(html1);
      }

    }

  });
}

function goSubmitModal(urlajax, act, title, call, data) {
  if (data == undefined)
    data = {};

  data.ismodal = 1;

  if (act)
    $('#main_form_modal #form').val(act);
  else
    act = $('#main_form_modal #form').val();

  // if (act == 'edit') {
  //   $("#btnsavemodal").show();
  // } else if (act != 'set_value') {
  $("#btnsavemodal").html('');
  $("#btnbackmodal").html('');
  // }

  if (urlajax != null) {
    if ($('#modalcontent').hasClass('in') == false) {
      $('#modalbody').html("");
    }
  }

  if (urlajax !== undefined && urlajax !== null)
    $('#main_form_modal #urlajax').val(urlajax);
  else
    urlajax = $('#main_form_modal #urlajax').val();

  goSubmitAjax(urlajax, act, '#modalbody', function (html) {
    $("#modaltitle").html(title);
    $("#modalcontent").modal("show");

    if (call != undefined)
      call(html);
    else {
      try {
        var json = JSON.parse(html);
        if (json.redirect) {
          hrefAjaxModal(json.redirect);
        } else if (json.success) {
          alert("Sukses");
        } else if (json.error)
          alert(json.error);
        else {
          alert(html);
        }
      } catch (e) {
        $('#modalbody').html(html);
      }
    }

  }, data, '#main_form_modal');
}

function goSaveModal(act) {
  var data = {};

  var urlajax = $('#main_form_modal #urlajax').val();

  goSubmitAjax(null, "save", undefined, function (html1) {
    try {
      var json = JSON.parse(html1);

      if (json.success) {
        $("#" + json.data.key).val(json.data.val);
        data[json.data.key] = json.data.val;
        goSubmitAjax(urlajax, act, "#modalbody", function (html1) {
          try {
            var json = JSON.parse(html1);

            if (json.success) {
              $("#modalcontent").modal("hide");
              $("#modalcontent").on('hidden.bs.modal', function (e) {
                $('#modalbody').html('');
                goSubmitAjax();
              });
            } else {
              alert(html1);
            }
          } catch (e) {
            $('#modalbody').html(html1);
          }

        }, data, '#main_form_modal');
      } else if (json.error)
        alert(json.error);
      else {
        alert(html1);
      }
    } catch (e) {
      $("#modalcontent").modal("hide");
      $("#modalcontent").on('hidden.bs.modal', function (e) {
        $('#mainajax').html(html1);
        $(window).scrollTop(0);
      });
    }
  });
}


function goSaveModalInline() {
  var urlajax = $('#main_form_modal #urlajax').val();
  goSubmitAjax(urlajax, "save", undefined, function (html) {
    try {
      var json = JSON.parse(html);
      if (json.redirect) {
        hrefAjaxModal(json.redirect);
      } else if (json.success) {
        window.location = "";
      } else if (json.error)
        alert(json.error);
      else {
        alert(html);
      }
    } catch (e) {
      $('#modalbody').html(html);
    }
  }, { "ismodal": 1 }, '#main_form_modal');
}


function hrefAjax(urlajax) {
  if (urlajax == undefined)
    urlajax = null;

  $.ajax({
    dataType: 'html',
    method: 'get',
    url: urlajax,
  }).done(function (html) {
    try {
      var json = JSON.parse(html);
      if (json.redirect) {
        hrefAjax(json.redirect);
      } else if (json.success) {
        window.location = "";
      } else if (json.error)
        alert(json.error);
      else {
        alert(html);
      }
    } catch (e) {
      $('#mainajax').html(html);
    }
  });
}

function goSaveInline() {
  goSubmitAjax(null, "save", undefined, function (html) { });
}

function goDeleteAjax(urlajax) {
  goSubmitAjax(urlajax, "delete", undefined, function (html1) {
    try {
      var json = JSON.parse(html1);

      if (json.success) {
        goSubmitAjax();
      } else if (json.error)
        alert(json.error);
      else {
        alert(html1);
      }
    } catch (e) {
      goSubmitAjax();
    }
  });
}