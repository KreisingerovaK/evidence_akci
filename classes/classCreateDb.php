<?php 

class CreateDb 
{
  const DB_SERVER   = 'localhost';
  const DB_USER     = 'root';
  const DB_NAME     = 'ukol_kreisingerova';
  const DB_PASSWORD = 'spravce';

  private $connection;

  // Metoda pro prvni pripojeni do databaze
  private function connectFirst()
  {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try
    {
      $this->connection = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Nelze se připojit do databáze. </strong> 
        <br> Zkontrolujte, zda jsou přístupové údaje správně: 
        <br> server - <strong>'.self::DB_SERVER.'</strong>
        <br> uživatel - <strong>'.self::DB_USER.'</strong>
        <br> heslo - <strong>'.self::DB_PASSWORD.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
  }
  
  // Metoda pro pripojeni do databaze
  private function connect()
  {
    try
    {
      $this->connection = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB_NAME);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Nelze se připojit do databáze. </strong> 
        <br> Zkontrolujte, zda jsou přístupové údaje správně: 
        <br> server - <strong>'.self::DB_SERVER.'</strong>
        <br> uživatel - <strong>'.self::DB_USER.'</strong>
        <br> heslo - <strong>'.self::DB_PASSWORD.'</strong>
        <br> jméno databáze - <strong>'.self::DB_NAME.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
  }
  
  // Metoda pro odpojeni z databaze
  private function disconnect()
  {
    $this->connection->close();
  }

  // Metoda pro vytvoreni a naplneni databaze 
  public function create()
  {
    self::connectFirst();
    try
    {
      $sql = "CREATE DATABASE IF NOT EXISTS ".self::DB_NAME;
      $this->connection->query($sql);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('<strong>Databázi se nepodařilo vytvořit: </strong>'.$e->getMessage());
    }
    self::disconnect();
    self::connect();
    self::createTableEvents();
    self::createTableTypes();
    self::createTableTypesEvents();
    self::createTableFile();
  }

  // Metoda pro vytvoreni tabulky akce
  private function createTableEvents()
  {
    $sql = "CREATE TABLE IF NOT EXISTS Events (
              eventId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              eventName VARCHAR(250) NOT NULL,
              typeId INT REFERENCES Types(typeId), 
              eventFrom DATETIME NOT NULL,
              eventTo DATETIME NOT NULL,
              numberParticipant INT(255) UNSIGNED,
              note VARCHAR(300)
            )";
    try
    {
      $this->connection->query($sql);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Tabulku Events se nepodařilo vytvořit </strong> 
        <br> Zkontrolujte, zda je sql správně: 
        <br> sql - <strong>'.$sql.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
  }

  // Metoda pro vytvoreni tabulky s typy akci 
  private function createTableTypes()
  {
    $sql = "CREATE TABLE IF NOT EXISTS Types (
              typeId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              typeName VARCHAR(250) NOT NULL
            )";
    try
    {
      $this->connection->query($sql);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Tabulku Types se nepodařilo vytvořit </strong> 
        <br> Zkontrolujte, zda je sql správně: 
        <br> sql - <strong>'.$sql.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
    $sql = "INSERT INTO types 
              (typeName)
            VALUES 
              ('typ1')
            ;";
    $this->connection->query($sql);
    $sql = "INSERT INTO types 
              (typeName)
            VALUES 
              ('typ2')
            ;";
    $this->connection->query($sql);
    $sql = "INSERT INTO types 
              (typeName)
            VALUES 
              ('typ3')
            ;";
    try
    {
      $this->connection->query($sql);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Tabulku Types se nepodařilo naplnit daty </strong> 
        <br> Zkontrolujte, zda je sql správně: 
        <br> sql - <strong>'.$sql.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
  }

  // Metoda pro vytvoreni tabulky typy akci
  private function createTableTypesEvents()
  {
    $sql = "CREATE TABLE IF NOT EXISTS TypesEvents (
              typesId INT REFERENCES Types(typeId),
              eventId INT REFERENCES Events(eventId)
            )";
    try
    {
      $this->connection->query($sql);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Tabulku TypesEvents se nepodařilo vytvořit </strong> 
        <br> Zkontrolujte, zda je sql správně: 
        <br> sql - <strong>'.$sql.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
  }

  // Metoda pro vytvoreni tabulky prilohy
  private function createTableFile()
  {
    $sql = "CREATE TABLE IF NOT EXISTS File (
              fileId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
              eventId INT REFERENCES Events(eventId),
              fileName VARCHAR(250) NOT NULL
            )";
    try
    {
      $this->connection->query($sql);
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Tabulku File se nepodařilo vytvořit </strong> 
        <br> Zkontrolujte, zda je sql správně: 
        <br> sql - <strong>'.$sql.'</strong>
        <br> Chyba:'.$e->getMessage()
      );
    }
  }
}