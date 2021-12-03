<?php
require "includes/head.php";
?>

<body>
    <?php require_once "includes/header.php"; ?>
    <main>

        <?php

        $res = $db->searchEventOfThisDay("this_day", "localhost", "root", "", $day, $month);
        $events = [];

        foreach ($res as $row) {
            array_push($events, $row);
        }

        ?>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="slider-main">
                        <?php
                        $slideContent = array();
                        $text = "";

                        if (count($events) == 0) {
                            echo "<div class='events_no-events'>Событий сегодня нет</div>";
                        } else {
                            for ($i = 0; $i < count($events); $i++) {
                                $temp = '
                                    <div class="slide-content">
                                    <a href="event.php?id=' . $events[$i]["id"] . '">
                                        <img class="left-slide-img photo"  src="' . $events[$i]["img1"] . '" />
                                    </a>
                                    <p class="event-text">
                                    ' . $events[$i]["short_text"] . '
                                    </p>
                                    </div>';
                                $slideContent[] = $temp;
                            }


                            if (count($events) == 1) {
                                $text = $events[0]["short_text"];

                                echo '
                                <div class="center-slide slide" style="margin: 0 auto">
                                    ' . $slideContent[0] . '
                                </div>';
                            } else if (count($events) == 2) {
                                $text = $events[1]["short_text"];

                                echo '
                                <div class="left-slide subsidiary-slide slide">
                                        ' . $slideContent[0] . '
                                    <div class="slider_left-btn">
                                        <img class="left-arrow arrow" src="assets/image/Стрелка влево.png" />
                                    </div>
                                </div>
                                <div class="center-slide slide">
                                    ' . $slideContent[1] . '
                                </div>
                                <div class="right-slide subsidiary-slide slide">
                                    ' . $slideContent[0] . '
                                    <img class="right-arrow arrow" src="assets/image/Стрелка вправо.png" />
                                </div>
                                <div class="additional-slide slide d-none">
                                    ' . $slideContent[1] . '
                                </div>';
                            } else {
                                $text = $events[1]["short_text"];
                                echo '
                                <div class="left-slide subsidiary-slide slide">
                                    ' . $slideContent[0] . '
                                    <div class="slider_left-btn">
                                        <img class="left-arrow arrow" src="assets/image/Стрелка влево.png" />
                                    </div>
                                </div>
                                <div class="center-slide slide">
                                    ' . $slideContent[1] . '
                                </div>
                                <div class="right-slide subsidiary-slide slide">
                                    ' . $slideContent[2] . '
                                    <img class="right-arrow arrow" src="assets/image/Стрелка вправо.png" />
                                </div>';


                                if (count($events) > 3) {
                                    for ($i = 3; $i < count($events); $i++) {


                                        echo
                                            '<div class="additional-slide slide d-none">
                                            ' . $slideContent[$i] . '
                                        </div>';
                                    }
                                }
                            }
                        }

                        ?>
                    </div>

                    <?php echo '
                                <div class="main-page_event-text">
                                    <p>
                                    ' . $text . '
                                    </p>
                                </div> ';
                    ?>

                    <!-- <div class="slider-main">

                        <div class="left-slide subsidiary-slide slide">
                            <div class="slide-content">
                                <a href="#">
                                    <img class="left-slide-img photo" src="assets/image/unnamed.jpg" />
                                </a>
                            </div>
                            <div class="slider_left-btn">
                                <img class="left-arrow arrow" src="assets/image/Стрелка влево.png" />
                            </div>
                            <p class="event-text"></p>
                        </div>
                        <div class="center-slide slide">
                            <div class="slide-content">
                                <a href="#">
                                    <img class="center-slide-img photo" src="assets/image/шонин.png" />
                                </a>
                            </div>
                            <p class="event-text"></p>
                        </div>
                        <div class="right-slide subsidiary-slide slide">
                            <div class="slide-content">
                                <a href="#">
                                    <img class="right-slide-img photo" src="assets/image/unnamed (1).jpg" />
                                </a>
                            </div>
                            <img class="right-arrow arrow" src="assets/image/Стрелка вправо.png" />
                            <p class="event-text"></p>
                        </div>
                        <div class="additional-slide slide d-none">
                            <div class="slide-content">
                                <a href="#">
                                    <img class="right-slide-img photo" src="https://img.discogs.com/lxUSaPGPiJ9ATssSeEGR-t4Ofrg=/fit-in/300x300/filters:strip_icc():format(jpeg):mode_rgb():quality(40)/discogs-images/A-715919-1265047006.jpeg.jpg" />
                                </a>
                            </div>
                            <img class="right-arrow arrow" src="assets/image/Стрелка вправо.png" />
                            <p class="event-text"></p>
                        </div>
                    </div> -->
                    <!-- <div class="main-page_event-text">
                        <p>
                            Гео́ргий Степа́нович Шо́нин (3 августа 1935, Ровеньки — 6 апреля
                            1997, Звёздный городок) — советский космонавт № 17. Герой
                            Советского Союза (22 октября 1969). Генерал-лейтенант авиации
                            (1985). Почётный гражданин городов Вологда, Гагарин, Калуга
                            (Россия); Балта, Одесса, Ровеньки (Украина); Караганда
                            (Казахстан); Бричпорт (США).
                        </p>
                    </div> -->
                </div>
            </div>
        </div>
    </main>
    <script src="assets/script/slider.js"></script>
</body>

</html>