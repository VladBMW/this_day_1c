$(document).ready(function () {
  left();
  right();
});

function left() {
  $(".subsidiary-slide.left-slide").click(function () {
    let slider = $(".slider-main");
    let arraySlides = $(slider).children(".slide");
    let countEvent = arraySlides.length;

    let prev,
      current;

    for (let i = 0; i < countEvent; i++) {

      if (i === 0)
        prev = $(arraySlides[countEvent - 1]).find(".slide-content");
      else prev = current;

      current = $(arraySlides[i]).find(".slide-content");
      $(arraySlides[i]).find(".slide-content").remove();
      $(arraySlides[i]).prepend(prev);
    }

    $(".main-page_event-text").html($(".center-slide .event-text").html());
  });
}

function right() {
  $(".subsidiary-slide.right-slide").click(function () {
    let slider = $(".slider-main");
    let arraySlides = $(slider).children(".slide");
    let countEvent = arraySlides.length;

    let next,
      current;

    for (let i = countEvent - 1; i >= 0; i--) {
      if (i === countEvent - 1)
        next = $(arraySlides[0]).find(".slide-content");
      else next = current;

      current = $(arraySlides[i]).find(".slide-content");
      $(arraySlides[i]).find(".slide-content").remove();
      $(arraySlides[i]).prepend(next);
    }

    $(".main-page_event-text").html($(".center-slide .event-text").html());
  });
}