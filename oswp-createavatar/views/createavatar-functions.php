<?php

    var $base_avatar = UUID_ZERO;

//
// Avatar erstellen.
//
function  opensim_create_avatar($UUID, $firstname, $lastname, $passwd, $homeregion, $base_avatar=UUID_ZERO, &$db=null)
{
	if (!isGUID($UUID)) return false;
	if (!isAlphabetNumericSpecial($firstname))  return false;
	if (!isAlphabetNumericSpecial($lastname))   return false;
	if (!isAlphabetNumericSpecial($passwd))		return false;
	if (!isAlphabetNumericSpecial($homeregion)) return false;
	if (!is_object($db)) $db = opensim_new_db();

	$nulluuid   = UUID_ZERO;
	$passwdsalt = make_random_hash();
	$passwdhash = md5(md5($passwd).":".$passwdsalt);

	$db->query("SELECT uuid,regionHandle FROM regions WHERE regionName='$homeregion'");
	$errno = $db->Errno;
	if ($errno==0) {
		list($regionID,$regionHandle) = $db->next_record();

		$serviceURLs = 'HomeURI= GatekeeperURI= InventoryServerURI= AssetServerURI=';
		$db->query('INSERT INTO UserAccounts (PrincipalID,ScopeID,FirstName,LastName,Email,ServiceURLs,Created,UserLevel,UserFlags,UserTitle) '.
						 "VALUES ('$UUID','$nulluuid','$firstname','$lastname','','$serviceURLs','".time()."','0','0','')");
		$errno = $db->Errno;
		if ($errno==0) {
			$db->query('INSERT INTO GridUser (UserID,HomeRegionID,HomePosition,HomeLookAt,LastRegionID,LastPosition,LastLookAt,Online,Login,Logout) '.
							"VALUES ('$UUID','$regionID','<128,128,0>','<0,0,0>','$regionID','<128,128,0>','<0,0,0>','false','0','0')");
			$errno = $db->Errno;
		}
		if ($errno==0) {
			$db->query('INSERT INTO auth (UUID,passwordHash,passwordSalt,webLoginKey,accountType) '.
							"VALUES ('$UUID','$passwdhash','$passwdsalt','$nulluuid','UserAccount')");
			$errno = $db->Errno;
		}
		//
		if ($errno==0) {
			opensim_create_avatar_inventory($UUID, $base_avatar, $db);
		}
		else {
			$db->query("DELETE FROM UserAccounts WHERE PrincipalID='$UUID'");
			$db->query("DELETE FROM auth		 WHERE UUID='$UUID'");
			$db->query("DELETE FROM inventoryfolders WHERE agentID='$UUID'");
			$db->query("DELETE FROM GridUser WHERE UserID='$UUID'");
		}
	}

	if ($errno!=0) return false;
	return true;
}


//
// Entfernt Avatar-Informationen aus der Datenbank.
//
function  opensim_delete_avatar($uuid, &$db=null)
{
	if (!isGUID($uuid)) return false;
	if (!is_object($db)) $db = opensim_new_db();

	$db->query("DELETE FROM UserAccounts WHERE PrincipalID='$uuid'");
	$db->query("DELETE FROM auth		 WHERE UUID='$uuid'");
	$db->query("DELETE FROM Avatars	     WHERE PrincipalID='$uuid'");
	$db->query("DELETE FROM Friends	     WHERE PrincipalID='$uuid'");
	$db->query("DELETE FROM tokens	     WHERE UUID='$uuid'");
	$db->query("DELETE FROM GridUser     WHERE UserID='$uuid'");
	if ($db->exist_table('Presence')) $db->query("DELETE FROM Presence WHERE UserID='$uuid'");
	if ($db->exist_table('Avatars'))  $db->query("DELETE FROM Avatars  WHERE PrincipalID='$uuid'");

	$db->query("DELETE FROM estate_managers	 WHERE uuid='$uuid'");
	$db->query("DELETE FROM estate_users	 WHERE uuid='$uuid'");
	$db->query("DELETE FROM estateban		 WHERE bannedUUID='$uuid'");
	$db->query("DELETE FROM inventoryfolders WHERE agentID='$uuid'");
	$db->query("DELETE FROM inventoryitems	 WHERE avatarID='$uuid'");
	$db->query("DELETE FROM landaccesslist   WHERE AccessUUID='$uuid'");
	$db->query("DELETE FROM regionban		 WHERE bannedUUID='$uuid'");

	// for DTL Money Server
	if ($db->exist_table('balances')) {
		$db->query("DELETE FROM balances WHERE user='$uuid'");
		$db->query("DELETE FROM userinfo WHERE user='$uuid'");
	}

	return true;
}

//
// Avatar-Informationen einfuegen.
//
function  opensim_create_default_avatar_wear($uuid, $invent, &$db=null)
{
	if (!is_object($db)) $db = opensim_new_db();
	if (!$db->exist_table('Avatars')) return false;

	$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','AvatarHeight','".DEFAULT_AVATAR_HEIGHT."')");
	$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','AvatarType','1')");
	$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Serial','0')");
	$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','VisualParams','".DEFAULT_AVATAR_PARAMS."')");

	if (is_array($invent)) {
		if (isset($invent['Shape'])) 
			$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Wearable 0:0','".$invent['Shape'].':'.DEFAULT_ASSET_SHAPE."')");
		if (isset($invent['Skin']))
			$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Wearable 1:0','".$invent['Skin']. ':'.DEFAULT_ASSET_SKIN."')");
		if (isset($invent['Hair'])) 
			$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Wearable 2:0','".$invent['Hair']. ':'.DEFAULT_ASSET_HAIR."')");
		if (isset($invent['Eyes'])) 
			$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Wearable 3:0','".$invent['Eyes']. ':'.DEFAULT_ASSET_EYES."')");
		if (isset($invent['Shirt'])) 
			$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Wearable 4:0','".$invent['Shirt'].':'.DEFAULT_ASSET_SHIRT."')");
		if (isset($invent['Pants'])) 
			$db->query("INSERT INTO Avatars (PrincipalID,Name,Value) VALUES ('$uuid','Wearable 5:0','".$invent['Pants'].':'.DEFAULT_ASSET_PANTS."')");
	}

	return true;
}