<?php

namespace Gsquad\PiafBundle\Service;

class GsquadGetDepartements
{
    public function getDepartementsArray()
    {
        $depts = array();
        $depts["Non précisé"] = false;
        $depts["Ain"] = "Ain";
        $depts["Aisne"] = "Aisne";
        $depts["Allier"] = "Allier";
        $depts["Alpes de Haute Provence"] = "Alpes de Haute Provence";
        $depts["Hautes Alpes"] = "Hautes Alpes";
        $depts["Alpes Maritimes"] = "Alpes Maritimes";
        $depts["Ardèche"] = "Ardèche";
        $depts["Ardennes"] = "Ardennes";
        $depts["Ariège"] = "Ariège";
        $depts["Aube"] = "Aube";
        $depts["Aude"] = "Aude";
        $depts["Aveyron"] = "Aveyron";
        $depts["Bouches du Rhône"] = "Bouches du Rhône";
        $depts["Calvados"] = "Calvados";
        $depts["Cantal"] = "Cantal";
        $depts["Charente"] = "Charente";
        $depts["Charente Maritime"] = "Charente Maritime";
        $depts["Cher"] = "Cher";
        $depts["Corrèze"] = "Corrèze";
        $depts["Corse du Sud"] = "Corse du Sud";
        $depts["Haute Corse"] = "Haute Corse";
        $depts["Côte d'Or"] = "Côte d'Or";
        $depts["Côtes d'Armor"] = "Côtes d'Armor";
        $depts["Creuse"] = "Creuse";
        $depts["Dordogne"] = "Dordogne";
        $depts["Doubs"] = "Doubs";
        $depts["Drôme"] = "Drôme";
        $depts["Eure"] = "Eure";
        $depts["Eure et Loir"] = "Eure et Loir";
        $depts["Finistère"] = "Finistère";
        $depts["Gard"] = "Gard";
        $depts["Haute Garonne"] = "Haute Garonne";
        $depts["Gers"] = "Gers";
        $depts["Gironde"] = "Gironde";
        $depts["Hérault"] = "Hérault";
        $depts["Ille et Vilaine"] = "Ille et Vilaine";
        $depts["Indre"] = "Indre";
        $depts["Indre et Loire"] = "Indre et Loire";
        $depts["Isère"] = "Isère";
        $depts["Jura"] = "Jura";
        $depts["Landes"] = "Landes";
        $depts["Loir et Cher"] = "Loir et Cher";
        $depts["Loire"] = "Loire";
        $depts["Haute Loire"] = "Haute Loire";
        $depts["Loire Atlantique"] = "Loire Atlantique";
        $depts["Loiret"] = "Loiret";
        $depts["Lot"] = "Lot";
        $depts["Lot et Garonne"] = "Lot et Garonne";
        $depts["Lozère"] = "Lozère";
        $depts["Maine et Loire"] = "Maine et Loire";
        $depts["Manche"] = "Manche";
        $depts["Marne"] = "Marne";
        $depts["Haute Marne"] = "Haute Marne";
        $depts["Mayenne"] = "Mayenne";
        $depts["Meurthe et Moselle"] = "Meurthe et Moselle";
        $depts["Meuse"] = "Meuse";
        $depts["Morbihan"] = "Morbihan";
        $depts["Moselle"] = "Moselle";
        $depts["Nièvre"] = "Nièvre";
        $depts["Nord"] = "Nord";
        $depts["Oise"] = "Oise";
        $depts["Orne"] = "Orne";
        $depts["Pas de Calais"] = "Pas de Calais";
        $depts["Puy de Dôme"] = "Puy de Dôme";
        $depts["Pyrénées Atlantiques"] = "Pyrénées Atlantiques";
        $depts["Hautes Pyrénées"] = "Hautes Pyrénées";
        $depts["Pyrénées Orientales"] = "Pyrénées Orientales";
        $depts["Bas Rhin"] = "Bas Rhin";
        $depts["Haut Rhin"] = "Haut Rhin";
        $depts["Rhône"] = "Rhône";
        $depts["Haute Saône"] = "Haute Saône";
        $depts["Saône et Loire"] = "Saône et Loire";
        $depts["Sarthe"] = "Sarthe";
        $depts["Savoie"] = "Savoie";
        $depts["Haute Savoie"] = "Haute Savoie";
        $depts["Paris"] = "Paris";
        $depts["Seine Maritime"] = "Seine Maritime";
        $depts["Seine et Marne"] = "Seine et Marne";
        $depts["Yvelines"] = "Yvelines";
        $depts["Deux Sèvres"] = "Deux Sèvres";
        $depts["Somme"] = "Somme";
        $depts["Tarn"] = "Tarn";
        $depts["Tarn et Garonne"] = "Tarn et Garonne";
        $depts["Var"] = "Var";
        $depts["Vaucluse"] = "Vaucluse";
        $depts["Vendée"] = "Vendée";
        $depts["Vienne"] = "Vienne";
        $depts["Haute Vienne"] = "Haute Vienne";
        $depts["Vosges"] = "Vosges";
        $depts["Yonne"] = "Yonne";
        $depts["Territoire de Belfort"] = "Territoire de Belfort";
        $depts["Essonne"] = "Essonne";
        $depts["Hauts de Seine"] = "Hauts de Seine";
        $depts["Seine St Denis"] = "Seine St Denis";
        $depts["Val de Marne"] = "Val de Marne";
        $depts["Val d'Oise"] = "Val d'Oise";
        $depts["DOM"] = "DOM";

        asort($depts);

        return $depts;
    }
}