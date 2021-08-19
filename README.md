# Broadway-dynamodb-demo

This is a simple demo with Symfony 4 broadway and dynamodb.

## Configuration

Inside ````composer.json```` file you can get all the dependencies required to create your events store into a dynamodb database on aws.

Into ```config/packages/aws.yaml``` you need to put your credentials and information about aws

Into  ```config/services.yaml``` you need to put your service and class dependencies like this for example:

```
App\Infrastructure\Repository\CreatePostReadModelRepository:
        class: App\Infrastructure\Repository\CreatePostReadModelRepository
        arguments:
            - '@aws.dynamodb'

    Broadway\EventStore\DynamoDb\DynamoDbEventStore:
        class: Broadway\EventStore\DynamoDb\DynamoDbEventStore
        arguments:
            - '@aws.dynamodb'
            - '@broadway.serializer.metadata'
            - '@broadway.serializer.payload'
            - "%env(AWS_EVENT_STORE_TABLE)%"
            -
    App\Infrastructure\Repository\PostRepository:
        class: App\Infrastructure\Repository\PostRepository
        arguments:
            - '@Broadway\EventStore\DynamoDb\DynamoDbEventStore'
            - '@broadway.event_handling.event_bus'

    App\Application\Handler\PostCommandHandler:
        class: App\Application\Handler\PostCommandHandler
        arguments:
            - '@App\Infrastructure\Repository\PostRepository'
        tags:
            - { name: broadway.command_handler }

    App\Application\Projector\CreatePostProjector:
        class: App\Application\Projector\CreatePostProjector
        arguments:
            - '@App\Infrastructure\Repository\CreatePostReadModelRepository'
        tags:
            - { name: broadway.domain.event_listener}

    App\Application\Service\Post\CreatePostService:
        class: App\Application\Service\Post\CreatePostService
        arguments:
            - '@broadway.command_handling.simple_command_bus'

    App\Infrastructure\Command\CreateDatabase:
        tags:
            - { name: 'console.command', command: 'app:create-database' }
```

Inside your .env you need to set your aws environment variables, for example:

```
AWS_KEY=not-a-real-key
AWS_SECRET=@@not-a-real-secret
AWS_ENDPOINT=http://dynamodb:8000
AWS_EVENT_STORE_TABLE=event_store
AWS_REGION=eu-west-1
```

## DynamoDb Local

Inside this repository there is a docker that contains: nginx, php and dynamodb to use in local.

To launch docker you need to write into your CLI: 

```
docker-compose up
```

This command get up all services need from this application.


## TEST YOUR APPLICATION

To verify if your event store works fine you can launch tests that create a simple event calling the ```/posts``` route.  
To launch tests, write into your CLI:

```
./runtests.sh
```
## TEST YOUR APPLICATION USING DOCKER CONTAINER

You can also launch tests inside the container.

```
docker-compose up -d
./container_execute_test.sh
```

After that you can open your browser and visit this link: ```http://localhost:8000/shell```

Now you can view dynamodb shell where you can make query to the local database.
To view all tables for example use this query:

```
AWS.config = new AWS.Config();
AWS.config.accessKeyId = "not-a-real-key";
AWS.config.secretAccessKey = "@@not-a-real-secret";
AWS.config.region = "eu-west-1";

var dynamodb = new AWS.DynamoDB({
    version: 'latest',
    endpoint: "http://localhost:8000"
});

var params = {
    //ExclusiveStartTableName: 'table_name', // optional (for pagination, returned as LastEvaluatedTableName)
    Limit: 10, // optional (to further limit the number of table names returned per page)
};

function doScan() {
        dynamodb.listTables(params, function(err, data) {
            if (err) console.log(err); // an error occurred
            else console.log(data); // successful response
        });
}

console.log("Starting list tables");
doScan();
```

To view your events saved into the read model launch this query


```
AWS.config = new AWS.Config();
AWS.config.accessKeyId = "not-a-real-key";
AWS.config.secretAccessKey = "@@not-a-real-secret";
AWS.config.region = "eu-west-1";

var dynamodb = new AWS.DynamoDB({
    version: 'latest',
    endpoint: "http://localhost:8000"
});


var tableName = "post_read_model";

var params = {
TableName: tableName,
Select: "ALL_ATTRIBUTES"
};


function doScan(response) {
if (response.error) ppJson(response.error); // an error occurred
else {
    ppJson(response.data); // successful response

    // More data.  Keep calling scan.
    if ('LastEvaluatedKey' in response.data) {
        response.request.params.ExclusiveStartKey = response.data.LastEvaluatedKey;
        dynamodb.scan(response.request.params)
            .on('complete', doScan)
            .send();
    }
}
}
console.log("Starting a Scan of the table");
dynamodb.scan(params)
.on('complete', doScan)
.send();
```
