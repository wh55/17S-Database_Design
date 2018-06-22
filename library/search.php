<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">
    <title>Search and Check out</title>
</head>

<body>

<h1>Search books and Check out</h1>

<?php
if (isset($_GET['bookID']) || isset($_GET['bookID10']) ||isset($_GET['bookTitle']) || isset($_GET['bookAuthor']))
{
	if (isset($_GET['bookID']))
		$bookID=$_GET['bookID'];
	else
		$bookID='';
		
	if (isset($_GET['bookID10']))
		$bookID10=$_GET['bookID10'];
	else
		$bookID10='';
	
	if (isset($_GET['bookTitle']))
		$bookTitle=$_GET['bookTitle'];
	else
		$bookTitle='';
	
	if (isset($_GET['bookAuthor']))
		$bookAuthor=$_GET['bookAuthor'];
	else
		$bookAuthor='';
?>

	<form action="search.php" method="get">
            <div><label for="bookID">Isbn13</label><div><input type="text" name="bookID" placeholder="Please Enter Isbn13" value="<?php echo $bookID?>"></div></div>         
            <div><label for="bookID10">Isbn10</label> <div><input type="text" name="bookID10" placeholder="Please Enter Isbn10" value="<?php echo $bookID10?>"></div></div>
            <div><label for="bookTitle">Title</label><div><input type="text" name="bookTitle" placeholder="Please Enter Book Title" value="<?php echo $bookTitle?>"></div></div>
            <div><label for="bookAuthor">Author</label> <div><input type="text" name="bookAuthor" placeholder="Please Enter Author Name" value="<?php echo $bookAuthor?>"></div></div>
            <button type="submit">SEARCH</button>
    </form>	
			
<?php	
	include('config.php');
	$query= "SELECT BOOK.Isbn13, Isbn10, Title, GROUP_CONCAT(Name) AS Name, Copies FROM BOOK, BOOK_AUTHOR, AUTHOR WHERE BOOK.Isbn13 = BOOK_AUTHOR.Isbn13 AND BOOK_AUTHOR.Author_id = AUTHOR.Author_id AND BOOK.Isbn13 LIKE '%" . $bookID . "%' AND  BOOK.Isbn10 LIKE '%" . $bookID10 . "%'AND BOOK.Title LIKE '%" . $bookTitle . "%' AND AUTHOR.Name LIKE '%" . $bookAuthor . "%' GROUP BY BOOK.Isbn13, Isbn10, Title, Copies";
	$res= mysqli_query($conn,$query);
	
	if(!$res) {
    	die("Connection failed: " . mysql_error()); 
	}
	$row = mysqli_num_rows($res);
	
	if($row>0){
?>
  <p class="g2">CHECK OUT button will lead to check out page and a Card ID will be required.</p>
	<table>
        <caption>Found <?php echo $row;?> Records:</caption>  
    <thead>
            <tr>
                <th>Isbn13</th>
                <th>Isbn10</th>
                <th>Title</th>
                <th>Author</th>
                <th>Total Copies</th>
                <th>Available Copies</th>
                <th></th>
            </tr>
        </thead>
        <tbody>       

<?php	
	while($resArray=mysqli_fetch_array($res))
	{
		$query1 = "SELECT * FROM BOOK_LOANS WHERE Isbn13 LIKE '" . $resArray['Isbn13'] . "' AND Date_in='0000-00-00'";
		$res1= mysqli_query($conn,$query1);
		$checkedOut = mysqli_num_rows($res1); //number of checked out copies
	
?>
     	<tr>
            <td><?php echo $resArray['Isbn13']; ?></td>
            <td><?php echo $resArray['Isbn10']; ?></td>
            <td><?php echo $resArray['Title']; ?></td>
            <td><?php echo $resArray['Name']; ?></td>
            <td><?php echo $resArray['Copies']; ?></td>
            <td><?php echo $resArray['Copies'] - $checkedOut; ?></td>
            <td>
            	<?php if(($resArray['Copies'] - $checkedOut) >0){//copie available
					?>
            		<button type="button" id="<?php echo $resArray['Isbn13'];?>" findbookID="<?php echo $resArray['Isbn13']; ?>" onClick="checkout(this.id)">CHECK OUT</button>
            <?php
					        }
					  else{//Book is not available in this particular library
		       ?>
					  <button class="disable" type="button" disabled id="<?php echo $resArray['Isbn13'];?>"  findbookID="<?php echo $resArray['Isbn13']; ?>"  onClick="checkout(this.id)">CHECK OUT</button>
			<?php
                      }
			?>	  
            </td>
		</tr>
<?php   
    }//while
?>

		</tbody>
	</table>
    
<?php
 }
	else{  //if no records exist
?>		
        <p class="g4">No records found.</p> 
<?php
	  }
}


else
{
?>
		
	<form action="search.php" method="get">        
           <div><label for="bookID"> Isbn13</label> <div><input type="text" name="bookID" placeholder="Please Enter Isbn13"></div></div>
            <div><label for="bookID10">Isbn10</label>  <div><input type="text" name="bookID10" placeholder="Please Enter Isbn10"></div></div>
            <div><label for="bookTitle">Title</label>     <div><input type="text" name="bookTitle" placeholder="Please Enter Book Title"></div></div>
            <div><label for="bookAuthor">Author </label> <div><input type="text" name="bookAuthor" placeholder="Please Enter Author Name"></div></div>
            <button type="submit">SEARCH</button>
	</form>

<?php
}

?>

<script>
function checkout(buttonID){
		var button=document.getElementById(buttonID);		
		var bookID=button.getAttribute("findbookID");		
		
		window.location.href = 'checkout.php?bookID='+bookID; 
}
</script>



</body>

</html>