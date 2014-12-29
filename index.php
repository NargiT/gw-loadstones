<!DOCTYPE html>
<?php require "lib/php/GW2SpidyDataset.php"; ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="style/style.css" />
        <link rel="stylesheet" type="text/css" href="style/toolTip.css" />
        <script type="text/javascript" src="lib/js/jquery-1.8.3.min.js"></script>
        <title>Error Legacy Trading Post</title>
    </head>
    <body>
        <?php

        function showPrice($value) {

            $minus = ($value < 0) ? "-" : "";
            $value = abs($value);
// gold
            $g = (strlen($value) > 4) ? substr($value, 0, strlen($value) - 4) : 0;

// silver
            if (strlen($value) >= 4)
                $s = substr($value, strlen($value) - 4, 2);
            elseif (strlen($value) == 3)
                $s = substr($value, 0, 1);
            else
                $s = 0;

// copper
            if (strlen($value) < 3)
                $c = $value;
            else
                $c = substr($value, strlen($value) - 2, 2);

            $result = $minus;
            $result .= ($g != 0) ? $g . '<img src="http://www.gw2spidy.com/assets/v20121220v2/img/gold.png" alt="gold" />' : '';
            $result .= ($s != 0) ? ' ' . $s . '<img src="http://www.gw2spidy.com/assets/v20121220v2/img/silver.png" alt="gold" />' : '';
            $result .= ($c != 0) ? ' ' . $c . '<img src="http://www.gw2spidy.com/assets/v20121220v2/img/copper.png" alt="gold" />' : '';
            return $result;
        }

        $gw2spidy = new GW2SpidyDataset(array(24305, 24310, 24315, 24320, 24325, 24330, 24340, 24304, 24339, 24329, 24324, 24319, 24314, 24309, 24277, 19663));
        //$gw2spidy->showData();

        $lodestones = array("Charged" => "Chargé",
            "Corrupted" => "Corrompu",
            "Crystal" => "Cristal",
            "Destroyer" => "Destructeur",
            "Molten" => "Fusion",
            "Glacial" => "Glaciale",
            "Onyx" => "Onyx",
        );

        $wine = $gw2spidy->getItem("Bottle of Elonian Wine");
        $dust = $gw2spidy->getItem("Pile of Crystalline Dust");

        $table = "";
        $max = (PHP_INT_MAX * -1) - 1;
        $offreCOLOR = "#4572A7";
        $demandeCOLOR = "#AA4643";
        $precision = 2;
        $MAX_H = 40;

        foreach ($lodestones as $key => $value) {
            $noyau = "";
            $columnODN = "";
            $magnetite = "";
            $columnOD = "";
            $lodestone = $gw2spidy->getItem($key . ' Lodestone'); // récupère les magnétite
            $core = $gw2spidy->getItem($key . ' Core'); // récupère les noyaux
            $fab = (int) ($core['max_offer_unit_price'] * 2 + $wine['max_offer_unit_price'] + $dust['max_offer_unit_price']); // prix de fabrication
            $gain = $lodestone['min_sale_unit_price'] - annonce($lodestone['min_sale_unit_price']) - taxe($lodestone['min_sale_unit_price']) - $fab; // les gains
            // offre / demande Magnétite
            $total = $lodestone['sale_availability'] + $lodestone['offer_availability'];
            $offre = $lodestone['sale_availability'] * $MAX_H / $total;
            $demande = $lodestone['offer_availability'] * $MAX_H / $total;

            // offre / demande Noyau
            $totalN = $core['sale_availability'] + $core['offer_availability'];
            $offreN = $core['sale_availability'] * $MAX_H / $totalN;
            $demandeN = $core['offer_availability'] * $MAX_H / $totalN;

            // MAJ date
            $date = intval(date("i", time() - strtotime($lodestone['price_last_changed']))); // dernière maj
            $class = ($date < 6) ? "green" : "red"; // couleur de la maj

            $columnODN .= "<span href='#' class='tooltip'>";
            $columnODN .= "<span class='classic'>";
            $columnODN .= "o/d: " . round($core['sale_availability'] / $core['offer_availability'], $precision);
            $columnODN .= "<br />";
            $columnODN .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='10' height='10'><rect width='10' height='10' x='0' y='0' fill='$offreCOLOR' /></svg> ";
            $columnODN .= $core['sale_availability'];
            $columnODN .= "<br />";
            $columnODN .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='10' height='10'><rect width='10' height='10' x='0' y='0' fill='$demandeCOLOR' /></svg> ";
            $columnODN .= $core['offer_availability'];
            $columnODN .= "</span>";
            $columnODN .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='21' height='$MAX_H'>";
            $columnODN .= "<rect width='10' height='" . $offreN . "' x='0' y='" . ($MAX_H - $offreN) . "' fill='$offreCOLOR' />";
            $columnODN .= "<rect width='10' height='" . $demandeN . "' x='11' y='" . ($MAX_H - $demandeN) . "' fill='$demandeCOLOR' />";
            $columnODN .= "</svg>";
            $columnODN .= "</span>";

            $noyau .= "<a href='http://www.gw2spidy.com/item/" . $core['data_id'] . "' target='_blank'>";
            $noyau .= "<img src='" . $core['img'] . "'/> ";
            $noyau .= $columnODN;
            $noyau .= "</a>";

            $columnOD .= "<span href='#' class='tooltip'>";
            $columnOD .= "<span class='classic'>";
            $columnOD .= "o/d: " . round($lodestone['sale_availability'] / $lodestone['offer_availability'], $precision);
            $columnOD .= "<br />";
            $columnOD .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='10' height='10'><rect width='10' height='10' x='0' y='0' fill='$offreCOLOR' /></svg> ";
            $columnOD .= $lodestone['sale_availability'];
            $columnOD .= "<br />";
            $columnOD .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='10' height='10'><rect width='10' height='10' x='0' y='0' fill='$demandeCOLOR' /></svg> ";
            $columnOD .= $lodestone['offer_availability'];
            $columnOD .= "</span>";
            $columnOD .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='21' height='$MAX_H'>";
            $columnOD .= "<rect width='10' height='" . $offre . "' x='0' y='" . ($MAX_H - $offre) . "' fill='$offreCOLOR' />";
            $columnOD .= "<rect width='10' height='" . $demande . "' x='11' y='" . ($MAX_H - $demande) . "' fill='$demandeCOLOR' />";
            $columnOD .= "</svg>";
            $columnOD .= "</span>";

            $magnetite .= "<a href='http://www.gw2spidy.com/item/" . $lodestone['data_id'] . "' target='_blank'>";
            $magnetite .= "<img src='" . $lodestone['img'] . "'/> ";
            $magnetite .= $columnOD;
            $magnetite .= "</a>";

            $table .= "<tr><td>$value</td>";
            $table .= "<td class='alignleft'>$magnetite</td>";
            $table .= "<td class='alignleft'>$noyau</td>";
            $table .= "<td class='alignright'>";
            $table .= showPrice($core['max_offer_unit_price']);
            $table .= "</td><td class='alignright'>";
            $table .= showPrice($lodestone['min_sale_unit_price']);
            $table .= "</td><td class='alignright'>";
            $table .= showPrice($fab);
            $table .= "</td><td class='alignright'>";
            $table .= showPrice(annonce($lodestone['min_sale_unit_price']));
            $table .= "</td><td class='alignright'>";
            $table .= showPrice(taxe($lodestone['min_sale_unit_price']));
            $table .= "</td><td class='alignright'>";
            $table .= showPrice($gain);
            $table .= "</td>";
            $table .= "<td class='aligncenter " . $class . "'>";
            $table .= $date . " min";
            $table .= "</td></tr>";
        }

        $date_wine = intval(date("i", time() - strtotime($wine['price_last_changed']))); // dernière maj
        $date_dust = intval(date("i", time() - strtotime($dust['price_last_changed']))); // dernière maj
        $class_wine = ($date_wine < 6) ? "green" : "red"; // couleur de la maj
        $class_dust = ($date_dust < 6) ? "green" : "red"; // couleur de la maj

        // offre / demande Magnétite
        $total = $dust['sale_availability'] + $dust['offer_availability'];
        $offre = $dust['sale_availability'] * $MAX_H / $total;
        $demande = $dust['offer_availability'] * $MAX_H / $total;

        $columnODP = "<span href='#' class='tooltip'>";
        $columnODP .= "<span class='classic'>";
        $columnODP .= "o/d: " . round($dust['sale_availability'] / $dust['offer_availability'], $precision);
        $columnODP .= "<br />";
        $columnODP .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='10' height='10'><rect width='10' height='10' x='0' y='0' fill='$offreCOLOR' /></svg> ";
        $columnODP .= $dust['sale_availability'];
        $columnODP .= "<br />";
        $columnODP .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='10' height='10'><rect width='10' height='10' x='0' y='0' fill='$demandeCOLOR' /></svg> ";
        $columnODP .= $dust['offer_availability'];
        $columnODP .= "</span>";
        $columnODP .= "<svg xmlns='http://www.w3.org/2000/svg' version='1.1' width='21' height='$MAX_H'>";
        $columnODP .= "<rect width='10' height='" . $offre . "' x='0' y='" . ($MAX_H - $offre) . "' fill='$offreCOLOR' />";
        $columnODP .= "<rect width='10' height='" . $demande . "' x='11' y='" . ($MAX_H - $demande) . "' fill='$demandeCOLOR' />";
        $columnODP .= "</svg>";
        $columnODP .= "</span>";

        $poussiere = "<a href='http://www.gw2spidy.com/item/" . $dust['data_id'] . "' target='_blank'>";
        $poussiere .= "<img src='" . $dust['img'] . "'/> ";
        $poussiere .= $columnODP;
        $poussiere .= "</a>";

        $other = "<tr><td>Bouteille de vin élonien ";
        $other .= "<a href='http://www.gw2spidy.com/item/" . $wine['data_id'] . "' target='_blank'>";
        $other .= "<img src='" . $wine['img'] . "' alt='" . $wine["name"] . "'/>";
        $other .= "</a>";
        $other .= "</td><td>";
        $other .= showPrice($wine['max_offer_unit_price']);
        $other .= "</td><td class='" . $class_wine . "' > ";
        $other .= $date_wine . " min";
        $other .= "</td></tr>";

        $other .= "<tr><td>Tas de poussière cristalline " . $poussiere . "</td><td>";
        $other .= showPrice($dust['max_offer_unit_price']);
        $other .= "</td><td class='" . $class_dust . "' > ";
        $other .= $date_dust . " min";
        $other .= "</td></tr>";
        ?>
        <div id="content">
            <table class="mytable" cellspacing="0" style="width: 100%">
                <thead>
                    <tr>
                        <td class="alignleft">Magnétite</td>
                        <td class="alignleft" colspan="2">Offre/Demande</td>
                        <td class="alignright">Achat noyau</td>
                        <td class="alignright">Vente</td>
                        <td class="alignright">Fabrication</td>
                        <td class="alignright">Prix d'annonce</td>
                        <td class="alignright">Taxe par vente</td>
                        <td class="alignright">Gain</td>
                        <td class="aligncenter">Dernière MAJ</td>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $table; ?>
                </tbody>
            </table>
            <iframe src="http://tpcalc.com/" width="500" height="350" class="flottant" style="border: none"></iframe>
            <table class="flottant mytable" cellspacing="0">
                <thead>
                    <tr>
                        <td>Composant</td>
                        <td>Prix d'achat</td>
                        <td>Dernière MAJ</td>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $other; ?>
                </tbody
            </table>
        </div>
    </body>
</html>
