<?php
include 'Place Bet Header.html';
require_once '../../Database.php';

   try { 
        $pdo = Database::getInstance()->connect();

        $sql = 'CALL PROCEDURE selectFixturesForBet()';
        $result = $pdo->query($sql); 
        ?>

        <br>
        <table class="table table-hover">
        <tr>
            <th>Home Team</th>
            <th>Away Team</th>
            <th>Select</th>
        </tr>

        <?php 
        while ($row = $result->fetch())  {

            ?>
        <tr>
            <td> <?php echo $row['fk_HTeam'] ?> </td>
            <td>  <?php echo $row['fk_ATeam'] ?></td> 
            <td><a href="placeBet.php?fixtureID=<?php echo $row['fixtureID']?>">Select</a></td>
        </tr>
        <?php } ?>
        </table>
        <?php
        $pdo = Database::getInstance()->disconnect();
   }

catch (PDOException $e) { 
$output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(); 
}
?>
