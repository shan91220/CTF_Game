<?php
	require_once('../utils/check_admin.php');
	
	if ($_POST) {
		 		 
          require_once('../config/database.php');
		  
		  $Modify = $_POST['Modify'];
          $Name = $_POST['Name'];
          $Point = $_POST['Point'];
          $Description = $_POST['Description'];
          $Flag = $_POST['Flag'];
          
          if($Name == "cuTeTurt1eDe1eteU"){
			  
			  $dir=(string)$Point;
			  if(is_dir($dir)){
				
				$file = scandir($dir);
			    for($i=2;$i<sizeof($file);$i++){
					
					$f = $file[$i];
					unlink($dir.'/'.$f);
				}
				rmdir($dir);
			  }
			  else{
				  echo"not a dir";
			  }
			  
			  $sql = "DELETE FROM Challenge WHERE pid = $Point";
			  $statement = $db->prepare($sql);
			  var_dump($statement->execute());
			  
			 
		  }
          else if($Modify == -1){
			  if($_POST['file'] === "upload"){
				  
				  $fileCount = count($_FILES['file']['tmp_name']);
				  
				  for ($i = 0; $i < $fileCount; $i++) {
					# Error?
					if ($_FILES['file']['error'][$i] === UPLOAD_ERR_OK){
						
					  $folder_name = (string)$_POST['lastpid'];
					  mkdir($folder_name);
					  chmod($folder_name, 0777);
					  # File existed?
					  if (file_exists($folder_name.'/' . $_FILES['file']['name'][$i])){
						echo $folder_name;
						echo 'This file has existed!。<br/>';
					  } 
					  else {
						 
						$file = $_FILES['file']['tmp_name'][$i];
						$dest = $folder_name.'/' . $_FILES['file']['name'][$i];
						
						move_uploaded_file($file,$dest);
						
					  }
					} 
					else {
					 // echo 'Error：' . $_FILES['file']['error'][$i] . '<br/>';
					}
				  }
			  }
			  
			  $sql = "INSERT INTO Challenge VALUES(NULL,:Name,:Point,:Description,:Flag)";
		      $statement = $db->prepare($sql);
			  $statement->bindParam(':Name', $Name);
			  $statement->bindParam(':Point', $Point,PDO::PARAM_INT);
			  $statement->bindParam(':Description', $Description);
			  $statement->bindParam(':Flag', $Flag);
			  var_dump($statement->execute());
			  header("Location: http://final.duckll.tw/ctf/index.php"); 
			  
		  }else{
			  $sql = "UPDATE Challenge SET Name=:Name, Point=:Point, Description=:Description, Flag=:Flag WHERE pid=:Modify";
			  $statement = $db->prepare($sql);
              $statement->bindParam(':Name', $Name);
              $statement->bindParam(':Point', $Point,PDO::PARAM_INT);
              $statement->bindParam(':Description', $Description);
              $statement->bindParam(':Flag', $Flag);
              $statement->bindParam(':Modify', $Modify);
			  var_dump($statement->execute());
		  }
		  
        
	}
?>
<?php
    require_once('../config/database.php');
    
    $sql = "SELECT * FROM Challenge";
    $stm = $db->prepare($sql);
    $stm -> execute();
    $result = $stm->fetchAll(PDO::FETCH_ASSOC);


	$sql = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'Challenge'";
	$stm = $db->prepare($sql);
    $stm -> execute();
    $next = $stm->fetch(PDO::FETCH_ASSOC);
	$last = $next['AUTO_INCREMENT'];
?>


  <head>
    <title>AddProblem</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <style type="text/css">
      .mid{
        position: absolute;
        width:600px;
        top:0;
        right:0;
        bottom:0;
        left:0;
        margin:auto;
      }
      .des{
        height:200;
      }
      .tableW_{
		  width:300px;
	  }
	  .tableW{
		  width:150px;
	  }
    </style>
  </head>
<html>
  <body>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
  
    <script>
    function doModify(modify)
    {
	 
	    var Modify = modify;
		var Name = document.getElementById('m_Name' + modify).value;
		var Point = document.getElementById('m_Point' + modify).value;
		var Description = document.getElementById('m_Description' + modify).value.replace(/\n|\r\n/g, "<br>");
		var Flag = document.getElementById('m_Flag' + modify).value;
		
	  $.post("admin/challenge.php",{Modify:Modify, Name:Name, Point:Point, Description:Description, Flag:Flag}, function(data){
        setTimeout('window.location.href = "http://final.duckll.tw/ctf/index.php"',100)
     
	     })
 
    }
	function doDelete(pid)
	{
	  var Modify = 0;
	  var Name = "cuTeTurt1eDe1eteU";
      var Point = pid;
      var Description = "0";
      var Flag = "0";
      $.post("admin/challenge.php",{Modify:Modify, Name:Name, Point:Point, Description:Description, Flag:Flag}, function(data){
        setTimeout('window.location.href = "http://final.duckll.tw/ctf/index.php"',100)
	     })
	}
    </script>


    <div id="content" class="container">
    <div class='row'>
      
      <div class="col-md-8 col-md-offset-2 mid">
        <h2>Add problem</h2>
        <br>
        <form id="fileup" method="post" action="admin/challenge.php" enctype="multipart/form-data">
          <div class="row">
            <div class="col">
            <label >Name</label>
              <input name="Name" class="form-control" type="text"  required>
            </div>
            <div class="col">
            <label>Point</label>
              <input name="Point" class="form-control" type="text"  required>
            </div>
          </div>
          
          <label>Description</label>
            <textarea name="Description" class="form-control des" type="text"  required></textarea>
          <label>Flag</label>
            <input name="Flag" class="form-control" type="text"  required>
		  <label>File input</label>
		  <br>
		    <input type="hidden" name="Modify" value="-1">
		    <input type="hidden" name="file" value="upload">
		    <input type="hidden" name="lastpid" value="<?php echo $last;?>">
            <input type="file" id="file" name="file[]" multiple> 
          <br>
		  <br>
		  <input type="reset" class="btn btn-primary" value="reset">
          <input type="submit" name="submit" value="Submit" class="btn btn-primary" style="float:right;" >
        </form>
		<br>
		<br>
		<br>
		<br>

		<h2>Modify problems</h2>
        <br>
		<table class="table">
		  <thead>
			<tr>
			  <th>Problem</th>
			  <th></th>
			  <th></th>
			</tr>
		  </thead> 
		  <tbody>
		  <?php
			foreach($result as $row)
			{
		  ?>
			<tr>
			  <td class = "tableW_"><?php echo $row['Name'] ?></td>
			  <td class = "tableW"><button type="button" role="button" data-toggle="modal" data-target="#<?php echo"delete". $row['pid'];?>" class="btn btn-outline-danger">Delete</button></td>
			  
			  <div class="modal fade" id="<?php echo"delete". $row['pid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-body">
						<div class="alert alert-danger" role="alert">
						  <strong><?php echo "Determine to delete Problem ". $row['Name']. ' ?';?></strong>
						</div>
					  </div>
					  <div class="modal-footer">
						<button type="button" id="<?php echo"delete". $row['pid'];?>" class="btn btn-danger"onclick="doDelete(<?php echo $row['pid']?>)">Yes</button>
					  
						<button type="button" class="btn btn-primary" role="button" data-dismiss="modal">No</button>
					  </div>
					</div>
				  </div>
			  </div>
			 
			  <td class = "tableW"><button type="button" role="button" data-toggle="modal" data-target="#<?php echo"modify". $row['pid'];?>" class="btn btn-outline-primary">Modify</button></td>
			  <div class="modal fade" id="<?php echo"modify". $row['pid'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				 
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-body">
						
						 <div class="row">
							<div class="col">
							<label>Name</label>
							  <input id="<?php echo 'm_Name'. $row['pid'];?>" class="form-control" type="text" value="<?php echo $row['Name']?>" required>
							
							</div>
							<div class="col">
							<label>Point</label>
							  <input id="<?php echo 'm_Point'. $row['pid'];?>" class="form-control" type="text" value="<?php echo $row['Point']?>" required>
							</div>
						  </div>
						  <label>Description</label>
							<textarea id="<?php echo 'm_Description'. $row['pid'];?>" class="form-control des" type="text" required><?php echo $row['Description']?></textarea>
						  <label>Flag</label>
							<input id="<?php echo 'm_Flag'. $row['pid'];?>" class="form-control" type="text" value="<?php echo $row['Flag']?>" required>
						  <br>
						  <input type="button"  id="<?php echo 'modify'. $row['pid'];?>" role="button" class="btn btn-primary"  value="Submit" style="float:right;" onclick="doModify(<?php echo $row['pid'];?>)">
						
					  </div>
					</div>
				  </div>
				 
			  </div>
			  </tr>
		  <?php
			}
		  ?>
		  </tbody>
		</table>
      </div>
     
    </div>
    </div>
  </body>
</html>
