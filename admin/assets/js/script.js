var element = document.getElementById('date');
var maskOptions = {
    mask: '0000-00-00',
    lazy: false
} 
var mask = new IMask(element, maskOptions);

$("#btnAddImgGroup").on("click", function() {
    let len = 0;
	$(".img-group").each(function () {
        len = $(this).find('.img-group_row').length;
    });

    $(".img-group").append(`<div class="img-group_row">
        <div class="toggle-group my-2">
            <div class="form-check form-check-inline ">
                <input class="form-check-input toggle-img active" type="radio" name="option${len + 1}" id="option${len * 2 + 1}" value="file" checked="checked">
                <label class="form-check-label" for="option1">Файл</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input toggle-img" type="radio" name="option${len + 1}" id="option${len * 3 }" value="url">
                <label class="form-check-label" for="option2">URL</label>
            </div>
        </div>

        <div class="form-group row group-img align-items-center">
            <label class="col-sm-2 col-form-label" for="img1">Картинка</label>
            <input class="form-control col-sm-10 d-none v-center" name="url${len + 1}" type="url" id="img${len + 1}" placeholder="http://..." >
            <input type="file" name="file[]" id="img${len + 1}">
        </div>
    </div>`);

    if (len === 2) {
        $(this).remove();
    }
});

$(".img-group").on("click", ".toggle-img", function () {
    if (!$(this).hasClass("active")) {
        let parent = $(this).closest(".toggle-group");
        parent.find(".toggle-img").toggleClass("active");

        parent.siblings().find("input").toggleClass("d-none");

        // $(".group-img [id*='img']").toggleClass("d-none");
        // $("[class*='toggle-download-img']")
    }
});