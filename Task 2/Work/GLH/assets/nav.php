<?php
echo "<div class='navi'>";
echo "<nav>";  #decales

    echo "<ul>";  #declares an unordered list


        echo "<li class='linkbox'> <a href='index.php'>Index</a></li>"; #open a cell for a link to be housed
        echo "<li class='linkbox'> <a href='aboutus.php'>About us</a></li>"; # link to the register page

        if(!isset($_SESSION["user"]) and !isset($_SESSION["Puser"])){ #if user is not logged on to any user account
            echo "<li class='linkbox'> <a href='login.php'>Login</a></li>"; # link to the login page
            echo "<li class='linkbox'> <a href='register.php'>Register</a></li>"; # link to the register page
            echo "<li class='linkbox'> <a href='Slogin.php'>Staff Login</a></li>"; # link to the login page
            echo "<li class='linkbox'> <a href='Sregister.php'>Staff Registration</a></li>"; # link to the register page

        } elseif(isset($_SESSION["Puser"])){ #if user is logged in as staff
            echo "<li class='linkbox'> <a href='logout.php'>Logout</a></li>"; # lihk to logout page
            echo "<li class='linkbox'> <a href='addstocks.php'>Add stocks</a></li>"; # link to add stocks page
            echo "<li class='linkbox'> <a href='viewstock.php'>View Stocks</a></li>"; # link to add stocks page
        } else { # if user is logged in as Customer
            echo "<li class='linkbox'> <a href='order.php'>Order</a></li>"; # link to
            echo "<li class='linkbox'> <a href='Basket.php'>Basket</a></li>"; # link to
            echo "<li class='linkbox'> <a href='logout.php'>Logout</a></li>"; # link to logout page
        }

    echo "</ul>";  # closes the row of the table.

echo "</nav>"; #end of nav bar

echo "</div>";
?>

