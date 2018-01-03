<html lang="en">
<head>
    <title>My Shop</title>
</head>
<body>
    <h1>Welcome to my shop</h1>
    <ul>
        <?php
            $search = 'http://product-service:9200/products/_search';
            $json = file_get_contents($search);
            $obj = json_decode($json);
            $hits = $obj->{'hits'}->{'hits'};
            
            foreach($hits as $hit){
                $product = $hit->{'_source'}->{'name'}; 
                echo "<li>$product</li>";
            }           
        ?>
    </ul>
</body>
</html>
