<?php
  $url = 'https://orchestraiaks-dns-ej826v6t.hcp.westeurope.azmk8s.io/apis/batch/v1/namespaces/default/jobs';


  $linkYtb = $_GET['youtube-link'];
  $idMidi = $_GET['id-midi'];
  $headers = [
      'Authorization: Bearer 96dy2ahy0pr947yev0n53hsl0er2gw8lmf92dtnvbj4os1five3k6sac2e5lgoovcy2zy1l2f3uu965419mvslwpp5rj24si10gz2n6iiakhy6spbl8f4qryl97lhgxa',
      'Accept: application/json',
      'Content-Type: application/json'
  ];

  $data = [
    'apiVersion' => 'batch/v1',
    'kind' => 'Job',
    'metadata' => ['name' => 'testjob'],
    'spec' => [
      'template' => [
        'metadata' => [
          'labels' => [
            'app' => 'orchestraiapp'
          ]
        ],
        'spec' => [
          'containers' => [
            [
              'name' => 'orchestraiapp',
              'image' => 'ochestrairegistry.azurecr.io/orchestrai13:latest',
              'resources' => [
                'requests' => [
                  'memory' => '1Gi',
                  'cpu' => '500m'
                ],
                'limits' => [
                  'memory' => '6Gi',
                  'cpu' => '1'
                ]
              ],
              'ports' => [
                ['containerPort' => 80]
              ],
              'command' => ['python', './task.py'],
              'args' => ['--link', $linkYtb, '--id_midi', $idMidi],
              'env' => [
                ['name' => 'AZURE_STORAGE_ACCOUNT_NAME', 'value' => 'ccc'],
                ['name' => 'AZURE_STORAGE_ACCOUNT_KEY', 'value' => 'ccc']
              ]
            ]
          ],
          'restartPolicy' => 'Never',
          'imagePullSecrets' => [
            ['name' => 'orchestraisecret']
          ]
        ]
      ]
    ]
  ];


  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

  $response = curl_exec($ch);
  curl_close($ch);
  $responseData = json_decode($response, true);

  if (!$response) {
      die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
  }

  if (isset($responseData['status']['conditions'])) {
    foreach ($responseData['status']['conditions'] as $condition) {
      if ($condition['type'] == 'Complete') {echo 'Job completed';}
      elseif ($condition['type'] == 'Failed') {echo 'Job failed';}
    }
  } else {echo 'Job is still running';}

?>
