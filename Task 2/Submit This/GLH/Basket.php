<?php
session_start(); # start session to track logged-in user

require_once "assets/common.php"; # include common utility functions
require_once "assets/dbconn.php"; # include database connection functions

$conn = dbconnect_select(); # connect to the database for selection queries

if (!isset($_SESSION['user'])) { # check if user is not logged in
    $_SESSION['usermessage'] = "ERROR: You have not logged in!"; # set error message
    header("Location: login.php"); # redirect to login page
    exit; # stop further execution



} elseif($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['itemremove'])) {
        // ... handle removing item
    } elseif (isset($_POST['quantchange'])) {
        // ... handle changing quantity
    } elseif (isset($_POST['completeorder'])) {
        // Step 2: mark order as complete
        $OrderID = only_order($conn); // get current order ID
        try {
            $sql = "UPDATE `order` SET Status = 1 WHERE OrderID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$OrderID]);

            // Clear session so user can start a new order
            unset($_SESSION['OrderID']);
            $_SESSION['usermessage'] = "Your order has been completed!";
        } catch (PDOException $e) {
            $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
        }
    }
}
    elseif (isset($_POST['quantchange'])) {
        $productID = intval($_POST['ProductID']);
        $quantity = intval($_POST['quantity']);
        $OrderID = only_order($conn);

        if (basket_check($productID, $quantity, $conn)) {
            // Safe to update cart
            $sql = "UPDATE cart SET quantity = ? WHERE ProductID = ? AND OrderID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$quantity, $productID, $OrderID]);

            $_SESSION["usermessage"] = "Product quantity updated successfully!";
        } else {
            $_SESSION["usermessage"] = "Not enough stock!";
        }

}





echo "<!DOCTYPE html>"; # HTML5 document declaration
echo "<html>"; # start HTML document
echo "<head>"; # start head section

echo "<title>Order Page</title>"; # title of the page
echo "<link rel='stylesheet' href='css/styles.css'>"; # link external stylesheet
echo "</head>";
echo "<body>";  # opens the body for main content

echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";

echo user_message();
echo "<br>";

echo "<h2> GLH - Your Basket </h2>";

echo "<p class='content'> Below is your Basket </p>>";
$products = Basket_getter($conn);
if (!$products) { # if no products are found
    echo "no Products found"; # display suitable message
} else { # if products are found

    echo "<table id='product'>"; # display products linked to producer ID


    foreach ($products as $product) {

        echo "<tr>";
        echo "<form action='' method='post'>";

        echo "<td> Producer: " . $product['Fname'] . " " . $product['Lname'] . "</td>"; # producer name
        echo "<td> Name: " . $product['Pname'] . "</td>"; # product name
        echo "<td> Description:" . $product['Pdesc'] . "</td>"; # product description
        echo "<td> Price:" . $product['Price'] . "</td>"; # product price
        echo "<td> Quantity:" . $product['quantity'] . "</td>"; # product price
        echo "<td> Stock: " . $product['Stock'] . "</td>"; # product stock
        echo "<td><input type='hidden' name='ProductID' value=" . $product['ProductID'] . ">";
        echo "<input type ='submit' name ='itemremove' value = 'Remove'/>";
        echo "<input type='number' name='quantity' placeholder='Enter quantity...' min='1' value='1' required>";
        echo "<br>";
        echo "<input type='submit' name='quantchange' value='edit amount'>";
        echo "</td>";

        echo "</form>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type ='submit' name ='completeorder' value = 'Complete Order'/>";
}