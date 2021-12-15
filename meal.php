<HTML>
<head><meta
charset="utf-8"><meta
name="viewport" content="width=device-width, initial-scale=1"><style type="text/css">body{margin:40px
auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
10px}h1,h2,h3{line-height:1.2}</style></head>
  <BODY>
      <BR><FORM ACTION="cookbook.php" METHOD="POST">  
    <INPUT TYPE="SUBMIT" VALUE="MENU"> </BR>
  </FORM>

    <?PHP 
      session_start();
      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }

      if ($_GET['voted'] == 'true') {
        $stmt = oci_parse($conn, "UPDATE RECIPE SET average_rating = average_rating + 1 WHERE id = :id_");
        oci_bind_by_name($stmt, ":id_", $_GET['id']);
        oci_execute($stmt);
      }

      ?>
      <center>
      <?PHP
      $stmt1 = oci_parse($conn, "SELECT * FROM RECIPE WHERE id = :id_");
      oci_bind_by_name($stmt1, ":id_", $_GET['id']);
      oci_execute($stmt1, OCI_NO_AUTO_COMMIT);

      $row = oci_fetch_array($stmt1, OCI_BOTH);
      echo "<BR><b><h2>".$row['NAME']."</h2></b>\n";
      echo "<BR> <b>description</b>: ".$row['DESCRIPTION']."<BR>\n";
      echo "<BR> <b>instructions</b>: ".$row['INSTRUCTIONS']."<BR>\n";
      echo "<BR> <b>servings</b>: ".$row['SERVINGS']."<BR>\n";
      echo "<BR> <b>cooking time</b>: ".$row['COOK_TIME']." min <BR>\n";
      echo "<BR> <b>yield</b>: ".$row['YIELD']."<BR>\n";
      echo "<BR> <b>calories</b>: ".$row['CALORIES']."<BR>\n";
      echo "<BR> <b>protein</b>: ".$row['PROTEIN']."<BR>\n";
      echo "<BR> <b>carbohydrates</b>: ".$row['CARBOHYDRATES']."<BR>\n";
      echo "<BR> <b>fat</b>: ".$row['FAT']."<BR>\n";
      echo "<BR> <b>cholesterol</b>: ".$row['CHOLESTEROL']."<BR>\n";
      echo "<BR> <b>sodium</b>: ".$row['SODIUM']."<BR>\n";
      echo "<BR> <b>author</b>: ".$row['AUTHOR']."<BR>\n";
      echo "<BR><b>rating</b>: ".$row['AVERAGE_RATING'];
      ?>

      <FORM ACTION="meal.php?voted=true&id=<?PHP echo $_GET['id']; ?>" METHOD="POST">  
      <INPUT TYPE="SUBMIT" VALUE="upvote">
      </FORM>

      <?PHP
      echo "<BR> <b>ingredients</b>:";
      $stmt2 = oci_parse($conn, "SELECT name FROM INGREDIENT WHERE id IN 
        (SELECT ingredient_id FROM RECIPE_INGREDIENTS WHERE recipe_id = :recipe_id_)");
      oci_bind_by_name($stmt2, ":recipe_id_", $_GET['id']);
      oci_execute($stmt2, OCI_NO_AUTO_COMMIT);

      while (($row2 = oci_fetch_array($stmt2, OCI_BOTH))) {
        echo "<BR>-".$row2['NAME'];
      }

      if ($row['LINK'] == 1) {
         echo "<BR><A HREF=\"https://www.allrecipes.com/recipe/".$row['ID']."\"> source </A></BR>";
      }
     
      ?>
      </center>
  </BODY>
</HTML>
