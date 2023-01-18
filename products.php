<?php
require_once('./cfg.php');  
require_once('./category.php');

function ListaProduktow() {
    global $conn;
    $query="SELECT * FROM product_list ORDER BY 'id' DESC LIMIT 100";
    $result = mysqli_query($conn, $query);

    echo '<span>Lista produktow</span> <br />';


    $lista .= '<table style="border:1px solid #000000";>
                    <tr>
                        <th style="border:1px solid #000000">Nr&nbsp&nbsp</th>
                        <th style="border:1px solid #000000">Nazwa</th>
                        <th style="border:1px solid #000000">Opis</th>
                        <th style="border:1px solid #000000">Data utworzenia</th>
                        <th style="border:1px solid #000000">Data modyfikacji</th>
                        <th style="border:1px solid #000000">Data wygaśniecia</th>
                        <th style="border:1px solid #000000">Cena</th>
                        <th style="border:1px solid #000000">Vat&nbsp&nbsp&nbsp</th>
                        <th style="border:1px solid #000000">Ilość sztuk</th>
                        <th style="border:1px solid #000000">Dostepność</th>
                        <th style="border:1px solid #000000">Kategoria</th>
                        <th style="border:1px solid #000000">Gabaryt</th>
                        <th style="border:1px solid #000000">Zdjęcie</th>
                    </tr>';    
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['dostepnosc'] == 1) {
                            $dostepnosc = "Tak";
                        }
                        else {
                            $dostepnosc = "Nie";
                        }
                        $queryy = "SELECT * FROM category_list WHERE id=".$row['kategoria']." LIMIT 1";
                        $resultt = mysqli_query($conn, $queryy);
                        $roww = mysqli_fetch_assoc($resultt);

                        $category = $roww['name'];
                        $lista .= '<tr> <td style="border:1px solid #000000">' . $row['id'] . '</td> <td style="border:1px solid #000000">' . $row['nazwa'] . '</td> <td style="border:1px solid #000000">' . $row['opis'] . '</td> <td style="border:1px solid #000000">' . $row['data_utw'] . '</td> <td style="border:1px solid #000000"> '. $row['data_mod'] . '</td> <td style="border:1px solid #000000">' . $row['data_wyg'] . '</td> <td style="border:1px solid #000000"> '. $row['cena'] .'zł</td>
                        <td style="border:1px solid #000000">' . $row['vat'] . '%</td><td style="border:1px solid #000000"> '. $row['sztuki'] . '</td><td style="border:1px solid #000000">' . $dostepnosc . '</td><td style="border:1px solid #000000"> '. $category .'</td><td style="border:1px solid #000000"> ' . $row['gabaryt'] . '</td><td style="border:1px solid #000000"><img style="height: 150px; width: auto; display: block;" src="data:image; base64,' . $row['zdj'] . '"></td></tr>';
                  }
                  $lista .= '</table><br />';
                  $lista .= '<a href="./admin.php?id=dodaj_produkt">Dodaj nowy produkt</a>';
                  echo $lista;
                  
                  $usw = '
                  <form method="post">
                  <h4>Usuwanie produktu </h4>
                  <input type="text" name="delete_id" placeholder="Podaj id">
                  <input type="submit" name="delete" value="Wybierz"/>
                  </form>
                  ';
                  echo $usw;
                  $ed = '
                  <form method="post">
                  <h4>Edytowanie produktu </h4>
                  <input type="text" name="edit_id" placeholder="Podaj id">
                  <input type="submit" name="edit" value="Wybierz"/>
                  </form>
                  ';
                  echo $ed;
                  if (isset($_POST['delete'])) {
                    usunProdukt($_POST['delete_id']);
                }
                if (isset($_POST['edit'])) {
                  $location = 'Location: ./admin.php?id=edycja_produktu&produkty=' . $_POST['edit_id'] . '';
                  header($location);
                  echo $location;
              }
  }

  function dodajprodukt() {
    $nazwa = $_POST['nazwa'];
    $opis = $_POST['opis'];
    $data_utw = $_POST['data_utw'];
    $data_mod = $_POST['data_mod'];
    $data_wyg = $_POST['data_wyg'];
    $cena = $_POST['cena'];
    $vat = $_POST['vat'];
    $sztuki = $_POST['sztuki'];
    $dostepnosc = $_POST['dostepnosc'];
    $kategoria = $_POST['kategoria'];
    $gabaryt = $_POST['gabaryt'];
    $zdj = '';
    if (!empty($_FILES["zdj"]["tmp_name"])) {
      $zdj = base64_encode(file_get_contents(addslashes($_FILES["zdj"]["tmp_name"])));
    }

    if ($data_wyg >= $data_mod && $sztuki > 0) {
        $dostepnosc = 1;
    }
    else {
        $dostepnosc = 0;
    }

    global $conn;
    $query = "INSERT INTO product_list (id, nazwa, opis, data_utw, data_mod, data_wyg, cena, vat, sztuki, dostepnosc, kategoria, gabaryt, zdj) VALUES (NULL, '$nazwa', '$opis','$data_utw','$data_mod','$data_wyg','$cena','$vat', '$sztuki','$dostepnosc','$kategoria','$gabaryt','$zdj');";
    echo $query;
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=produkty");
  }

  function dodajproduktform() {
    $form = '<h4>Dodaj nowy produkt</h4>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <label for="nazwa">Nazwa:</label><br />
                    <input type="text" name="nazwa" id="nazwa" required /> <br />

                    <label for="opis">Opis:</label><br />
                    <textarea name="opis" id="opis" cols="50" rows="5" required></textarea><br /><br />

                    <label for="data_utw">Data utworzenia:</label><br />
                    <input type="date" name="data_utw" id="data_utw" required /> <br />

                    <label for="data_mod">Data modyfikacji:</label><br />
                    <input type="date" name="data_mod" id="data_mod" required /> <br />

                    <label for="data_wyg">Data wygaśnięcia:</label><br />
                    <input type="date" name="data_wyg" id="data_wyg" required /> <br />

                    <label for="cena">Cena:</label><br />
                    <input type="number" name="cena" id="cena" required /> <br />

                    <label for="vat">Vat:</label><br />
                    <input type="number" name="vat" id="vat" required /> <br />

                    <label for="sztuki">Ilość dostępnych sztuk:</label><br />
                    <input type="number" name="sztuki" id="sztuki" required /> <br />

                    <label for="dostepnosc">Dostępność:</label><br />
                    <input type="number" name="dostepnosc" id="dostepnosc" required /> <br />

                    <label for="kategoria">Kategoria:</label><br />
                    <input type="number" name="kategoria" id="kategoria" required /> <br />

                    <label for="gabaryt">Gabaryt:</label><br />
                    <input type="text" name="gabaryt" id="gabaryt" required /> <br /><br />

                    <label for="zdj">Zdjęcie:</label><br />
                    <input type="file" name="zdj" id="zdj"/><br /><br />

                    <button type="submit" name="add">Zatwierdź</button>
                    <button type="reset" name="reset">Resetuj</button>
                </form>';
    echo $form;

    if (isset($_POST['add'])) {
        dodajprodukt();
    }
}

function edytujProdukt($id) {
  
    global $conn;
          
      $id_clear = htmlspecialchars($id);
      $query = "SELECT * FROM product_list WHERE id='$id_clear' LIMIT 1";
      $result = mysqli_query($conn, $query);
      $row = mysqli_fetch_assoc($result);
      $id = $row['id'];
      $nazwa = $row['nazwa'];
      $opis = $row['opis'];
      $data_utw = $row['data_utw'];
      $data_mod = $row['data_mod'];
      $data_wyg = $row['data_wyg'];
      $cena = $row['cena'];
      $vat = $row['vat'];
      $sztuki = $row['sztuki'];
      $dostepnosc = $row['dostepnosc'];
      $category = $row['kategoria'];
      $gabaryt = $row['gabaryt'];
      $zdj = $row['zdj'];
       
    $form = '<h4>Edytuj produkt</h4>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <label for="id">Id:</label><br />
                    <input type="text" name="edit_id" value="'.$id.'" /> <br />

                    <label for="nazwa">Nazwa:</label><br />
                    <input type="text" name="edit_nazwa" id="nazwa" value="'.$nazwa.'" /> <br />

                    <label for="opis">Opis:</label><br />
                    <textarea name="edit_opis" id="opis" cols="50" rows="5" >'.$opis.'</textarea><br /><br />

                    <label for="data_utw">Data utworzenia:</label><br />
                    <input type="date" name="edit_data_utw" id="data_utw" value="'.$data_utw.'" /> <br />

                    <label for="data_mod">Data modyfikacji:</label><br />
                    <input type="date" name="edit_data_mod" id="data_mod" value="'.$data_mod.'" /> <br />

                    <label for="data_wyg">Data wygaśnięcia:</label><br />
                    <input type="date" name="edit_data_wyg" id="data_wyg" value="'.$data_wyg.'" /> <br />

                    <label for="cena">Cena:</label><br />
                    <input type="number" name="edit_cena" id="cena" value="'.$cena.'" /> <br />

                    <label for="vat">Vat:</label><br />
                    <input type="number" name="edit_vat" id="vat" value="'.$vat.'" /> <br />

                    <label for="sztuki">Ilość dostępnych sztuk:</label><br />
                    <input type="number" name="edit_sztuki" id="sztuki" value="'.$sztuki.'" /> <br />

                    <label for="dostepnosc">Dostępność:</label><br />
                    <input type="number" name="edit_dostepnosc" id="dostepnosc" value="'.$dostepnosc.'" /> <br />

                    <label for="kategoria">Kategoria:</label><br />
                    <input type="number" name="edit_kategoria" id="kategoria" value="'.$category.'" /> <br />

                    <label for="gabaryt">Gabaryt:</label><br />
                    <input type="text" name="edit_gabaryt" id="gabaryt"value="'.$gabaryt.'"  /> <br /><br />

                    <label for="zdj">Zdjęcie:</label><br />
                    <input type="file" name="edit_zdj"/><br /><br />

                    <button type="submit" name="edit">Zatwierdź</button>
                    <button type="reset" name="reset">Resetuj</button>
                </form>';
                echo $form;
  
  
    if (isset($_POST['edit'])) {
        $edit_id = $_POST['edit_id'];
        $edit_nazwa = $_POST['edit_nazwa'];
        $edit_opis = $_POST['edit_opis'];
        $edit_data_utw = $_POST['edit_data_utw'];
        $edit_data_mod = $_POST['edit_data_mod'];
        $edit_data_wyg = $_POST['edit_data_wyg'];
        $edit_cena = $_POST['edit_cena'];
        $edit_vat = $_POST['edit_vat'];
        $edit_sztuki = $_POST['edit_sztuki'];
        $edit_dostepnosc = $_POST['edit_dostepnosc'];
        $edit_category = $_POST['edit_kategoria'];
        $edit_gabaryt = $_POST['edit_gabaryt'];
        $edit_zdj = '';
        if (!empty($_FILES["edit_zdj"]["tmp_name"])) {
          $edit_zdj = base64_encode(file_get_contents(addslashes($_FILES["edit_zdj"]["tmp_name"])));
    }
      global $conn;
      $id_clear = htmlspecialchars($id);
      $query = "UPDATE product_list SET id='$edit_id', nazwa='$edit_nazwa', opis='$edit_opis', data_utw='$edit_data_utw', data_mod='$edit_data_mod', data_wyg='$edit_data_wyg', cena='$edit_cena', vat='$edit_vat', sztuki='$edit_sztuki', dostepnosc='$edit_dostepnosc', kategoria='$edit_category', gabaryt='$edit_gabaryt', zdj='$edit_zdj' WHERE id='$id_clear' LIMIT 1;";
      $result = mysqli_query($conn, $query);
      header("Location: ./admin.php?id=produkty");
      exit;
  }
  }

  function usunProdukt($id) {
    global $conn;
  
    $id_clear = htmlspecialchars($id);
    $query = "DELETE FROM product_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=produkty");
    exit;
  }
?>