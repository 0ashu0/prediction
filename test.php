<?php

include('./includes/connect.inc.php');
include 'algo.class.php';
?>

<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/colorbox.css" />
		<script type="text/javascript" src="js/jquery-latest.js"></script>
		<script type="text/javascript" src="js/jquery.colorbox.js"></script>
		<script type="text/javascript" src="js/jquery.validate.js"></script>
		<script>
			$(document).ready(function(){ 

				$(".group1").colorbox({width:"80%",height:"90%"});
				

			
				
				//Example of preserving a JavaScript event for inline calls.
				$("#click").click(function(){ 
					$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
					return false;
				});
	$("input").focus(function(){ 
		
		var value = $( this ).attr("title");
		
		if($(this).attr("value") == value)
		{ 
			$(this).attr( "value", "" );
			$( this ).removeClass( "hint" );
		}

	}).blur( function(){ 
		
		var value = $( this ).attr("title");
		if( $( this ).attr("value") == "" )
		{
			$( this ).attr( "value", value );
			$( this ).addClass( "hint" );
		}
	
	});
	$("#commentForm").validate();
});

		</script>
	</head>
	<body>
		<div class="form">
			<h2><a href="form.php">Prediction</a> <a style="float :right"; href="images/demo.jpg" class="group1">Demo Form</a></h2><hr><br>
		<form class="cmxform" id="commentForm" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
				
			<label>Algorithm Type : </label>
			<select name="algo_type" id="select">
				<option>ALGO 1</option>
				<option>ALGO 2</option>
				<option>ALGO 3</option>
				<option>ALGO 4</option>
			</select><br><br>

			<label>&nbsp;</label>
			<input type="submit" name="submit" value="SUBMIT" class="button"/>

		</form>

<?php
if(isset($_POST['submit'])){			
	extract($_POST);
	$db = mysqli_connect(HOST, USER, PASSWORD,NAME);
	$algo_type	= mysqli_real_escape_string($db, trim($algo_type));
	mysqli_query($db,"delete from OldTestLocMumResult");
	$query = "select * from OldTestLocMum";
	//$query = "select * from TestDataLocMum";
	$data = mysqli_query($db,$query);

	while($row = mysqli_fetch_array($data)){
		//echo $row['percent']." | ".$row['merit']." | ".$row['type']." | ".$row['gender']." <br />"; 
		$percent = "";
		$merit  = "";
		$type = "";
		$gender = "";
		$result = "";
				if($row['percent'] >= 80){ 
					
					$percent = "distinction";

				}elseif($row['percent'] < 80 && $row['percent'] >= 60){ 

					$percent = "first_class";
					
				}else{ 
				
					$percent = "second_class";
				}

				if($row['merit'] > 120){ 
					
					$merit = "good";

				}else{ 
				
					$merit = "bad";
				}
				if($algo_type === "ALGO 1"){
					$obj = new Algo();
					$result = $obj->dtalgo1($percent, $merit, $type, $gender);
				}
				if($algo_type === "ALGO 2"){
					$obj = new Algo();
					$result = $obj->dtalgo2($percent, $merit, $type, $gender);
				}
				if($algo_type === "ALGO 3"){
					$obj = new Algo();
					$result = $obj->dtalgo3($percent, $merit, $type, $gender);
				}
				if($algo_type === "ALGO 4"){
					$obj = new Algo();
					$result = $obj->dtalgo4($percent, $merit, $type, $gender);
				}

		$app_id = $row['app_id'];
		$query2 = "insert into OldTestLocMumResult values('$app_id','$result')";
		//$query2 = "update TestDataLocMum set class = '$result' where app_id = '$app_id'";
		mysqli_query($db,$query2);
	}	
	$query3 = "select count(*) as total from OldTestLocMum natural join OldTestLocMumResult";
	$data2 = mysqli_query($db,$query3);
	$row1 = mysqli_fetch_array($data2);
	$count = $row1['total'];
	$total = mysqli_num_rows($data);

	$accuracy = (float)($count/$total)*100; 
?>
<br />
<hr>
<h2>Evalutaion was successful</h2>
<h1>Achieved Accuracy = <?php echo $accuracy?>%</h1>
<?php
}


?>
		</div>

		</body>
</html>