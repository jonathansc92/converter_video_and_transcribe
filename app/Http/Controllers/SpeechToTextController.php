<?php
/**
 * Created by PhpStorm.
 * User: lucas.rosa
 * Date: 13/06/2018
 * Time: 15:42
 */

namespace App\Http\Controllers;

use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;
use GuzzleHttp\Client;

class SpeechToTextController
{

    public function videoToMp3()
    {

        $urlVideo = array(
            'http://player.vimeo.com/external/66847414.hd.mp4?s=d89ce185a57cd5d586d118521190f82b63bc14d7&profile_id=113&oauth2_token_id=44998640',
            'http://player.vimeo.com/external/78178765.mobile.mp4?s=845626e09762281e6dff7264749fd093'
        );

        foreach ($urlVideo as $url) {

            // Salva o video no app
            $video = $this->saveVideoToStorage($url);

            // Converte o video para MP3
            $media = FFMpeg::fromDisk('local')
                ->open('videos/' . $video . '.mp4')
                ->export()
                ->inFormat(new \FFMpeg\Format\Audio\MP3)
                ->save('audios/' . $video . '.mp3');

            // Transcreve o em texto o conteúdo do áudio (MP3) gerado
            $transcrition = $this->readAudio($video . '.mp3');


        }

    }

    public function saveVideoToStorage($pUrl)
    {

        $nameFile = time() . '.mp4';

        $file = fopen($pUrl, "r");
        if (!$file) {
            echo "<p>Unable to open remote file.\n";
            exit;
        }

        $localfile = storage_path('app/videos/' . $nameFile);
        $lf = fopen($localfile, "w");
        while (!feof($file)) {
            $line = fgets($file, 1024);
            fputs($lf, $line);
        }
        fclose($file);
        fclose($lf);

        $nameWithoutExtension = substr($nameFile, 0, strrpos($nameFile, "."));

        return $nameWithoutExtension;

    }

    public function readAudio($pMp3)
    {

        $client = new Client([
            'base_uri' => 'https://stream.watsonplatform.net/'
        ]);

        $audio = fopen(storage_path('app/audios/' . $pMp3), 'r');
        $resp = $client->request('POST', 'speech-to-text/api/v1/recognize?model=pt-BR_NarrowbandModel', [
            'auth' => ['40f65521-5ce1-4ab7-91c5-ab2012ab84e4', 'vrllr4NuqIBU'],
            //'model' => 'pt-BR_BroadbandModel',
            'headers' => [
                'Content-Type' => 'audio/mpeg',
            ],
            'body' => $audio
        ]);

        echo $resp->getBody();

    }
}