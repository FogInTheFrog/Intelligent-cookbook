<HTML>
  <HEAD>
    <TITLE> Cook Book </TITLE>
  </HEAD>
  <BODY>
    <H2>  </H2>
    <?PHP 
      session_start(); 
      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }

      $stmt_get_id = oci_parse($conn, "SELECT MAX(id) FROM RECIPE");
      oci_execute($stmt_get_id);
      $row = oci_fetch_array($stmt_get_id, OCI_BOTH);
      $new_id = $row['MAX(ID)'] + 1;
      $insert = oci_parse($conn, "INSERT INTO RECIPE VALUES($new_id, :name_, :desc_, :ins_, :time_, :yield_, :calories_, :protein_, :carbohydrates_, :fat_, :cholesterol_, :sodium_, :serv_, :author_, 0, 0)");
      oci_bind_by_name($insert, ':name_', $_POST['name']);
      oci_bind_by_name($insert, ':desc_', $_POST['desc']);
      oci_bind_by_name($insert, ':ins_', $_POST['instr']);
      oci_bind_by_name($insert, ':time_', $_POST['time']);
      oci_bind_by_name($insert, ':yield_', $_POST['yield']);
      oci_bind_by_name($insert, ':calories_', $_POST['calories']);
      oci_bind_by_name($insert, ':protein_', $_POST['protein']);
      oci_bind_by_name($insert, ':carbohydrates_', $_POST['carbohydrates']);
      oci_bind_by_name($insert, ':fat_', $_POST['fat']);
      oci_bind_by_name($insert, ':cholesterol_', $_POST['cholesterol']);
      oci_bind_by_name($insert, ':sodium_', $_POST['sodium']); 
      oci_bind_by_name($insert, ':serv_', $_POST['serv']);
      oci_bind_by_name($insert, ':author_', $_POST['author']);
      if (oci_execute($insert)) {

        $stmt = oci_parse($conn, "SELECT id, name FROM INGREDIENT");
        oci_execute($stmt);
        while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
          if (isset($_POST['submit'])) {
              foreach ($_POST['ingredients'] as $ing) {
                if ($ing == $row['NAME']) {
                  $insert = oci_parse($conn, "INSERT INTO RECIPE_INGREDIENTS VALUES($new_id, :ing_id)");
                  oci_bind_by_name($insert, ":ing_id", $row['ID']);
                  oci_execute($insert);
                }
              }
          }
        } 

        echo "NEW RECIPE ADDED!<BR>";
      } else {
        echo "whoops! something went wrong :(<BR>";
      }
   
    ?>
  <BR><FORM ACTION="cookbook.php" METHOD="POST">  
    <INPUT TYPE="SUBMIT" VALUE="MENU"> </BR>
  </FORM>

  </BODY>
</HTML>
