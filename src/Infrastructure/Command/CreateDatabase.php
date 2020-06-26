<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 2019-01-03
 * Time: 12:36
 */

namespace App\Infrastructure\Command;

use Aws\Credentials\Credentials;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Exception\DynamoDbException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateDatabase extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:create-database')
            ->setDescription('Creates database.')
            ->setHelp('This command create dynamodb database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $credentials = new Credentials(getenv("AWS_KEY"), getenv("AWS_SECRET"));

        $dynamoDbClient = new DynamoDbClient([
            'region' => 'eu-west-1',
            'version' => 'latest',
            'endpoint' => 'http://dynamodb:8000',
            'credentials' => $credentials
        ]);

        $this->deleteTables($dynamoDbClient);
        $this->createEventStoreTable($dynamoDbClient);
        $this->createPostTable($dynamoDbClient);
    }

    private function deleteTables(DynamoDbClient $dynamoDbClient)
    {
        $tables = $dynamoDbClient->listTables();

        if (isset($tables['TableNames'])) {
            foreach ($tables['TableNames'] as $dynamoDbTable) {
                $dynamoDbClient->deleteTable([
                    'TableName' => $dynamoDbTable,
                ]);

                $dynamoDbClient->waitUntil('TableNotExists', array(
                    'TableName' => $dynamoDbTable
                ));
            }
        }
    }

    private function createEventStoreTable(DynamoDbClient $dynamoDbClient)
    {
        $params = [
            'TableName' => getenv("AWS_EVENT_STORE_TABLE"),
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH'  //Partition key
                ],
                [
                    'AttributeName' => 'recorded_on',
                    'KeyType' => 'RANGE'  //Partition key
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ],
                [
                    'AttributeName' => 'recorded_on',
                    'AttributeType' => 'S'
                ]
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ]
        ];

        try {
            $dynamoDbClient->createTable($params);
        } catch (DynamoDbException $e) {
            echo "Unable to create table:\n" . $e->getMessage();
        }

    }

    private function createPostTable(DynamoDbClient $dynamoDbClient)
    {
        $params = [
            'TableName' => 'post_read_model',
            'KeySchema' => [
                [
                    'AttributeName' => 'id',
                    'KeyType' => 'HASH'  //Partition key
                ],
                [
                    'AttributeName' => 'occurred_at',
                    'KeyType' => 'RANGE'  //Partition key
                ]
            ],
            'AttributeDefinitions' => [
                [
                    'AttributeName' => 'id',
                    'AttributeType' => 'S'
                ],
                [
                    'AttributeName' => 'occurred_at',
                    'AttributeType' => 'S'
                ]
            ],
            'ProvisionedThroughput' => [
                'ReadCapacityUnits' => 10,
                'WriteCapacityUnits' => 10
            ]
        ];

        try {
            $dynamoDbClient->createTable($params);
        } catch (DynamoDbException $e) {
            echo "Unable to create table:\n" . $e->getMessage();
        }
    }
}
