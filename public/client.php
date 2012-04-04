<?php
# create our client object
$gmclient= new GearmanClient();

# add the default server (localhost)
$gmclient->addServer();

# run reverse client in the background
$params = serialize(array('cpr_id'=>22, 'numCupons'=>100));
$job_handle = $gmclient->doBackground("reverse", $params);
if ($gmclient->returnCode() != GEARMAN_SUCCESS) {
  echo "bad return code\n";
  exit;
}
echo "done!\n";

