<?php
include("includes/head.php");


// if (!isset($_SESSION["logged_user"])) {
//   include($root . "/404.php");
//   die();
// }

?>

<body>

  <div id="admin" class="admin">
    <div class="container-fluide">
      <?php include("includes/header.php") ?>
      <div class="body">
        <form method="post">
          <input type="submit" name="basic" class="button" value="Назад" />
          <input type="submit" name="convertDB" class="button" value="Отобразить DB.XLSX" />
          <input type="submit" name="createErrXLSX" class="button" value="Создать таблицу ошибок" />
          <input type="submit" name="clearTable" class="button" value="Очистить таблицу" />
          <input type="submit" name="deleteTable" class="button" value="Удалить таблицу" />
          <input type="submit" name="createTable" class="button" value="Создать таблицу" />
          <input type="submit" name="uploadDB" class="button" value="Загрузить базу" />
        </form>
        <?php

        // Подключение конфигурационного файла
        $config = include($_SERVER['DOCUMENT_ROOT'] . "/config.php");

        // $config = parse_ini_file("config.ini");
        // echo $config["db_pass"];

        // Подключение библиотеки
        require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

        // Путь к файлу
        $inputFileName = "./db.xlsx";
        // Имя листа
        $sheetName = "Календарь событий";

        // Создание читателя xlsx
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        // Только чтение данных из файла электронной таблицы
        $reader->setReadDataOnly(false);
        // Получить доступ к нужному листу
        $reader->setLoadSheetsOnly($sheetName);


        // Закгрузка файла
        $spreadsheet = $reader->load($inputFileName);
        // Текущий файл
        $ss = $spreadsheet->getActiveSheet();

        // Количество строк
        $highestRow = $ss->getHighestRow();
        // Количество столбцов (Буква)
        $highestColumn = $ss->getHighestColumn();
        // Количество столбцов (Индекс)
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

        // Желаемые колонки
        $desiredColumnsName = ["Дата", "Кратко (для фото)", "Текст250", "Рис1", "Рис2", "Рис3"];
        // Строка шапки
        $headRow = 1;


        // массив индексов нужных колонок
        $arrayIndexColumn = [];
        // Массив хранящий данные из xlsx
        $arrayToDB = [];

        /**
         * Функция возвращает массив индексов, чьи столбцы необходимо добавить в БД
         * 
         * @param object $ss - текущий файл
         * @param int $headRow - строка шапки листа
         * @param array $desiredColumnsName - массив желаемых колонок
         * @param int $highestColumnIndex - количество столбцов в листе Excel документа
         * 
         * @return array - массив позиций желаемых колонок
         */
        function getIndexDesiredColumn($ss, $headRow, $desiredColumnsName, $highestColumnIndex)
        {
          // Позиции желаемых колонок
          $desiredColumnsPosition = [];

          for ($col = 1; $col <= $highestColumnIndex; $col++) {
            $value = $ss->getCellByColumnAndRow($col, $headRow)->getValue();

            if (in_array($value, $desiredColumnsName, true)) {
              array_push($desiredColumnsPosition, $col);
              $desiredColumnsName = array_diff($desiredColumnsName, [$value]);
            }
          }

          return $desiredColumnsPosition;
        }


        /**
         * Функция возвращает массив из xlsx файла
         * 
         * @param object $ss - текущий файл
         * @param int $headRow - строка шапки листа
         * @param int $highestRow - количество строк в листе
         * @param int $arrayIndexColumn - массив позиций желаемых колонок
         * 
         * @return array - массив для добавление в БД 
         */
        function createArrayToDB($ss, $headRow, $highestRow, $arrayIndexColumn)
        {
          // Готовый массив в БД
          $arrayToDB = [];
          // Количество нужных колонок
          $countElemInArr = count($arrayIndexColumn);

          for ($row = $headRow + 1; $row <= $highestRow; $row++) {

            // Строка для массива, содержит правильный формат;
            $rowToArray = [];
            // код ошибки
            $error = 0;

            for ($i = 0; $i < $countElemInArr; $i++) {
              // Нужная колонка
              $col = $arrayIndexColumn[$i];
              // Определение типа ячейки
              $typeCell = $ss->getCellByColumnAndRow($col, $row)->getDataType();
              // Записываем значение в переменную
              $value = $ss->getCellByColumnAndRow($col, $row)->getValue();

              if ($col === 1 || $col === 4) {
                if ($col === 3 && $typeCell !== "n") {
                  $error = 1;
                  break;
                }

                if ($col === 4 && empty($value)) {
                  $error = 2;
                  break;
                }
              }

              // Если дата, то вписать в нужном формате
              if ($typeCell == "n" && $col === 1) {
                $value = date_format(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value), "Y-m-d");
              }

              // Записываем в промежуточный массив
              array_push($rowToArray, $value);
            }

            if ($error != 0)
              continue;

            array_push($arrayToDB, $rowToArray);
          }

          return $arrayToDB;
        }


        /**
         * Функция ищет битые строки не подходящие для экспорта в БД
         * Проверяется строка "Дата" и "Крато (для фото)"
         * Строки, где в столбце "Дата" значение "НД", пропускются
         * 
         * @param object $ss - текущий файл
         * @param int $headRow - строка шапки листа
         * @param int $highestRow - количество строк в листе
         * @param int $arrayIndexColumn - массив позиций желаемых колонок
         * 
         * @return array - массив битых строк
         */
        function searchBrokenRows($ss, $headRow, $highestRow, $arrayIndexColumn)
        {
          // Массив с битыми строками (неккоректная запись)
          $brokenRows = [];
          // Количество нужных колонок
          $countElemInArr = count($arrayIndexColumn);
          // Добавление закголовка
          array_push($brokenRows, ["Строка", "Ошибка"]);

          for ($row = $headRow + 1; $row <= $highestRow; $row++) {
            // код ошибки
            $error = 0;
            // Строка ошибок
            $stringErr = "";
            // Пустая строка boolean
            $rowEmpty = true;


            for ($i = 0; $i < $countElemInArr; $i++) {
              // Нужная колонка
              $col = $arrayIndexColumn[$i];
              // Определение типа ячейки
              $typeCell = $ss->getCellByColumnAndRow($col, $row)->getDataType();
              // Записываем значение в переменную
              $value = $ss->getCellByColumnAndRow($col, $row)->getValue();


              if ($col === 3 || $col === 24) {
                if ($col === 3 && $value === 'нд') {
                  break;
                }

                if ($col === 3 && $typeCell !== "n") {
                  $error = 1200;
                  $stringErr .= "<p>" . $error . " - Неверный формат даты.</p>";

                  if ($typeCell == "s") {
                    $error = 1201;
                    $stringErr .= "<p>" . $error . " - Значение даты не может являтся строкой.</p>";
                  }
                }

                if (empty($value)) {
                  $error = 1210;
                  $stringErr .= "<p>" . $error . " - Пустое значение: В столбце: " . $col . "</p>";
                } else {
                  $rowEmpty = false;
                }
              }
            }

            if ($error == 0)
              continue;

            if ($rowEmpty)
              continue;

            // Записываем в промежуточный массив
            array_push($brokenRows, [$row, $stringErr]);
          }

          return $brokenRows;
        }


        /**
         * Функция выводит двумерный массив в виде таблицы в HTML
         * 
         * @param array $array - выводимый массив
         * 
         * @return null
         */
        function outputArray($array)
        {
          echo '<table>' . "\n";
          foreach ($array as $row) {
            echo '<tr>' . PHP_EOL;

            foreach ($row as $value) {
              echo '<td>' . $value . '</td>' . PHP_EOL;
            }
            echo '</tr>' . PHP_EOL;
          }
          echo '</table>' . PHP_EOL;
        }


        /**
         * Функция записи массива в Excel
         * 
         * @param array $array - массив, который необходимо записать в Excel
         * @param string $name - название файла
         * 
         * @return null
         */
        function printArrayToExcel(array $array, $name)
        {
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          foreach ($array as $i => $row) {
            foreach ($row as $j => $cell) {
              $cell = str_replace("<p>", "", $cell);
              $cell = str_replace("</p>", "\n", $cell);
              $array[$i][$j] = $cell;
            }
          }

          // Диапозон для форматирования ячеек
          $diaposon = "A1:";

          // Количество строк в массиве
          $countRows = count($array);
          // Количество столбцов в массиве
          $countColumns = count($array[0]);

          // Перевод из числа в букву и добавление к строки диапозона
          $diaposon .= \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($countColumns);
          $diaposon .= $countRows;

          // Установить автоизменение размера
          $sheet->getColumnDimension('B')->setAutoSize(true);
          // Перенос текста на новую строку
          $sheet->getStyle($diaposon)->getAlignment()->setWrapText(true);

          $sheet->fromArray(
            $array,
            null,
            'A1'
          );

          $writer = new Xlsx($spreadsheet);
          $writer->save($name . '.xlsx');
          echo "IsReady!";
        }
        ?>

        <?php
        // Конвертирование бд из xlsx
        if (isset($_POST['convertDB'])) {
          // Поиск интдексов желаемых колонок [array]
          $arrayIndexColumn = getIndexDesiredColumn($ss, $headRow, $desiredColumnsName, $highestColumnIndex);
          // Создание массива для БД [array]
          $arrayToDB = createArrayToDB($ss, $headRow, $highestRow, $arrayIndexColumn);
          print("<p style='color: #3a3a3a; font-weight: bold;'>Произведено конвертирование!</p>" . PHP_EOL);
          print("<h1 style='color: #3a3a3a; font-weight: bold;'>Результы:</h1>" . PHP_EOL);
          // Вывод массива для БД в HTML в виде табилцы []
          outputArray($arrayToDB);
        }

        if (isset($_POST['createErrXLSX'])) {
          // Поиск интдексов желаемых колонок [array]
          $arrayIndexColumn = getIndexDesiredColumn($ss, $headRow, $desiredColumnsName, $highestColumnIndex);

          // Поиск проблемных мест
          $brokenRows = searchBrokenRows($ss, $headRow, $highestRow, $arrayIndexColumn);
          printArrayToExcel($brokenRows, "1");

          print("<p style='color: #3a3a3a; font-weight: bold;'>Произведен поиск ошибок!</p>" . PHP_EOL);
          print("<h1 style='color: #3a3a3a; font-weight: bold;'>Результы:</h1>" . PHP_EOL);

          // Вывод массива для БД в HTML в виде табилцы []
          outputArray($brokenRows);
        }

        if (isset($_POST['basic'])) {
        }

        if (isset($_POST['clearTable'])) {
          // include "database.php";
          $db = new Database();
          $db->clearTable("this_day", "localhost", "root", "");
        }

        if (isset($_POST['deleteTable'])) {
          $db = new Database();
          $db->deleteTable("this_day", "localhost", "root", "", 'base');
        }

        if (isset($_POST['createTable'])) {
          $sql = "CREATE TABLE base ( id INT NOT NULL AUTO_INCREMENT , date DATE NOT NULL , long_text VARCHAR(8192) NULL , short_text VARCHAR(1024) NOT NULL , img1 VARCHAR(1024) NULL DEFAULT NULL , img2 VARCHAR(1024) NULL DEFAULT NULL , img3 VARCHAR(1024) NULL DEFAULT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";
          // createTableOnDB("this_day", "localhost", "root", "", $sql);

          $db = new Database();
          $db->createTable("this_day", "localhost", "root", "root", $sql);
        }

        if (isset($_POST['uploadDB'])) {
          // Поиск интдексов желаемых колонок [array]
          $arrayIndexColumn = getIndexDesiredColumn($ss, $headRow, $desiredColumnsName, $highestColumnIndex);
          // Создание массива для БД [array]
          $arrayToDB = createArrayToDB($ss, $headRow, $highestRow, $arrayIndexColumn);

          $db = new Database();
          $db->downloadToDB($arrayToDB, "this_day", "localhost", "root", "root");

          print("<p style='color: darkgreen; font-weight: bold;'>База данных загружена.</p>");
        }
        ?>
      </div>
    </div>
  </div>

</body>

</html>