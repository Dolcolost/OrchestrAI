<?php
  require_once(__DIR__.'/../vendor/autoload.php');
  use RenokiCo\PhpK8s\KubernetesCluster;


  $cluster = KubernetesCluster::fromUrl('https://orchestraiaks-dns-ej826v6t.hcp.westeurope.azmk8s.io');
  $cluster->withToken("96dy2ahy0pr947yev0n53hsl0er2gw8lmf92dtnvbj4os1five3k6sac2e5lgoovcy2zy1l2f3uu965419mvslwpp5rj24si10gz2n6iiakhy6spbl8f4qryl97lhgxa");
  $cluster->withoutSslChecks();
  $allJobs = $cluster->job()->allNamespaces();

  $job = null;
  foreach ($allJobs as $item) {
    if($item->getName() == "testjob") {
      $job = $item;
      break;
    }
  }

  if ($job) {
    $arrayJobs = $job->toArray();
    $status = $arrayJobs['status'];
    if (array_key_exists('succeeded', $status) && $status['succeeded'] == 1) {
      echo "gg";
      $job->delete();
      $allPods = $cluster->pod()->allNamespaces();
      foreach ($allPods as $pod) {
        $labels = $pod->getLabels();
        if (array_key_exists('job-name', $labels)) {
          $jobName = $labels['job-name'];
          if ($jobName == "testjob"){
            $pod->delete();
          }
        }
      }
    }
    else {
      echo "not yet";
    }
  }
  else {
    echo "error";
  }
?>
