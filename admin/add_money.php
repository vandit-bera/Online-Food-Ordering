<?php 
include('top.php');
$id="";

if(isset($_POST['submit'])){
	$money=get_safe_value($_POST['money']);
	$msg=get_safe_value($_POST['msg']);
	$id=get_safe_value($_GET['id']);
	
	manageWallet($id,$money,'in',$msg);
	redirect('user.php');
}
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Money</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Category</label>
                      <input type="text" class="form-control" placeholder="Money" name="money" required>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3" required>Message</label>
                      <input type="textbox" class="form-control" placeholder="Message" name="msg">
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
            
		 </div>
        
<?php include('footer.php');?>