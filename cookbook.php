<HTML>
  <HEAD>
    <meta
charset="utf-8"><meta
name="viewport" content="width=device-width, initial-scale=1"><title>COOKBOOK</title><style type="text/css">body{margin:40px
auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
10px}h1,h2,h3{line-height:1.2}</style>
  </HEAD>
  <BODY>
    <H2> COOKBOOK </H2>
    <DIV ALIGN="right"> 
        <FORM ACTION="addmeal.php" METHOD="POST">  
          <INPUT TYPE="SUBMIT" VALUE="ADD NEW RECIPE">
        </FORM>

        <FORM ACTION="search.php" METHOD="POST">
          <INPUT TYPE="SUBMIT" VALUE="SEARCH">
        </FORM>

        <FORM ACTION="ranking.php" METHOD="POST">
          <INPUT TYPE="SUBMIT" VALUE="TOP RATED RECIPES">
        </FORM>

    </DIV>

    <?PHP
      session_start();
      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }
      $stmt = oci_parse($conn, "SELECT id, description, name FROM RECIPE");
      oci_execute($stmt, OCI_NO_AUTO_COMMIT);

      while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        echo "<BR><H2><A HREF=\"meal.php?voted=false&id=".$row['ID']."\">".$row['NAME']."</A></H2>".$row['DESCRIPTION']."<BR>\n";
      }
    ?>

  </BODY>
</HTML>
