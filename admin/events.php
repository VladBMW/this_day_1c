<?php
include("includes/head.php");
function vardump($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}

if (!isset($_SESSION["logged_user"])) {
	include($root . "/404.php");
	die();
}

?>


<body>
	<div id="admin" class="admin">
		<div class="container-fluide">
			<?php include("includes/header.php") ?>
			<div class="body">
				<?php
    

				if (isset($_POST['insert'])) {
					$root = $_SERVER["DOCUMENT_ROOT"];
					$date = $_POST['date'];
					$shortText = $_POST['short-text'];
					$longText = $_POST['long-text'];
					$option1 = $_POST['option1'];
					$img1 = $_POST["url1"];

					$errors = array();

					if (trim($date) == "")
						$errors[] = "Поле 'Дата' пусто";
					if (trim($shortText) == "")
						$errors[] = "Поле 'Кратко для фото' пусто";
					if (trim($longText) == "")
						$errors[] = "Поле 'Текст' пусто";

					$options = array();
					$images = array();

					foreach ($_POST as $key => $value) {
						if (stripos($key, "option") !== false)
							$options[] = $value;
					}


					foreach ($options as $i => $value) {
						if ($options[$i] === "file") {
							if (!isset($_FILES['file']["name"][$i])) {
								$errors[] = "Файл не выбран";
								break;
							}

							$baseNameFile = new SplFileInfo($_FILES['file']["name"][$i]);
							$expansion = $baseNameFile->getExtension();

							$fotodir = $root . '/assets/image/events/'; // ПРИСВОЕНИЕ АЙДИ КОДУ
							$filesdir = scandir($fotodir, 1);
							$filesCount = count($filesdir) - 2;
							$fotoname = $filesCount . '.' . $expansion;
							$filename = $fotodir . "" . $fotoname;

							move_uploaded_file($_FILES["file"]['tmp_name'][$i], $filename);
							// $images[] = $filename;
							$images[] = "/assets/image/events/" . $fotoname;
						} else {
							if (empty($_POST["url" . ($i + 1)])) {
								$errors[] = "Путь к картинке не указан";
								break;
							}

							$baseNameFile = new SplFileInfo($_POST["url" . ($i + 1)]);
							$expansion = $baseNameFile->getExtension();

							$fotodir = $root . '/assets/image/events/'; // ПРИСВОЕНИЕ АЙДИ КОДУ
							$filesdir = scandir($fotodir, 1);
							$filesCount = count($filesdir) - 2;
							$fotoname = $filesCount . '.' . $expansion;

							$source = $_POST["url" . ($i + 1)];
							$dest = $fotodir . $fotoname;
                            vardump($source);
                             vardump($dest);

							copy($source, $dest);
							// $images[] = $dest;
                          
							$images[] = "/assets/image/events/" . $fotoname;
						}
					}

					if (!empty($errors)) {
						echo "
						<div class='card-footer'>
							<p style='color: red;'>" . array_shift($errors) . "</p>
						</div>
						";
					} else {
						// var_dump($images);

						$sql = "INSERT INTO base (date, long_text, short_text";
						$columns = "";
						$values = "";

						for ($i = 1; $i <= count($images); $i++) {
							$columns .= ", img" . $i;
							$values .= ", '" . $images[$i - 1] . "'";
						}

						$sql .= $columns;
						$sql .= ") VALUES ('$date', '$longText', '$shortText'";
						$sql .= $values;
						$sql .= ");";

						if ($db->setQuery($sql)) {
							print("<p style='color: darkgreen; font-weight: bold;'>Событие успешно добавлено.</p>");
						}
					}
				}
				?>

				<form class="m-3 col-md-6" action="" id="form_add-event" method="post" enctype="multipart/form-data">
					<div class="form-group row">
						<label for="date" class="col-md-2 col-form-label">Дата (гггг.мм.дд)</label>
						<input type="text" class="form-control col-md-10" id="date" name="date" placeholder="Дата" required>
					</div>
					<div class="form-group row">
						<label for="short-text" class="col-md-2 col-form-label">Кратко для фото</label>
						<textarea class="form-control col-md-10" name="short-text" id="short-text" required></textarea>
					</div>
					<div class="form-group row">
						<label for="long-text" class="col-md-2 col-form-label">Текст</label>
						<textarea class=" form-control col-md-10" name="long-text" id="long-text" required></textarea>
					</div>

					<div class="img-group">
						<div class="img-group_row">
							<div class="toggle-group my-2">
								<div class="form-check form-check-inline ">
									<input class="form-check-input toggle-img active" type="radio" name="option1" id="option1" value="file" checked="checked">
									<label class="form-check-label" for="option1">Файл</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input toggle-img " type="radio" name="option1" id="option2" value="url">
									<label class="form-check-label" for="option2">URL</label>
								</div>
							</div>

							<div class="form-group row group-img align-items-center">
								<label class="col-md-2 col-form-label" for="img1">Картинка</label>
								<input class="form-control col-md-10 d-none v-center" name="url1" type="url" id="img1" placeholder="http://...">
								<input type="file" name="file[]" id="img1">
							</div>
						</div>
					</div>

					<div class="mb-2">
						<button class="btn btn-secondary" type="button" id="btnAddImgGroup">Еще картинку</button>
					</div>

					<div>
						<button class="btn btn-primary btn-lg mt-4" type="submit" name="insert">Добавить событие</button>
					</div>
				</form>

				<script src='https://unpkg.com/imask'></script>
				<script src="assets/js/script.js"></script>
				<script src="assets/js/adding.js"></script>
			</div>
			<?php
			if (!empty($errors)) {
				echo '
						<div class="footer">
						<div class="alert alert-warning" role="alert">
						<p>' . array_shift($errors) . '</p>
						</div>
						</div>';
			}
			?>

		</div>
	</div>
</body>

</html>