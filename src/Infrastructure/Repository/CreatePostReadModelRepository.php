<?php
/**
 * Created by PhpStorm.
 * User: alessandrominoccheri
 * Date: 21/11/2018
 * Time: 16:06
 */

namespace App\Infrastructure\Repository;

use App\Infrastructure\Object\ConvertAwsItemToArray;
use App\Infrastructure\Object\ScanFilter;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Broadway\ReadModel\Identifiable;
use Broadway\ReadModel\Repository;

class CreatePostReadModelRepository implements Repository, ReadModelRepository
{
    private $dynamoDbClient;

    public function __construct(DynamoDbClient $dynamoDbClient)
    {
        $this->dynamoDbClient = $dynamoDbClient;
    }

    public function save(Identifiable $procedure)
    {
        $marshal = new Marshaler();
        $data = $marshal->marshalJson(json_encode($procedure->serialize()));

        $this->dynamoDbClient->putItem(
            [
                'TableName' => $this->getTableName(),
                'Item' => $data
            ]
        );
    }

    public function getTableName() :string
    {
       return 'post_read_model';
    }

    /**
     * @param mixed $id
     *
     * @return Identifiable|null
     */
    public function find($id)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param array $fields
     *
     * @return Identifiable[]
     */
    public function findBy(array $fields): array
    {
        $marshaler = new Marshaler();

        $scanFilter = new ScanFilter($fields);

        $eav = $marshaler->marshalJson($scanFilter->getJson());

        $iterator = $this->dynamoDbClient->getIterator('Scan', array(
            'TableName' => $this->getTableName(),
            'FilterExpression' => $scanFilter->getFilter(),
            "ExpressionAttributeValues" => $eav,
        ));

        $results = [];
        foreach ($iterator as $item) {
            $results[] = ConvertAwsItemToArray::convert($item);
        }

        return $results;
    }

    /**
     * @return Identifiable[]
     */
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }

    /**
     * @param mixed $id
     */
    public function remove($id)
    {
        $this->dynamoDbClient->delete($this->getTableName(), ['id' => $id]);
    }

}
