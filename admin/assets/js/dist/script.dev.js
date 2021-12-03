"use strict";

var element = document.getElementById('date');
var maskOptions = {
  mask: '0000-00-00',
  lazy: false
};
var mask = new IMask(element, maskOptions);
$("#btnAddImgGroup").on("click", function () {
  var len = 0;
  $(".img-group").each(function () {
    len = $(this).find('.img-group_row').length;
  });
  $(".img-group").append("<div class=\"img-group_row\">\n        <div class=\"toggle-group my-2\">\n            <div class=\"form-check form-check-inline \">\n                <input class=\"form-check-input toggle-img active\" type=\"radio\" name=\"option".concat(len + 1, "\" id=\"option").concat(len * 2 + 1, "\" value=\"file\" checked=\"checked\">\n                <label class=\"form-check-label\" for=\"option1\">\u0424\u0430\u0439\u043B</label>\n            </div>\n            <div class=\"form-check form-check-inline\">\n                <input class=\"form-check-input toggle-img\" type=\"radio\" name=\"option").concat(len + 1, "\" id=\"option").concat(len * 3, "\" value=\"url\">\n                <label class=\"form-check-label\" for=\"option2\">URL</label>\n            </div>\n        </div>\n\n        <div class=\"form-group row group-img align-items-center\">\n            <label class=\"col-sm-2 col-form-label\" for=\"img1\">\u041A\u0430\u0440\u0442\u0438\u043D\u043A\u0430</label>\n            <input class=\"form-control col-sm-10 d-none v-center\" name=\"url").concat(len + 1, "\" type=\"url\" id=\"img").concat(len + 1, "\" placeholder=\"http://...\" >\n            <input type=\"file\" name=\"file[]\" id=\"img").concat(len + 1, "\">\n        </div>\n    </div>"));

  if (len === 2) {
    $(this).remove();
  }
});
$(".img-group").on("click", ".toggle-img", function () {
  if (!$(this).hasClass("active")) {
    var parent = $(this).closest(".toggle-group");
    parent.find(".toggle-img").toggleClass("active");
    parent.siblings().find("input").toggleClass("d-none"); // $(".group-img [id*='img']").toggleClass("d-none");
    // $("[class*='toggle-download-img']")
  }
});