<!DOCTYPE html>
<html>
 <head>
  <title> search meals </title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
 </head>
 <body>
  <?PHP
   echo "<BR><A HREF=\"cookbook.php\"> menu </A></BR>";
   session_start();
      $conn = oci_connect(hg417878, kappa, "labora.mimuw.edu.pl/LABS");
      if (!$conn) {
        echo "oci_connect failed\n";
        $e = oci_error();
        echo $e['message'];
      }
      $stmt = oci_parse($conn, "SELECT id, name FROM INGREDIENT");
      oci_execute($stmt);
      $stmtn = oci_parse($conn, "SELECT id, name FROM INGREDIENT");
      oci_execute($stmtn);
      $stmt2 = oci_parse($conn, "SELECT unique(author) FROM RECIPE");
      oci_execute($stmt2);

  ?> 
  <center>
  <br /><br />
  <div class="container" style="width:600px;">
    <h2> Search meals by ingredients and nutrition info <BR> </h2>
   <form action="result.php" method="post" id="result_form">
    <div class="form-group">
     <label></label>
     <select id="ingredient" name="ingredients[]" multiple class="form-control">
      <?PHP while(($row = oci_fetch_array($stmt, OCI_BOTH))) { ?>
        <option value="<?PHP echo $row['ID']; ?>"> <?PHP echo $row['NAME']; ?> </option>
      <?PHP } ?>
     </select>
    </div>
     <div class="form-group">
     <label></label>
     <select id="ningredient" name="ningredients[]" multiple class="form-control">
      <?PHP while(($row = oci_fetch_array($stmtn, OCI_BOTH))) { ?>
        <option value="<?PHP echo $row['ID']; ?>"> <?PHP echo $row['NAME']; ?> </option>
      <?PHP } ?>
     </select>
    </div>
    <div class="form-group">
    <BR> OPTIONAL:      <BR> </BR> Type cooking time limit [mins] <INPUT TYPE="NUMBER" NAME="time"><BR>
    <BR>CALORIES:       <BR> <INPUT TYPE="NUMBER" NAME="caloriesmin" PLACEHOLDER="min"> 
                             <INPUT TYPE="NUMBER" NAME="caloriesmax" PLACEHOLDER="max"><BR>
    <BR>PROTEIN:        <BR> <INPUT TYPE="NUMBER" NAME="proteinmin" PLACEHOLDER="min"> 
                             <INPUT TYPE="NUMBER" NAME="proteinmax" PLACEHOLDER="max"><BR>
    <BR>CARBOHYDRATES:  <BR> <INPUT TYPE="NUMBER" NAME="carbomin" PLACEHOLDER="min"> 
                             <INPUT TYPE="NUMBER" NAME="carbomax" PLACEHOLDER="max"><BR>
    <BR>FAT:            <BR> <INPUT TYPE="NUMBER" NAME="fatmin" PLACEHOLDER="min"> 
                             <INPUT TYPE="NUMBER" NAME="fatmax" PLACEHOLDER="max"><BR>
    <BR>CHOLESTEROL:    <BR> <INPUT TYPE="NUMBER" NAME="cholesterolmin" PLACEHOLDER="min">
                             <INPUT TYPE="NUMBER" NAME="cholesterolmax" PLACEHOLDER="max"><BR>
    <BR>SODIUM:         <BR> <INPUT TYPE="NUMBER" NAME="sodiummin" PLACEHOLDER="min"> 
                             <INPUT TYPE="NUMBER" NAME="sodiummax" PLACEHOLDER="max"><BR>
    <BR><input type="submit" class="btn btn-info" name="submit" value="submit"/>
    </div>
  </form>
 </div>
   </form>
  </div>
<div class="container" style="width:600px;">
   <br /><br /> 
  
    <h2> Search meals by author <BR> </h2>
   <form action="result2.php" method="post" id="result_form">
    <div class="form-group">
     <label></label>
     <select id="author" name="authors[]" multiple class="form-control">
      <?PHP while(($row = oci_fetch_array($stmt2, OCI_BOTH))) { ?>
        <option value="<?PHP echo $row['AUTHOR']; ?>"> <?PHP echo $row['AUTHOR']; ?> </option>
      <?PHP } ?>
     </select>
    </div>
    <div class="form-group">
    <BR><input type="submit" class="btn btn-info" name="submit" value="submit"/>
  
    </div>
   </form>
  </div>
</center>
 </body>
</html>

<script>
$(document).ready(function(){
 $('#ingredient').multiselect({
  nonSelectedText: 'Select ingredients that must be included',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
});

$(document).ready(function(){
 $('#ningredient').multiselect({
  nonSelectedText: 'Select ingredients that must not be included',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
});

$(document).ready(function(){
 $('#author').multiselect({
  nonSelectedText: 'Select authors',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
});
</script>
