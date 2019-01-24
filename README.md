# oswp-createavatar
oswp-createavatar ist ein Wordpress Plugin-Widget welches ein neuen Avatar in der OpenSim Datenbank anlegt.

Das Plugin ist noch nicht fertig.

oswp was bedeutet das? OpenSimulator Wordpress um es kurz zu sagen.

Diese Plugin Reihe ist dafür gedacht den OpenSimulator mit Wordpress zu kombinieren, zu erweitern oder zu ergänzen.

Die Konfiguration muss in die Datei oscreateavatar-widget.php eingetragen werden.

Dort folgende Datenbank Einträge laut eurer OpenSim Konfiguration anpassen:

      /* MySQL Database */
      $CONF_db_server   = "localhost";		     //Your Database-Server
      $CONF_db_user  = "databaseuser";       	             // login
      $CONF_db_pass    = "password";     	     // password
      $CONF_db_database   = "opensimdatabasename"; // Name of BDD

TODO: Einstellungen über Wordpress funktionieren nicht.
