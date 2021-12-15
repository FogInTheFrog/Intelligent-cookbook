<HTML>
  <HEAD>
    <TITLE> Cook Book </TITLE>
  </HEAD>
  <BODY>
    <H2></H2>
    
    <?PHP
      echo "<BR><A HREF=\"cookbook.php\"> menu </A></BR>";

      session_start();

      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }
      ?>

      <h2> Meals that satisfy your preferences: </h2>

      <?PHP  
      $ok = 0;
      $meal = -1;
      $match = 0;
      $time = 0;
      $calories = 0;
      $protein = 0;
      $carbo = 0;
      $fat = 0;
      $cholesterol = 0;
      $sodium = 0;
      $caloriesmax = 0;
      $proteinmax = 0;
      $carbomax = 0;
      $fatmax = 0;
      $cholesterolmax = 0;
      $sodiummax = 0;
      if ($_POST['caloriesmin'] != "") {
        $calories = $_POST['caloriesmin'];
      } else {
        $calories = 0;
      }
      if ($_POST['proteinmin'] != "") {
        $protein = $_POST['proteinmin'];
      } else {
        $protein = 0;
      }

      if ($_POST['carbomin'] != "") {
        $carbo = $_POST['carbomin'];
      } else {
        $carbo = 0;
      }

      if ($_POST['fatmin'] != "") {
        $fat = $_POST['fatmin'];
      } else {
        $fat = 0;
      }
      
      if ($_POST['cholesterolmin'] != "") {
        $cholesterol = $_POST['cholesterolmin'];
      } else {
        $cholesterol = 0;
      }

      if ($_POST['sodiummin'] != "") {
        $sodium = $_POST['sodiummin'];
      } else {
       $sodium = 0;
      }

      if ($_POST['caloriesmax'] != "") {
        $caloriesmax = $_POST['caloriesmax'];
      } else {
        $caloriesmax = 99999;
      }

      if ($_POST['proteinmax'] != "") {
        $proteinmax = $_POST['proteinmax'];
      } else {
        $proteinmax = 99999;
      }

      if ($_POST['carbomax'] != "") {
        $carbomax = $_POST['carbomax'];
      } else {
        $carbomax = 99999;
      }

      if ($_POST['fatmax'] != "") {
        $fatmax = $_POST['fatmax'];
      } else {
        $fatmax = 99999;
      }
      
      if ($_POST['cholesterolmax'] != "") {
        $cholesterolmax = $_POST['cholesterolmax'];
      } else {
        $cholesterolmax = 99999;
      }

      if ($_POST['sodiummax'] != "") {
        $sodiummax = $_POST['sodiummax'];
      } else {
        $sodiummax = 99999;
      }
    
      if ($_POST['time'] == "") {
        $time = 99999;
      } else {
        $time = $_POST['time'];
      }


      $stmt = oci_parse($conn, "SELECT recipe_id AS rid, ingredient_id AS iid 
                                FROM RECIPE_INGREDIENTS 
                                WHERE recipe_id IN (SELECT id FROM RECIPE 
                                              WHERE calories  <= $caloriesmax 
                                              AND protein <= $proteinmax
                                              AND carbohydrates <= $carbomax
                                              AND fat <= $fatmax
                                              AND cholesterol <= $cholesterolmax
                                              AND sodium <= $sodiummax
                                              AND calories >= $calories
                                              AND protein >= $protein
                                              AND carbohydrates >= $carbo
                                              AND fat >= $fat
                                              AND cholesterol >= $cholesterol
                                              AND sodium >= $sodium)
                                ORDER BY recipe_id");
      oci_execute($stmt);

      while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        if ($row['RID'] != $meal) {
          $tmp_ck = oci_parse($conn, "SELECT cook_time AS ck FROM recipe WHERE id = $meal");
          oci_execute($tmp_ck);
          $ck_res = oci_fetch_array($tmp_ck, OCI_BOTH);
          if ($ok && count($_POST['ingredients']) == $match && $ck_res['CK'] <= $time) {
            $search = oci_parse($conn, "SELECT id, name FROM RECIPE WHERE id = $meal");
            oci_execute($search);
            $curr = oci_fetch_array($search, OCI_BOTH);
            echo "<BR><A HREF=\"meal.php?voted=false&id=".$curr['ID']."\">".$curr['NAME']."</A></BR>\n";
          }
          $ok = 1;
          $meal = $row['RID'];
          $match = 0;
        } 
        foreach ($_POST['ingredients'] as $ing) {
          if ($ing == $row['IID']) {
            $match = $match + 1;
          }
        }
        foreach ($_POST['ningredients'] as $ning) {
          if ($ning == $row['IID']) {
            $ok = 0;
          }
        }
      
      }

      $tmp_ck = oci_parse($conn, "SELECT cook_time AS ck FROM recipe WHERE id = $meal");
      oci_execute($tmp_ck);
      $ck_res = oci_fetch_array($tmp_ck, OCI_BOTH);
      if ($ok && count($_POST['ingredients']) == $match && $ck_res['CK'] <= $time) {
        $search = oci_parse($conn, "SELECT id, name FROM RECIPE WHERE id = $meal");
        oci_execute($search);
        $curr = oci_fetch_array($search, OCI_BOTH);
        echo "<BR><A HREF=\"meal.php?voted=false&id=".$curr['ID']."\">".$curr['NAME']."<A><BR>\n";
      }
        
    ?>


  </BODY>
</HTML>
