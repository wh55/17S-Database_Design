<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">
    <title>Fines</title>
</head>

<body>


<?php
include('config.php');

if (isset($_GET['cardID']))
	$cardID=$_GET['cardID'];
else
	$cardID='';
?>


<h1>Search fines and Pay</h1>

<p class="g3"><a href="updatefine.php"><button type="button">UPDATE AND VIEW RECORDS</button></button></a></p>

	<form action="fine.php" method="get">   	
        <div><label for="cardID">Card ID</label><div><input type="text" id="cardID" name="cardID" placeholder="Please Enter Card ID" value="<?php echo $cardID?>"></div></div>
        <button type="submit">SEARCH by CARD ID</button>
    </form>	

<?php

if (isset($_GET['cardID']) && !isset($_GET['pay'])){
	$cardID=$_GET['cardID'];
	
	$query="SELECT SUM(FINES.Fine_amt), Card_id FROM FINES, BOOK_LOANS WHERE FINES.Loan_id=BOOK_LOANS.Loan_id AND FINES.Paid=\"0\" AND BOOK_LOANS.Card_id =\"". $cardID ."\"";
	$res=mysqli_query($conn,$query);
	$resArray=mysqli_fetch_array($res);
	if($resArray['SUM(FINES.Fine_amt)']!=NULL){ //fine record exists
		
?>
	<div class="g2">
	<p>Card ID: <?php echo $resArray['Card_id'];?><br>
	       Amount to pay: $<?php echo $resArray['SUM(FINES.Fine_amt)'];?></p>
	<p><a href="fine.php?cardID=<?php echo $cardID;?>&pay=1"><button type="button">PAY NOW</button></button></a></p>	
	</div>
	 
<?php
   $query="SELECT * FROM FINES, BOOK_LOANS, BORROWER WHERE FINES.Loan_id=BOOK_LOANS.Loan_id AND BOOK_LOANS.Card_id=BORROWER.Card_id AND BOOK_LOANS.Card_id =\"". $cardID ."\" AND FINES.Paid=\"0\"";
	$res=mysqli_query($conn, $query);
?>
	
	<table>
        <caption>Details:</caption>
        <thead>
            <tr>
                <th>Loan ID</th>
                <th>Card ID</th>
                <th>Borrower</th>
                <th>Fine</th>
                <th>Paid</th>
            </tr>
        </thead>
     	<tbody>
		
<?php		
	while($resArray=mysqli_fetch_array($res)) {
?>				                       
            <tr>
			       <td><?php echo $resArray['Loan_id']; ?></td>
                <td><?php echo $resArray['Card_id']; ?></td>
                <td><?php echo $resArray['Bname']; ?></td>
                <td><?php echo $resArray['Fine_amt']; ?></td>
                
<?php
    if($resArray['Paid']=='0')
	      echo "<td>No</td>";
    else
	      echo "<td>Yes</td>";
?>
            </tr>
        	
<?php	
	} //while
?>
		</tbody>
    </table>		

<?php
	
	}
	else{
?>
    <br>
	<p class="g3">No fines found. </p>
<?php
	}
	
}

if (isset($_GET['cardID']) && isset($_GET['pay'])){//pay fine
	$cardNo=$_GET['cardID'];

	$query="SELECT COUNT(*) FROM BOOK_LOANS WHERE Card_id=\"" . $cardID . "\" and Date_in='0000-00-00'";
	$res=mysqli_query($conn, $query);
	$resArray=mysqli_fetch_array($res);
	
	if($resArray['COUNT(*)']>0){//book not returned
?>
	<p class="g3">Borrower should return all the books to pay the fine.</p>
<?php			
	}
	
	else{
		$query="UPDATE FINES SET Paid=1 WHERE Loan_id IN (SELECT Loan_id FROM BOOK_LOANS WHERE Card_id=\"" . $cardID. "\")";
		$res=mysqli_query($conn, $query);
		if($res){
	?>
		<p class="g4">Fine successfully Paid.</p>
	<?php	
		}
	 }
	 
	 
}

?>


</body>

</html>