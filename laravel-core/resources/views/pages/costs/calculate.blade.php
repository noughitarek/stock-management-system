<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Couts</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .a4 {
            width: 210mm;
            height: 297mm;
            margin: auto;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            position: relative;
        }
        header {
            text-align: center;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            margin: 0;
        }
        h2 {
            font-size: 18px;
            margin: 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            font-size: 16px;
        }
        .section-content {
            margin-left: 10px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        footer {
            position: absolute;
            bottom: 20mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="a4">
        <header>
            <h1>Couts</h1>
        </header>
        <div class="section">
            <div class="section-title">Rubrique:</div>
            <div class="section-content">{{$rubrique->name}}</div>
        </div>
        <div class="section">
            <div class="section-title">Mois:</div>
            <div class="section-content">{{$date}}</div>
        </div>
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>Designation</th>
                        <th>Qte</th>
                        <th>Service</th>
                        <th>Prix UN/HT</th>
                        <th>Prix UN/TTC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $stock)
                    @foreach($stock['products'] as $product)
                    <tr>
                        <td>{{$product['product']['designation']}}</td>
                        <td>{{$product['qte']}}</td>
                        <td>{{$stock['service']['name']}}</td>
                        <td>{{$product['unit_price_excl_tax']}} DZD</td>
                        <td>{{$product['unit_price_net']}} DZD</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <footer>
            &copy; 2024 Command Notes. All rights reserved.
        </footer>
    </div>
</body>
</html>
