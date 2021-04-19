<?php
include ("databaseHandler.php");
db_connect();
?>

<table width="500" border="1" style = "margin:auto;">
 <tr>
    <th>ClientID</th>
    <th>NickName</th>
    <th>Count</th>
    <th>Ban</th>
  </tr>

<?php
$sql_statement = "SELECT * FROM tblCurrClients ORDER BY count DESC";
$query_results = $db_link->query($sql_statement) or die("This did not work Prining Table");

if($query_results->num_rows>0)
{
	while($data_row = $query_results->fetch_assoc()){?>
 
  <tr>
      <td><?php echo $data_row[clientID]?></td>
	  <td><?php echo $data_row[nickName]?></td>
      <td><?php echo $data_row[count]?></td>
      <td><input name="banBtn" type="button" value = "Ban" onclick="banClient('<?php echo $data_row[clientID]?>')"/></td>
  </tr>

<?php }}?>

</table>

