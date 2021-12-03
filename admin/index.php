<?php
require "includes/head.php";
?>

<?php
if (isset($_POST["submit"])) {
	$errors = array();

	$sql = "SELECT * FROM this_day.user WHERE login='" . $_POST["login"] . "' AND password='" . $_POST["password"] . "' AND access='0'";
	$res = $db->signIn($sql);

	if ($res) {
		$_SESSION["logged_user"] = $_POST["login"];
	} else {
		$errors[] = "Неверный логин или пароль!";
	}

	if (trim($_POST["login"] == "")) {
		$errors[] = "Введите логин!";
	}

	if (trim($_POST["password"] == "")) {
		$errors[] = "Введите пароль!";
	}
}
?>



<body>
	<?php if (isset($_SESSION["logged_user"])) : ?>
		<!-- Пользователь авторизован -->
		<div id="admin" class="admin">
			<div class="container-fluide">
				<?php include("includes/header.php") ?>
				<div class="body">
					<div class=" alert alert-primary alert-dismissible fade show" role="alert">
						Добро пожаловать в админ панель <span class="text-success font-weight-bold"><?php echo $_SESSION["logged_user"]; ?></span>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
				<div class="footer"></div>
			</div>
		</div>

	<?php else : ?>
		<!-- Если пользователь не авторизован -->
		<div class="card" id="authorization">
			<div class="card-header">
				<p>Если вы не являетесь членом админастрации сайта <span>"Этот день в истории"</span> покиньте пожалуйста эту страницу!</p>
			</div>
			<div class="card-body">
				<form action="index.php" method="post">
					<div class="wrapper form-group">
						<input class="form-control form-control-lg mb-3" name="login" type="text" placeholder="Логин" value="<?php echo @$_POST["login"] ?>">
						<input class="form-control form-control-lg mb-3" name="password" type="password" placeholder="Пароль">
						<button class="btn btn-success btn-lg btn-block mb-3" name="submit" type="sumbit">Вход</button>
					</div>
				</form>
			</div>
			<?php
			if (!empty($errors)) {
				echo "
				<div class='card-footer'>
					<p style='color: red;'>" . array_shift($errors) . "</p>
				</div>
				";
			}
			?>
		</div>
	<?php endif; ?>
</body>

</html>