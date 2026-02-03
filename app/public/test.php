<?php
echo "<h1>It Works!</h1>";
echo "<p>Docker is successfully serving files from the <b>app/public</b> folder.</p>";
echo "<pre>";
print_r(PDO::getAvailableDrivers()); 
echo "</pre>";
phpinfo();
?>