<?php
require_once('./funkcje.php');
require_once('./category.php');
require_once('./products.php');
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
ob_start();

switch ($_GET['id']) {
    case "logowanie":
      Logowanie();
    break;

    case "lista":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        ListaPodstron();
    break;

    case "edycja":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        editForm($_GET['strona']);
    break;

    case "nowa":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        addForm();
    break;

    case "kategorie":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        ListaKategorii();
    break;
    
    case "dodaj_kategorie":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        addCategoryForm();
    break;
    
    case "edycja_kategorii":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        edytujkategorie($_GET['kategorie']);
    break;

    case "produkty":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        ListaProduktow();
    break;

    case "dodaj_produkt":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        dodajproduktform();
    break;

    case "edycja_produktu":
      if (!isset($_SESSION['logged_in'])) {
        header("Location: ./admin.php?id=logowanie");
        exit;
      }
      else
        edytujProdukt($_GET['produkty']);
    break;

    default:
    if (!isset($_SESSION['logged_in']))
        Logowanie();
      else
        ListaPodstron();
}
?>