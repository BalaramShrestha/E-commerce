<?php
session_start();


include("functions/functions.php");

?>
<!DOCTYPE>


<html>

<head>
	<title>My Online Shop</title>
	<link rel="stylesheet" href="styles/style.css " media="all" type="text/css">

	<head>

	<body>
		<!--Main containter or wrapper starts from here    -->
		<div class="main_wrapper">

			<!-- header starts from here-->
			<div class="header_wrapper"><!--this is header-->
				<a href="index.php"><img id="logo" src="images/logo.png" /></a>

				<img id="banner" src="images/ad_banner.PNG" />


			</div><!-- close of class header_wrapper -->

			<!--Navigation Bar starts-->
			<div class="menubar">
				<ul id="menu">
					<li><a href="index.php">Home</a></li>
					<li><a href="all_products.php">All Product</a></li>
					<li><a href="customer/my_account.php">My Account</a></li>
					<li><a href="customer_register.php">Sign Up</a></li>
					<li><a href="cart.php">Shopping Cart</a></li>
					<li><a href="#">Contact us</a></li>

				</ul>

				<div id="form">
					<form method="get" action="results.php" enctype="multipart/form-data">
						<input type="text" name="user_query" placeholder="Search a product" />
						<input type="submit" name="search" value="Search" />

					</form>


				</div>
			</div> <!--Navigation Bar ends-->

			<!--Content wrapper starts-->
			<div class="content_wrapper">

				<div id="sidebar">
					<div id="sidebar_title">Categories</div>

					<ul id="cats">

						<?php
						//show categories (list) from database  
						getCats();
						?>
					</ul>

					<div id="sidebar_title">Brands</div>
					<ul id="cats">
						<?php getBrands(); ?>

					</ul>





				</div>

				<div id="content_area">

					<?php
					cart();
					?>
					<div id="shopping_cart">
						<span style="float: right; font-size: 18px; padding: 5px; line-height: 40px;">
							<?php
							if (isset($_SESSION['customer_email'])) {
								echo "<b>welcome: </b>" . $_SESSION['customer_email'] . "<b style='color:yellow;'>Your </b>";
							} else {
								echo "<b>Welcome Guest</b>";
							}
							?>

							<b style="color:yellow">Shopping Cart -</b>Total Items:<?php total_items(); ?> Total Price: <?php total_price(); ?><a href="index.php" style="color:yellow">Back to Shop</a>

							<?php
							if (!isset($_SESSION['customer_email'])) {
								echo "<a href='checkout.php' style='color:orange'>Login</a>";
							} else {
								echo "<a href='logout.php' style='color:orange'>Logout</a>";
							}
							?>

						</span>
					</div>

					<div id="products_box">
						<br>
						<form action="" method="POST" enctype="multipart/form-data">
							<table align="center" width="900px" bgcolor="skyblue">

								<tr align="center">
									<th>Remove</th>
									<th>Product(s)</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Total Price</th>
								</tr>
								<?php

								$total = 0;
								global $con;
								$ip = getIp();
								if (!isset($_SESSION['cus_id'])) {
									echo "Please Login";
								} else {
									$cus_id = $_SESSION['cus_id'];

									$sel_price = "select * from cart where customer_id='$cus_id' ";
									$run_price = mysqli_query($con, $sel_price);
									while ($p_price = mysqli_fetch_array($run_price)) {
										$pro_id  = $p_price['p_id'];
										$quant = $p_price['qty'];
										$pro_price = "select * from products where product_id ='$pro_id' ";
										$run_pro_price = mysqli_query($con, $pro_price);
										while ($pp_price = mysqli_fetch_array($run_pro_price)) {

											$product_price = array($pp_price['product_price']); //total product price(sum)
											$product_image = $pp_price['product_image'];
											$product_title = $pp_price['product_title'];
											$single_price = $pp_price['product_price']; //single product price
											$total_cart = array_sum($product_price);
											$total += $total_cart
								?>
											<tr align="center">
												<input type="number" name="ballu" value="<?php echo $pro_id; ?>" hidden>
												<td>
													<input type="checkbox" name="remove" value="<?php echo $pro_id; ?>">

												</td>
												<td><?php echo $product_title; ?><br>
													<img src="admin_area/product_images/<?php echo $product_image; ?>" width="80" height="80" />
												</td>

												<td><?php echo $single_price; ?></td>
												<td><input type="number" name="qty[<?php echo $pro_id; ?>]" min="1" value="<?php echo $quant; ?>"> </td>

											</tr>
											<?php
											if (isset($_POST['update_cart'])) {
												$check = $_POST['remove'];
												$delete = mysqli_query($con, "DELETE from cart where p_id = '$check'");
												echo "<script>window.open('cart.php','self')</script>";
											}
											?>
								<?php }
									}
								} ?>

								<?php

								if (isset($_POST['update_cart'])) {
									foreach ($_POST['qty'] as $pro_id => $qty) {
									
										$cus_id = $_SESSION['cus_id'];
										
										$update_query = "UPDATE cart SET qty='$qty' WHERE p_id='$pro_id' AND customer_id = '$cus_id'";
										$run_update = mysqli_query($con, $update_query);
									}
									echo "<script>window.open('cart.php','self')</script>";
								}
								?>

								<tr align="right">
									<td colspan="4"><b>Sub Total:</b></td>
									<td colspan="4"><?php total_price(); ?></td>
								</tr>

								<tr align="center">
									<td colspan="2"><input type="submit" name="update_cart" value="Update Cart"></td>
									<!-- <td><input type="submit" name="continue" value="Continue Shopping"></td> -->
									<td>
										<button>

											<a href="index.php">
												Continue Shopping
											</a>
										</button>
									</td>
									<td><button><a href="checkout.php" style="text-decoration: none; color: black;">Checkout</a></button></td>

								</tr>
							</table>

						</form>




					</div>


				</div><!-- end of content area(This is content area)-->
			</div> <!--content wrapper ends-->

			<div id="footer">
				<h2 style="text-align: center; padding-top: 30px;">&copy; 2022 by www.onlineshopping.com</h2>

			</div>

		</div><!-- close of main class main_wrapper-->
	</body>

</html>