<!DOCTYPE html>
<head>
   <title>Test Page</title>
</head>

<body>
   <?php
      // printing (not case sensitive)
      ecHO "Hello, World!";

      // variables: $variable = x; (case sensitive)
      $name = "Campus Hub";
      echo "<br>Welcome to $name!";
      
      $arr = [1, 2, 3, 4, 5];
      $arr = array(1, 2, 3, 4, "five"); // alternative syntax

      // Indexed array: $array[0] = 1; $array[1] = 2; ...
      // Associative array: $array["key"] = "value";

      // Null 
      $n = null;
      if (is_null($n)) {
         echo "<br>Variable n is null.";
      }

      // Functions
      function greet($name): string {
         return "Hello, $name!";
      }

   ?>
</body>
</html>