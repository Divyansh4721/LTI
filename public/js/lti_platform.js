$(document).ready(function () {
    $("#searchit").on("keyup", function () {
      var value = $(this).val().toLowerCase();
      $("#platformlist tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      });
    });
  });
  var myModal = document.getElementById("myModal");
  var myModal1 = document.getElementById("myModal1");
  var myInput = document.getElementById("myInput");
  
  myModal.addEventListener("shown.bs.modal", function () {
    myInput.focus();
  });
  myModal1.addEventListener("shown.bs.modal", function () {
    myInput.focus();
  });
  
  $(function () {
    $(".toggle-class").change(function () {
      var enabled = $(this).prop("checked") == true ? 1 : 0;
      var platform_id = $(this).data("platform_id");
      $.ajax({
        type: "GET",
        dataType: "json",
        url: "/changeStatus",
        data: { enabled: enabled, platform_id: platform_id },
        success: function (data) {
          console.log(data.success);
        },
      });
    });
  });
  
  $(window).load(function () {
    $(".actionButton").click(function () {
      var platform_id = $(this).data("platform_id");
      $dropdown = $("#contextMenu" + platform_id);
      var id = $(this).closest("tr").children().first().html();
      $(this).after($dropdown);
      $dropdown
        .find(".payLink")
        .attr("href", "/transaction/pay?id=" + platform_id);
      $dropdown
        .find(".delLink")
        .attr("href", "/transaction/delete?id=" + platform_id);
      $(this).dropdown();
    });
  });
  
  $(".myBtn").click(function () {
    var client = $(this).data("client");
    $.ajax({
      type: "GET",
      dataType: "json",
      url: "ltilaunch/jwks/" + client,
      success: function (data) {
        $("#showkeys").html(JSON.stringify(data.keys).replace(/[\[\]']+/g, ""));
        $("#myModal").show();
      },
    });
  });
  
  var modal = document.getElementById("myModal");
  var span = document.getElementsByClassName("jwkey_close")[0];
  
  span.onclick = function () {
    modal.style.display = "none";
  };
  
  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };
  
  $("#myBtn1").click(function () {
    $("#myModal1").show();
  });
  var modal1 = document.getElementById("myModal1");
  var span1 = document.getElementsByClassName("report_close")[0];
  
  span1.onclick = function () {
    modal1.style.display = "none";
  };
  
  window.onclick = function (event) {
    if (event.target == modal1) {
      modal1.style.display = "none";
    }
  };
  
  //Confirm Delete
  var elems = document.getElementsByClassName("delete_confirmation");
  var confirmIt = function (e) {
    if (!confirm("Are you sure do you want to delete a platform?"))
      e.preventDefault();
  };
  for (var i = 0, l = elems.length; i < l; i++) {
    elems[i].addEventListener("click", confirmIt, false);
  }
  
  $("document").ready(function () {
    $("p").click(function () {
      let id = this.id;
      let value = $("#showkeys").val();
      let $temp = $("<input>");
      $("body").append($temp);
      $temp.val(value).select();
      document.execCommand("copy");
      $temp.remove();
      setTimeout(function () {
        $("#copied_tip").remove();
      }, 800);
      $("#" + id).append("<div class='tip' id='copied_tip'>Copied!</div>");
    });
  });
  
  new DataTable("#datatableid", {
    layout: {
      topStart: {
        buttons: [
          {
            extend: "copyHtml5",
            text: '<i class="fa fa-files-o"> Copy</i>',
            titleAttr: "Copy",
          },
          {
            extend: "excelHtml5",
            text: '<i class="fa fa-file-excel-o"> Excel</i>',
            titleAttr: "Excel",
          },
          {
            extend: "csvHtml5",
            text: '<i class="fa fa-file-text-o"> CSV</i>',
            titleAttr: "CSV",
          },
          {
            extend: "pdfHtml5",
            text: '<i class="fa fa-file-pdf-o"> PDF</i>',
            titleAttr: "PDF",
          },
          // {
          //     extend: 'pdfHtml5',
          //     text: '<i class="fa fa-file-pdf-o"></i>',
          //     titleAttr: 'PDF'
          // }
        ],
        // 'copyHtml5', 'pdfHtml5'
      },
    },
    //  "paging": false,
    // "ordering": false,
    searching: false,
  });
  