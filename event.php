<?php
include("includes/head.php");
?>

<body class="event">
    <?php require_once "includes/header.php"; ?>
    <?php
    $id = $_GET["id"];

    $event = $db->getQuery("SELECT * FROM this_day.base WHERE id='" . $id . "'")[0];

    if (!isset($event)) {
        echo "<div class='container'><div>Такого события не существует, перейдите на <a href='index.php'>главную страницу</a></div></div>";
        exit();
    }
    ?>
    <div class="container">
        <div class="row event-header mb-4">
            <div class="col">
                <div class="event-header_photo text-center">
                    <img src="<?php echo $event->img1; ?>">
                </div>
            </div>
        </div>
        <div class="row event-body mb-4">
            <div class="col">
                <div class="event-body_text">
                    <p>
                        <?php echo $event->long_text; ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row event_collage mb-5">
            <?php
            $imgs = array();
            $imgs[] = $event->img1;
            $imgs[] = $event->img2;
            $imgs[] = $event->img3;

            foreach ($imgs as $val) {
                echo "<div class='col-4'>
                        <div class='event_collage-photo'>
                            <img src='" . $val . "'>
                        </div>
                    </div>";
            }
            ?>
        </div>
    </div>


</body>

</html>