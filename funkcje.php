<?php
require_once('./cfg.php');

  session_start();
  
    function ListaPodstron() {
      global $conn;
      $query="SELECT * FROM page_list ORDER BY 'id' DESC LIMIT 100";
      $result = mysqli_query($conn, $query);
      echo '<span>Lista podstron</span> <br />';
      while($row = mysqli_fetch_assoc($result))
      {
        echo $row['id'].' '.$row['page_title'].' <br />';
      }
      echo '<a href="./admin.php?id=nowa" ">Dodaj nową stronę</a>';
      $usw = '
      <form method="post">
      <h4>Usuwanie strony </h4>
      <input type="text" name="delete_id" placeholder="Podaj id">
      <input type="submit" name="delete" value="Wybierz"/>
      </form>
      ';
      echo $usw;
      $ed = '
      <form method="post">
      <h4>Edytowanie strony </h4>
      <input type="text" name="edit_id" placeholder="Podaj id">
      <input type="submit" name="edit" value="Wybierz"/>
      </form>
      ';
      echo $ed;
      echo '<a href="./admin.php?id=kategorie">Kategorie</a><br />';
      echo '<a href="./admin.php?id=produkty">Produkty</a>';
      if (isset($_POST['delete'])) {
        deletePage($_POST['delete_id']);
    }
    if (isset($_POST['edit'])) {
      $location = 'Location: ./admin.php?id=edycja&strona=' . $_POST['edit_id'] . '';
      header($location);
  }
    }

    $wyloguj='
    <form class="logout" method="post">
    <input type="hidden" value="yes">
    <input type="submit"  name="logout_submit" value="Wyloguj"/>
    </form>
    ';
    if(isset($_SESSION['logged_in'])) {
    echo "<br>" .$wyloguj;

    if(isset($_POST['logout_submit'])) {
      Wyloguj();
    }
  }


  require './PHPMailer/src/Exception.php';
  require './PHPMailer/src/PHPMailer.php';
  require './PHPMailer/src/SMTP.php';
  
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  function remindPassword()
  {
      global $pass;
  
      $mail = new PHPMailer(true);
  
      try {
          $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'bagno294@gmail.com';
          $mail->Password   = 'uapw lkch khbe oouy';
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Port       = 465;
          $mail->setLanguage('pl', '../PHPMailer/language');
          $mail->CharSet = 'UTF-8';
  
          $mail->setFrom('admin@email.com', 'Panel administracyjny');
          $mail->addAddress('bagno294@gmail.com');
  
          $mail->isHTML(false);
          $mail->Subject = 'Przypomnienie hasła do panelu administracyjnego';
          $mail->Body    = 'Hasło: ' . $pass;
          $mail->AltBody = 'Hasło: ' . $pass;
  
          $mail->send();
          echo '<span>Przypomnienie hasła zostało wysłane.</span>';
      }
      
      catch (Exception $e) {
          echo '<span>Wystąpił błąd podczas wysyłania przypomnienia. <br />' . $mail->ErrorInfo . '</span>';
      }
  }
    function Logowanie() {
        
        global $user,$pass;
    
        $wynik = '
        <div class="logowanie">
          <h1 class="heading">Panel CMS:</h1>
          <h4>Zaloguj się</h4>
            <div class="logowanie">
              <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.$_SERVER['REQUEST_URI'].'">
                <table class="logowanie">
                  <tr><td class="log4_t">Login:</td><td><input type="text" name="user" class="logowanie" /></td></tr>
                  <tr><td class="log4_t">Haslo:</td><td><input type="password" name="pass" class="logowanie" /></td></tr>
                  <tr><td>&nbsp;</td><td><input type="submit" class="submit" name="login" class="logowanie" value="Zaloguj" /></td></tr>
                </table>
              </form>
            </div>
        </div>
        ';
    
        echo $wynik;
    
        if (isset($_POST['login'])) {
          if ($_POST['user'] == $user && $_POST['pass'] == $pass) {
              $_SESSION['logged_in'] = 'tak';
              header("Location: ./admin.php?id=lista");
          }
          else {
              echo '<span id="alert">Podano niepoprawne dane logowania.</span>';
              echo '<form method="POST" action="' . $_SERVER['REQUEST_URL'] . '" enctype="multipart/form-data">
              <input type="submit" name="remind" value="Przypomnij hasło" />
        </form>';
          }
      }

    if (isset($_POST['remind'])) {
        remindPassword();
    }
  } 
    function Wyloguj() {
      session_destroy(); 
      header("Refresh:1"); 
      exit;
    }  
    
    function deletePage($id) {
        global $conn;
    
        $id_clear = htmlspecialchars($id);
        $query = "DELETE FROM page_list WHERE id='$id_clear' LIMIT 1";
        $result = mysqli_query($conn, $query);
        header("Location: ./admin.php");
        exit;
    }

    function addPage() {
      $title = $_POST['title'];
      $content = $_POST['content'];
      $status = $_POST['status'];
      global $conn;
      $query = "INSERT INTO page_list (id, page_title, page_content, status) VALUES (NULL, '$title', '$content', '$status');";
      $result = mysqli_query($conn, $query);
      header("Location: ./admin.php");
    }
    

    function addForm() {
    $form = '<h4>Dodaj nowa strone</h4>';
    $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                    <label for="title">Tytuł:</label><br />
                    <input type="text" name="title" id="title" required /> <br />
                   
                    <label for="content">Treść:</label><br />
                    <textarea name="content" id="content" cols="50" rows="5" required></textarea><br />

                    <label for="status">Status:</label><br />
                    <input type="text" name="status" id="status" required/><br /><br />

                    <button type="submit" name="add" class="btn">Zatwierdź</button>
                    <button type="reset" name="reset" class="btn">Resetuj</button>
                </form>';
    echo $form;

    if (isset($_POST['add'])) {
        addPage();
    }
}

function editForm($id) {
  
  global $conn;
        
    $id_clear = htmlspecialchars($id);
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    $page_title = $row["page_title"];
    $page_content = $row["page_content"]; 
    $status = $row["status"];

     

  $form = '<h4>Edytuj strone</h4>';
  $form .= '<form action="' . $_SERVER['REQUEST_URL'] . '" method="POST" enctype="multipart/form-data">
                  <label for="title">Tytuł:</label><br />
                  <input type="text" name="title" id="title" value="'.$page_title.'" required /> <br />
                 
                  <label for="content">Treść:</label><br />
                  <textarea name="content" id="content" cols="60" rows="30" required>'.$page_content.'</textarea><br />
                  <label for="status">Aktywna:</label>
                  <input type="checkbox" name="status" id="status"';
                  if  ($status == 1) {
                    $form .= ' checked/><br /><br />';}
                    else {
                          $form .= '/><br /><br />';} 

                  $form .='
                  <button type="submit" name="edit" class="btn">Zatwierdź</button>
                  <button type="reset" name="reset" class="btn">Resetuj</button>
              </form>';
              echo $form;

  if ($_POST['status'] == 'on')
  $status = 1;
  else
  $status = 0;

  if (isset($_POST['edit'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    global $conn;
    $id_clear = htmlspecialchars($id);
    $query = "UPDATE page_list SET page_title='$title', page_content='$content', status='$status' WHERE id='$id_clear' LIMIT 1;";
    $result = mysqli_query($conn, $query);
    header("Location: ./admin.php");
    exit;
}
}

?>
