<?php

//|=========================================|
//|  INTRODUCTION                           |
//|=========================================|
/*
    This config file is what makes FIDO work for your environment.
    It is automatically loaded in every page of the project.
    It is required and FIDO will NOT load if it is not available.

    Customize and manage the settings in this file based on your specific environment. As your environment changes, this file will hopefully allow FIDO to change with it.

    Ideally you will be able to change things (such as database credentials or a path to a specific resource) one time in this file rather than on every page of the project.

    System administrators MUST SECURE THIS FILE! Database credentials are in plain text and as such are only as secure as this file.

    See the readme (readme.php) for more information.
*/

//|=========================================|
//|  Configuration File Setup               |
//|=========================================|
/*
    In order for this file to do it's job, three things must happen:
    1. Move this file to a secure area and make sure it's permissions are locked down tight. 

    2. Find the FIDO/pages/configPointer.php file. Edit the variable "$path_to_config_file" should reference the location of "config.php" (This File.)

    3. Fill out the remainder of this file correctly.
*/

//|=========================================|
//|  Data Tables Setup                      |
//|=========================================|
/*
    Each page has individual control of it's data tables via an inline script.
    The DataTables jquery plugin is being used to handle tables in FIDO. 
    Check out datatables.net for documentation and customization options.
*/



//+=========================================+
//|  Database Credentials                   |
//+=========================================+

    //Error reporting.
    ini_set("error_reporting", "true");
    error_reporting(E_ALL & ~E_NOTICE);
    //error_reporting(E_ALL|E_STRCT);

    //DB Credentials
    $dbHost = "your_db_host";
    $dbName = "your_db_name";
    $dbUser = "your_db_user";
    $dbPassword = "your_db_password";

    //web directory
    $pathToWebDir='/var/www/html/projects/FiDo';

//+=========================================================+
//|  Configuration Array                                    |
//|  AKA:"The Holy Handgrenade of Configuration"            |
//|  An array with a bunch of your configuration settings   |
//|  in one variable. Try it in your next project.          |
//|  It works really well.                                  |
//+=========================================================+
    
    $config = array(
    //+==========================================+
    //|  SECTION 1 - Admin Contact Information   |
    //+==========================================+
        "administrator" => array(
            "name" => "admin_name",
            "phone" => "admin_number",
            "email" => "admin_email"
        ),
    //+==========================================+
    //|  SECTION 2 - Project Header Settings     |
    //+==========================================+
    //PROJECT HEADER - (and window title)
        "header" => array(
            "windowTitle" => "Fido | Fiber App.",
            "projectName" => "FiDo",
            "projectVersion" => "2.0",
            "projectTagline" => "<span class='linkColor'>Fi</span>ber <span class='linkColor'>Do</span>cumentation."
        ),
    //+==========================================+
    //|  SECTION 3 - Project Footer Settings     |
    //+==========================================+
        "footer" => array(
            "copyDate" => "&copy; ".date("Y"),
            "content" => " - org_name."
        ),
    //+==========================================+
    //|  SECTION 4 - StorageUnit Types           |
    //+==========================================+
        "storageUnitTypes" => array(
            "0" => "wallmount",
            "1" => "rack"
        ),
    //+==========================================+
    //|  SECTION 4.1 - Panel Types           |
    //+==========================================+
        "panelTypes" => array(
            "0" => "st",
            "1" => "sc",
            "2" => "lc",
            "3" => "mtrj",
            "default" => "st"
        ),
    //+==========================================+
    //|  SECTION 4.2 - Panel Sizes           |
    //+==========================================+
        "panelSizes" => array(
            "0" => "4",
            "1" => "6",
            "2" => "12"
        ),
    //+==========================================+
    //|  SECTION 5 - Strand Options           |
    //+==========================================+
        "strandOptions" => array(
            "mode" => array(
                "0" => "singlemode",
                "1" => "multimode"
            ),
            "wavelength" => array(
                "singlemode" => array(
                    "0" => "1310",  //0.4 loss per km
                    "1" => "1365",  //0.4 loss per km
                    "2" => "1550",  //0.3 loss per km
                    "3" => "1625"   //0.3 loss per km
                ),
                "multimode" => array(
                    "0" => "850",   //core size dependent
                    "1" => "1300"   //core size dependent
                )
            ),
            "coresize" => array(
                "singlemode" => array(
                    "0" => "8.3",  //dependent on wavelength
                ),
                "multimode" => array(
                    "0" => "50",    //3.0 loss per km
                    "1" => "62.5"   //3.4 loss per km
                )
            )
        ),
    //+==========================================+
    //|  SECTION 6 - Default Options           |
    //+==========================================+
        "defaultOptions" => array(
            "singlemodeWavelength" => '1550',
            "singlemodeCoreSize" => '8.3',
            "multimodeWavelength" => '1300',
            "multimodeCoreSize" => '50'
        )
    );







?>
