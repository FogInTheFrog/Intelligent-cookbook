<!DOCTYPE html>
<html>
 <head>
  <title> ADD NEW RECIPE </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
 </head>
 <body>
  <BR><FORM ACTION="cookbook.php" METHOD="POST">  
    <INPUT TYPE="SUBMIT" VALUE="MENU"> </BR>
  </FORM>

  <BR><FORM ACTION="addingredient.php" METHOD="POST">  
    <INPUT TYPE="SUBMIT" VALUE="ADD NEW INGREDIENT"></BR>
  </FORM>
  <?PHP
   session_start();
      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }
      $stmt = oci_parse($conn, "SELECT name FROM INGREDIENT");
      oci_execute($stmt);
  ?>
  <br /><br />
  <center>
  <div class="container" style="width:600px;">
   <br /><br />
   <form action="mealadded.php" method="post" id="ingredients_form">
    <div class="form-group">
     <label></label>
     <select id="ingredients" name="ingredients[]" multiple class="form-control" >
      <?PHP while(($row = oci_fetch_array($stmt, OCI_BOTH))) { ?>
        <option value="<?PHP echo $row['NAME']; ?>"> <?PHP echo $row['NAME']; ?> </option>
      <?PHP } ?>
     </select>
    </div>
    <div class="form-group">
      <INPUT TYPE="TEXT" NAME="name" PLACEHOLDER="NAME"><BR><BR>
      <INPUT TYPE="TEXT" NAME="desc" PLACEHOLDER="DESCRIPTION"><BR><BR> 
      <INPUT TYPE="TEXT" NAME="instr" PLACEHOLDER="INSTRUCTIONS"><BR><BR> 
      <INPUT TYPE="NUMBER" NAME="serv" PLACEHOLDER="SERVINGS"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="time" PLACEHOLDER="COOKING TIME [MINS]"><BR><BR>
      <INPUT TYPE="TEXT" NAME="yield" PLACEHOLDER="YIELD"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="calories" PLACEHOLDER="CALORIES"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="protein" PLACEHOLDER="PROTEIN [G]"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="carbohydrates" PLACEHOLDER="CARBOHYDRATES [G]"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="fat" PLACEHOLDER="FAT [G]"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="cholesterol" PLACEHOLDER="CHOLESTEROL [G]"><BR><BR>
      <INPUT TYPE="NUMBER" NAME="sodium" PLACEHOLDER="SODIUM [G]"><BR><BR>
      <INPUT TYPE="TEXT" NAME="author" PLACEHOLDER="AUTHOR"><BR><BR>
      <input type="submit" class="btn btn-info" name="submit" value="submit" />
    </div>
   </form>
   <br />
  </div>
</center>
 </body>
</html>

<script>
$(document).ready(function(){
 $('#ingredients').multiselect({
  nonSelectedText: 'Select ingredients',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
});
</script>
