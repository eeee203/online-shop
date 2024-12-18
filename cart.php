<?php
session_start();
if(isset($_POST['add_to_cart'])){
  

  //if user have already added
  if(isset($_SESSION['cart'])){
     $products_array_ids = array_column($_SESSION['cart'],"product_id");  //[2,3,4,15]

     if( !in_array($_POST['product_id'], $products_array_ids ) ){
          $product_id = $_POST['product_id'];

            $product_array = array( 

              'product_id' => $_POST ['product_id'],
              'product_name' => $_POST['product_name'],
              'product_price' => $_POST ['product_price'],
              'product_image' => $_POST ['product_image'],
              'product_quantity' => $_POST ['product_quantity']

              );
              $_SESSION['cart'][$product_id] = $product_array;

    //calculate total          
    calculatetotalcart();    
     }else{
      echo '<script>alert("product was already to cart");</script>';
      //echo '<script>window.location="index.php";</script>';
     }
   //if this is the first product
  }else{
     $product_id = $_POST ['product_id'] ;
     $product_name = $_POST['product_name'] ;
     $product_price = $_POST ['product_price'] ;
     $product_image = $_POST ['product_image'] ;
     $product_quantity = $_POST ['product_quantity'];
     $product_array = array(
                      'product_id' => $product_id,
                      'product_name' => $product_name,
                      'product_price' => $product_price,
                      'product_image' => $product_image,
                      'product_quantity' => $product_quantity
     );
     $_SESSION['cart'][$product_id] = $product_array;
     

  }

  
//remove from cart

}else if(isset($_POST['remove_product'])){

  $product_id = $_POST['product_id'];
  unset($_SESSION['cart'][$product_id]);
  calculatetotalcart();
  

}else if(isset($_POST['edit_quantity'])){

  //gget product id && quantity from form
  $product_id = $_POST['product_id'];
  $product_quantity = $_POST['product_quantity'];

  $product_array = $_SESSION['cart'][$product_id];

  $product_array['product_quantity'] = $product_quantity;

  $_SESSION['cart'][$product_id] = $product_array;
  calculatetotalcart();
  

}//else{
 // header('location: index.php');




function calculatetotalcart(){
   $total = 0;
  foreach($_SESSION['cart'] as $key => $value){
    $product = $_SESSION['cart'][$key];
    $price = $product['product_price'];
    $quantity = $product['product_quantity'];
    $total = $total + ($price * $quantity);
    
  }
  $_SESSION['total'] = $total;
  

}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 fixed-top">
      <div class="container">
        <img class="logo" src="assets/imgs/logo.jpeg"/>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="shop.php">Shop</a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="checkout.php">checkout</a>
            </li>



            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact Us</a>
            </li>
            <li class="nav-item">
              <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
              <a href="account.html"><i class="fas fa-user"></i></a>
        
            
            
            
          </ul>

        </div>
      </div>
    </nav>
      <!--cart-->
      <section class="cart container my-5 py-5">
        <div class="container mt-5">
            <h2 class="font-weight-bolde">Your Cart</h2>
            <hr>
        </div>
        <table class="mt-5 pt-5">
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Subtotal</th>
            </tr>
            <?php foreach($_SESSION['cart'] as $key => $value){?>
            <tr>
                <td>
                    <div class="product-info">
                        <img src="assets/imgs/<?php echo $value['product_image']; ?>"/>
                        <div>
                            <p><?php echo $value['product_name']; ?></p>
                            <small><span>$</span><?php echo $value['product_price']; ?></small>
                            <br>
                            <form method="POST" action="cart.php">
                              <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                              <input type="submit" name="remove_product"class="remove-btn" value="remove"/>
                            </form>  

                        </div>
                    </div>
                </td>
                <td>
                    
                    <form method="POST" action="cart.php">
                              <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>"/>
                              <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>"/>
                              <input type="submit" name="edit_quantity"class="edit-btn" value="edit"/>
                            </form>  

                   
                </td>
                <td>
                    <span>$</span>
                    <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price']; ?></span>
                </td>
            </tr>
            <?php } ?>
            
        </table>
        <div class="total">
            <table>
                <tr>                
                  <td>Total</td>
                  <td> $ <?php echo $_SESSION['total']; ?></td>
                </tr>
            </table>
        </div>
        <div class="checkout-containor">
          <form method = "POST" action = "checkout.php">
            <input type = "submit" class="btn checkout-btn" value="checkout" name="checkout" />
          </form>  
        </div>

      </section>
    <!--footer-->
    <footer class="mt-5 py-5">
        <div class="row container mx-auto pt-5">
          <div class="footer one col-lg-3 col-md-6 col-sm-12">
            <img class="logo" src="assets/imgs/logo.jpeg"/>
            <p class="pt-3">We provide the best products for the affordable price</p>
  
          </div>
          <div class="footer one col-lg-3 col-md-6 col-sm-12">
            <h5 class="pb-2">Featured</h5>
            <ul class="text-uppercase">
              <li><a href="#">men</a></li>
              <li><a href="#">boys</a></li>
              <li><a href="#">women</a></li>
              <li><a href="#">girls</a></li>
              <li><a href="#">new arrivals</a></li>
              <li><a href="#">clothes</a></li>
            </ul>
  
          </div>
  
          <div class="footer one col-lg-3 col-md-6 col-sm-12">
            <h5 class="pb-2">Contact Us</h5>
            <div>
              <h6 class="text-uppercase">Address</h6>
              <p>1234 Street Naeme, City</p>
            </div>
  
            <div>
              <h6 class="text-uppercase">Phone</h6>
              <p>012 345 678 910</p>
            </div>
  
            <div>
              <h6 class="text-uppercase">Email</h6>
              <p>Info@gmail.com</p>
            </div>
          </div>
          <div class="footer one col-lg-3 col-md-6 col-sm-12">
            <h5 class="pb-2">Instgram</h5>
            <div class="row">
              <img src="assets/imgs/footer1.jpeg" class="w-25 h-100 m-2"/>
              <img src="assets/imgs/footer2.jpeg" class="w-25 h-100 m-2"/>
              <img src="assets/imgs/footer3.jpeg" class="w-25 h-100 m-2"/>
              <img src="assets/imgs/footer4.jpeg" class="w-25 h-100 m-2"/>
              <img src="assets/imgs/footer5.jpeg" class="w-25 h-100 m-2"/>
            </div>          
          </div>
  
        </div>
  
        <div class="copyrighte mt-5">
          <div class="row container mx-auto">
            <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
              <img src="assets/imgs/pay.jpeg"/>       
            </div>
            <div class="col-lg-3 col-md-5 col-sm-12 mb-4 text-nowrap mb-2">
              <p>Ecommerse @2025 all right reserved</p>
            </div>
            <div class="col-lg-3 col-md-5 col-sm-12 mb-4">
              <a href="#"><i class="fab fa-facebook"></i></a>
              <a href="#"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-twitter"></i></a> 
            </div>                 
          </div>
  
        </div>
      </footer>
  
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
      
  </body>
  </html>
  