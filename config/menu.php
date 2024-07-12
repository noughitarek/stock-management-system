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
        "route" => "stock",
        "icon" => array("type" => "lucide", "content" => "Boxes"),
    ),
    array(
        "type" => "link",
        "content" => "Entrées",
        "route" => "boxes",
        "icon" => array("type" => "lucide", "content" => "BetweenHorizontalEnd"),
    ),
    array(
        "type" => "link",
        "content" => "Sorties",
        "route" => "boxes",
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
        "route" => "rubriques",
        "icon" => array("type" => "lucide", "content" => "Blocks"),
    ),
    array(
        "type" => "link",
        "content" => "Produits",
        "route" => "products",
        "icon" => array("type" => "lucide", "content" => "Package"),
    ),
    array(
        "type" => "link",
        "content" => "Fournisseurs",
        "route" => "suppliers",
        "icon" => array("type" => "lucide", "content" => "Contact"),
    ),
    array(
        "type" => "link",
        "content" => "Services",
        "route" => "services",
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