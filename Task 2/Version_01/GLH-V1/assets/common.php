<?php

function only_user($conn, $email) {
    try {
        $sql = "SELECT email FROM customer WHERE email = ?"; //set up the sql statement
        $stmt = $conn->prepare($sql);//prepares
        $stmt->bindParam(1, $email);
        $stmt->execute();//run the sql code
        $result = $stmt->fetch(PDO::FETCH_ASSOC); //brings back results
        $conn = null;
        if ($result) {
            return true; # there is only one user
        } else {
            return false; # this customer has already registered
        }
    }
    catch (PDOException $e) {//catch error
        //log the error
        error_log("Database error in only_user:    " . $e->getMessage());
        //throw the exception
        throw $e;//re-throw the exception
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


function reg_user($conn, $post){ // finish this
        try{
            $sql = "insert into customer (email, password, Fname, Lname, address) values (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $post['email']); // binds param for security
            //hash the password
            $hpswd = password_hash($post["password"], PASSWORD_DEFAULT); //has the password - using prebuilt libary to encrypt password using default algorithm encryption, because we dont have anything else to encrpyt it with - if this was real i would use a better encryption like: BCRYPT, ARGON2I, ARGON2ID
            $stmt->bindParam(2, $hpswd);
            $stmt->bindParam(3, $post["Fname"]);
            $stmt->bindParam(4, $post["Lname"]);
            $stmt->bindParam(5, $post["address"]);

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

function login($conn, $post){
    try {
        $conn = dbconnect_select();
        $sql = "SELECT CustomerID, password FROM customer WHERE email = ?"; //set up the sql statement
        $stmt = $conn->prepare($sql); //prepares
        $stmt->bindParam(1, $_POST["email"]); //binds the parameters to execute
        $stmt->execute(); //run the sql code
        $result = $stmt->fetch(PDO::FETCH_ASSOC); //brngs back results
        $conn = null; //nulls off the connection so cant be abused

        if ($result) { //if a result is returned
            return $result;

        } else {
            $_SESSION["usermessage"] = "user was not found";
            header("Location: login.php");
            exit; // stop further execution
        }
    } catch (PDOException $e) {
        $_SESSION["usermessage"] = "user login".$e->getMessage();
        header("Location: login.php");
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


function auditor($conn, $userid, $code, $long)
{ # on doing any action auditor is required
    $sql = "INSERT INTO audit (date, userid, code, long) values (?, ?, ?, ?)";
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