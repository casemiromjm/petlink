<?php
  declare(strict_types = 1);

  function getDatabaseConnection() : PDO {
    $dbPath = __DIR__ . '/petsitting.db'; 
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA foreign_keys = ON');

    return $db;
  }
?>