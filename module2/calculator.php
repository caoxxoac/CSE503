<!doctype html>
<html>
<head>
  <title>My Calculator</title>
</head>

<body>
  <form name="input" action="calculator.php" method="GET">
    First Number: <br><input type="text" name="num1" value="<?php if(isset($_GET["num1"])) echo $_GET["num1"]; ?>"/><br>
    Second Number: <br><input type="text" name="num2" value="<?php if(isset($_GET["num2"])) echo  $_GET["num2"]; ?>"/><br>
    <input type="radio" name="calculation" value="addition" checked="checked"/>Addition<br>
    <input type="radio" name="calculation" value="subtraction"/>Subtraction<br>
    <input type="radio" name="calculation" value="multiplication"/>Multiplication<br>
    <input type="radio" name="calculation" value="division" />Division<br>
    <input type="submit" value="Calculate"/>
  </form>
  <?php
  $firstNum = $_GET["num1"];
  $secondNum = $_GET["num2"];
  $calcType = $_GET["calculation"];
  $addition = $firstNum + $secondNum;
  $subtraction = $firstNum - $secondNum;
  $multiplication = $firstNum * $secondNum;

  if ($firstNum == NULL || $secondNum == NULL){
    echo "Please fill in both fields";
  }
  else {
    if ($calcType == "addition"){
      echo "$firstNum + $secondNum equals to $addition";
    }
    elseif ($calcType == "subtraction") {
      echo "$firstNum - $secondNum equals to $subtraction";
    }
    elseif ($calcType == "multiplication") {
      echo "$firstNum * $secondNum equals to $multiplication";
    }
    elseif ($calcType == "division") {
      if ($secondNum == 0) {
        echo "The denominator cannot be 0, try other numbers";
      }
      else {
        $division = $firstNum / $secondNum;
        echo "$firstNum / $secondNum equals to $division";
      }
    }
  }
  ?>
</body>
</html>
