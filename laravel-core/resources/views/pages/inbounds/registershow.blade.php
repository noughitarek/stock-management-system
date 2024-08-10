@php
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre des entrees</title>
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
            <h1>Registre des entrees</h1>
        </header>
        <div class="section">
            <div class="section-title">Rubrique:</div>
            <div class="section-content">{{$rubrique->name}}</div>
        </div>
        <div class="section">
            <div class="section-title">Annee:</div>
            <div class="section-content">{{$date}}</div>
        </div>
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Designation</th>
                        <th>Qte</th>
                        <th>Prix UN/HT</th>
                        <th>Montant UN/HT</th>
                        <th>N de bon de livraison</th>
                        <th>N de bon de commande</th>
                        <th>N de fiche de stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $inbound)
                    @foreach($inbound->inboundProducts as $product)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($inbound->inbound_at)->format('d-m-Y') }}</td>
                        <td>{{$product->product->designation}}</td>
                        <td>{{$product->qte}}</td>
                        <td>{{$product->unit_price_excl_tax}} DZD</td>
                        <td>{{$product->total_amount_excl_tax}} DZD</td>
                        <td>{{$inbound->delivery_note_number}}</td>
                        <td>{{$inbound->commande_note_number}}</td>
                        <td>{{$product->product->id}}</td>
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
