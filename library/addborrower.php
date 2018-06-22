<!DOCTYPE html>


<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="mycss.css">
    <title>Search and Check out</title>
</head>

<body>

<h1>Add Borrower </h1>

<?php
if(!isset($_GET['ssn']) && !isset($_GET['bname']) && !isset($_GET['address']) && !isset($_GET['phone'])) {
?>

    <form action="addborrower.php" method="get">
        <div><label for="ssn">Ssn *</label><div><input type="text" id="ssn" name="ssn" placeholder="Please Enter Ssn No."></div></div>        
        <div><label for="bname">Name *</label><div><input type="text" id="bname" name="bname" placeholder="Please Enter Borrower's Name"></div></div>        
        <div><label for="address">Address *</label><div><input type="text" id="address" name="address" placeholder="Please Enter Address"></div></div>       
        <div><label for="phone">Phone No.</label><div><input type="text" id="phone" name="phone" placeholder="Please Enter Phone No."></div></div>       
        <button type="submit">CREATE NEW</button>
    </form>	
<?php
}

else{
	include('config.php');
	if (isset($_GET['ssn']))
		$ssn=$_GET['ssn'];
	else
		$ssn='';
		
	if (isset($_GET['bname']))
		$bname=$_GET['bname'];
	else
		$bname='';
		
	if (isset($_GET['address']))
		$address=$_GET['address'];
	else
		$address='';
	
	if (isset($_GET['phone']))
		$phone=$_GET['phone'];
	else
		$phone='';
		
	if ($ssn=='' || $bname=='' || $address==''){//information not completed
?>
      <form action="addborrower.php" method="get">
            <div><label for="ssn">Ssn *</label><div><input type="text" id="ssn" name="ssn" placeholder="Please Enter Ssn No." value="<?php echo $ssn;?>"> 
            <?php if($ssn=='') {?>
			   <span class="g5">Please enter borrower's Ssn number.</span>
             <?php } ?> </div></div>
            
            <div><label for="bname">Name *</label><div><input type="text" id="bname" name="bname" placeholder="Please Enter Borrower's Name" value="<?php echo $bname;?>">
             <?php if($bname=='') {?>
				<span class="g5">Please enter borrower's name:</span>
             <?php } ?> </div></div>
            
            <div><label for="address">Address *</label><div><input type="text" id="address" name="address" placeholder="Please Enter Address" value="<?php echo $address;?>">
             <?php if($address==''){?>
				<span class="g5">Please enter borrower's address.</span>
            <?php } ?> </div></div>
            
            <div><label for="phone">Phone No.</label><div><input type="text" id="phone" name="phone" placeholder="Please Enter Phone No." value="<?php echo $phone;?>">
             </div></div>
             <button type="submit">CREATE NEW</button>
        </form>	
        
<?php
	}
	
	else{//information completed
	    //check if Ssn is unique
		$query = "SELECT 1 FROM BORROWER WHERE Ssn=\"" . $ssn . "\"";
		$res = mysqli_query($conn,$query);
		if($res == FALSE) {
?>
		<p>Something went wrong. Please try again or contact the webmaster.</p>
		
<?php
			die(mysql_error()); 
		}
		$row = mysqli_num_rows($res);
		if($row==1){ //borrower already exisits
?>
		<form action="addborrower.php" method="get">
           <div><label for="ssn">Ssn *</label><div><input type="text" id="ssn" name="ssn" placeholder="Please Enter Ssn No." value="<?php echo $ssn?>"></div></div>
           <div><label for="bname">Name *</label><div><input type="text" id="bname" name="bname" placeholder="Please Enter Borrower's Name" value="<?php echo $bname?>"></div></div>
           <div><label for="address">Address *</label><div><input type="text" id="address" name="address" placeholder="Please Enter Address" value="<?php echo $address?>"></div></div>
           <div><label for="phone">Phone No.</label><div><input type="text" id="phone" name="phone" placeholder="Please Enter Phone No." value="<?php echo $phone?>"></div></div>
           <button type="submit">CREATE NEW</button>
       </form>	
            <p class="g4">Borrower already exists. Please use a different Ssn number to register.</p>
<?php
		}
		else{//create new borrower
			$query="SELECT MAX(Card_id) FROM BORROWER";
			$res= mysqli_query($conn,$query);
			$resArray=mysqli_fetch_array($res);
			$nextCardID=$resArray['MAX(Card_id)']+1;   //new card ID
			
			$query="INSERT INTO BORROWER (Card_id, Ssn, Bname, Address, Phone) Values(\"" . $nextCardID . "\", \"" . $ssn . "\", \"" . $bname . "\", \"" . $address . "\", \"" . $phone . "\")";
			$res=mysqli_query($conn, $query);
			if(!$res){//update fails
?>
				<p>Something went wrong. Please try again or contact the webmaster.</p>
<?php
			}
			else{//add new successfully
				$query="SELECT MAX(Card_id) FROM BORROWER";
				$res= mysqli_query($conn,$query);
				
				if(!$res){ //query fails
					die(mysql_error()); 
				}
				$resArray=mysqli_fetch_array($res);
				$cardID=$resArray['MAX(Card_id)'];
				$query="Select * from BORROWER WHERE Card_id=\"" . $cardID . "\"";
				$res=mysqli_query($conn, $query);
				$resArray=mysqli_fetch_array($res);
?>
			<p class="g3"> Borrower can checkout maximum 3 books. Each book can be checked out for 14 days.</p>
            <table>
             <caption>New borrower added:</caption>  
    			<thead>
                    <tr>
                        <th>Card ID</th>
                        <th>Ssn</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Phone No.</th>
                    </tr>
        		</thead>
       		 <tbody>            
 
               		<tr>
            			     <td><?php echo $resArray['Card_id']; ?></td>
            			     <td><?php echo $resArray['Ssn']; ?></td>
                        <td><?php echo $resArray['Bname']; ?></td>
                        <td><?php echo $resArray['Address']; ?></td>
                        <td><?php echo $resArray['Phone']; ?></td>
                    </tr>
             
            </tbody>
            </table>         
<?php
			}
		
		}
	}
		
}
?>


</body>
</html>