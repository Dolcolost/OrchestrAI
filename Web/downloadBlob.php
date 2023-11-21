<?php
  require_once(__DIR__.'/../vendor/autoload.php');

  use MicrosoftAzure\Storage\Blob\BlobRestProxy;
  use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
  use MicrosoftAzure\Storage\Blob\Models\CreateBlobBlockOptions;
  if (isset($_POST['download'])) {

    $idMidi = "./midi/".htmlspecialchars($_POST['idMidi']).".mid";

    $connectionString = "DefaultEndpointsProtocol=https;AccountName=orchestraistorage;AccountKey=dlsgy/EolQrsXcwyGn0YUbsJuPmDvsRr2zXwDmVxhvWgVxu3iz0lCob5But8aIxdFrX1p/ovwGV5+AStg8NewQ==";
    $blobClient = BlobRestProxy::createBlobService($connectionString);
    $containerName = "orchestraicontainer";

    try {
        $getBlobResult = $blobClient->getBlob($containerName, $idMidi);

        header('Content-Disposition: attachment; filename="' . $idMidi . '"');
        header('Content-Type: ' . $getBlobResult->getProperties()->getContentType());
        fpassthru($getBlobResult->getContentStream());

    } catch(ServiceException $e){
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
  }
  ?>
