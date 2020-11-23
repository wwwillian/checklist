<?php

$path = "data/in/";
$directory = dir($path);

while ($file = $directory -> read())
{
    if(pathinfo($file,PATHINFO_EXTENSION) === "dat")
    {
        $files = $path.$file;
        $data = file($files);

        $salesmanQuantity = 0;
        $customerQuantity = 0;
        $salesmanSalary = 0;
        $saleExpensive = 0;
        $saleRating = [];
        $saleArray = [];
        $idSaleExpensive = null;
        $SalesmanSaleCheap = null;

        foreach ($data as $lineData)
        {
            $lineData = trim($lineData);
            $value = explode(',', $lineData);

            $id = $value[0];

            if($id == 001)
            {
                $salesmanQuantity++;
                $salesmanSalary += floatval($value[3]);
            }

            if($id == 002)
            {
                $customerQuantity++;
            }

            if($id == 003)
            {
                preg_match('/\[(.*)\]/', $lineData, $matches);

                $saleValue = explode(',', $matches[1]);

                $salesTotal = 0;

                foreach ($saleValue as $sales)
                {
                    $sale = explode('­', trim($sales));

                    $salesTotal += floatval(str_replace(' ', '', $sale[2]));


                }
                array_push($saleRating, $salesTotal);

                $sales = [$value[1], $salesTotal, $value[5]];
                array_push( $saleArray, $sales);
            }
        }

        $averageSalary = $salesmanSalary/$salesmanQuantity;
        $max = max($saleRating);
        $min = min($saleRating);


        for ($i = 0; $i < count($saleArray); $i++)
        {
            if ($max === $saleArray[$i][1]) $idSaleExpensive = $saleArray[$i][0];
            if ($min === $saleArray[$i][1]) $SalesmanSaleCheap = $saleArray[$i][2];
        }

        $ext = explode('.',$file);
        $name = "data/out/{$ext[0]}.done.{$ext[1]}";

        $message = "#\nCustomer quantity: {$customerQuantity}\n".
            "Salesman quantity: {$salesmanQuantity}\n".
            "Salesman average wage: {$salesmanSalary}\n".
            "Expensive sale: {$idSaleExpensive}\n".
            "Worst seller: {$SalesmanSaleCheap}\n#\n";

        file_put_contents($name, $message, FILE_APPEND);
    }
}

$directory -> close();

