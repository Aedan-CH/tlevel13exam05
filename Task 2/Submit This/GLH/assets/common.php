<?php

/**
 * Check if a user with the given email already exists.
 *
 * @param PDO $conn   Active database connection
 * @param string $email Email to check
 * @return bool True if user exists, false otherwise
 * @throws PDOException
 */
function only_user($conn, $email) {
    try {
        // Prepare query to search for existing email
        $sql = "SELECT email FROM customer WHERE email = ?";
        $stmt = $conn->prepare($sql);

        // Bind email parameter securely
        $stmt->bindParam(1, $email);
        $stmt->execute();

        // Fetch result as associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Close connection
        $conn = null;

        // Return true if user exists
        return $result ? true : false;

    } catch (PDOException $e) {
        // Log error for debugging (do not expose to user)
        error_log("Database error in only_user: " . $e->getMessage());
        throw $e;
    }
}


function user_message(){
    if(isset($_SESSION["usermessage"])){
        $message = "<p>". $_SESSION["usermessage"]."</p>";
        unset($_SESSION["usermessage"]);
        return $message;
    } else {
        $message = "";
        return $message;
    }
}


/**
 * Register a new customer account.
 *
 * @param PDO $conn Database connection
 * @param array $post User input data
 * @return bool True on success
 * @throws Exception
 */
function reg_user($conn, $post){
    try{
        // Insert new customer into database
        $sql = "INSERT INTO customer (email, password, Fname, Lname, address)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Hash password using secure default algorithm (bcrypt/argon)
        $hashedPassword = password_hash($post["password"], PASSWORD_DEFAULT);

        // Bind parameters
        $stmt->bindParam(1, $post['email']);
        $stmt->bindParam(2, $hashedPassword);
        $stmt->bindParam(3, $post["Fname"]);
        $stmt->bindParam(4, $post["Lname"]);
        $stmt->bindParam(5, $post["address"]);

        // Execute insert
        $stmt->execute();

        // Close connection
        $conn = null;

        return true;

    } catch (PDOException $e) {
        error_log("User registration DB error: " . $e->getMessage());
        throw new Exception("Database error during registration");
    } catch (Exception $e){
        error_log("User registration error: " . $e->getMessage());
        throw new Exception("General registration error");
    }
}

function reg_puser($conn, $post){ // finish this
    try{
        $sql = "insert into producer (email, password, Fname, Lname) values (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $post['email']); // binds param for security
        //hash the password
        $hpswd = password_hash($post["password"], PASSWORD_DEFAULT); //has the password - using prebuilt libary to encrypt password using default algorithm encryption, because we dont have anything else to encrpyt it with - if this was real i would use a better encryption like: BCRYPT, ARGON2I, ARGON2ID
        $stmt->bindParam(2, $hpswd);
        $stmt->bindParam(3, $post["Fname"]);
        $stmt->bindParam(4, $post["Lname"]);

        $stmt->execute(); //run the query to insert
        $conn = null; // closes the connection so cant be abused.
        return true; // registration successful
    } catch (PDOException $e) {
        // handle database errors
        error_log("User Reg database error:    " . $e->getMessage());
        throw new exception("User Reg Database error". $e);
    }catch (Exception $e){
        //handle validation or other errors
        error_log("User Registration error:    " . $e->getMessage()); // log the error
        throw new exception("User Registration error". $e->getmessage()); //throw exception
    }
}

function reg_product($conn, $post){
    try{
        $sql = "insert into product (ProducerID, Pname, Pdesc, Price, Stock) values (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $post['ProducerID']);
        $stmt->bindParam(2, $post['Pname']); // binds param for security
        $stmt->bindParam(3, $post['Pdesc']); // binds param for security
        $stmt->bindParam(4, $post["Price"]);
        $stmt->bindParam(5, $post["Stock"]);

        $stmt->execute(); //run the query to insert
        $conn = null; // closes the connection so cant be abused.
        return true; // registration successful
    } catch (PDOException $e) {
        // handle database errors
        error_log("Product Reg database error:    " . $e->getMessage());
        throw new exception("Product Reg Database error". $e);
    }catch (Exception $e){
        //handle validation or other errors
        error_log("Product Registration error:    " . $e->getMessage()); // log the error
        throw new exception("Product Registration error". $e->getmessage()); //throw exception
    }
}


/**
 * Retrieve user login credentials by email.
 *
 * NOTE: This function does NOT verify password.
 * Password verification should be done using password_verify().
 *
 * @param PDO $conn
 * @param array $post
 * @return array User record (CustomerID + hashed password)
 */
function login($conn, $post){
    try {
        // Use read-only DB connection
        $conn = dbconnect_select();

        $sql = "SELECT CustomerID, password FROM customer WHERE email = ?";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $post["email"]);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $conn = null;

        if ($result) {
            return $result;
        } else {
            $_SESSION["usermessage"] = "User not found";
            header("Location: login.php");
            exit;
        }

    } catch (PDOException $e) {
        $_SESSION["usermessage"] = "Login error";
        header("Location: login.php");
        exit;
    }
}

function Slogin($conn, $post){
    try {
        $conn = dbconnect_select();
        $sql = "SELECT ProducerID, password FROM producer WHERE email = ?"; //set up the sql statement
        $stmt = $conn->prepare($sql); //prepares
        $stmt->bindParam(1, $post["email"]); //binds the parameters to execute
        #$stmt->bindParam(1, $email);
        $stmt->execute(); //run the sql code
        $result = $stmt->fetch(PDO::FETCH_ASSOC); //brngs back results
        $conn = null; //nulls off the connection so cant be abused

        if ($result) { //if a result is returned
            return $result;

        } else {
            $_SESSION["usermessage"] = "user was not found";
            header("Location: Slogin.php");
            exit; // stop further execution
        }
    } catch (PDOException $e) {
        $_SESSION["usermessage"] = "user login".$e->getMessage();
        header("Location: Slogin.php");
        exit; // stop further execution
    }
}

function getnewuserid($conn, $email){  # upon registering, retrieves the userid from the system to audit.
    $sql = "SELECT userid FROM user WHERE email = ?"; //set up the sql statement
    $stmt = $conn->prepare($sql); //prepares
    $stmt->bindParam(1, $email);
    $stmt->execute(); //run the sql code
    $result = $stmt->fetch(PDO::FETCH_ASSOC);  //brings back results
    return $result["userid"];
}


function customeraudit($conn, $userid, $code, $long)
{ # on doing any action auditor is required
    $sql = "INSERT INTO audit (date, customerid, code, long) values (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); //prepare to sql
    $date = date("Y-m-d"); //only variables should be passed - is date format mysql needs and accepts
    $stmt->bindParam(1, $date); //bind parameters for security
    $stmt->bindParam(2, $userid);
    $stmt->bindParam(3, $code);
    $stmt->bindParam(4, $long);

    $stmt->execute(); //run the query to insert
    $conn = null; //closes the connection so cant be abused
    return true; //registration successful
}

function produceraudit($conn, $userid, $code, $long)
{ # on doing any action auditor is required
    $sql = "INSERT INTO audit (date, ProducerID, code, long) values (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); //prepare to sql
    $date = date("Y-m-d"); //only variables should be passed - is date format mysql needs and accepts
    $stmt->bindParam(1, $date); //bind parameters for security
    $stmt->bindParam(2, $userid);
    $stmt->bindParam(3, $code);
    $stmt->bindParam(4, $long);

    $stmt->execute(); //run the query to insert
    $conn = null; //closes the connection so cant be abused
    return true; //registration successful
}

function product_getter($conn){
    $sql = "SELECT b.ProductID, b.Pname, b.Pdesc, b.Price, b.Stock, s.Fname, s.Lname FROM product b JOIN producer s ON b.ProducerID = s.ProducerID WHERE b.ProducerID = ? ORDER BY b.ProductID ASC";
//book table is main source of data, staff table is only filling gaps
    $stmt = $conn->prepare($sql);

    $stmt->execute([$_SESSION["ProducerID"]]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    if($result){
        return $result;
    } else {
        return false;
    }

}

function Basket_getter($conn){

    $sql = "SELECT b.Fname, b.Lname, p.ProductID,p.Pname,p.Pdesc,p.Price, p.Stock, c.quantity FROM cart c JOIN `order` o ON c.OrderID = o.OrderID JOIN product p ON c.ProductID = p.ProductID JOIN producer b ON p.ProducerID = b.ProducerID WHERE o.CustomerID = ?";



    #$sql = "SELECT b.ProductID, b.Pname, b.Pdesc, b.Price, b.Stock, s.Fname, s.Lname FROM product b JOIN order s ON b.OrderID = s.OrderID WHERE b.OrderID = ? ORDER BY b.ProductID ASC";
//book table is main source of data, staff table is only filling gaps
    $stmt = $conn->prepare($sql);

    $stmt->execute([$_SESSION["CustomerID"]]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    if($result){
        return $result;
    } else {
        return false;
    }

}

function remove_product($conn, $ProductID)
{
    $sql = "DELETE FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ProductID]);
    return true;
}

function remove_item($conn, $ProductID)
{
    $sql = "DELETE FROM cart WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ProductID]);
    return true;
}

function shop($conn)
{
    $sql = "SELECT b.ProductID, b.Pname, b.Pdesc, b.Price, b.Stock, s.Fname, s.Lname FROM product b JOIN producer s ON b.ProducerID = s.ProducerID ORDER BY b.ProductID ASC";
//book table is main source of data, staff table is only filling gaps
    $stmt = $conn->prepare($sql);

    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $conn = null;
    if($result){
        return $result;
    } else {
        return false;
    }
}

/**
 * Add a product to the current user's cart.
 *
 * If no active order exists, one will be created.
 * If the product already exists in the cart, its quantity will be increased.
 *
 * @param PDO $conn Database connection
 * @param int $productID Product to add
 * @return bool True on success
 * @throws Exception
 */
function product_buy($conn, $ProductID){
    try{
        if (!isset($_SESSION['OrderID'])) {
            $sql = "INSERT INTO 'order' (CustomerID, Time) values (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $_POST['CustomerID']);
            $stmt->bindParam(2, $_POST['Time']); // binds param for security
            $stmt->execute();
            $usr = login(dbconnect_insert(), $_POST["email"]);
            $conn = dbconnect_select();
            $sql = "SELECT OrderID FROM `order` WHERE CustomerID = ?"; //set up the sql statement
            $stmt = $conn->prepare($sql); //prepares
            $stmt->bindParam(1, $_POST["OrderID"]); //binds the parameters to execute
            $stmt->execute(); //run the sql code
            $result = $stmt->fetch(PDO::FETCH_ASSOC); //brngs back results
            $conn = null; //nulls off the connection so cant be abused

            if ($result) { //if a result is returned
                return $result;
            }
        }
        $stmt->execute(); //run the query to insert
        $conn = null; // closes the connection so cant be abused.
        return true; // registration successful
    } catch (PDOException $e) {
        // handle database errors
        error_log("Product Reg database error:    " . $e->getMessage());
        throw new exception("Product Reg Database error". $e);
    } catch (Exception $e){
        //handle validation or other errors
        error_log("Product Registration error:    " . $e->getMessage()); // log the error
        throw new exception("Product Registration error". $e->getmessage()); //throw exception
    }
}


function stock_check($productID, $quantity, $conn) {
    // Get stock
    $sql = "SELECT Stock FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productID]);
    $product = $stmt->fetch();
    if (!$product) { return false; }
    // Get current cart quantity
    $OrderID = only_order($conn);
    $sql = "SELECT quantity FROM cart WHERE ProductID = ? AND OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productID, $OrderID]);
    $cartItem = $stmt->fetch();
    $currentQty = $cartItem ? $cartItem['quantity'] : 0;
    // Final safety check
    if (($currentQty + $quantity) > $product['Stock']) {
        return false;
    }
    // Update if safe
    $sql = "UPDATE cart SET quantity = quantity + ? WHERE ProductID = ? AND OrderID = ?";
    $stmt = $conn->prepare($sql);
    return $stmt->execute([$quantity, $productID, $OrderID]);#
}



function basket_check($productID, $requestedQty, $conn) {
    $sql = "SELECT Stock FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productID]);
    $product = $stmt->fetch();

    if (!$product) {
        return false;
    }

    // For UPDATE: just compare requested quantity
    return $requestedQty <= $product['Stock'];
}

function more_product($conn, $productID, $quantity, $OrderID) {

    // Get stock
    $sql = "SELECT Stock FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productID]);
    $product = $stmt->fetch();

    if (!$product) {
        return false;
    }

    // Get current cart quantity
    $sql = "SELECT quantity FROM cart WHERE ProductID = ? AND OrderID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$productID, $OrderID]);
    $cartItem = $stmt->fetch();

    $currentQty = $cartItem ? $cartItem['quantity'] : 0;

    // Final safety check
    if (($currentQty + $quantity) > $product['Stock']) {
        return false;
    }

    // Update if safe
    $sql = "UPDATE cart SET quantity = quantity + ? WHERE ProductID = ? AND OrderID = ?";

    $stmt = $conn->prepare($sql);
    return $stmt->execute([$quantity, $productID, $OrderID]);
}


function only_order($conn) {
    try {
        if (!isset($_SESSION['OrderID'])) {
            $CustomerID = $_SESSION['CustomerID'];

            // Check if customer already has an incomplete order
            $sql = "SELECT OrderID FROM `order` WHERE CustomerID = ? AND Status = 0 ORDER BY OrderID DESC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$CustomerID]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Use the existing incomplete order
                $_SESSION['OrderID'] = $result['OrderID'];
            } else {
                // No incomplete order — create a new one
                $sql = "INSERT INTO `order` (CustomerID, Status) VALUES (?, 0)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$CustomerID]);
                $_SESSION['OrderID'] = $conn->lastInsertId();
            }
        }

        return $_SESSION['OrderID'];
    } catch (PDOException $e) {
        error_log("Order DB error: " . $e->getMessage());
        throw new Exception("Database error");
    }
}
function only_product($conn, $ProductID) {
    try {
        if (!isset($_SESSION['OrderID'])) {
            return false; // no order exists yet
        }

        $OrderID = $_SESSION['OrderID'];

        $sql = "SELECT * FROM cart WHERE OrderID = ? AND ProductID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$OrderID, $ProductID]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
    }

    catch (PDOException $e) {//catch error
        //log the error
        error_log("Database error in only_user:    " . $e->getMessage());
        //throw the exception
        throw $e;//re-throw the exception
    }
}