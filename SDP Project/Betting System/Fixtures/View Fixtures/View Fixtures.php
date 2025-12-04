<?php
include 'View Fixture Head.html';
require_once '../../Database.php';
   try { 
        $pdo = Database::getInstance()->connect();

        $stmt = $pdo->prepare('CALL selectByDate()');
        $stmt->execute();
        $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
?>
<br>
<table class="table table-hover">
<tr>
    <th>Home Team:</th> <th>Away Team:</th> <th>Date:</th> <th>Time:</th> 
    <th>Home Team Manger:</th> <th>Away Team Manager:</th> <th>Grounds:</th>
</tr>

<?php 
    foreach ($allRows as $row) {
        $fixtureID = $row['fixtureID'];

        $stmt1 = $pdo->prepare('CALL selectHTeamEachFixture(:fixtureID)');
        $stmt1->bindValue(':fixtureID', $fixtureID, PDO::PARAM_INT);

        $stmt2 = $pdo->prepare('CALL selectATeamEachFixture(:fixtureID)');
        $stmt2->bindValue(':fixtureID', $fixtureID, PDO::PARAM_INT);
    
        $row1 = $stmt1->fetch();
        $row2 = $stmt2 ->fetch();
?>
        <tr><td><?php echo $row['fk_HTeam']?></td> <td><?php echo $row['fk_ATeam']?></td> <td><?php echo $row['Fdate'] ?></td>
        <td><?php echo $row['Ftime'] ?></td> <td><?php echo $row1['manager'] ?></td> <td><?php echo $row2['manager'] ?></td> 
        <td><?php echo $row1['grounds'] ?></td>
        </tr>
<?php 
    } 
?>
</table>
<?php
$pdo = Database::getInstance()->disconnect();
   }

    catch (PDOException $e) { 
        $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
    }
?>
