<HTML>
  <HEAD>
    <TITLE> Cook Book </TITLE>
  </HEAD>
  <BODY>
    <?PHP
      session_start();
      $_SESSION['ingname'] = $_POST['ingname'];

      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }

      $checkstmt = oci_parse($conn, "SELECT name FROM INGREDIENT");
      oci_execute($checkstmt, OCI_NO_AUTO_COMMIT);
      $helper = 0;
      while (($row = oci_fetch_array($checkstmt, OCI_BOTH))) {
        if ($row['NAME'] == $_SESSION['ingname']) {
          $helper = 1;
        }
      }

      if ($helper == 0) {
        $stmt = oci_parse($conn, "INSERT INTO INGREDIENT VALUES (null, :ingname_)");
        oci_bind_by_name($stmt, ':ingname_', $_SESSION['ingname']);
        oci_execute($stmt);
        echo "ingredient added!";
      } else { 
        echo "ERROR: ingredient already exists!";
      }      
    ?>

    <BR><FORM ACTION="cookbook.php" METHOD="POST">  
      <INPUT TYPE="SUBMIT" VALUE="BACK TO MENU"> </BR>
    </FORM>
    <BR><FORM ACTION="addmeal.php" METHOD="POST">  
      <INPUT TYPE="SUBMIT" VALUE="BACK TO ADDING RECIPE"> </BR>
    </FORM>
  </BODY>
</HTML>
