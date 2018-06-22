<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">
    <title>Check out</title>
</head>

<body>

<h1>Check out book</h1>

<?php

if (isset($_GET['bookID']) && !isset($_GET['cardID'])){
	   if (isset($_GET['bookID']))
		       $bookID=$_GET['bookID'];
	   else
		       $bookID='';
?>

	<form action="checkout.php" method="get">
          <div><label for="cardID">Card ID</label><div><input type="text" id="cardID" name="cardID" placeholder="Please Enter Card ID"></div></div>
          <div><label for="bookID">Book ID</label><div><input type="text" id="bookID" name="bookID" placeholder="Please Enter Isbn13" value="<?php echo $bookID?>"></div></div>
           <button type="submit">CHECK OUT</button>
    </form>	

<?php
}

elseif(isset($_GET['bookID']) && isset($_GET['cardID'])){
	
	     if (isset($_GET['bookID']))
		          $bookID=$_GET['bookID'];
	     else
		          $bookID='';
	
	     if (isset($_GET['cardID']))
		          $cardID=$_GET['cardID'];
	     else
		          $cardID='';
?>

	<form action="checkout.php" method="get">
	      <div><label for="cardID">Card ID</label><div><input type="text" id="cardID" name="cardID" placeholder="Please Enter Card ID" value="<?php echo $cardID ?>">
	      <?php   
						include('config.php');
						$query= "SELECT 1 from BORROWER WHERE Card_id='" . $cardID . "'";
						$res2= mysqli_query($conn,$query);
						if (mysqli_num_rows($res2) == 0) { //card ID not valid
			?>
						<span class="g5">The Card ID was not correct.</span>
			<?php	
						}						
         ?>
	      </div></div>
	      
        <div><label for="bookID">Isbn13</label><div><input type="text" id="bookID" name="bookID" placeholder="Please Enter Isbn13" value="<?php echo $bookID?>">
         <?php   						
						$query= "SELECT 1 from BOOK WHERE Isbn13='" . $bookID . "'";
						$res1= mysqli_query($conn,$query);
						if (mysqli_num_rows($res1) == 0) {  //book ID not valid
			?>
						<span class="g5">The Isbn13 was not correct.</span>
			<?php	
						}
          ?>
        </div></div>

<?php
			if(mysqli_num_rows($res1) == 0 || mysqli_num_rows($res2) == 0){
?>
           <button type="submit">CHECK OUT</button>
<?php
			}
			else{//this means bookID, branchID and CardNo. entered is correct
				
				$query= "SELECT * FROM BOOK_LOANS, BOOK, BOOK_AUTHOR WHERE BOOK_LOANS.Isbn13 = BOOK.Isbn13 AND BOOK.Isbn13=BOOK_AUTHOR.Isbn13 AND Card_id = '" . $cardID . "' AND Date_in = '0000-00-00'";
				$res3= mysqli_query($conn,$query);
							$checkout='0';
					      //check if this user has unpaid fines
							$query="SELECT * FROM BOOK_LOANS, FINES WHERE BOOK_LOANS.Loan_id=FINES.Loan_id AND FINES.PAID=0";
							$result=mysqli_query($conn, $query);
							$resArray = $result->fetch_all();
							$cardIDArray[]="";
							foreach($resArray as $res1){
								$cardIDArray[]=$res1[3];	
							}
					if(array_search($cardID, $cardIDArray)!=FALSE){//user has unpaid fines
						?>
                        <p class="g3">The borrower has unpaid fines. Please pay fines before check out any books.</p>			
<?php
					}
					elseif (mysqli_num_rows($res3) >= 3 ) { //maximum number of books
							?>
                            <p class="g3">The borrower has checked out maximum number of allowed books. </p>
							  <table>
                               <caption>Loan details:</caption>  
                                <thead>
                                    <tr>
                                        <th>Loan ID</th>
                                        <th>Book ID</th>
                                        <th>Title</th>
                                        <th>Checkout Date</th>
                                        <th>Due Date</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>       
                        
                        <?php	
                            while($resArray=mysqli_fetch_array($res3))
                            {
                        ?>
                                <tr>
                                    <td><?php echo $resArray['Loan_id']; ?></td>
                                    <td><?php echo $resArray['Isbn13']; ?></td>
                                    <td><?php echo $resArray['Title']; ?></td>
                                    <td><?php echo $resArray['Date_out']; ?></td>
                                    <td><?php echo $resArray['Due_date']; ?></td>
                                    <td><button type="button" id=" <?php echo $resArray['Isbn13'].$cardID;?> " findbookID="<?php echo $resArray['Isbn13']; ?>" findcardID="<?php echo $cardID;?>" onClick="checkout(this.id)">CHECK IN</button></td>
                                </tr>
                        <?php   
                            }
                        ?>
                                   </tbody>
                            </table>			

<?php
				     	}

					else{

						$query1 = "SELECT * FROM BOOK_LOANS WHERE Isbn13 LIKE '" . $bookID . "' AND Date_in='0000-00-00'";
						$res1= mysqli_query($conn,$query1);
						$checkedOut = mysqli_num_rows($res1);
						
						$query1 = "SELECT Copies FROM BOOK WHERE Isbn13 LIKE '" . $bookID . "'";
						$res1= mysqli_query($conn,$query1);
						$resArray=mysqli_fetch_array($res1);
						$totalBooks=$resArray['Copies'];
						
						$remaining = $totalBooks - $checkedOut; //copies available
						
						if($remaining>0){ //copies available
							$query="SELECT MAX(Loan_id) FROM BOOK_LOANS";
							$res= mysqli_query($conn,$query);
							$resArray=mysqli_fetch_array($res);
							$nextLoanID=$resArray['MAX(Loan_id)']+1;
							
							$query = "INSERT INTO BOOK_LOANS (Loan_id, Isbn13, Card_id, Date_out, Due_date, Date_in) VALUES ('" . $nextLoanID . "', '" . $bookID . "', '" . $cardID . "', '" . date("Y/m/d") . "', " . "DATE_ADD(Date_out, INTERVAL 14 DAY)" . ", '0000-00-00')"  ;
							$res= mysqli_query($conn,$query);
							if($res){  //check out succeed
							
							$query = "SELECT * FROM BOOK_LOANS, BORROWER, BOOK WHERE Loan_id = \"" . $nextLoanID . "\" AND BOOK.Isbn13=BOOK_LOANS.Isbn13 AND BOOK_LOANS.Card_id=BORROWER.Card_id";
							$res= mysqli_query($conn,$query);
							$resArray=mysqli_fetch_array($res);
?>
							<p class="g3">Book successfully checked out. Due date is <?php echo $resArray['Due_date'] ?>:</p>											 
						                     
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
																
								
<?php	
					      }
							else{ //check out failed
?>
								<p class="g4">Something went wrong. Please try again or contact webmaster.</p>
							
<?php
							  }
					}
			else{
?>
                            	<p class="g4">No available copy of this book. Please search for another book.</p>
<?php
              }
								
	}
					
}
				
			
?>	
    </form>
    	
<?php	
}

else
{
?>
<form action="checkout.php" method="get">
   		<div><label for="cardID">Card ID</label><div><input type="text" id="cardID" name="cardID" placeholder="Please Enter Card ID"></div></div>
       <div><label for="bookID">Isbn13</label><div><input type="text" id="bookID" name="bookID" placeholder="Please Enter Isbn13"></div></div>
       <button type="submit">CHECK OUT</button>
</form>	
    
<?php 
}
?>
	

<script>
	function checkout(buttonID){
		var button=document.getElementById(buttonID);		
		var bookID = button.getAttribute("findbookID");
		var cardID = button.getAttribute("findcardID");
		
		window.location.href = 'checkin.php?bookID=' + bookID + '&cardID=' + cardID; 
}
	</script>



</body>

</html>