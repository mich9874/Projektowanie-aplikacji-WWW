<?php
require_once('./cfg.php');  
  function ListaKategorii() {
    global $conn;
    $query="SELECT * FROM category_list ORDER BY 'id' DESC LIMIT 100";
    $result = mysqli_query($conn, $query);
    echo '<span>Lista kateogrii</span> <br />';

    $lista .= '<table style="border:1px solid #000000";>
                    <tr>
                        <th style="border:1px solid #000000">Nr</th>
                        <th style="border:1px solid #000000">Nazwa</th>
                        <th style="border:1px solid #000000">Kategoria</th>
                    </tr>';    
                    while ($row = mysqli_fetch_assoc($result)) {
                      if ($row['mother'] == 0) {
                          $main_category = "—";
                          $lista .= '<tr> <td style="border:1px solid #000000">' . $row['id'] . '</td> <td style="border:1px solid #000000">' . $row['name'] . '</td> <td style="border:1px solid #000000">' . $main_category . '</td>';
                          $res = mysqli_query($conn, $query);
                          while ($rows = mysqli_fetch_assoc($res)) {
                              if ($rows['mother'] == $row['id']) {
                                  $main_category = $row['name'];
                                  $lista .= '<tr> <td style="border:1px solid #000000">' . $rows['id'] . '</td> <td style="border:1px solid #000000">' . $rows['name'] . '</td> <td style="border:1px solid #000000">' . $main_category . '</td></tr>';
                              }
                          }
                      }
                  }
                  $lista .= '</table><br />';
                  $lista .= '<a href="./admin.php?id=dodaj_kategorie">Dodaj nową kategorię</a>';
                  echo $lista;
                  
                  $usw = '
                  <form method="post">
                  <h4>Usuwanie kategorii </h4>
                  <input type="text" name="delete_id" placeholder="Podaj id">
                  <input type="submit" name="delete" value="Wybierz"/>
                  </form>
                  ';
                  echo $usw;
                  $ed = '
                  <form method="post">
                  <h4>Edytowanie kategorii </h4>
                  <input type="text" name="edit_id" placeholder="Podaj id">
                  <input type="submit" name="edit" value="Wybierz"/>
                  </form>
                  ';
                  echo $ed;
                  if (isset($_POST['delete'])) {
                    usunKategorie($_POST['delete_id']);
                }
                if (isset($_POST['edit'])) {
                  $location = 'Location: ./admin.php?id=edycja_kategorii&kategorie=' . $_POST['edit_id'] . '';
                  header($location);
              }
  }

  function addCategory() {
    $name = $_POST['name'];
    $mother = $_POST['mother'];
    global $conn;
    $query = "INSERT INTO category_list (id, name, mother) VALUES (NULL, '$name', '$mother');";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=kategorie");
  }
  function addCategoryForm() {
    $form = '<h4>Dodaj nowa kategorie</h4>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <label for="name">Nazwa:</label><br />
                    <input type="text" name="name" id="name" required /> <br />

                    <label for="mother">Kategoria główna:</label><br />
                    <input type="text" name="mother" id="mother" required/><br /><br />

                    <button type="submit" name="add">Zatwierdź</button>
                    <button type="reset" name="reset">Resetuj</button>
                </form>';
    echo $form;

    if (isset($_POST['add'])) {
        addCategory();
    }
}

function edytujkategorie($id) {
  
  global $conn;
        
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM category_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $name = $row['name'];
    $mother = $row['mother']; 

     

  $form = '<h4>Edytuj kategorie</h4>';
  $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                  <label for="edit_name">Nazwa:</label><br />
                  <input type="text" name="edit_name" id="edit_name" value="'.$name.'" required /> <br />
                 
                  <label for="mother">Kategoria główna:</label><br />
                  <input type="text" name="edit_mother" id="edit_mother" value="'.$mother.'" required /> <br /><br />

                  <button type="submit" name="edit">Zatwierdź</button>
                  <button type="reset" name="reset">Resetuj</button>
              </form>';
              echo $form;


  if (isset($_POST['edit'])) {
    $edit_name = $_POST['edit_name'];
    $edit_mother = $_POST['edit_mother'];
    global $conn;
    $id_clear = htmlspecialchars($id);
    $query = "UPDATE category_list SET name='$edit_name', mother='$edit_mother' WHERE id='$id_clear' LIMIT 1;";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php?id=kategorie");
    exit;
}
}

function usunKategorie($id) {
  global $conn;

  $id_clear = htmlspecialchars($id);
  $query = "DELETE FROM category_list WHERE id='$id_clear' LIMIT 1";
  $result = mysqli_query($conn, $query);
  header("Location: ./admin.php?id=kategorie");
  exit;
}
?>
