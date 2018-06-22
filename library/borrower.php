<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">	
    <title>Search Borrower</title>
</head>

<body>

<h1>Add or Search Borrowers</h1>

<p class="g3"><a href="addborrower.php"><button type="button">ADD NEW</button></a><br><br>
ADD NEW button will lead to a registration form. </p> 

<?php
	if (isset($_GET['cardID']))
		$cardID=$_GET['cardID'];
	else
		$cardID='';
		
	if (isset($_GET['bname']))
		$bname=$_GET['bname'];
	else
		$bname='';	
	?>
    
  <form action="borrower.php" method="get">
         <div><label for="cardID">Card ID</label><div><input type="text" id="cardID" name="cardID" placeholder="Please Enter Card ID" value="<?php echo $cardID?>"></div></div>
          <div><label for="bname">Borrower's Name</label><div><input type="text" id="bname" name="bname" placeholder="Please Enter Borrower's Name" value="<?php echo $bname?>"></div></div>
          <button type="submit">SEARCH</button>
  </form>	
    
        
<?php
        include('config.php');
        if (isset($_GET['cardID']) ||isset($_GET['bname'])){
        
            $query= "SELECT * from BORROWER WHERE Card_id LIKE \"%".  $cardID . "%\" AND Bname LIKE \"%" . $bname . "%\"" ;
            $res= mysqli_query($conn,$query);
            $row = mysqli_num_rows($res);
            
            if($row>0){           
 ?>
                <p class="g2">To check the loan information, please refer to SEARCH LOANS / CHECK IN page.</p>

                <table>
                    <caption>Found <?php echo $row;?> Results</caption>  
                    <thead>
                        <tr>
                            
                            <th>Borrower Name</th>                                                        
                            <th>Card ID</th>
                            <th>Ssn</th>
                            <th>Adress</th>
                            <th>Phone</th>
                        </tr>
                     </thead>
                     <tbody>       
            
<?php	
                while($resArray=mysqli_fetch_array($res)){
?>
                    <tr>
                        <td><?php echo $resArray['Bname']; ?></td>
                        <td><?php echo $resArray['Card_id']; ?></td>
                        <td><?php echo $resArray['Ssn']; ?></td>
                        <td><?php echo $resArray['Address']; ?></td>
                        <td><?php echo $resArray['Phone']; ?></td>
                        <td>
<?php
                  }
?>
                        </td>
                    </tr>
                    </tbody>
                   </table>
<?php
              }		
		
		else{ //row<=0, no records
?>		
                 <p class="g4">No records found.</p> 
<?php
	     }
		
}
?>	
		
	


</body>

</html>