<?php 
include('top.php');

$msg="";
$category_id="";
$dish="";
$dish_detail="";
$image="";
$type="";
$id="";
$image_status='required';
$image_error="";
if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($con,"select * from dish where id='$id'"));
	$category_id=$row['category_id'];
	$dish=$row['dish'];
	$type=$row['type'];
	$dish_detail=$row['dish_detail'];
	$image=$row['image'];
	$image_status='';
}

if(isset($_GET['dish_details_id']) && $_GET['dish_details_id']>0){
	$dish_details_id=get_safe_value($_GET['dish_details_id']);
	$id=get_safe_value($_GET['id']);
	mysqli_query($con,"delete from dish_details where id='$dish_details_id'");
	redirect('manage_dish.php?id='.$id);
}

if(isset($_POST['submit'])){
	
	$category_id=get_safe_value($_POST['category_id']);
	$dish=get_safe_value($_POST['dish']);
	$dish_detail=get_safe_value($_POST['dish_detail']);
	$food_type=get_safe_value($_POST['type']);
	$added_on=date('Y-m-d h:i:s');
	
	if($id==''){
		$sql="select * from dish where dish='$dish'";
	}else{
		$sql="select * from dish where dish='$dish' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($con,$sql))>0){
		$msg="Dish already added";
	}else{
		$type=$_FILES['image']['type'];
		if($id==''){
			if($type!='image/jpeg' && $type!='image/png'){
				$image_error="Invalid image format";
			}else{
				$image=rand(111111111,999999999).'_'.$_FILES['image']['name'];
				move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image);
				mysqli_query($con,"insert into dish(category_id,dish,dish_detail,status,added_on,image,type) values('$category_id','$dish','$dish_detail',1,'$added_on','$image','$food_type')");
				$did=mysqli_insert_id($con);
				
				$attributeArr=$_POST['attribute'];
				$priceArr=$_POST['price'];
				$statusArr=$_POST['status'];
				
				foreach($attributeArr as $key=>$val){
					$attribute=$val;
					$price=$priceArr[$key];
					$status=$statusArr[$key];
					mysqli_query($con,"insert into dish_details(dish_id,attribute,price,status,added_on) values('$did','$attribute','$price','$status','$added_on')");
					//echo "insert into dish_details(dish_id,attribute,price,status,added_on) values('$did','$attribute','$price',1,'$added_on')";
				}
				
				redirect('dish.php');
			}
		}else{
			$image_condition='';
			if($_FILES['image']['name']!=''){
				if($type!='image/jpeg' && $type!='image/png'){
					$image_error="Invalid image format";
				}else{
					$image=rand(111111111,999999999).'_'.$_FILES['image']['name'];
					move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image);
					$image_condition=", image='$image'";
					
					$oldImageRow=mysqli_fetch_assoc(mysqli_query($con,"select image from dish where id='$id'"));
					$oldImage=$oldImageRow['image'];
					unlink(SERVER_DISH_IMAGE.$oldImage);
		
				}
			}
			if($image_error==''){
				$sql="update dish set category_id='$category_id', dish='$dish' , dish_detail='$dish_detail',type='$food_type' $image_condition where id='$id'";
				mysqli_query($con,$sql);
				$attributeArr=$_POST['attribute'];
				$priceArr=$_POST['price'];
				$statusArr=$_POST['status'];
				$dishDetailsIdArr=$_POST['dish_details_id'];
				
				foreach($attributeArr as $key=>$val){
					$attribute=$val;
					$price=$priceArr[$key];
					$status=$statusArr[$key];
					
					if(isset($dishDetailsIdArr[$key])){
						$did=$dishDetailsIdArr[$key];
						mysqli_query($con,"update dish_details set attribute='$attribute',price='$price',status='$status' where id='$did'");
					}else{
						mysqli_query($con,"insert into dish_details(dish_id,attribute,price,status,added_on) values('$id','$attribute','$price','$status','$added_on')");
					}
					
					
					//echo "insert into dish_details(dish_id,attribute,price,status,added_on) values('$did','$attribute','$price',1,'$added_on')";
				}
				
				
				redirect('dish.php');
			}
		}
	}
}
$res_category=mysqli_query($con,"select * from category where status='1' order by category asc");
$arrType=array("veg","non-veg");
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Dish</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Category</label>
                      <select class="form-control" name="category_id" required>
						<option value="">Select Category</option>
						<?php
						while($row_category=mysqli_fetch_assoc($res_category)){
							if($row_category['id']==$category_id){
								echo "<option value='".$row_category['id']."' selected>".$row_category['category']."</option>";
							}else{
								echo "<option value='".$row_category['id']."'>".$row_category['category']."</option>";
							}
						}
						?>
					  </select>
					  
                    </div>
					<div class="form-group">
                      <label for="exampleInputName1">Dish</label>
                      <input type="text" class="form-control" placeholder="dish" name="dish" required value="<?php echo $dish?>">
					  <div class="error mt8"><?php echo $msg?></div>
                    </div>
					<div class="form-group">
                      <label for="exampleInputName1">Type</label>
                      <select class="form-control" name="type" required>
						<option value="">Select Type</option>
						<?php 
						foreach($arrType as $list){
							if($list==$type){
								echo "<option value='$list' selected>".strtoupper($list)."</option>";
							}else{
								echo "<option value='$list'>".strtoupper($list)."</option>";
							}
						}
						?>
					  </select>
					  
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3" required>Dish Detail</label>
                      <textarea name="dish_detail" class="form-control" placeholder="Dish Detail"><?php echo $dish_detail?></textarea>
                    </div>
					<div class="form-group">
                      <label for="exampleInputEmail3">Dish Image</label>
                      <input type="file" class="form-control" placeholder="Dish Image" name="image" <?php echo $image_status?>>
					  <div class="error mt8"><?php echo $image_error?></div>
                    </div>
					<div class="form-group" id="dish_box1">
						<label for="exampleInputEmail3">Dish Attributes</label>
					<?php if($id==0){?>
						<div class="row">
							<div class="col-4">
								<input type="text" class="form-control" placeholder="Attribute" name="attribute[]" required>
							</div>
							<div class="col-3">
								<input type="text" class="form-control" placeholder="Price" name="price[]" required>
							</div>
							<div class="col-3">
								<select required name="status[]" class="form-control">
									<option value="">Select Status</option>
									<option value="1">Active</option>
									<option value="0">Deactive</option>
								</select>
							</div>
						</div>
					<?php } else{
						$dish_details_res=mysqli_query($con,"select * from dish_details where dish_id='$id'");
						$ii=1;
						while($dish_details_row=mysqli_fetch_assoc($dish_details_res)){
						?>
						<div class="row mt8">
							<div class="col-4">
								<input type="hidden" name="dish_details_id[]" value="<?php echo $dish_details_row['id']?>">
								<input type="text" class="form-control" placeholder="Attribute" name="attribute[]" required value="<?php echo $dish_details_row['attribute']?>">
							</div>
							<div class="col-3">
								<input type="text" class="form-control" placeholder="Price" name="price[]" required  value="<?php echo $dish_details_row['price']?>">
							</div>
							<div class="col-3">
								<select required name="status[]" class="form-control">
									<option value="">Select Status</option>
									<?php
									if($dish_details_row['status']==1){
									?>
										<option value="1" selected>Active</option>
										<option value="0">Deactive</option>
									<?php } ?>
									<?php
									if($dish_details_row['status']==0){
									?>
										<option value="1">Active</option>
										<option value="0" selected>Deactive</option>
									<?php } ?>
								</select>
							</div>
							<?php if($ii!=1){
							?>
							<div class="col-2"><button type="button" class="btn badge-danger mr-2" onclick="remove_more_new('<?php echo $dish_details_row['id']?>')">Remove</button></div>
							
							<?php
							}
							?>
						</div>
					<?php 
					$ii++;
					} } ?>
					</div>
						
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
					
					<button type="button" class="btn badge-danger mr-2" onclick="add_more()">Add More</button>
                  </form>
                </div>
              </div>
            </div>
            
		 </div>
		 <input type="hidden" id="add_more" value="1"/>
        <script>
		function add_more(){
			var add_more=jQuery('#add_more').val();
			add_more++;
			jQuery('#add_more').val(add_more);
			var html='<div class="row mt8" id="box'+add_more+'"><div class="col-4"><input type="text" class="form-control" placeholder="Attribute" name="attribute[]" required></div><div class="col-3"><input type="text" class="form-control" placeholder="Price" name="price[]" required></div><div class="col-3"><select class="form-control"  required name="status[]"><option value="">Select Status</option><option value="1">Active</option><option value="0">Deactive</option></select></div><div class="col-2"><button type="button" class="btn badge-danger mr-2" onclick=remove_more("'+add_more+'")>Remove</button></div></div>';
			jQuery('#dish_box1').append(html);
		}
		
		function remove_more(id){
			jQuery('#box'+id).remove();
		}
		
		function remove_more_new(id){
			var result=confirm('Are you sure?');
			if(result==true){
				var cur_path=window.location.href;
				window.location.href=cur_path+"&dish_details_id="+id;
			}
		}	
		</script>
<?php include('footer.php');?>