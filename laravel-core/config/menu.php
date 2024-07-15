<?php

return array(
    array(
        "type" => "link",
        "content" => "Tableau de bord",
        "active_when" => ["App\Http\Controllers\DashboardController@index"],
        "route" => "/",
        "icon" => array("type" => "lucide", "content" => "House"),
    ),
    array("type" => "divider"),
    array(
        "type" => "link",
        "content" => "Stock",
        "active_when" => ["App\Http\Controllers\StockController#"],
        "route" => "/stock",
        "icon" => array("type" => "lucide", "content" => "Boxes"),
    ),
    array(
        "type" => "link",
        "content" => "Entrées",
        "active_when" => ["App\Http\Controllers\InboundController#"],
        "route" => "/inbounds",
        "icon" => array("type" => "lucide", "content" => "BetweenHorizontalEnd"),
    ),
    array(
        "type" => "link",
        "content" => "Sorties",
        "active_when" => ["App\Http\Controllers\OutboundController#"],
        "route" => "/outbounds",
        "icon" => array("type" => "lucide", "content" => "LogOut"),
    ),
    array(
        "type" => "link",
        "content" => "Coûts",
        "route" => "boxes",
        "icon" => array("type" => "lucide", "content" => "Calculator"),
    ),
    
    array("type" => "divider"),
    array(
        "type" => "link",
        "content" => "Rubriques",
        "active_when" => ["App\Http\Controllers\RubriqueController#"],
        "route" => "/rubriques",
        "icon" => array("type" => "lucide", "content" => "Blocks"),
    ),
    array(
        "type" => "link",
        "content" => "Produits",
        "active_when" => ["App\Http\Controllers\ProductController#"],
        "route" => "/products",
        "icon" => array("type" => "lucide", "content" => "Package"),
    ),
    array(
        "type" => "link",
        "content" => "Fournisseurs",
        "active_when" => ["App\Http\Controllers\SupplierController#"],
        "route" => "/suppliers",
        "icon" => array("type" => "lucide", "content" => "Contact"),
    ),
    array(
        "type" => "link",
        "content" => "Services",
        "active_when" => ["App\Http\Controllers\ServiceController#"],
        "route" => "/services",
        "icon" => array("type" => "lucide", "content" => "Building2"),
    ),
    array("type" => "divider"),

    array(
        "type" => "link",
        "content" => "Users",
        "section" => "dashboard",
        "route" => "dashboard",
        "icon" => array("type" => "lucide", "content" => "Users"),
    ),
    array(
        "type" => "link",
        "content" => "Settings",
        "section" => "dashboard",
        "route" => "dashboard",
        "icon" => array("type" => "lucide", "content" => "Settings"),
    ),

);