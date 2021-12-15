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
      <h2> Meals of the authors you chose: </h2>

      <?PHP
      foreach ($_POST['authors'] as $auth) {
        $stmt = oci_parse($conn, "SELECT id, name FROM RECIPE WHERE author = '${auth}'");
        oci_execute($stmt); 
        while($row = oci_fetch_array($stmt, OCI_BOTH)) {
          echo "<BR><A HREF=\"meal.php?voted=false&id=".$row['ID']."\">".$row['NAME']."</A></BR>\n";
        }
      }
      
      
    ?>


  </BODY>
</HTML>
