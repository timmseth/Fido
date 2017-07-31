<?php
/*
+==============================+
|                              |
| ███████╗██╗██████╗  ██████╗  |
| ██╔════╝██║██╔══██╗██╔═══██╗ |
| █████╗  ██║██║  ██║██║   ██║ |
| ██╔══╝  ██║██║  ██║██║   ██║ |
| ██║     ██║██████╔╝╚██████╔╝ |
| ╚═╝     ╚═╝╚═════╝  ╚═════╝  |
+==============================+=========================================+
| Description: FiDo is a web app used to manage fiber optic resources.   |
| Author: Seth Timmons                                                   |
+==============================+=========================================+
| This file is part of FiDo.                                             |
|                                                                        |
| FiDo is free software: you can redistribute it and/or modify           |
| it under the terms of the GNU General Public License as published by   |
| the Free Software Foundation, either version 3 of the License, or      |
| (at your option) any later version.                                    |
|                                                                        |
| FiDo is distributed in the hope that it will be useful,                |
| but WITHOUT ANY WARRANTY; without even the implied warranty of         |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          |
| GNU General Public License for more details.                           |
|                                                                        |
| You should have received a copy of the GNU General Public License      |
| along with FiDo.  If not, see <http://www.gnu.org/licenses/>.          |
|                                                                        |
+========================================================================+
*/
?>
<!DOCTYPE html>
<html lang="en">

<?php 
//include snippet - shared head html
include('snippets/sharedHead.php');

//get totals buildings from database.
$totalBuildings=getTotalFromDatabase('buildings');

//get totals locations from database.
$totalLocations=getTotalFromDatabase('locations');

//get totals storageUnits from database.
$totalStorageUnits=getTotalFromDatabase('storageUnits');

//get totals cabinets from database.
$totalCabinets=getTotalFromDatabase('cabinets');

//get totals panels from database.
$totalPanels=getTotalFromDatabase('panels');

//get totals ports from database.
$totalPorts=getTotalFromDatabase('ports');

//get totals strands from database.
$totalStrands=getTotalFromDatabase('strands');

//get totals paths from database.
$totalPaths=getTotalFromDatabase('paths');

?>


<body>
<div id="wrapper">
    <!-- Navigation -->
    <?php
    $thisPage='tutorials';
    ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
        <?php include('snippets/sharedTopNav.php');?>
        <?php include('snippets/sharedSideNav.php');?>
    </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            
            <div class="container-fluid animated fadeIn">

        <?php include('snippets/sharedBreadcrumbs.php');?>


            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">FiDo Tutorials</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->






            <div class="row">
                <!-- Add Any Element -->
                <div class="col-xs-12">
                       
<!-- <a target="_blank" href="module_help.php" class="btn btn-default">Help. I don't know what to do.</a><br /><br /> -->


                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->

            <div class="row">
                <!-- Add Any Element -->
                <div class="col-xs-12">
                    <div class="jumbotron">
                        <h1>FiDo Tutorials</h1>
                        <p>Check out the interactive training modules below for easy step-by-step guides to common FiDo tasks.</p>
                        <div class="alert alert-danger">This page will take a while to fully load the tutorials below. Sorry about that. It's necessary.</div>


                    </div>
                </div>
            </div>
            <!-- /.row -->

<!-- START of all trainings -->
<div class="row">
<div class="col-xs-12">
      <div class="panel panel-default">
<div class="col-xs-12">
<h3>Manage Cabinet Contents</h3>
<hr>
</div>
    <div class="panel-group" id="accordionOne">
      <div class="panel panel-default">

        <div class="panel-heading">
          <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordionOne" href="#collapseA"><i class="fa fa-fw fa-chevron-right"></i> Finding a Cabinet</a>
          </h4>
        </div>
        <div id="collapseA" class="panel-collapse collapse">
          <div class="panel-body">
          <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/59498/Finding-a-Cabinet?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"> <p style="display: none;"> The first step is to navigate to&amp;nbsp;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;&lt;a href=&quot;http://fido.netel.isu.edu&quot; title=&quot;Link: http://fido.netel.isu.edu&quot;&gt;Fido&lt;/a&gt;&amp;nbsp;&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;and click &lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt; Browse Database.&lt;/b&gt;&lt;/i&gt;&lt;/span&gt; </p> <p style="display: none;"> Click on the&amp;nbsp;&lt;span class=&quot;&quot;&gt;&lt;b&gt;&lt;i&gt;Search Bo&lt;/i&gt;&lt;/b&gt;&lt;b&gt;&lt;i&gt;x&lt;/i&gt;&lt;/b&gt;&lt;i&gt;.&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Type the &lt;b&gt;&lt;i&gt;Building Name&lt;/i&gt;&lt;br&gt;&lt;/b&gt;in the &lt;b&gt;&lt;i&gt;Search Box&lt;/i&gt;&lt;/b&gt; to filter the list of buildings according to your search term. </p> <p style="display: none;"> Select the &lt;b&gt;&lt;i&gt;Drill Down&lt;/i&gt;&lt;/b&gt; button next to a &lt;b&gt;&lt;i&gt;Building&lt;/i&gt;&lt;/b&gt; to view all &lt;b&gt;&lt;i&gt;Locations &lt;/i&gt;&lt;/b&gt;within the &lt;b&gt;&lt;i&gt;Selected Building&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Select the&amp;nbsp;&lt;b&gt;&lt;i&gt;Drill Down&lt;/i&gt;&lt;/b&gt;&amp;nbsp;button next to a &lt;b&gt;&lt;i&gt;Location&lt;/i&gt;&lt;/b&gt; to view all&amp;nbsp;&lt;b&gt;&lt;i&gt;Storage Units&amp;nbsp;&lt;/i&gt;&lt;/b&gt;within the &lt;b&gt;&lt;i&gt;Selected&amp;nbsp;&lt;/i&gt;&lt;/b&gt;&lt;b&gt;&lt;i&gt;Location&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Select the&amp;nbsp;&lt;b&gt;&lt;i&gt;Drill Down&lt;/i&gt;&lt;/b&gt;&amp;nbsp;button next to a &lt;i&gt;&lt;b&gt;Storage Unit&lt;/b&gt;&lt;/i&gt;&amp;nbsp;to view all &lt;b&gt;&lt;i&gt;Cabinet&lt;/i&gt;&lt;/b&gt;&lt;b&gt;&lt;i&gt;s&amp;nbsp;&lt;/i&gt;&lt;/b&gt;within the&amp;nbsp;&lt;b&gt;&lt;i&gt;Selected Storage Unit&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Select the&amp;nbsp;&lt;b&gt;&lt;i&gt;Drill Down&lt;/i&gt;&lt;/b&gt;&amp;nbsp;button next to a&amp;nbsp;&lt;i&gt;&lt;b&gt;Cabinet&lt;/b&gt;&lt;/i&gt;&amp;nbsp;to view the contents of that &lt;b&gt;&lt;i&gt;Cabinet&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">That&apos;s it. You&apos;re done.&lt;br&gt;The &lt;b&gt;&lt;i&gt;Cabinet Contents&lt;/i&gt;&lt;/b&gt; are displayed and you can manage &lt;b&gt;&lt;i&gt;Panels&lt;/i&gt;&lt;/b&gt;, &lt;b&gt;&lt;i&gt;Ports&lt;/i&gt;&lt;/b&gt;, &lt;b&gt;&lt;i&gt;Jumpers&lt;/i&gt;&lt;/b&gt;, and &lt;b&gt;&lt;i&gt;Strands &lt;/i&gt;&lt;/b&gt;from this screen.</p></p>
          </div>
        </div>
      </div>


<!-- Add A Panel -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordionOne" href="#collapse6"><i class="fa fa-fw fa-chevron-right"></i> Add a Panel to a Cabinet</a>
      </h4>
    </div>
    <div id="collapse6" class="panel-collapse collapse">
      <div class="panel-body">
      <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/60342/Add-A-Panel?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to open &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Fido | Fiber App.&lt;/b&gt;&lt;/i&gt;&lt;/span&gt; and click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;FiDo v2.0&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;             Guided C.R.U.D.&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Get Started!&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select Building...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;000 - Test Building&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select level...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Level 0 (lowest possible)&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Location...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Test Room&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Storage Unit...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;000-00-01&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Cabinet...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;001&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Port Capacity&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Type **** in &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Port Capacity&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Submit&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Success!  1 Panel Created.&times;&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">That&apos;s it. You&apos;re done.</p></p>
      </div>
    </div>
  </div>

<!-- Add A Port -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordionOne" href="#collapse7"><i class="fa fa-fw fa-chevron-right"></i> Add Ports to a Panel</a>
      </h4>
    </div>
    <div id="collapse7" class="panel-collapse collapse">
      <div class="panel-body">Ports are added automatically when you create a panel. Refer to the <b><i>Add a Panel</i></b> tutorial.</div>
    </div>
  </div>

<!-- Add A Strand To A Port -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordionOne" href="#collapse8"><i class="fa fa-fw fa-chevron-right"></i> Add a Strand to a Port</a>
      </h4>
    </div>
    <div id="collapse8" class="panel-collapse collapse">
      <div class="panel-body">
      <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/60523/Add-a-Strand?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to &lt;b&gt;&lt;i&gt;Find a Cabinet&lt;/i&gt;&lt;/b&gt;&amp;nbsp;(see tutorial) and click on the &quot;&lt;b&gt;&lt;i&gt;+&lt;/i&gt;&lt;/b&gt;&quot; icon on the port where you want to &lt;b&gt;&lt;i&gt;Add a Strand&lt;/i&gt;&lt;/b&gt;!</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Building...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the&amp;nbsp;&lt;b&gt;&lt;i&gt;Destination Building&lt;/i&gt;&lt;/b&gt;&amp;nbsp;to which the&amp;nbsp;&lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt;&amp;nbsp;will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select Level...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">&lt;div&gt;Select the &lt;b&gt;&lt;i&gt;Destination Level&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt; will be connecting.&lt;br&gt;&lt;/div&gt;</p><p style="display: none;">&lt;span&gt;&lt;i&gt;&lt;b&gt;Click on &quot;Select a&amp;nbsp;&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;&lt;i&gt;&lt;b&gt;location&lt;/b&gt;&lt;/i&gt;&lt;span&gt;&lt;i&gt;&lt;b&gt;...&quot;.&lt;br&gt;&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Destination Location&amp;nbsp;&lt;/i&gt;&lt;/b&gt;to which the &lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;Select Storage Unit...&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;Destination Storage Unit&lt;/b&gt; to which the &lt;b&gt;New Strand&lt;/b&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Cabinet...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Destination Cabinet&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Panel...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Destination Panel&lt;/i&gt;&lt;/b&gt; to which the &lt;i&gt;&lt;b&gt;New Strand&lt;/b&gt;&lt;/i&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select Port...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Destination Port&lt;/i&gt;&lt;/b&gt; to which the &lt;i&gt;&lt;b&gt;New Strand&lt;/b&gt;&lt;/i&gt; will be connecting.</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Length (ft)&quot;.&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Enter the &lt;b&gt;&lt;i&gt;Strand Length&lt;/i&gt;&lt;/b&gt; (in feet) in this box.</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;Select a Mode...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the&amp;nbsp;&lt;span class=&quot;&quot;&gt;&lt;b&gt;&lt;i&gt;Mode&lt;/i&gt;&lt;/b&gt; of the &lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt;.&lt;/span&gt;</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;b&gt;&lt;i&gt;Select a core size...&lt;/i&gt;&lt;/b&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Core Size&amp;nbsp;&lt;/i&gt;&lt;/b&gt;&lt;span&gt;of the&amp;nbsp;&lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt;.&lt;/span&gt;</p><p style="display: none;">Enter the number of&amp;nbsp;&lt;b&gt;&lt;i&gt;Splices&lt;/i&gt;&lt;/b&gt;&amp;nbsp;the &lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt;&amp;nbsp;has.</p><p style="display: none;">Enter the number of &lt;b&gt;&lt;i&gt;Connector Pairs&amp;nbsp;&lt;/i&gt;&lt;/b&gt;the&amp;nbsp;&lt;b&gt;&lt;i&gt;New Strand&lt;/i&gt;&lt;/b&gt;&amp;nbsp;has.</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Create Strand&lt;/b&gt;&lt;/i&gt;&lt;b&gt;&quot;.&lt;/b&gt;&lt;/span&gt;</p><p style="display: none;">That&apos;s it. You&apos;re done.&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;You should see a &quot;Success!&quot; notification at the top of the page.&lt;/div&gt;</p></p>
      </div>
    </div>
  </div>

<!-- Add A Jumper To A Port -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordionOne" href="#collapse9"><i class="fa fa-fw fa-chevron-right"></i> Add a Jumper to a Port</a>
      </h4>
    </div>
    <div id="collapse9" class="panel-collapse collapse">
      <div class="panel-body">
      <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/60529/Add-a-Jumper?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to&amp;nbsp;&lt;b&gt;&lt;i&gt;Find a Cabinet&lt;/i&gt;&lt;/b&gt;&amp;nbsp;(see tutorial) and click on the&amp;nbsp;&lt;i&gt;&lt;b&gt;&quot;&lt;/b&gt;&lt;/i&gt;&lt;i&gt;&lt;b&gt;+&lt;/b&gt;&lt;/i&gt;&lt;i&gt;&lt;b&gt;&quot; icon on the port&lt;/b&gt;&lt;/i&gt;&amp;nbsp;where you want to&amp;nbsp;&lt;b&gt;&lt;i&gt;Add a Jumper&lt;/i&gt;&lt;/b&gt;!</p><p style="display: none;">Select what the &lt;b&gt;&lt;i&gt;New Jumper&lt;/i&gt;&lt;/b&gt; is meant to connect.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Building...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Destination Building&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Jumper&lt;/i&gt;&lt;/b&gt;&amp;nbsp;will be connecting.</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;b&gt;Select Level...&lt;/b&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Level&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Jumper&lt;/i&gt;&lt;/b&gt; will connect.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Location...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Location&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Jumper&lt;/i&gt;&lt;/b&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;Select Storage Unit...&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Storage Unit&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Jumper&lt;/i&gt;&lt;/b&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Cabinet...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;Cabinet&lt;/b&gt; to which the &lt;b&gt;New Jumper&lt;/b&gt; will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select Panel...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Panel &lt;/i&gt;&lt;/b&gt;to which the &lt;b&gt;&lt;i&gt;New Jumper&amp;nbsp;&lt;/i&gt;&lt;/b&gt;will be connecting.</p><p style="display: none;">Click on &quot;&lt;b&gt;&lt;i&gt;Select a Port...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Port&lt;/i&gt;&lt;/b&gt; to which the &lt;b&gt;&lt;i&gt;New Jumper&lt;/i&gt;&lt;/b&gt; will be connecting.</p><p style="display: none;">Click&amp;nbsp;&lt;span class=&quot;&quot;&gt;&lt;b&gt;Create Jumper&lt;/b&gt;.&lt;/span&gt;</p><p style="display: none;">That&apos;s it. You&apos;re done.&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;You should see a &quot;&lt;b&gt;&lt;i&gt;Success&lt;/i&gt;&lt;/b&gt;!&quot; notification at the top of the page.&lt;/div&gt;</p></p>
      </div>
    </div>
  </div>



    </div>
</div>
</div>



<div class="col-xs-12">
      <div class="panel panel-default">
<div class="col-xs-12">
<h3>Add Elements</h3>
<hr>
</div>

<div class="panel-group" id="accordion">
<!-- Add A Building -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2"><i class="fa fa-fw fa-chevron-right"></i> Add a Building</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">
      <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/59811/Add-a-Building?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to navigate to&amp;nbsp;&lt;span&gt;&lt;i&gt;&lt;b&gt;&lt;a target=&quot;_blank&quot; rel=&quot;nofollow&quot; href=&quot;http://fido.netel.isu.edu/&quot;&gt;Fido&lt;/a&gt;&amp;nbsp;&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;and click&amp;nbsp;&lt;span&gt;&lt;i&gt;&lt;b&gt;Get Started!&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;+&amp;nbsp;&lt;/i&gt;&lt;/b&gt;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Building&quot;.&lt;br&gt;&lt;/b&gt;&lt;/i&gt;(Add Building).&lt;/span&gt;</p><p style="display: none;">Click the&amp;nbsp;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Name&lt;/b&gt;&lt;/i&gt; field on the form.&lt;/span&gt;</p><p style="display: none;">Type in the &lt;b&gt;&lt;i&gt;Building Name.&lt;/i&gt;&lt;/b&gt;</p><p style="display: none;">Click the&amp;nbsp;&lt;span&gt;&lt;i&gt;&lt;b&gt;Number&lt;/b&gt;&lt;/i&gt;&amp;nbsp;field on the form.&lt;/span&gt;</p><p style="display: none;">Type in the&amp;nbsp;&lt;b&gt;&lt;i&gt;Building Number.&lt;/i&gt;&lt;/b&gt;</p><p style="display: none;">Click the&amp;nbsp;&lt;span&gt;&lt;i&gt;&lt;b&gt;Level&lt;/b&gt;&lt;/i&gt;&amp;nbsp;field on the form.&lt;/span&gt;</p><p style="display: none;">Type in the number of &lt;b&gt;&lt;i&gt;Levels &lt;/i&gt;&lt;/b&gt;in this &lt;b&gt;&lt;i&gt;Building&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Click the&amp;nbsp;&lt;span&gt;&lt;i&gt;&lt;b&gt;Notes&lt;/b&gt;&lt;/i&gt;&amp;nbsp;field on the form.&lt;/span&gt;</p><p style="display: none;">Type any relevant information into the&amp;nbsp;&lt;b&gt;&lt;i&gt;Building Notes&lt;/i&gt;&lt;/b&gt; section.</p><p style="display: none;">Click &lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Submit.&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p></p>
      </div>
    </div>
  </div>

<!-- Add A Location -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3"><i class="fa fa-fw fa-chevron-right"></i> Add a Location</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">
      <p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/60293/Add-a-Location?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to navigate to Fido and click &lt;b&gt;&lt;i&gt;Get Started&lt;/i&gt;&lt;/b&gt;!</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;+&amp;nbsp;&lt;/i&gt;&lt;/b&gt;&lt;span class=&quot;&quot;&gt;&lt;b&gt;&lt;i&gt;Location&lt;/i&gt;&lt;/b&gt;&quot;.&lt;br&gt;(&lt;b&gt;&lt;i&gt;Add Location&lt;/i&gt;&lt;/b&gt;).&lt;/span&gt;</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;b&gt;&lt;i&gt;Select Building...&lt;/i&gt;&lt;/b&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Select the building where you want to create the new&amp;nbsp;&lt;b&gt;&lt;i&gt;Location&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Click &quot;&lt;span&gt;&lt;b&gt;&lt;i&gt;Select Level...&lt;/i&gt;&lt;/b&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Select the level / floor where you want to create the &lt;b&gt;New Location&lt;/b&gt;. &lt;br&gt;&lt;br&gt;In this case we will select &quot;&lt;span class=&quot;&quot;&gt;&lt;b&gt;&lt;i&gt;Level 0 (lowest possible)&lt;/i&gt;&lt;/b&gt;&lt;i&gt;&lt;b&gt;&quot;&lt;/b&gt;&lt;/i&gt;&lt;b&gt;.&lt;/b&gt;&lt;/span&gt;</p><p style="display: none;">Click on &lt;b&gt;&lt;i&gt;Description&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Fill in the &lt;b&gt;&lt;i&gt;Description&lt;/i&gt;&lt;/b&gt; field.&lt;br&gt;&lt;br&gt;Be as specific as possible. Some good options are:&lt;br&gt;&lt;b&gt;&lt;i&gt;Room Names&lt;/i&gt;&lt;/b&gt;.&lt;br&gt;&lt;b&gt;&lt;i&gt;Room Numbers&lt;/i&gt;&lt;/b&gt;.&lt;br&gt;&lt;b&gt;&lt;i&gt;Hallway + Nearest Room&lt;/i&gt;&lt;/b&gt;.&lt;br&gt;&lt;b&gt;&lt;i&gt;Cardinal Directions&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Submit&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">That&apos;s it. You&apos;re done.&lt;br&gt;&lt;br&gt;You should see a &lt;i&gt;&quot;&lt;/i&gt;&lt;span&gt;&lt;i&gt;&lt;b&gt;Success!&lt;/b&gt;&lt;/i&gt;&quot;&lt;i&gt;&lt;b&gt;&amp;nbsp;&lt;/b&gt;&lt;/i&gt;notification at the top of the page.&lt;/span&gt;</p></p>
      </div>
    </div>
  </div>

<!-- Add A Storage Unit -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse4"><i class="fa fa-fw fa-chevron-right"></i> Add a Storage Unit</a>
      </h4>
    </div>
    <div id="collapse4" class="panel-collapse collapse">
      <div class="panel-body"><p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/60304/Add-a-Storage-Unit?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to navigate to&amp;nbsp;&lt;i&gt;&lt;b&gt;Fido&lt;/b&gt;&lt;/i&gt;&amp;nbsp;and click&amp;nbsp;&lt;b&gt;&lt;i&gt;Get Started&lt;/i&gt;&lt;/b&gt;!</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;+ Storage Unit&lt;/i&gt;&lt;/b&gt;&quot;.&lt;div&gt;(&lt;b&gt;&lt;i&gt;Add Storage Unit&lt;/i&gt;&lt;/b&gt;).&lt;/div&gt;</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;Select Building...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the building where you want to create the new&lt;b&gt; Storage Unit&lt;/b&gt;.</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Select level...&lt;/b&gt;&lt;/i&gt;&lt;b&gt;&quot;&lt;/b&gt;.&lt;/span&gt;</p><p style="display: none;">Select the level / floor where you want to create the &lt;b&gt;&lt;i&gt;New Location&lt;/i&gt;&lt;/b&gt;.&amp;nbsp;&lt;div&gt;&lt;b&gt;&lt;br&gt;&lt;/b&gt;&lt;/div&gt;&lt;div&gt;In this case we will select &lt;b&gt;&quot;&lt;i&gt;Level 0 (lowest possible)&lt;/i&gt;&quot;&lt;/b&gt;.&lt;/div&gt;</p><p style="display: none;">Click &lt;i&gt;&quot;&lt;/i&gt;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Location...&lt;/b&gt;&lt;/i&gt;&quot;&lt;i&gt;.&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select the location where you want to create the New Storage Unit.&amp;nbsp;&lt;div&gt;&lt;br&gt;&lt;/div&gt;In this case we will select &quot;&lt;span&gt;&lt;i&gt;&lt;b&gt;Test Location 2&lt;/b&gt;&lt;/i&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Storage Unit Type...&lt;/b&gt;&lt;/i&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Select the &lt;b&gt;&lt;i&gt;Storage Unit Type&lt;/i&gt;&lt;/b&gt;&amp;nbsp;that this new Storage Unit will be categorized.&lt;br&gt;&lt;br&gt;In this case we will select &quot;&lt;b&gt;&lt;i&gt;Rack&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Click on &quot;&lt;b&gt;Storage Unit Label&lt;/b&gt;&quot;.</p><p style="display: none;">Fill out &quot;&lt;b&gt;&lt;i&gt;Storage Unit Label&lt;/i&gt;&lt;/b&gt;&quot; according to the provided rules as&amp;nbsp;&lt;b&gt;&lt;i&gt;&quot;xx-yy-zz&quot;&lt;/i&gt;&lt;/b&gt;.&lt;br&gt;&lt;br&gt;&lt;b&gt;xxx=&lt;/b&gt;&amp;nbsp;3 Digit Building Number.&lt;br&gt;&lt;b&gt;yy&lt;/b&gt;&amp;nbsp;= 2 Digit Floor.&lt;br&gt;&lt;b&gt;zz =&lt;/b&gt;&amp;nbsp;Arbitrary 1,2,3...etc.</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Submit&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">That&apos;s it. You&apos;re done.&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;You should see a &quot;&lt;b&gt;&lt;i&gt;Success!&lt;/i&gt;&lt;/b&gt;&quot; notification at the top of the page.&lt;/div&gt;</p></p></div>
    </div>
  </div>

<!-- Add A Cabinet -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse5"><i class="fa fa-fw fa-chevron-right"></i> Add a Cabinet</a>
      </h4>
    </div>
    <div id="collapse5" class="panel-collapse collapse">
      <div class="panel-body"><p style="border: 2px solid #ebebeb; min-width: 100%; border-bottom: 0 none; height: 501px;"><iframe style="border: 0 none; min-width: 100%" src="https://www.iorad.com/player/60330/Add-A-Cabinet?src=iframe" width="100%" height="500px" allowfullscreen="true"></iframe></p><p style="display: none;"><p style="display: none;">The first step is to navigate to &lt;b&gt;&lt;i&gt;Fido &lt;/i&gt;&lt;/b&gt;and click &lt;b&gt;&lt;i&gt;Get Started&lt;/i&gt;&lt;/b&gt;!</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;+ Location&lt;/i&gt;&lt;/b&gt;&quot;.&lt;div&gt;(&lt;b&gt;&lt;i&gt;Add Location&lt;/i&gt;&lt;/b&gt;).&lt;/div&gt;</p><p style="display: none;">Click &quot;&lt;b&gt;&lt;i&gt;Select Building...&lt;/i&gt;&lt;/b&gt;&quot;.</p><p style="display: none;">Select the building where you want to create the new &lt;b&gt;&lt;i&gt;Cabinet&lt;/i&gt;&lt;/b&gt;.</p><p style="display: none;">Click &quot;&lt;span class=&quot;&quot;&gt;&lt;i&gt;&lt;b&gt;Select Level...&lt;/b&gt;&lt;/i&gt;&quot;.&lt;/span&gt;</p><p style="display: none;">Select the level / floor where you want to create the &lt;b&gt;&lt;i&gt;New Location&lt;/i&gt;&lt;/b&gt;.&amp;nbsp;&lt;div&gt;&lt;br&gt;&lt;/div&gt;&lt;div&gt;In this case we will select &quot;&lt;b&gt;&lt;i&gt;Level 0 (lowest possible)&lt;/i&gt;&lt;/b&gt;&quot;.&lt;/div&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Location...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Test Location 2&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Select a Storage Unit...&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Select &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;975-00-01&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;highlight&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Type **** in &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;0&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;highlight&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Type **** in &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;12&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;highlight&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Type **** in &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;T&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Submit&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">Click &lt;span class=&quot;component&quot;&gt;&lt;i&gt;&lt;b&gt;Success!  1 Row Created.&times;&lt;/b&gt;&lt;/i&gt;&lt;/span&gt;</p><p style="display: none;">That&apos;s it. You&apos;re done.</p></p></div>
    </div>
  </div>


</div>
</div>

</div>
</div>
<!-- END of all trainings -->

     </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../bower_components/raphael/raphael-min.js"></script>

    <script type="text/javascript">
        $('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".fa-chevron-right").removeClass("fa-chevron-right").addClass("fa-chevron-down");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".fa-chevron-down").removeClass("fa-chevron-down").addClass("fa-chevron-right");
});
    </script>

</body>

</html>
