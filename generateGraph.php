<?php
require_once ('jpgraph-4.2.0/src/jpgraph.php');
require_once ('jpgraph-4.2.0/src/jpgraph_line.php');
require_once ('database.php');

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "coffeeAdministration";
$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmtForBudgets = "SELECT Istguthaben, Sollguthaben, Datum FROM ( SELECT * FROM Gesamtguthaben ORDER BY Gesamtguthaben.ID DESC limit 6 ) GG ORDER BY GG.ID ASC";
$getBudgetStmt = $DB_con->prepare($stmtForBudgets);
$getBudgetStmt->execute();
$userRowBudget = $getBudgetStmt->fetchAll(PDO::FETCH_ASSOC);

$shouldAmounts = [];
$realAmounts = [];
$dates = [];

for ($i = 0; $i < count($userRowBudget); $i++) {
    for ($j = 0; $j < count($userRowBudget[$i]); $j++) {

        $shouldAmounts[$i] = $userRowBudget[$i]['Sollguthaben'];
        $realAmounts[$i] = $userRowBudget[$i]['Istguthaben'];

        $time = strtotime($userRowBudget[$i]['Datum']);
        $dates[$i] = date('d.m',$time);
    }

}

$graph = new Graph(300,250,"auto");
$graph->SetScale("textlin");
$graph->title->Set('Gesamtguthaben aller Konsumenten');

$lineplot=new LinePlot($shouldAmounts);
$lineplot2=new LinePlot($realAmounts);

$graph->Add($lineplot);
$graph->Add($lineplot2);

$graph->xaxis->title->Set("Zeit");
$graph->yaxis->title->Set("Gesamtguthaben");
$graph->img->SetMargin(40,20,20,40);
$lineplot->SetLegend("Sollguthaben");
$lineplot2->SetLegend("Istguthaben");
//$lineplot->value->Show();
//$lineplot2->value->Show();
$graph->xaxis->SetTickLabels($dates);
$graph->legend->SetPos(0.41,0.9999,'center','bottom');





$graph->Stroke();


/*
$theme_class=new UniversalTheme();

$graph->SetTheme($theme_class);
$graph->img->SetAntiAliasing(false);
$graph->title->Set('');
$graph->SetBox(false);

$graph->img->SetAntiAliasing();

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle("solid");
$graph->xaxis->SetTickLabels(array('A','B','C','D'));
$graph->xgrid->SetColor('#E3E3E3');

// Create the first line
$p1 = new LinePlot($userRowRealAmount);
$graph->Add($p1);
$p1->SetColor("#6495ED");
$p1->SetLegend('Line 1');

// Create the second line
$p2 = new LinePlot($datay2);
$graph->Add($p2);
$p2->SetColor("#B22222");
$p2->SetLegend('Line 2');

// Create the third line
$p3 = new LinePlot($datay3);
$graph->Add($p3);
$p3->SetColor("#FF1493");
$p3->SetLegend('Line 3');

$graph->legend->SetFrameWeight(1);
$graph->Stroke();
// Output line
//$fileName = "graph.png";
//$graph->img->Stream($fileName);

//print '<img src="'. $fileName .'" />';

*/