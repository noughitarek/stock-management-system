@php
$data = array(
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
    array(
        "date" => "16/07/2024 19:21",
        "provenance" => "n/a",
        "InQte" => 2,
        "price" => 2500,

        "dn" => 18,
        "service" => "CCI",
        "OutQte" => 2,

        "oldStock" => 53,
        "newwStock" => 74,
        
        "obs" => "ss",
    ),
)

@endphp
<!DOCTYPE html>
<html>
<head>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
        }
        @page {
            size: A4;
            margin: 0;
        }
        table {
            width: 100%;
            height: 100vh;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td {
            padding: 8px;
            text-align: left;
        }
        th {
            border: 1px solid black;
            padding: 8px;
            background-color: #f2f2f2;
            text-align: left;
        }
        h1 {
            text-align: center;
            margin: 0;
        }
        .inner-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        .inner-table td, .inner-table th {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Fiche nº: 09</td>
        </tr>
        <tr>
            <td>DESIGNATION</td>
            <td><div style="border: 1px solid black;">s</div></td>
            <td></td>
            <td colspan="2"><h1>FICHE DE STOCK</h1></td>
        </tr>
        <tr>
            <td colspan="5" style="padding: 0;">
                <table class="inner-table">
                    <tr>
                        <th colspan="4">Mouvement Entrées</th>
                        <th colspan="3">Mouvement Sorties</th>
                        <th colspan="2">Stock</th>
                        <th rowspan="2">OBSERVATION</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Provenance</th>
                        <th>Quantité</th>
                        <th>Prix</th>
                        <th>Nº B.S</th>
                        <th>Service</th>
                        <th>Quantité</th>
                        <th>Ancien</th>
                        <th>Nouveau</th>
                    </tr>
                    @for($i=0;$i<10000;$i++)
                    <tr>
                        <td>{{$data[0]['date']}}</td>
                        <td>{{$data[0]['provenance']}}</td>
                        <td>{{$data[0]['InQte']}}</td>
                        <td>{{$data[0]['price']}}</td>
                        <td>{{$data[0]['dn']}}</td>
                        <td>{{$data[0]['service']}}</td>
                        <td>{{$data[0]['OutQte']}}</td>
                        <td>{{$data[0]['oldStock']}}</td>
                        <td>{{$data[0]['newwStock']}}</td>
                        <td>{{$data[0]['obs']}}</td>
                    </tr>
                    @endfor
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
