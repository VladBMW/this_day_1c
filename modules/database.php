<?php

class Database
{
  private $link;
  private $name;
  private $host;
  private $user;
  private $pass;

  /**
   * Функция подключения к базе данных
   * 
   * @param string $nameDB - название базы данных
   * @param string $server - название сервера (на локальном: localhost)
   * @param string $user - имя пользователя для входа в СУБД
   * @param string $password - пароль пользователя для входа в СУБД
   * 
   * @return link
   */
  public function connectToDB($nameDB, $server, $user, $password)
  {
    $link = mysqli_connect($server, $user, $password, $nameDB);

    if ($link == false) {
      print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
      exit(1);
    } else {
      $this->link = $link;
      $this->name = $nameDB;
      $this->host = $server;
      $this->user = $user;
      $this->pass = $password;
    }

    return $this->link;
  }

  /**
   * Функция cоздание таблицы в базе данных
   * 
   * @param string $nameDB - название базы данных
   * @param string $server - название сервера (на локальном: localhost)
   * @param string $user - имя пользователя для входа в СУБД
   * @param string $password - пароль пользователя для входа в СУБД
   * @param string $sql - sql запрос
   * 
   * @return null
   */
  public function createTable($nameDB, $server, $user, $password, $sql)
  {
    $this->link = mysqli_connect($server, $user, $password, $nameDB);

    if ($this->link == false) {
      print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
      return;
    } else {

      $result = mysqli_query($this->link, $sql);

      if ($result == false) {
        print(mysqli_error($this->link));
        print("<p style='color: firebrick; font-weight: bold;'>Произошла ошибка при выполнении запроса.</p>" . PHP_EOL);
      } else {
        print("<p style='color: darkgreen; font-weight: bold;'>Таблица успешно создана.</p>");
      }
    }
  }

  /**
   * Функция удаления таблицы из базы данных
   * 
   * @param string $nameDB - название базы данных
   * @param string $server - название сервера (на локальном: localhost)
   * @param string $user - имя пользователя для входа в СУБД
   * @param string $password - пароль пользователя для входа в СУБД
   * @param string $tableName - назваение таблицы которую нужно удалить
   * 
   * @return null
   */
  public function deleteTable($nameDB, $server, $user, $password, $tableName)
  {
    $this->link = mysqli_connect($server, $user, $password, $nameDB);

    if ($this->link == false) {
      print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
      return;
    } else {

      $sql = "DROP TABLE " . $tableName;
      $result = mysqli_query($this->link, $sql);

      if ($result == false) {
        print(mysqli_error($this->link));
        print("<p style='color: firebrick; font-weight: bold;'>Произошла ошибка при выполнении запроса.</p>" . PHP_EOL);
      } else {
        print("<p style='color: darkgreen; font-weight: bold;'>Таблица успешно удалена.</p>");
      }
    }
  }

  /**
   * Функция загружает массив в БД
   * 
   * @param array $array - массив, который необходимо записать в БД
   * @param string $nameDB - название базы данных
   * @param string $server - название сервера (на локальном: localhost)
   * @param string $user - имя пользователя для входа в СУБД
   * @param string $password - пароль пользователя для входа в СУБД
   * 
   * @return null
   */
  public function  downloadToDB(array $array, $nameDB, $server, $user, $password)
  {
    $this->link = mysqli_connect($server, $user, $password, $nameDB);

    if ($this->link == false) {
      print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
      return;
    } else {

      foreach ($array as $row) {

        $sql = "INSERT INTO base (date, long_text, short_text, img1, img2, img3) VALUES ('" . implode("', '", $row) . "' )";
        $result = mysqli_query($this->link, $sql);

        if ($result == false) {
          print("Произошла ошибка при выполнении запроса.<br/>" . PHP_EOL);
          print("<p style='color: firebrick; margin: 0;'>" . mysqli_error($this->link) . "</p>" . PHP_EOL);
          print(implode("', '", $row) . PHP_EOL);
          print("<br/><br/>");
        }
      }
    }
  }


  /**
   * Функция для очистки таблицы в базе данных
   * 
   * @param string $nameDB - название базы данных
   * @param string $server - название сервера (на локальном: localhost)
   * @param string $user - имя пользователя для входа в СУБД
   * @param string $password - пароль пользователя для входа в СУБД
   * 
   * @return null
   */
  public function clearTable($nameDB, $server, $user, $password)
  {
    $this->link = mysqli_connect($server, $user, $password, $nameDB);

    if ($this->link == false) {
      print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
      return;
    } else {

      $sql = "DELETE FROM `base` WHERE `base`.`id` >= 0;";
      $result = mysqli_query($this->link, $sql);

      if ($result == false) {
        print("<p style='color: firebrick; font-weight: bold;'>Произошла ошибка при выполнении запроса.</p>" . PHP_EOL);
        print(mysqli_error($this->link));
      } else {
        print("<p style='color: darkgreen; font-weight: bold;'>Таблица успешно создана.</p>");
      }
    }
  }

  /**
   * Поиск событий в этот день
   * 
   * @param string $nameDB - название базы данных
   * @param string $server - название сервера (на локальном: localhost)
   * @param string $user - имя пользователя для входа в СУБД
   * @param string $password - пароль пользователя для входа в СУБД
   * @param string $day - день
   * @param string $month - месяц
   * 
   * @return result 
   */
  public function searchEventOfThisDay($nameDB, $server, $user, $password, $day = 2, $month)
  {
    $this->link = mysqli_connect($server, $user, $password, $nameDB);

    if ($this->link == false) {
      print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());
      return;
    } else {
      $sql = "SELECT * FROM `base` WHERE DAY(base.date) = {$day} and MONTH(base.date) = {$month} ;";
      $result = mysqli_query($this->link, $sql);
      return $result;
    }
  }


  /**
   * sql запрос возвращающий массив
   * 
   * @param string $sql - sql запрос
   * 
   * @return arr[] 
   */
  public function getQuery($sql)
  {
    $result = mysqli_query($this->link, $sql);

    if ($result == false) {
      print(mysqli_error($this->link));
      print("<p style='color: firebrick; font-weight: bold;'>Произошла ошибка при выполнении запроса.</p>" . PHP_EOL);
    }

    $arr = array();

    if ($result) {
      // Cycle through results
      while ($row = $result->fetch_object()) {
        $arr[] = $row;
      }
      // Free result set
      $result->close();
    }

    return $arr;
  }


  /**
   * вставка
   * 
   * @param string $sql - sql запрос
   * 
   * @return result 
   */
  public function setQuery($sql)
  {
    $result = mysqli_query($this->link, $sql);

    if ($result == false) {
      print(mysqli_error($this->link));
      print("<p style='color: firebrick; font-weight: bold;'>Произошла ошибка при выполнении запроса.</p>" . PHP_EOL);
    }

    return $result;
  }

  /**
   * Проверка входа
   * 
   * @param string $sql - sql запрос
   * @param string $access - уровень доступа (Админ = 0, обычный пользователь = 1)
   * 
   * @return bool 
   */
  public function signIn($sql)
  {
    $result = mysqli_query($this->link, $sql);

    if ($result == false) {
      return false;
    }

    $arr = array();

    if ($result) {

      while ($row = $result->fetch_object()) {
        $arr[] = $row;
      }

      $result->close();
    }

    if (!empty($arr)) {
      return true;
    }

    return false;
  }
}
