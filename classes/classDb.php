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

  // Metoda pro vypsani vseho v tabulce
  function selectAll($tableName)
  {
    try
    {
      $sql = 'SELECT * FROM '.$tableName;
      $result = $this->connection->query($sql);
      return $result;
    }
    catch (mysqli_sql_exception $e)
    {
      echo '<strong></strong>'.$e->getMessage();
    }
  }

  // Metoda pro odeslani sql 
  function sql($sql)
  {
    try
    {
      $result = $this->connection->query($sql);
      return $result;
    }
    catch (mysqli_sql_exception $e)
    {
      echo '<strong></strong>'.$e->getMessage();
    }
  }

  // Metoda pro zjisteni id poslednich odeslanych dat
  function getId()
  {
    try
    {
      $result = $this->connection->insert_id;
      return $result;
    }
    catch (mysqli_sql_exception $e)
    {
      echo '<strong></strong>'.$e->getMessage();
    }
  }

  // Metoda pro odpojeni z databaze
  function disconnect ()
  {
    $this->connection->close();
  }
}