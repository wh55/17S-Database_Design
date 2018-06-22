<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">
    <title>Update Fines</title>
</head>

<body>

<?php
	include('config.php');
		
	$query="SELECT * FROM FINES";
	$res=mysqli_query($conn, $query);
	$finesArray = $res->fetch_all();
	
	$fineList[]="";
	$paidList[]="";
	foreach($finesArray as $fine){
		  $fineList[]=$fine[0];
		  $paidList[]=$fine[2];	
	}
	
	$query="SELECT * FROM BOOK_LOANS WHERE (Due_Date<Date_in AND Date_in!='0000-00-00') OR (Date_in='0000-00-00' AND Due_Date<\"" . date("Y/m/d") . "\")";
	$res=mysqli_query($conn, $query);
	
	while($resArray=mysqli_fetch_array($res)){
			
			$dueDate= new DateTime($resArray['Due_date']);
			if($resArray['Date_in']=='0000-00-00'){  //book not yet returned	
				$currDate= new DateTime(date("Y/m/d"));
				$diff=date_diff($dueDate,$currDate);
			  }
			
			else{
				$currDate= new DateTime($resArray['Date_in']);
				$diff=date_diff($dueDate,$currDate);
			 }
			
			$e = $diff->format("%R%a days");
			$diff=explode("+", $e);
			$dayDiff=explode(" ", $diff[1]);
			$dayDiff=$dayDiff[0];
						
		if(array_search($resArray['Loan_id'], $fineList) == FALSE){
			$query="INSERT INTO FINES(Loan_id, Fine_amt, Paid) VALUES(\"" . $resArray['Loan_id'] . "\", \"" . $dayDiff * 0.25 . "\",\"0\")";
			$res1=mysqli_query($conn, $query);
			if($res==FALSE)
			{
				die(mysql_error());
			}			
		}
		else{
			$index=array_search($resArray['Loan_id'], $fineList);
			if($paidList[$index]=='0'){  
					$query="UPDATE FINES SET Fine_amt = \"" . $dayDiff * 0.25 . "\" WHERE Loan_id=\"" . $resArray['Loan_id'] . "\"";
					$res1=mysqli_query($conn,$query);
			}
		}
	}//while


if(!isset($_GET['page'])){ //page goes to all the fines history
?>
	
<h1>Fines unpaid </h1> 

<?php
	$query="SELECT BOOK_LOANS.Card_id, Bname, SUM(FINES.Fine_amt) FROM FINES, BOOK_LOANS, BORROWER WHERE FINES.Loan_id=BOOK_LOANS.Loan_id AND BOOK_LOANS.Card_id=BORROWER.Card_id AND FINES.PAID=\"0\" GROUP BY Card_id";
	$res=mysqli_query($conn, $query);
	$row = mysqli_num_rows($res);
?>

	<p class="g3"><a href="updatefine.php?page=1"><button type="button">FINES HISTORY</button></a><br><br>
   <a href="fine.php"><button type="button">BACK TO SEARCH</button></a></p>
     <table>
      <caption>Found <?php echo $row;?> Records:</caption>     
        <thead>
            <tr>
                <th>Card ID</th>
                <th>Borrower</th>
                <th>Fine to pay</th>
            </tr>
        </thead>
     	<tbody>
     	
<?php	
		while($resArray=mysqli_fetch_array($res)){
?>		
    			<tr>
                <td><?php echo $resArray['Card_id']; ?></td>
                <td><?php echo $resArray['Bname']; ?></td>
                <td><?php echo $resArray['SUM(FINES.Fine_amt)']; ?></td>               
            </tr>
         	
<?php	
	}
?>
		</tbody>
    </table>	
    
  
<?php
}

else{
?>


	<h1>Fines History</h1> 

<?php
   $query="SELECT * FROM FINES, BOOK_LOANS, BORROWER WHERE FINES.Loan_id=BOOK_LOANS.Loan_id AND BOOK_LOANS.Card_id=BORROWER.Card_id";
	$res=mysqli_query($conn, $query);
	$row = mysqli_num_rows($res);
?>

<p class="g3"><a href="updatefine.php"><button type="button">FINES UNPAID</button></a><br><br>
<a href="fine.php"><button type="button">BACK TO SEARCH</button></a></p>	
	<table>
        <caption>Found <?php echo $row;?> Records:</caption>  
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
	while($resArray=mysqli_fetch_array($res)){
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
?>


</body>

</html>