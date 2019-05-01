<?php

namespace E2Consult\IGApi\Traits;

use E2Consult\IGApi\Classes\AuthenticateStreamingApi;

trait StreamingTrait
{
    public function streamToDatabase()
    {
        echo 'Getting credentials:';

        $epics = [
            'CS.D.BITCOIN.CFD.IP', // Bitcoin
            'CS.D.LTCUSD.CFD.IP', // Litecoin
            'CC.D.LCO.UNC.IP', // Olje
            'CS.D.CFDGOLD.CFDGC.IP', // Gull
            'CC.D.NG.UNC.IP', // Naturgass
            'CS.D.EURUSD.MINI.IP', // EUR/USD Mini
        ];

        $stream = (new AuthenticateStreamingApi($this->username, $this->password, $this->apiToken))->login();
        // echo "Started streaming...";
        $query = "node app/IG/Scripts/Connect.js {$this->stream_enpoint} {$this->account_id} {$stream->cst()} {$stream->securityToken()} '".json_encode($epics)."'";
        echo $query.PHP_EOL;
        shell_exec($query);
    }
}
