<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">
    <title>Search and Check out</title>
</head>

<body>


<h1>Search loan records and Check in</h1>

<?php
	if (isset($_GET['bookID']))
		$bookID=$_GET['bookID'];
	else
		$bookID='';
	
	if (isset($_GET['cardID']))
		$cardID=$_GET['cardID'];
	else
		$cardID='';
		
	if (isset($_GET['bookTitle']))
		$bookTitle=$_GET['bookTitle'];
	else
		$bookTitle='';
		
	if (isset($_GET['bname']))
		$bname=$_GET['bname'];
	else
		$bname='';
	
	if (isset($_GET['loanID']))
		$loanID=$_GET['loanID'];
	else
		$loanID='';
	
	if (!isset($_GET['loanID'])){
?>
    
        <form action="checkin.php" method="get">
            <div><label for="cardID">Card ID</label><div><input type="text" id="cardID" name="cardID" placeholder="Please Enter Card ID" value="<?php echo $cardID?>"></div></div>
            <div><label for="bname">Borrower's Name</label><div><input type="text" id="bname" name="bname" placeholder="Please Enter Borrower's Name" value="<?php echo $bname?>"></div></div>
            <div><label for="bookID">Isbn13</label><div><input type="text" id="bookID" name="bookID" placeholder="Please Enter Isbn13" value="<?php echo $bookID?>"></div></div>
            <div><label for="bookTitle">Book Title</label><div><input type="text" id="bookTitle" name="bookTitle" placeholder="Please Enter Book Title" value="<?php echo $bookTitle?>"></div></div>
             <button type="submit">SEARCH</button>
        </form>	
            
<?php       
        include('config.php');
        if (isset($_GET['bookID']) ||isset($_GET['cardID'])){
        
            $query= "SELECT * from BOOK, BORROWER, BOOK_LOANS WHERE BOOK.Isbn13 = BOOK_LOANS.Isbn13 AND BORROWER.Card_id = BOOK_LOANS.Card_id AND BOOK_LOANS.Date_in = \"0000-00-00\" AND BOOK_LOANS.Card_id LIKE \"%". $cardID . "%\" AND Bname LIKE \"%" . $bname . "%\" AND BOOK.Isbn13 LIKE \"%" . $bookID . "%\" AND  BOOK.Title LIKE \"%" . $bookTitle . "%\"" ;
            $res= mysqli_query($conn,$query);
            $row = mysqli_num_rows($res);
            
            if($row>0){           
 ?>
                <p class="g2">Carefully choose a book then click CHECK IN to return it.</p>  
                <table>
                    <caption>Found <?php echo $row;?> Records</caption>                   
                    <thead>
                        <tr>
                            <th>Loan ID</th>
                            <th>Card ID</th>
                            <th>Borrower Name</th>
                            <th>Book ID</th>
                            <th>Title</th>
                            <th>Checkout Date</th>
                            <th>Due Date</th>
                            <th></th>
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
                        <td><?php echo $resArray['Isbn13']; ?></td>
                        <td><?php echo $resArray['Title']; ?></td>
                        <td><?php echo $resArray['Date_out']; ?></td>
                        <td><?php echo $resArray['Due_date']; ?></td>
                        <td>
                         <button type="button" id=" <?php echo $resArray['Loan_id']?> " findloanID="<?php echo $resArray['Loan_id']; ?>" onClick="checkin(this.id)">CHECK IN</button>
<?php
                    }
?>
                        </td>
                    </tr>
                    </tbody>
                   </table>

<?php
                 }
		else{ //row<=0, now records
?>		
              <p class="g4">No records found.</p> 
<?php
	   }
		
   }
}
	
    else{
			include('config.php');
			$query="SELECT * FROM BOOK_LOANS, BORROWER, BOOK WHERE Loan_id = \"" . $loanID . "\" AND BOOK.Isbn13=BOOK_LOANS.Isbn13 AND BOOK_LOANS.Card_id=BORROWER.Card_id" ;
			$res= mysqli_query($conn,$query);
			$resArray=mysqli_fetch_array($res);
?>
						                     
             <table>
                <thead>
                        <tr>
                            <th>Loan ID</th>
                            <th>Card ID</th>
                            <th>Borrower's Name</th>
                            <th>Isbn13</th>
                            <th>Title</th>
                            <th>Checkout Date</th>
                            <th>Due Date</th>
                        </tr>
                </thead> 
                <tbody>       
                        <tr>
                            <td><?php echo $resArray['Loan_id']; ?></td>
                            <td><?php echo $resArray['Card_id']; ?></td>
                            <td><?php echo $resArray['Bname']; ?></td>
                            <td><?php echo $resArray['Isbn13']; ?></td>
                            <td><?php echo $resArray['Title']; ?></td>
                            <td><?php echo $resArray['Date_out']; ?></td>
                            <td><?php echo $resArray['Due_date']; ?></td>
                        </tr>
                </tbody>
                </table> 
                <br>
                               
<?php
		 if($resArray['Date_in']=="0000-00-00"){ //book not yet returned
				  $query="UPDATE BOOK_LOANS SET Date_in= \"" .  date("Y/m/d") . "\" WHERE Loan_id = \"" . $loanID . "\"" ;
				  $res= mysqli_query($conn,$query);
				
				 if($res){ //update succeed
?>
					     <p class="g3">Book successfully checked in. </p>

<?php
				    }
		  }

}
		

?>


<script>
function checkin(buttonID){
	var button = document.getElementById(buttonID);
	var loanID = button.getAttribute("findloanID");
	
	window.location.href = "checkin.php?loanID=" + loanID;	
}

</script>


</body>

</html>