<?php 
	// CHANGED HERE TO ADD NEW readcooperados FILE REQUIREMENT 
	include_once(__DIR__."/conn.php");
	include_once(__DIR__."/connection.php");
	require_once(__DIR__."/model/Member.php");
	require_once(__DIR__."/model/BusinessUnit.php");
?>

<?php 
	$charset = "UTF-8";
    
    $url = $_GET['url'];
    list ($var1, $var2, $var3, $var4) = explode('/', $url);
    
	$idioma = $var1;
	if($idioma=="") $idioma="br";
    
    $language = $idioma;
	if ($language == 'br') $language = 'pt';

	$conn = makeConnection();
	$statement = $conn->prepare('SELECT id FROM business_units WHERE parent_id IS NULL');
	$statement->execute();

	$businessUnits = [];
	$rootBusinessUnits = [];

	foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $i => $j) {     
        $businessUnit = new BusinessUnit($j['id'], $language, TRUE, TRUE, TRUE, TRUE);
        
        // Start Trial 
        echo 'Business Unit: ';
        echo $businessUnit;
        echo '</br>';
        // End Trial

        array_push($businessUnits, $businessUnit->toJSON(FALSE));
		array_push($rootBusinessUnits, $businessUnit->name_getter());
	};

    // Start Trial 
    echo 'Business Units: ';
    echo print_r($businessUnits);
    echo '</br>';
    // End Trial

	terminateConnection();

?>