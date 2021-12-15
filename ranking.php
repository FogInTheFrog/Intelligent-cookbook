<HTML>
  <HEAD>
    <meta
charset="utf-8"><meta
name="viewport" content="width=device-width, initial-scale=1"><title>COOKBOOK - RANKING</title><style type="text/css">body{margin:40px
auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
10px}h1,h2,h3{line-height:1.2}</style>
  </HEAD>
  <BODY>
    <center><H2> TOP RECIPES </H2></center>
    
    <FORM ACTION="cookbook.php" METHOD="POST">  
          <INPUT TYPE="SUBMIT" VALUE="MENU">
    </FORM>
    <center>

    <?PHP
      session_start();

      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }

      $stmt = oci_parse($conn, "SELECT id, name, average_rating as rating FROM RECIPE ORDER BY rating DESC FETCH FIRST 100 ROWS ONLY");
      oci_execute($stmt);

      $place = 1;
      while (($row = oci_fetch_array($stmt, OCI_BOTH))) {
        echo $place.".<A HREF=\"meal.php?voted=false&id=".$row['ID']."\">".$row['NAME']."</A> rating: ".$row['RATING']."<BR>\n";
        $place = $place + 1;
      }
      
    ?>

  </center>
  </BODY>
</HTML>
