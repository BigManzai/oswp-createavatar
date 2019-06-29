<!-- This file is used to markup the administration form of the widget. -->

<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Ab hier die Ausgabe im Control-Fenster des Adminbereiches -->

<?php
// Gettext einfügen
/* Make theme available for translation */
	load_plugin_textdomain( 'oswp-createavatar', false, basename( dirname( __FILE__ ) ) . '/lang' );
 ?>

<?php if (!isset($_POST['oskonfig'])): ?>

<!-- Start Abfrage Nutzer -->
<form class="w3-container" action="" method="post">
    <input type="hidden" name="oskonfig" value="1" />
		
<!-- OpenSim Einstellung --> 
<!-- $CONF_os_name, $CONF_db_server, $CONF_db_user, $CONF_db_pass, $CONF_db_database -->
	<div class="w3-row w3-section">


    <p><label for="base" class="w3-label control-label"><i class="fa fa-pencil" style="font-size:24px"></i><?php echo esc_html__( '  OpenSim Name:', 'oswp-createavatar' ) ; ?></b></label></p>
        <div class="w3-row">
            <p><input class="w3-input w3-border" type="text" placeholder="My OpenSim" name="CONF_os_name"/></p>
        </div>
    </div>
	
	<div class="w3-row w3-section">	
    <p><label for="base" class="w3-label control-label"><i class="fa fa-pencil" style="font-size:24px"></i><?php echo esc_html__( '  MySQL Server IP:', 'oswp-createavatar' ) ; ?></b></label></p>
        <div class="w3-row">
            <p><input class="w3-input w3-border" type="text" placeholder="127.0.0.1" name="CONF_db_server"/></p>
        </div>
    </div>
 
	<div class="w3-row w3-section">
    <p><label for="base" class="w3-label control-label"><i class="fa fa-pencil" style="font-size:24px"></i><?php echo esc_html__( '  MySQL Database:', 'oswp-createavatar' ) ; ?></b></label></p>
        <div class="w3-row">
            <p><input class="w3-input w3-border" type="text" placeholder="opensim" name="CONF_db_database"/></p>
        </div>
    </div>

 	<div class="w3-row w3-section">
    <p><label for="base" class="w3-label control-label"><i class="fa fa-pencil" style="font-size:24px"></i><?php echo esc_html__( '  MySQL User:', 'oswp-createavatar' ) ; ?></b></label></p>
        <div class="w3-row">
            <p><input class="w3-input w3-border" type="text" placeholder="opensim" name="CONF_db_user"/></p>
        </div>
    </div>
	
 	<div class="w3-row w3-section">
    <p><label for="base" class="w3-label control-label"><i class="fa fa-pencil" style="font-size:24px"></i><?php echo esc_html__( '  Password:', 'oswp-createavatar' ) ; ?></b></label></p>
        <div class="w3-row">
            <p><input class="w3-input w3-border" type="password" placeholder="password" name="CONF_db_pass"/></p>
        </div>
    </div>

<?php endif ?>

<?php
//print_r($_POST);
	if (isset($_POST['oskonfig']) AND $_POST['oskonfig'] == 1)
	{
		// wir schaffen unsere Variablen
		//$CONF_os_name, $CONF_db_server, $CONF_db_user, $CONF_db_pass, $CONF_db_database 
		$CONF_os_name  		= $_POST['CONF_os_name']; //variable name, string value use: %s
		$CONF_db_server  	= $_POST['CONF_db_server']; //server http or IP, string value use: %s
		$CONF_db_user  		= $_POST['CONF_db_user']; //database user name, string value use: %s
		$CONF_db_pass  		= $_POST['CONF_db_pass']; //database password, string value use: %s
		$CONF_db_database  	= $_POST['CONF_db_database']; //database name, string value use: %s
		
/* 		$CONF_os_SHAPE  	= $_POST['CONF_os_SHAPE']; //variable name, string value use: %s
		$CONF_os_SKIN  		= $_POST['CONF_os_SKIN']; //variable name, string value use: %s
		$CONF_os_HAIR  		= $_POST['CONF_os_HAIR']; //variable name, string value use: %s
		$CONF_os_EYES  		= $_POST['CONF_os_EYES']; //variable name, string value use: %s
		$CONF_os_SHIRT  	= $_POST['CONF_os_SHIRT']; //variable name, string value use: %s
		$CONF_os_PANTS  	= $_POST['CONF_os_PANTS']; //variable name, string value use: %s
		$CONF_os_USHIRT  	= $_POST['CONF_os_USHIRT']; //variable name, string value use: %s
		$CONF_os_UPANTS  	= $_POST['CONF_os_UPANTS']; //variable name, string value use: %s */
		
		global $wpdb;
		// Fehler anzeigen
		//$wpdb->show_errors();
		
		// Tabellen Name
		$tablename = $wpdb->prefix . "createavatar";
		
		//Tabelle erstellen
		$charset_collate = $wpdb->get_charset_collate();
		
		// Neue Tabelleneinträge eintragen NEU: os_id mediumint (9) NOT NULL,
		$sql = "CREATE TABLE $tablename (
		  os_id mediumint (9) NOT NULL,
		  CONF_os_name text NOT NULL,
		  CONF_db_server text NOT NULL,
		  CONF_db_user text NOT NULL,
		  CONF_db_pass text NOT NULL,
		  CONF_db_database text NOT NULL,
		  PRIMARY KEY  (os_id)
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		// NEU: Erst Tabellen löschen dann schreiben os_id nicht vergessen.
		$wpdb->delete( $tablename, array( 'os_id' => 0 ) );
		
		// Eigentliche Daten speichern
		$sql2 = $wpdb->prepare("INSERT INTO $tablename (CONF_os_name, CONF_db_server, CONF_db_user, CONF_db_pass, CONF_db_database) values (%s, %s, %s, %s, %s)", $CONF_os_name, $CONF_db_server, $CONF_db_user, $CONF_db_pass, $CONF_db_database);
		dbDelta( $sql2 );
	}
?>