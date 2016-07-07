<?php
use Phalcon\Exception;
use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Config\Adapter\Ini as IniConfig;
use Phalcon\Config;

try {
    $configFile = __DIR__ . '/../app/config/config.ini';
    if (!is_file($configFile)) {
        throw new Exception(
            sprintf('Unable to read config file located at %s.', $configFile)
        );
    }
    $config = new IniConfig($configFile);
    /** @var \Phalcon\Config $config */
    $config = $config->get('database');
    if (!$config instanceof Config) {
        throw new Exception('Unable to read database config.');
    }
    $dbClass = sprintf('\Phalcon\Db\Adapter\Pdo\%s', $config->get('adapter', 'MySql'));
    if (!class_exists($dbClass)) {
        throw new Exception(
            sprintf('PDO adapter "%s" not found.', $dbClass)
        );
    }
    $dbConfig = $config->toArray();
    unset($dbConfig['adapter']);
    $connection = new $dbClass($dbConfig);
    $connection->begin();
    $connection->createTable(
        'users',
        null,
        [
            'columns' => [
                new Column('email', [
                    'type'          => Column::TYPE_VARCHAR,
                    'size'          => 50,
                    'unsigned'      => true,
                    'notNull'       => true,
                ]),
                new Column('password', [
                    'type'    => Column::TYPE_VARCHAR,
                    'size'    => 50,
                    'notNull' => true
                ]),
                new Column('ime', [
                    'type'    => Column::TYPE_VARCHAR,
                    'size'    => 30,
                    'notNull' => true
                ]),
                new Column('prezime', [
                    'type'    => Column::TYPE_VARCHAR,
                    'size'    => 40,
                    'notNull' => true
                ]),
                new Column('datum_rodenja', [
                    'type'    => Column::TYPE_VARCHAR,
                    'size'    => 11,
                    'notNull' => true
                ])
            ],
            "indexes" => [
                new Index('PRIMARY', ["email"], 'PRIMARY')
            ]
        ]
    );

    $connection->createTable(
        'posts',
        null,
        [
            'columns' => [
                new Column('id_posta', [
                    'type'          => Column::TYPE_INTEGER,
                    'size'          => 10,
                    'unsigned'      => true,
                    'notNull'       => true,
                    'autoIncrement' => true,
                ]),
                new Column('poruka', [
                    'type'    => Column::TYPE_VARCHAR,
                    'size'    => 255,
                    'notNull' => true,
                ]),
                new Column('created_at', [
                    'type'    => Column::TYPE_TIMESTAMP,
                    'notNull' => true,
                    'default' => 'CURRENT_TIMESTAMP',
                ]),
            ],
            'indexes' => [
                new Index('PRIMARY', ['id_posta'], 'PRIMARY'),
            ]
        ]
    );
    $connection->commit();
} catch (\Exception $e) {
    if ($connection->isUnderTransaction()) {
        $connection->rollback();
    }
    echo $e->getMessage(), PHP_EOL;
    echo $e->getTraceAsString(), PHP_EOL;
}