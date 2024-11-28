$(document).ready(function () {
    $(".container").hide();
    $(".preload").show();
    var status = 0;
  
    function loader() {
      var state = document.readyState;
      if (state == "complete") {
        status++;
        $(".preload").hide();
        $(".container").show();
      }
      if (status > 0) {
        clearInterval(loadInterval);
      }
    }
    var loadInterval = setInterval(loader, 1000);
  });
  
  var requestId = $("#request_id").val();
  var searchData = "";
  var fl_TopLevelContentDisplayName = "";
  var bookId = "";
  var back = "";
  var page = "";
  var clear = "";
  var title = "";
  var fl_SiteID = "";
  var chapterId = "";
  var contentType = "";
  var sectionType = "";
  var oldBookId = "";
  var oldTitle = "";
  var selectedAssets = [];
  
  $("document").ready(function () {
    $("#loader").show();
    fl_TopLevelContentDisplayName = "";
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  });
  
  $("#searchdata").click(function () {
    bookId = $("#hidden_Book_id").val();
    title = $("#hidden_Book_title").val();
    chapterId = $("#hidden_chapter_id").val();
    contentType = $("#hidden_content_type").val();
    sectionType = $("#hidden_section_type").val();
    searchData = $("#search").val();
    fl_TopLevelContentDisplayName = $("#fl_TopLevelContentDisplayName").val();
    fl_SiteID = $("#fl_SiteID").val();
    oldBookId = $("#hidden_old_book_id").val();
    oldTitle = $("#hidden_old_book_title").val();
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  });
  
  $("#fl_TopLevelContentDisplayName").change(function () {
    bookId = $("#hidden_Book_id").val();
    title = $("#hidden_Book_title").val();
    chapterId = $("#hidden_chapter_id").val();
    contentType = $("#hidden_content_type").val();
    sectionType = $("#hidden_section_type").val();
    fl_TopLevelContentDisplayName = $(this).val();
    searchData = $("#search").val();
    fl_SiteID = $("#fl_SiteID").val();
    oldBookId = $("#hidden_old_book_id").val();
    oldTitle = $("#hidden_old_book_title").val();
  
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  });
  
  $("#fl_SiteID").change(function () {
    $("#hidden_Book_id").val("");
    $("#hidden_Book_title").val("");
    $("#hidden_chapter_id").val("");
    $("#hidden_content_type").val("");
    $("#hidden_section_type").val("");
    $("#hidden_old_book_id").val("");
    $("#hidden_old_book_title").val("");
  
    bookId = $("#hidden_Book_id").val();
    title = $("#hidden_Book_title").val();
    chapterId = $("#hidden_chapter_id").val();
    contentType = $("#hidden_content_type").val();
    sectionType = $("#hidden_section_type").val();
    $("#hidden_flTopLevelContentDisplayName").val("");
    fl_TopLevelContentDisplayName = $(
      "#hidden_flTopLevelContentDisplayName",
    ).val();
    searchData = $("#search").val();
    fl_SiteID = $(this).val();
    oldBookId = $("#hidden_old_book_id").val();
    oldTitle = $("#hidden_old_book_title").val();
  
    $("#fl_TopLevelContentDisplayName").val("");
  
    if (fl_SiteID == 187) {
      $("#fl_TopLevelContentDisplayName").val(["Libros"]);
      $("#hidden_flTopLevelContentDisplayName").val("Libros");
      $("#fl_TopLevelContentDisplayName").val("Libros");
    } else if (fl_SiteID == 313) {
      $("#fl_TopLevelContentDisplayName").val(["Livros"]);
      $("#hidden_flTopLevelContentDisplayName").val("Livros");
      $("#fl_TopLevelContentDisplayName").val("Livros");
    } else {
      $("#fl_TopLevelContentDisplayName").val([""]);
      $("#hidden_flTopLevelContentDisplayName").val("");
      $("#fl_TopLevelContentDisplayName").val("");
    }
  
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  });
  
  $("#clear").click(function () {
    clear = "clear";
    searchData = "";
    fl_TopLevelContentDisplayName = ""; //Textbooks
    fl_SiteID = "";
    $("#fl_SiteID").val([""]);
    chapterId = "";
    contentType = "";
    sectionType = "";
    oldBookId = "";
    oldTitle = "";
    bookId = "";
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
    $("#search").val("");
    $("#fl_TopLevelContentDisplayName").val(""); //Textbooks
  });
  
  function fetch_details_object(
    contentType = null,
    bookId = null,
    chapterId = null,
    sectionType = null,
    title = null,
  ) {
    fl_SiteID = $("#fl_SiteID").val();
  
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  }
  
  function back_menu(el) {
    back = el;
    $("#fl_TopLevelContentDisplayName")
      .children("option[value^=Textbooks]")
      .show();
    $("#fl_TopLevelContentDisplayName").val(""); //Textbooks
    fl_TopLevelContentDisplayName = ""; //Textbooks
    bookId = "";
    $("#search").val("");
    searchData = "";
    chapterId = "";
    contentType = "";
    sectionType = "";
    oldBookId = "";
    oldTitle = "";
    fl_SiteID = $("#fl_SiteID").val();
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  }
  
  function paginate(page_num) {
    var page = page_num;
  
    fl_SiteID = $("#fl_SiteID").val();
    chapterId = $("#hidden_chapter_id").val();
    contentType = $("#hidden_content_type").val();
    sectionType = $("#hidden_section_type").val();
    getContent(
      requestId,
      searchData,
      fl_TopLevelContentDisplayName,
      fl_SiteID,
      bookId,
      title,
      back,
      page,
      clear,
      chapterId,
      contentType,
      sectionType,
      oldBookId,
      oldTitle,
      selectedAssets,
    );
  }
  
  function getContent(
    requestId,
    searchData,
    fl_TopLevelContentDisplayName,
    fl_SiteID,
    bookId,
    title,
    back,
    page,
    clear,
    chapterId,
    contentType,
    sectionType,
    oldBookId,
    oldTitle,
    selectedAssets,
  ) {
    $.ajax({
      url: launchContentUrl,
      type: "post",
      dataType: "json",
      data: {
        request_id: requestId,
        search: searchData,
        fl_TopLevelContentDisplayName: fl_TopLevelContentDisplayName,
        fl_SiteID: fl_SiteID,
        bookId: bookId,
        title: title,
        back: back,
        page: page,
        clear: clear,
        chapterId: chapterId,
        contentType: contentType,
        sectionType: sectionType,
        oldBookId: oldBookId,
        old_title: oldTitle,
        institutionId: institutionId,
        selectedAssets: selectedAssets,
      },
      cache: false,
      async: true,
      beforeSend: function (data, textStatus, jqXHR) {
        $("#loader").show();
      },
      success: function (response) {
        $("#loader").hide();
        $("#load-content").html(response.html);
  
        $.each(selectedAssets, function (i, val) {
          $("#" + val.UniqueId).prop("checked", true);
        });
      },
    });
  }
  