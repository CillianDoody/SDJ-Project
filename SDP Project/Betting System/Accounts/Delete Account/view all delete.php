<?php
include 'head.html';
require_once '../../Database.php';

   try { 
$pdo = Database::getInstance()->connect();

$sql = 'CALL selectCustomer()';
$result = $pdo->query($sql); 
?>

<br>
<table class="table table-hover">
<tr><th>User Id</th>
<th>First Name:</th><th>Last Name:</th><th>Delete</th></tr>

<?php 
while ($row = $result->fetch())  {

	?>
<tr><td> <?php echo $row['accountID'] ?> </td><td>  <?php echo $row['forename'] ?></td> <td><?php echo $row['surname'] ?></td>
<td><a href="delete.php?accountID=<?php echo $row['accountID'] ?>">Remove</a></td>
     </tr>
<?php } ?>
</table>
<?php
   }

catch (PDOException $e) { 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}




?>
