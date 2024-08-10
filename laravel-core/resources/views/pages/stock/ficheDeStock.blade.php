<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Command Note</title>
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
            <h1>Fiche de Stock</h1>
            <h2>n {{$product->id}}</h2>
        </header>
        <div class="section">
            <div class="section-title">Rubrique:</div>
            <div class="section-content">{{$product->rubrique->name}}</div>
        </div>
        <div class="section">
            <div class="section-title">Designation:</div>
            <div class="section-content">{{$product->designation}}</div>
        </div>
        <div class="section">
            <div class="section-title">Date:</div>
            <div class="section-content">{{now()}}</div>
        </div>
        <div class="section">
            <table>
                <thead>
                    <tr>
                        <th colspan="4">Entrees</th>
                        <th colspan="3">Sorites</th>
                        <th colspan="2">Stock</th>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <th>Provenance</th>
                        <th>Quantite</th>
                        <th>Prix TTC</th>
                        <th>Bon de sortie</th>
                        <th>Service</th>
                        <th>Quantite</th>
                        <th>Ancien</th>
                        <th>Nouveau</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $oldStock = $product->init_stock;

                    @endphp
                    @foreach($data as $stock)
                    @php
                    if($stock->is_inbound){
                        $newStock = $oldStock+$stock->qte;
                    }
                    if($stock->is_outbound){
                        $newStock = $oldStock-$stock->qte;
                    }

                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($stock->date)->format('d-m-Y') }}</td>
                        @if($stock->is_inbound)
                        <td>{{$stock->inbound->supplier->name}}</td>
                        <td>{{$stock->qte}}</td>
                        <td>{{$stock->total_amount_net}} DZD</td>
                        @else 
                        <td></td><td></td><td></td>
                        @endif
                        @if($stock->is_outbound)
                        <td>{{$stock->outbound->internal_delivery_note_number}}</td>
                        <td>{{$stock->outbound->service->name}}</td>
                        <td>{{$stock->qte}}</td>
                        @else 
                        <td></td><td></td><td></td>
                        @endif
                        <td>{{$oldStock}}</td>
                        <td>{{$newStock}}</td>
                    </tr>
                    @php
                    $oldStock = $newStock;
                    @endphp
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
