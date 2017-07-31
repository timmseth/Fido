<!DOCTYPE html>
<html lang="en">

<?php include('snippets/sharedHead.php');?>

<body>
<div id="wrapper">
    <!-- Navigation -->
    <?php
    $thisPage='mapping';
    ?>
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">   
        <?php include('snippets/sharedTopNav.php');?>
        <?php include('snippets/sharedSideNav.php');?>
    </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid animated fadeIn">
                 <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Maps &amp; Paths<small> (Document Paths.)</small></h1>
                    </div>
                  



                    <!-- /.col-lg-12 -->
                    <div class="col-lg-12">
                        <!-- Thumbnail 1 -->
                            <h2>Browse Building Maps</h2>
                            <p>Select a campus below to open the relevant map directory in a new tab.</p>
                            <p> 
                            <a target="_blank" class="btn btn-primary btn-lg" href="../maps/Pocatello Campus" role="button">Pocatello</a>

                            <a target="_blank" class="btn btn-primary btn-lg" href="../maps/Idaho Falls Campus" role="button">Idaho Falls</a>

                            <a target="_blank" class="btn btn-primary btn-lg" href="../maps/Meridian Campus" role="button">Meridian</a>
                            </p>
                    </div>


<hr>

                    <!-- 
                    <div class="col-lg-12">

                        <div class="col-lg-6">
                                <h2>Intra-building Maps</h2>
                                <p><a class="btn btn-primary btn-lg btn-block" href="./module_mapping_interior.php" role="button">Work With <b>Interior Maps</b>.</a></p>
                                <p>Connections within each level of each building.
                                <br />One level at a time.
                                <br />Lots of little details here, but they do come in handy.</p>
                        </div>

                    
                        <div class="col-lg-6">
                                <h2>Inter-building Maps</h2>
                                <p><a class="btn btn-primary btn-lg btn-block" href="./module_mapping_exterior.php" role="button">Work With <b>Exterior Maps</b>.</a></p>
                                <p>Connections outside and between all the buildings.
                                <br />Think "top-down" or "campus-wide".
                                <br />Uses the "Google Maps" API.</p>
                        </div>

                    </div>-->


                </div>
                <!-- /.row -->
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

</body>

</html>
