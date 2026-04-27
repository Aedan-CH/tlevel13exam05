<?php

session_start(); # start session to track logged-in user

require_once "assets/common.php"; # include common utility functions
require_once "assets/dbconn.php"; # include database connection functions

$conn = dbconnect_select(); # connect to the database for selection queries

if (!isset($_SESSION['user'])) { # check if user is not logged in
    $_SESSION['usermessage'] = "ERROR: You have not logged in!"; # set error message
    header("Location: login.php"); # redirect to login page
    exit; # stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Productbuy'])) { # check if form submitted and "Buy" button pressed
    $productID = intval($_POST['ProductID']); # get product ID from form and ensure it’s an integer
    $quantity = intval($_POST['quantity']); # get quantity from form and ensure it’s an integer

    $OrderID = only_order($conn); # ensure an order exists for this user and get its OrderID

    if (!only_product($conn, $productID)) { # check if product is not already in order
        stock_check($productID, $quantity, $conn);

        # Add product to cart
        $sql = "INSERT INTO cart (OrderID, ProductID, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql); # prepare SQL statement
        $stmt->execute([$OrderID, $productID, $quantity]);

        $_SESSION["usermessage"] = "Product was added to basket successfully!"; # success message
    } else {
        if (!more_product($conn, $productID, $quantity, $OrderID)) {
            $_SESSION["usermessage"] = "ERROR: Not enough stock available!";
        } else {
            $_SESSION["usermessage"] = "Product quantity updated!";
        }
    }
}
echo "<!DOCTYPE html>"; # HTML5 document declaration
echo "<html>"; # start HTML document
    echo "<head>"; # start head section

echo "<title>Order Page</title>"; # title of the page
echo "<link rel='stylesheet' href='css/styles.css'>"; # link external stylesheet
echo "</head>";
echo "<body>";  # opens the body for main content

echo "<div class='container'>"; # main page container
require_once "assets/topbar.php"; # include topbar navigation
require_once "assets/nav.php"; # include site navigation menu

echo user_message(); # display any session messages for the user
echo "<br>"; # line break

echo "<h2> GLH - Here are our products </h2>"; # heading for product section
echo "<p class='content'> Below are available products </p>"; # paragraph describing the product table

$products = shop($conn); # get products from database

if (!$products) { # if no products are found
    echo "no Products found"; # display message
} else { # if products exist

    echo "<table id='product'>"; # start HTML table to display products

    foreach ($products as $product) { # loop through each product
        echo "<tr>"; # start table row
        echo "<form action='' method='post'>"; # start form for buying product

        echo "<td> Producer: " . $product['Fname'] . " " . $product['Lname'] . "</td>"; # producer name
        echo "<td> Name: " . $product['Pname'] . "</td>"; # product name
        echo "<td> Description:" . $product['Pdesc'] . "</td>"; # product description
        echo "<td> Price:" . $product['Price'] . "</td>"; # product price
        echo "<td> Stock: " . $product['Stock'] . "</td>"; # product stock

        echo "<td>";
        echo "<input type='hidden' name='ProductID' value='" . $product['ProductID'] . "'>";
        echo "<input type='number' name='quantity' placeholder='Enter quantity...' min='1' value='1' required>";
        echo "<br>";
        echo "<input type='submit' name='Productbuy' value='Buy'>";
        echo "</td>"; # input fields and submit button for product purchase

        echo "</form>"; # end product form
        echo "</tr>"; # end table row
    }
    echo "</table>"; # end product table
}


