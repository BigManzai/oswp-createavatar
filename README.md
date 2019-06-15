# oswp-createavatar
Das Plugin ist noch nicht fertig, es funktioniert aber schon.

Oswp was bedeutet das? OpenSimulator Wordpress um es kurz zu sagen.

Diese Plugin Reihe ist dafür gedacht den OpenSimulator mit Wordpress zu kombinieren, zu erweitern oder zu ergänzen.

Plugin Widgets stehen nach dem aktivieren im Plugin Bereich  unter „Design“ „Widgets“ zur Verfügung.

### Setup

Die Plugins haben noch keine echte einstellmöglichkeit, sobald ich begriffen habe wie das geht werde ich diese hinzufügen.

Unter „Plugins“ „Editor“ können die Plugins geändert werden.

Hierzu muss erst rechts das „zu bearbeitende Plugin“ ausgewählt werden.

Unter „Plugin Dateien“ das Widget Plugin auswählen und die Zeile „// Konfig Anfang“ suchen.

Hier die Daten die in der Robust.ini oder in der GridCommon.ini stehen eintragen.

„localhost“ kann meist nur geändert werden wenn man auf dem Zielserver Externen mySQL zugriff erlaubt.

Dort folgende Datenbank Einträge laut eurer OpenSim Konfiguration anpassen:

      /* MySQL Database */
      $CONF_db_server   = "localhost";		     //Your Database-Server
      $CONF_db_user  = "databaseuser";       	             // login
      $CONF_db_pass    = "password";     	     // password
      $CONF_db_database   = "opensimdatabasename"; // Name of BDD
      

### MySQL
Sollte sich die Webseite nicht auf dem gleichen Server befinden wie OpenSim,

muss man in der mysqld.cnf Datei auf dem OpenSim Server folgendes eintragen.

MySQL wird einfach über die Datei mysqld.cnf konfiguriert.

bind-address = Die-IP-des-externen-Wordpress-Server

Beispiele:

bind-address = 127.0.0.1 #Nur der Server auf dem MySQL läuft hat zugriff auf die Datenbanken.

bind-address = 192.168.2.105 #Zugriff nur für den Server 192.168.2.105.

bind-address = 0.0.0.0 #Von allen Externen Rechnern auf MySQL zugreifen lassen (nicht empfohlen gefährlich).

Überprüfen ob MySQL auf dem Server erreichbar ist:

telnet 192.168.2.105 3306

### Lizens

This program is distributed in the hope that it will be useful,

but WITHOUT ANY WARRANTY. without even the implied warranty of

MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the

GNU General Public License 2 for more details.

http://www.gnu.org/licenses/gpl-2.0.html

Dieses Programm wird in der Hoffnung verbreitet, dass es nützlich sein wird,

aber OHNE GARANTIE. ohne auch nur die implizite garantie von

MARKTGÄNGIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK. 

Siehe GNU General Public License 2 für weitere Details.

http://www.gnu.de/documents/gpl-2.0.de.html


### TODO: 
Einstellungen über Wordpress funktionieren nicht.
