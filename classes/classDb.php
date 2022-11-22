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
      die ('        
        <strong> Nepodařilo se připojit do databáze. </strong> 
        <br> Zkontrolujte, zda databáze existuje a zda jsou správné údaje pro připojení:  
        <br> server - <strong>'.CreateDb::DB_SERVER.'</strong>
        <br> user - <strong>'.CreateDb::DB_USER.'</strong>
        <br> heslo - <strong>'.CreateDb::DB_PASSWORD.'</strong>
        <br> jméno databáze - <strong>'.CreateDb::DB_NAME.'</strong>
        <br>'.$e->getMessage().'
        <br> Pokud nexistuje databáze, jdete na stránku, kde se vytvoří:
        <a href="index.php">index.php</a>
      ');
    }
  }

  // Metoda pro vypsani vseho v tabulce
  function selectAll($tableName, $orderBy)
  {
    try
    {
      $sql = 'SELECT 
                * 
              FROM 
                '.$tableName.'
              ORDER BY 
                '.$orderBy.'
              ';
      $result = $this->connection->query($sql);
      return $result;
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Chyba v metodě selectAll. </strong> 
        <br> Zkontrolujte, zda existuje tabulka a sloupeček, podle kterého se mají data srovnat: 
        <br> tabulka - <strong>'.$tableName.'</strong>
        <br> sloupeček - <strong>'.$orderBy.'</strong>
        <br> Chyba je na řádku: <strong>'.$e->getLine().'</strong><br>'.$e->getMessage()
      );
    }
  }

  // Metoda pro vypsani podminenych zaznamu
  function selectWhere($tableName, $where)
  {
    try
    {
      $sql = 'SELECT 
                * 
              FROM 
                '.$tableName.' 
              WHERE
                '.$where.'
              ';
      $result = $this->connection->query($sql);
      return $result;
    }
    catch (mysqli_sql_exception $e)
    {
      die ('
        <strong> Chyba v metodě selectWhere. </strong> 
        <br> Zkontrolujte, zda existuje tabulka a podmínka, podle se mají vybrat data: 
        <br> tabulka - <strong>'.$tableName.'</strong>
        <br> podmínka - <strong>'.$where.'</strong>
        <br> Chyba je na řádku: <strong>'.$e->getLine().'</strong><br>'.$e->getMessage()
      );
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
      die ('
        <strong> Chyba v metodě sql. </strong> 
        <br> Zkontrolujte, zda je odeslané sql správně: 
        <br> sql - <strong>'.$sql.'</strong>
        <br> Chyba je na řádku: <strong>'.$e->getLine().'</strong><br>'.$e->getMessage()
      );
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
      echo '<strong> Chyba v metodě getId - nepodařilo se získat id.</strong>'.$e->getMessage();
    }
  }

  // Metoda pro odpojeni z databaze
  function disconnect()
  {
    $this->connection->close();
  }
}