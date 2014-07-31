<?php

require_once 'Console/CommandLine.php';
require_once "../../config.php";
require_once "../../utils.php";
require_once "$IP/connectors/groups-service-connector.php";

############
### MAIN ###
############

$parser = new Console_CommandLine();

$parser->addArgument('groupName');
$parser->addArgument('charter');

try
{
    $params = $parser->parse();
}
catch (Exception $e)
{
    $parser->displayError($e->getMessage());
    exit(1);
}

$groupId = $params->args['groupName'];
$charter = $params->args['charter'];

// Unfortunately, due to current OpenSimulator limitations all data is set so we have to get first 
// and feed back the fields we don't want to change!
$existingGroupE = GetGroupByName($GROUPS_SERVICE_URI, $groupId)->RESULT;

$existingFounderId = (string)$existingGroupE->FounderID;
$existingGroupId = (string)$existingGroupE->GroupID;
$existingGroupPictureId = (string)$existingGroupE->InsigniaID;
$existingAllowPublish = ToBool((string)$existingGroupE->AllowPublish);
$existingMaturePublish = ToBool((string)$existingGroupE->MaturePublish);
$existingOpenEnrollment = ToBool((string)$existingGroupE->OpenEnrollment);
$existingMembershipFee = (int)$existingGroupE->MembershipFee;
$existingShownInList = ToBool((string)$existingGroupE->ShownInList);

echo "existingGroupId '$existingGroupId'\n";
echo "existingGroupPictureId '$existingGroupPictureId'\n";
echo "existingAllowPublish '$existingAllowPublish'\n";

UpdateGroup(
    $GROUPS_SERVICE_URI, $existingGroupId, $existingFounderId, $charter, 
    $existingGroupPictureId, $existingAllowPublish, $existingMaturePublish, 
    $existingOpenEnrollment, $existingMembershipFee, $existingShownInList, TRUE);