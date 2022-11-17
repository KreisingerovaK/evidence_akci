<?php 

class Db
{
  private $connection;

  // Metoda pro pripojeni do databaze
  function connect()
  {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try
    {
      $this->connection = new mysqli(CreateDb::DB_SERVER, CreateDb::DB_USER, CreateDb::DB_PASSWORD, CreateDb::DB_NAME);
    }
    catch (mysqli_sql_exception $e)
    {
      echo '<strong>Zkontrolujte, zda jsou hodnoty pro připojení do databáze správně: </strong>'.$e->getMessage();
    }
  }

  // Metoda pro odpojeni z databaze
  function disconnect ()
  {
    $this->connection->close();
  }
}