<?php
require_once('./cfg.php');  
require_once('./category.php');
require_once('./products.php');
function Produkty() {
    global $conn;
    $query="SELECT * FROM product_list ORDER BY 'id' DESC LIMIT 100";
    $result = mysqli_query($conn, $query);



    $lista .= '<section class="text">
                <h1>Produkty</h1>
                <table>
                    <tr>
                        <th>Nazwa</th>
                        <th>Opis</th>
                        <th>Cena netto</th>
                        <th>Vat</th>
                        <th>Ilość sztuk</th>
                        <th>Kategoria</th>
                        <th>Gabaryt</th>
                        <th>Zdjęcie</th>
                        <th>Akcja</th>
                    </tr>';
                        
                    while ($row = mysqli_fetch_assoc($result)) {
                        if ($row['dostepnosc'] == 1) {

                        $queryy = "SELECT * FROM category_list WHERE id=".$row['kategoria']." LIMIT 1";
                        $resultt = mysqli_query($conn, $queryy);
                        $roww = mysqli_fetch_assoc($resultt);

                        $category = $roww['name'];
                        $lista .= '<tr>  <td>' . $row['nazwa'] . '</td> <td>' . $row['opis'] . '<td> '. $row['cena'] .'zł</td>
                        <td>' . $row['vat'] . '%</td><td> '. $row['sztuki'] . '</td> <td> '. $category .'</td><td> ' . $row['gabaryt'] . '</td><td><img style="height: 150px; width: auto; display: block;" src="data:image; base64,' . $row['zdj'] . '"></td><td><form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data"><input type="submit" name="dodaj_prod" value="Dodaj do koszyka" /><input type="number" name="sztuki" value="1" style="width:25px;"><input type="hidden" name="prod_id" value="' . $row['id'] . '"></form></td></tr>';
                        }
                    }
                  $lista .= '</table></section><br />';
                  echo $lista;
                  if (isset($_POST['dodaj_prod'])) {
                    dodaj_prod($_POST['prod_id'], $_POST['sztuki']);
                }
                }
    
function dodaj_prod($id, $sztuki){
    if(!isset($_SESSION['count'])) {
        $_SESSION['count'] = 1;
        $_SESSION['count_all'] = $sztuki;
    }
    else {
        $_SESSION['count']++;
        $_SESSION['count_all'] += $sztuki;
    }

    if (isset($_SESSION['produkty'])) {
        array_push($_SESSION['produkty'], array("nr" => $_SESSION['count'], "id" => $id, "sztuki" => $sztuki, "data" => date('Y-m-d')));
    }
    else {
        $_SESSION['produkty'] = array();
        array_push($_SESSION['produkty'], array("nr" => $_SESSION['count'], "id" => $id, "sztuki" => $sztuki, "data" => date('Y-m-d')));
    }
    }

function Koszyk() {

    global $conn;
    $lista .= '<section class="text"><center><h1>Koszyk</h1>
    <table>
    <tr>
        <th style="border:1px solid #000000">Zdjęcie</th>
        <th style="border:1px solid #000000">Nazwa</th>
        <th style="border:1px solid #000000">Ilośc</th>
        <th style="border:1px solid #000000">Cena brutto</th>
        <th style="border:1px solid #000000">Data</th>
        <th style="border:1px solid #000000">Akcja</th>
    </tr>';  
    $x=0;
    foreach ($_SESSION['produkty'] as $produkt) {
            $sql = "SELECT * FROM product_list WHERE id=".$produkt['id']." LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
            $x+=$row['cena']*($produkt['sztuki']*1.23).'zł<br />';

            $lista .= 
            '<tr>
                <td><img style="height: 150px; width: auto; display: block;" src="data:image; base64,'. $row['zdj'].'"/></td>
                <td>'.$row['nazwa'].'</td>  
                <td>'.$produkt['sztuki'].'</td>
                <td>'.$row['cena']*($produkt['sztuki']*1.23).'zł<br /></td>
                <td>'.$produkt['data'].'</td>
                <td><form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_nr" value="' . $produkt['nr'] . '">
                    <input type="submit" name="usunzkoszyka" value="Usuń z koszyka">
                    </form></td>
            </tr>';   
            }
        $lista .= '</table><h3>Liczba produktów w koszyku: ' . $_SESSION['count_all'] . '</h3><h2>Całkowity koszt: '.$x.'zł</h2></center></section><br />';          
        echo $lista;
}
    if (isset($_POST['usunzkoszyka'])) {
        usunzkoszyka($_POST['product_nr']);
    }

function usunzkoszyka($product_nr) {
    $_SESSION['count_all'] -= $_SESSION['produkty'][$product_nr - 1]['sztuki'];
    $_SESSION['count']--;

    unset($_SESSION['produkty'][$product_nr - 1]);
    header("Location: ./index.php?id=10");
    exit;
}
  ?>
  