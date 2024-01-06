<?php

declare(strict_types=1);

namespace L2Iterative\BonsaiSDK\Tests;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;
use donatj\MockWebServer\ResponseByMethod;
use L2Iterative\BonsaiSDK\Client;
use L2Iterative\BonsaiSDK\Exception;
use L2Iterative\BonsaiSDK\Responses\Quotas;
use PHPUnit\Framework\TestCase;

final class QuotasTest extends TestCase
{


    /**
     * @throws Exception
     */
    public function test_version()
    {
        $server = new MockWebServer();
        $server->start();

        $server->setResponseOfPath(
            '/user/quotas',
            new ResponseByMethod(
                [
                    ResponseByMethod::METHOD_GET => new Response(
                        json_encode(new Quotas(500, 2, 10, 100000, 1000000)),
                        ['content-type' => 'application/json'],
                        200
                    ),
                ]
            )
        );

        $client = new Client("{$server->getServerRoot()}", TEST_KEY, TEST_VERSION);
        $res    = $client->quotas();
        $this->assertEquals(500, $res->exec_cycle_limit);
        $this->assertEquals(2, $res->max_parallelism);
        $this->assertEquals(10, $res->concurrent_proofs);
        $this->assertEquals(100000, $res->cycle_budget);
        $this->assertEquals(1000000, $res->cycle_usage);

    }//end test_version()


}//end class
