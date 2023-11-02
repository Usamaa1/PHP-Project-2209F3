<?php require "aa/navber.php" ?>
<?php require "config/config.php" ?>

<?php






// single products


$id = $_GET["id"];

$single_product_query = "SELECT * FROM `products` where prod_id = :id";

$single_product_prepare = $connection->prepare($single_product_query);
$single_product_prepare->bindParam(':id', $id);
$single_product_prepare->execute();
$single_product = $single_product_prepare->fetch(PDO::FETCH_ASSOC);
// print_r($single_product);



// related products 

$related_products_query = "SELECT * FROM `products` where prod_type = :productType and prod_id != :id";

$related_products_prepare = $connection->prepare($related_products_query);
$related_products_prepare->bindParam(':productType', $single_product['prod_type']);
$related_products_prepare->bindParam(':id', $single_product['prod_id']);
$related_products_prepare->execute();
$related_products = $related_products_prepare->fetchAll(PDO::FETCH_ASSOC);
// print_r($related_products);


	// view cart

	$cart_view_query = "SELECT * FROM `cart` where user_id = :userId";
	$cart_view_prepare = $connection->prepare($cart_view_query);
	$cart_view_prepare->bindParam(':userId',$_SESSION['userId']);
	$cart_view_prepare->execute();

	$cart_view = $cart_view_prepare->fetch(PDO::FETCH_ASSOC);
	print_r($cart_view);
	
	
	// add to cart

if (isset($_POST['add_cart'])) {

	if (isset($_SESSION['userId'])) {
		$priceValue = $_POST['priceValue'];
		$quantity = $_POST['quantity'];




		$add_to_cart_query = "INSERT INTO `cart`(`prod_name`, `prod_description`, `prod_price`, `prod_quantity`, `prod_image`, `user_id`) VALUES (:product_name,  :product_description, :product_price, :product_quantity, :product_image, :user_id)";

		// $add_to_cart_query = "INSERT INTO `cart`(`product_name`, `product_price`, `product_description`, `product_quantity`, `product_image`, `user_id`) VALUES ";

		$add_to_cart_prepare = $connection->prepare($add_to_cart_query);
		$add_to_cart_prepare->bindParam(':product_name', $single_product['prod_name']);
		$add_to_cart_prepare->bindParam(':product_description', $single_product['prod_description']);
		$add_to_cart_prepare->bindParam(':product_price', $priceValue);
		$add_to_cart_prepare->bindParam(':product_quantity', $quantity);
		$add_to_cart_prepare->bindParam(':product_image', $single_product['prod_image']);
		$add_to_cart_prepare->bindParam(':user_id', $_SESSION['userId']);
		$add_to_cart_prepare->execute();
	}
	else
	{
		echo "<script>alert('Kindly login to add products into your cart')</script>";
	}



}






?>

<section class="home-slider owl-carousel">

	<div class="slider-item" style="background-image: url(images/bg_3.jpg);" data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row slider-text justify-content-center align-items-center">

				<div class="col-md-7 col-sm-12 text-center ftco-animate">
					<h1 class="mb-3 mt-5 bread">Product Detail</h1>
					<p class="breadcrumbs"><span class="mr-2"><a href="index.html">Home</a></span> <span>Product
							Detail</span></p>
				</div>

			</div>
		</div>
	</div>
</section>

<section class="ftco-section">
	<div class="container">
		<div class="row">
			<div class="col-lg-6 mb-5 ftco-animate">
				<a href="images/<?php echo $single_product['prod_image'] ?>" class="image-popup"><img
						src="images/<?php echo $single_product['prod_image'] ?>" class="img-fluid"
						alt="Colorlib Template"></a>
			</div>
			<div class="col-lg-6 product-details pl-md-5 ftco-animate">
				<h3>
					<?php echo $single_product['prod_name'] ?>
				</h3>
				<p class="price"><span id="price">$
						<?php echo $single_product['prod_price'] ?>
					</span></p>
				<p>
					<?php echo $single_product['prod_description'] ?>
				</p>

				<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

					<input type="hidden" value='' id='priceValue' name='priceValue'>
					<div class="row mt-4">
						<div class="col-md-6">
							<div class="form-group d-flex">
								<div class="select-wrap">
									<div class="icon"><span class="ion-ios-arrow-down"></span></div>

									<select name="size" id="size" class="form-control">
										<option value="small">Small</option>
										<option value="medium">Medium</option>
										<option value="large">Large</option>
										<option value="Elarge">Extra Large</option>
									</select>
								</div>
							</div>
						</div>
						<div class="w-100"></div>
						<div class="input-group col-md-6 d-flex mb-3">
							<span class="input-group-btn mr-2">
								<button type="button" class="quantity-left-minus btn" data-type="minus" data-field="">
									<i class="icon-minus"></i>
								</button>
							</span>
							<input type="text" id="quantity" name="quantity" class="form-control input-number" value="1"
								min="1" max="100">
							<span class="input-group-btn ml-2">
								<button type="button" class="quantity-right-plus btn" data-type="plus" data-field="">
									<i class="icon-plus"></i>
								</button>
							</span>
						</div>
					</div>

					<p><input type='submit' value="Add to Cart" name='add_cart' class="btn btn-primary py-3 px-5"></p>

				</form>


			</div>
		</div>
	</div>
</section>

<section class="ftco-section">
	<div class="container">
		<div class="row justify-content-center mb-5 pb-3">
			<div class="col-md-7 heading-section ftco-animate text-center">
				<span class="subheading">Discover</span>
				<h2 class="mb-4">Related products</h2>
				<p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live
					the blind texts.</p>
			</div>
		</div>
		<div class="row">



			<?php foreach ($related_products as $related_product) { ?>


				<div class="col-md-3">
					<div class="menu-entry">
						<a href="#" class="img"
							style="background-image:url(images/<?php echo $related_product['prod_image'] ?>);"></a>
						<div class="text text-center pt-4">
							<h3><a href="#">
									<?php echo $related_product['prod_name'] ?>
								</a></h3>
							<p>
								<?php echo $related_product['prod_description'] ?>
							</p>
							<p class="price">$
								<?php echo $related_product['prod_price'] ?></span>
							</p>
							<p><a href="product-single.php?id=<?php echo $related_product['prod_id'] ?>"
									class="btn btn-primary btn-outline-primary">Add to Cart</a></p>
						</div>
					</div>
				</div>

			<?php } ?>


		</div>
	</div>
</section>

<?php require "aa/footer.php" ?>

<script>
	$(document).ready(function () {

		var quantitiy = 0;
		$('.quantity-right-plus').click(function (e) {

			// Stop acting like a button
			e.preventDefault();
			// Get the field name
			var quantity = parseInt($('#quantity').val());

			// If is not undefined

			$('#quantity').val(quantity + 1);


			// Increment

		});

		$('.quantity-left-minus').click(function (e) {
			// Stop acting like a button
			e.preventDefault();
			// Get the field name
			var quantity = parseInt($('#quantity').val());

			// If is not undefined

			// Increment
			if (quantity > 0) {
				$('#quantity').val(quantity - 1);
			}
		});

	});

	//  size input

	let size = document.getElementById('size');
	let price = document.querySelector('#price');
	let priceValue = document.getElementById('priceValue');
	console.log(price)


	priceValue.value = '<?php echo $single_product['prod_price'] ?>';

	size.addEventListener('change', () => {
		console.log(size.value);

		let currentPrice = '<?php echo $single_product['prod_price'] ?>'

		console.log(currentPrice);

		if (size.value === 'small') {

			price.innerText = currentPrice;
			priceValue.value = currentPrice;

		} else if (size.value === 'medium') {
			currentPrice = Number(currentPrice) + 10;
			price.innerText = currentPrice;
			priceValue.value = currentPrice;
		} else if (size.value === 'large') {
			currentPrice = Number(currentPrice) + 15;
			price.innerText = currentPrice;
			priceValue.value = currentPrice;
		} else if (size.value === 'Elarge') {
			currentPrice = Number(currentPrice) + 23.44;
			price.innerText = currentPrice;
			priceValue.value = currentPrice;
		}

	})



</script>


</body>

</html>