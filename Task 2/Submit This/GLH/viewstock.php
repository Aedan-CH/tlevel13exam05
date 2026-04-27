<?php

session_start();
require_once "assets/common.php";
require_once "assets/dbconn.php";

$conn = dbconnect_select();

if (!isset($_SESSION['ProducerID'])) {
    $_SESSION['usermessage'] = "ERROR: You have not logged in!";
    header("Location: Slogin.php");
    exit;
} elseif($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['Productdelete'])) {
        try {
            if (remove_product($conn, $_POST['ProductID'])) {
                $_SESSION['usermessage'] = 'SUCCESS: Product deleted successfully!';
            } else {
                $_SESSION['usermessage'] = 'ERROR: Your Product could not be deleted.';
            }

        } catch (PDOException $e) {
            $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
        } catch (Exception $e) {
            $_SESSION['usermessage'] = "ERROR: " . $e->getMessage();
        }
    } elseif (isset($_POST['productchange'])) {
        $_SESSION['ProductID'] = $_POST['ProductID'];
    }
}

echo "<!DOCTYPE html>";
echo "<html>";
echo "<head>";

echo "<title>Staff Register Page</title>"; # title of the page
echo "<link rel='stylesheet' href='css/styles.css'>"; # accesses the stylesheet
echo "</head>";
echo "<body>";  # opens the body for the main content of the page.

echo "<div class='container'>";
require_once "assets/topbar.php";
require_once "assets/nav.php";

echo user_message();
echo "<br>";

echo "<h2> GLH - Your Products </h2>";

echo "<p class='content'> Below are your products </p>>";
$products = product_getter($conn);
if (!$products) { # if no products are found
    echo "no Products found"; # display suitable message
} else { # if products are found

    echo "<table id='product'>"; # display products linked to producer ID


    foreach ($products as $product) {

        echo "<tr>";
        echo "<form action='' method='post'>";

        echo "<td> Producer: " . $product['Fname'] . "</td>";
        echo "<td> Name: " . $product['Pname'] . "</td>";
        echo "<td> Description:" . $product['Pdesc'] . "</td>";
        echo "<td> Price:" . $product['Price'] . "</td>";
        echo "<td> Stock: " . $product['Stock'] . "</td>";
        echo "<td><input type='hidden' name='ProductID' value=" . $product['ProductID'] . ">
            <input type ='submit' name ='Productdelete' value = 'Delete Product'/>;
            <input type ='submit' name ='productchange' value = 'Change Product'>
        </td>";

    echo "</form>";
    echo "</tr>";
    }
    echo "</table>";
}

